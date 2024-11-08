<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Auth\Transformers\AccountResource;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Modules\Project\Transformers\ProjectResource;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = QueryBuilder::for(Project::class)
            ->where('status', ProjectStatus::ACTIVE->value)
            ->allowedIncludes(['account', 'account.user', 'account.user.avatar'])
            ->paginate($request->per_page ?? 10);

        return ProjectResource::collection($data);
    }

    /**
     * Show the specified resource.
     */
    public function show(Project $project)
    {

        return ProjectResource::make($project->load([
            'account' => [
                'user' => [
                    'avatar',
                    'languages',
                ],
            ],
        ]));
        //        return AccountResource::make($account->load([
        //            'skills',
        //            'tools',
        //            'educations',
        //            'workExperiences',
        //            'portfolios',
        //            'certificates',
        //            'user' => [
        //                'avatar',
        //                'banner',
        //                'languages',
        //            ],
        //        ]));
    }
}
