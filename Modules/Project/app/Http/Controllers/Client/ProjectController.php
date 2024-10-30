<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Project\Http\Requests\CreateProjectRequest;
use Modules\Project\Models\Project;
use Modules\Project\Transformers\ProjectResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Project::where('account_id', $request->account->id)->paginate($request->per_page ?? 10);

        return ProjectResource::collection($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProjectRequest $request)
    {
        $project = Project::create(
            [
                'account_id' => $request->account->id,
                ...Arr::only($request->validated(), [
                    'title',
                    'description',
                    'estimated_budget',
                    'length',
                    'experience_level',
                ]),
            ]
        );

        $project->skills()->sync($request->post('skills', []));

        $project->languages()->createMany($request->post('languages'));

        return ProjectResource::make($project->load(['languages', 'skills']));
    }

    /**
     * Show the specified resource.
     */
    public function show($id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
