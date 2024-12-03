<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\Enums\ContractStatus;
use Modules\Project\Models\Contract;
use Modules\Project\Models\Proposal;
use Modules\Project\Transformers\ContractResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Contract::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('project_id'),
            ])
            ->allowedIncludes([
                'account.user.avatar',
                'proposal.project.account.user',
                'proposal.project.languages',
                'proposal.project.skills',
                'proposal.project.images',
                'proposal.attachments',
                'proposal.account.skills',
                'proposal.project.account.user.avatar',
                'proposal.chat_channel',
            ])
            ->whereHas('project', function (Builder $query) use ($request) {
                $query->where('account_id', $request->account->id);
            })
            ->whereNot('status', ContractStatus::REJECTED->value)
            ->paginate($request->input('per_page', 10));

        return ContractResource::collection($data);
    }

    /**
     * Show the specified resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'amount' => 'required|numeric',
            'end_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:'.now()->format('Y-m-d'),
            ],
        ]);

        $proposal = Proposal::whereId($request->post('proposal_id'))->with('contract')->first();

        if ($proposal->contract) {
            return response()->json(['message' => 'Proposal already have contract'], 400);
        }

        $contract = Contract::create([
            'account_id' => $proposal->account_id,
            'project_id' => $proposal->project_id,
            'proposal_id' => $proposal->id,
            'amount' => $request->post('amount'),
            'platform_fee_percentage' => 0.05,
            'status' => ContractStatus::PENDING->value,
            'end_date' => $request->post('end_date'),
        ]);

        return ContractResource::make($contract->load(['account', 'proposal', 'project']));
    }

    /**
     * Show the specified resource.
     */
    public function update(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'amount' => ['required', 'sometimes', 'numeric'],
            'end_date' => [
                'sometimes',
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:'.now()->format('Y-m-d'),
            ],
            'status' => 'sometimes|string|in:'.ContractStatus::COMPLETED->value,
        ]);

        $contract->load(['account', 'proposal', 'project']);

        if ($request->account?->id != $contract->project->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        $contract->update($data);

        return ContractResource::make($contract->fresh()->load(['account', 'proposal', 'project']));
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Contract $contract)
    {
        $contract->load(['account.user.avatar', 'proposal' => ['project' => ['account.user.avatar', 'languages', 'skills', 'images'], 'attachments', 'chat_channel']]);

        if ($request->account?->id != $contract->project->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        return ContractResource::make($contract);
    }

    /**
     * Show the specified resource.
     */
    public function destroy(Request $request, Contract $contract)
    {
        $contract->load(['account', 'proposal', 'project']);

        if ($request->account?->id != $contract->project->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        $contract->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
