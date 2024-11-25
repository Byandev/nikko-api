<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Enums\ContractStatus;
use Modules\Project\Enums\ProposalStatus;
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
            ])
            ->allowedIncludes([
                'account.user.avatar',
                'proposal.project.account.user',
                'proposal.project.languages',
                'proposal.project.skills',
                'proposal.project.images',
                'proposal.attachments',
            ])
            ->whereAccountId($request->account->id)
            ->whereNot('status', ContractStatus::REJECTED->value)
            ->paginate($request->input('per_page', 10));

        return ContractResource::collection($data);
    }

    /**
     * Show the specified resource.
     */
    public function update(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'status' => 'required|string|in:'.ContractStatus::ACTIVE->value.','.ContractStatus::REJECTED->value,
        ]);

        $contract->load(['account', 'proposal', 'project']);

        if ($request->account?->id != $contract->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        if ($request->post('status') === ContractStatus::ACTIVE->value) {
            Proposal::where('id', $contract->proposal_id)
                ->update(['status' => ProposalStatus::PENDING_OFFER->value]);
        } else {
            Proposal::where('id', $contract->proposal_id)
                ->update(['status' => ProposalStatus::SUBMITTED->value]);
        }

        $contract->update($data);

        $contract->fresh()->load(['account.user', 'proposal' => ['project' => ['account.user', 'languages', 'skills', 'images'], 'attachments']]);

        return ContractResource::make($contract);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Contract $contract)
    {
        $contract->load(['account.user', 'proposal' => ['project' => ['account.user', 'languages', 'skills', 'images'], 'attachments']]);

        if ($request->account?->id != $contract->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        return ContractResource::make($contract);
    }
}
