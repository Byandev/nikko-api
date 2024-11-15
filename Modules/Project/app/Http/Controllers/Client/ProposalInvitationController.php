<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\Enums\ProposalInvitationStatus;
use Modules\Project\Models\Proposal;
use Modules\Project\Models\ProposalInvitation;
use Modules\Project\Transformers\ProposalInvitationResource;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalInvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(ProposalInvitation::class)
            ->allowedFilters([
                'project_id',
                'account_id',
                'status',
            ])
            ->whereHas('project', function (Builder $query) use ($request) {
                $query->where('account_id', $request->account->id);
            })
            ->allowedIncludes([
                'project',
                'account.user.avatar',
                'account.user.languages',
                'account.skills',
            ])
            ->paginate($request->per_page ?? 10);

        return ProposalInvitationResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'message' => 'required|string',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $exists = ProposalInvitation::whereAccountId($request->post('account_id'))
            ->whereProjectId($request->post('project_id'))
            ->exists();

        if ($exists) {
            return response(['message' => 'Invitation Exists'], 400);
        }

        $proposalExists = Proposal::whereAccountId($request->post('account_id'))
            ->whereProjectId($request->post('project_id'))
            ->exists();

        if ($proposalExists) {
            return response(['message' => 'Proposal Exists'], 400);
        }

        $invitation = ProposalInvitation::create([
            'project_id' => $request->post('project_id'),
            'status' => ProposalInvitationStatus::PENDING->value,
            'message' => $request->post('message'),
            'account_id' => $request->post('account_id'),
        ]);

        return ProposalInvitationResource::make($invitation);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, ProposalInvitation $invitation)
    {
        $invitation->load([
            'project',
            'account' => [
                'user' => ['avatar', 'languages'], 'skills',
            ],
        ]);

        if ($request->account?->id != $invitation->project->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        return ProposalInvitationResource::make($invitation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ProposalInvitation $invitation)
    {
        $invitation->load(['project']);

        if ($request->account?->id != $invitation->project->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        $invitation->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
