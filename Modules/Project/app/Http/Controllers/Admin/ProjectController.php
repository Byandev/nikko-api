<?php

namespace Modules\Project\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('length'),
                AllowedFilter::exact('experience_level'),
                AllowedFilter::scope('search'),
            ])
            ->allowedIncludes(['images', 'skills', 'languages'])
            ->paginate($request->per_page ?? 10);

        return ProjectResource::collection($data);
    }

    /**
     * Show the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['languages', 'skills', 'images']);
        $project->loadCount('proposals');

        return ProjectResource::make($project);
    }
}
