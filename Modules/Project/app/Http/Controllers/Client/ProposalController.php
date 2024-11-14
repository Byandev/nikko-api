<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\Models\Proposal;
use Modules\Project\Transformers\ProposalResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Proposal::class)
            ->appendIsSavedBy($request->account)
            ->allowedFilters([
                'project_id',
                'status',
                'length',
                AllowedFilter::callback('is_saved', function (Builder $query) use ($request) {
                    $query->onlySavedBy($request->account);
                }),
            ])
            ->whereHas('project', function (Builder $query) use ($request) {
                $query->where('account_id', $request->account->id);
            })
            ->allowedIncludes([
                'attachments',
                'project',
                'project.account.user.avatar',
            ])
            ->paginate($request->per_page ?? 10);

        return ProposalResource::collection($data);

    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Proposal $proposal)
    {
        $proposal->load(['project.account.user', 'attachments']);

        if ($request->account?->id != $proposal->project->account_id) {
            return response(['message' => 'Forbidden'], 403);
        }

        return ProposalResource::make($proposal);
    }
}
