<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Modules\Project\Transformers\ProjectResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Project::class)
            ->where('status', ProjectStatus::ACTIVE->value)
            ->withCount('proposals')
            ->when($request->account, function (Builder $query) use ($request) {
                $query->appendIsSavedBy($request->account);
            })
            ->allowedIncludes(['account', 'account.user', 'account.user.avatar', 'myProposal', 'skills', 'languages'])
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('length'),
                AllowedFilter::exact('experience_level'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('skills'),
                AllowedFilter::callback('is_saved', function (Builder $query) use ($request) {
                    $query->when($request->account, function (Builder $query) use ($request) {
                        $query->onlySavedBy($request->account);
                    });
                }),
            ])
            ->paginate($request->per_page ?? 10);

        $savedCount = QueryBuilder::for(Project::class)
            ->where('status', ProjectStatus::ACTIVE->value)
            ->when($request->account, function (Builder $query) use ($request) {
                $query->onlySavedBy($request->account);
            })
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('length'),
                AllowedFilter::exact('experience_level'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('skills'),
                AllowedFilter::callback('is_saved', function (Builder $query) {
                    $query->whereNotNull('id');
                }),
            ])
            ->count();

        $totalCount = QueryBuilder::for(Project::class)
            ->where('status', ProjectStatus::ACTIVE->value)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('length'),
                AllowedFilter::exact('experience_level'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('skills'),
                AllowedFilter::callback('is_saved', function (Builder $query) {
                    $query->whereNotNull('id');
                }),
            ])
            ->count();

        return ProjectResource::collection($data)
            ->additional([
                'meta' => [
                    'total_count' => $totalCount,
                    'total_saved_count' => $request->account ? $savedCount : 0,
                ],
            ]);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Project $project)
    {
        $project->load([
            'myProposal',
            'account' => [
                'user' => [
                    'avatar',
                    'languages',
                ],
            ],
            'languages',
            'skills',
            'images',
        ]);

        if ($request->account) {
            $project->is_saved = $project->isSavedBy($request->account);
        }

        $project->loadCount('proposals');

        return ProjectResource::make($project);
    }
}
