<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Project\Enums\ProjectLength;
use Modules\Project\Enums\ProposalStatus;
use Modules\Project\Models\Proposal;
use Modules\Project\Transformers\ProposalResource;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Proposal::class)
            ->allowedFilters([
                'project_id',
                'status',
                'length',
            ])
            ->where('account_id', $request->account->id)
            ->allowedIncludes([
                'attachments',
                'project.account.user.avatar',
            ])
            ->paginate($request->per_page ?? 10);

        return ProposalResource::collection($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'cover_letter' => 'required',
            'bid' => 'required|numeric',
            'length' => [
                'required', Rule::in(
                    ProjectLength::SHORT_TERM->value,
                    ProjectLength::MEDIUM_TERM->value,
                    ProjectLength::LONG_TERM->value,
                    ProjectLength::EXTENDED->value,
                ),
            ],
            'attachments' => 'required|array',
            'attachments.*' => 'required|numeric|exists:media,id',
        ]);

        $proposal = Proposal::create([
            ...$data,
            'account_id' => $request->account?->id,
            'transaction_fee' => $request->post('bid') * 0.05,
            'status' => ProposalStatus::SUBMITTED->value,
        ]);

        Media::whereIn('id', $request->post('attachments'))
            ->get()
            ->each(function (Media $media) use ($proposal) {
                $media->move($proposal, MediaCollectionType::PROPOSAL_ATTACHMENTS->value);
            });

        return ProposalResource::make($proposal->load(['project.account.user', 'attachments']));
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Proposal $proposal)
    {
        if ($request->account?->id != $proposal->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        return ProposalResource::make($proposal->load('project.account.user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proposal $proposal)
    {
        if ($request->account?->id != $proposal->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'cover_letter' => 'sometimes|string',
            'bid' => 'sometimes|numeric',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'required|numeric|exists:media,id',
            'length' => [
                'sometimes', Rule::in(
                    ProjectLength::SHORT_TERM->value,
                    ProjectLength::MEDIUM_TERM->value,
                    ProjectLength::LONG_TERM->value,
                    ProjectLength::EXTENDED->value,
                ),
            ],
        ]);

        $transaction_fee = $proposal->transaction_fee;

        if ($request->has('bid')) {
            $transaction_fee = $request->post('bid') * 0.05;
        }

        $proposal->update([
            ...$data,
            'transaction_fee' => $transaction_fee,
        ]);

        if ($request->has('attachments')) {
            $medias = Media::whereIn('id', $request->post('attachments', []))
                ->get();

            $updatedImageIds = $medias
                ->filter(fn (Media $media) => $media->collection_name === MediaCollectionType::PROPOSAL_ATTACHMENTS->value)
                ->map(fn (Media $media) => $media->id)
                ->toArray();

            $medias->filter(fn (Media $media) => $media->collection_name !== MediaCollectionType::PROPOSAL_ATTACHMENTS->value)
                ->each(function (Media $media) use (&$updatedImageIds, $proposal) {
                    $media = $media->move($proposal, MediaCollectionType::PROPOSAL_ATTACHMENTS->value);
                    $updatedImageIds[] = $media->fresh()->id;
                });

            Media::whereNotIn('id', $updatedImageIds)
                ->where('model_id', $proposal->id)
                ->where('model_type', get_class($proposal))
                ->delete();
        }

        return ProposalResource::make($proposal->fresh()->load(['project.account.user', 'attachments']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Proposal $proposal)
    {
        if ($request->account?->id != $proposal->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        $proposal->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
