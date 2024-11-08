<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Project\Models\Project;
use Modules\Project\Transformers\ProjectResource;

class SaveProjectController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $project->saveBy($request->account);

        $project->is_saved = $project->isSavedBy($request->account);

        return ProjectResource::make($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project)
    {
        $project->unSaveBy($request->account);

        $project->is_saved = $project->isSavedBy($request->account);

        return ProjectResource::make($project);
    }
}
