<?php

namespace Modules\Project\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Models\Contract;
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
            ])
            ->paginate($request->input('per_page', 10));

        return ContractResource::collection($data);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Contract $contract)
    {
        $contract->load(['account.user', 'proposal' => ['project' => ['account.user', 'languages', 'skills', 'images'], 'attachments']]);

        return ContractResource::make($contract);
    }
}
