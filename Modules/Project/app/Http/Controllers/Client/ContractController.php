<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Enums\ContractStatus;
use Modules\Project\Models\Contract;
use Modules\Project\Models\Proposal;
use Modules\Project\Transformers\ContractResource;

class ContractController extends Controller
{
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

        $proposal = Proposal::find($request->post('proposal_id'))->with('contract')->first();

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
        ]);

        $contract->update($data);

        return ContractResource::make($contract->fresh()->load(['account', 'proposal', 'project']));
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Contract $contract)
    {
        $contract->load(['account', 'proposal', 'project']);

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
