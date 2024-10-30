<?php

namespace Modules\Project\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Media\Enums\MediaCollectionType;
use Modules\Media\Models\Media;
use Modules\Project\Http\Requests\CreateProjectRequest;
use Modules\Project\Http\Requests\UpdateProjectRequest;
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

        Media::whereIn('id', $request->post('images'))
            ->get()
            ->each(function (Media $media) use ($project) {
                $media->move($project, MediaCollectionType::PROJECT_IMAGES->value);
            });

        return ProjectResource::make($project->load(['languages', 'skills', 'images']));
    }

    /**
     * Show the specified resource.
     */
    public function show(Project $project)
    {
        return ProjectResource::make($project->load(['languages', 'skills', 'images']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update(Arr::only($request->validated(), [
            'title',
            'description',
            'estimated_budget',
            'length',
            'experience_level',
        ]));

        if ($request->has('languages')) {
            $project->languages()->delete();

            $project->languages()->createMany($request->post('languages'));
        }

        if ($request->has('skills')) {
            $project->skills()->sync($request->post('skills', []));
        }

        if ($request->has('images')) {
            $medias = Media::whereIn('id', $request->post('images', []))
                ->get();

            $updatedImageIds = $medias
                ->filter(fn (Media $media) => $media->collection_name === MediaCollectionType::PROJECT_IMAGES->value)
                ->map(fn (Media $media) => $media->id)
                ->toArray();

            $medias->filter(fn (Media $media) => $media->collection_name !== MediaCollectionType::PROJECT_IMAGES->value)
                ->each(function (Media $media) use (&$updatedImageIds, $project) {
                    $media = $media->move($project, MediaCollectionType::PORTFOLIO_IMAGES->value);
                    $updatedImageIds[] = $media->fresh()->id;
                });

            Media::whereNotIn('id', $updatedImageIds)
                ->where('model_id', $project->id)
                ->where('model_type', get_class($project))
                ->delete();
        }

        return ProjectResource::make($project->fresh()->load(['languages', 'skills', 'images']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
