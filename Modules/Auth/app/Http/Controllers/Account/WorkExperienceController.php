<?php

namespace Modules\Auth\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\Account\CreateWorkExperience;
use Modules\Auth\Http\Requests\Account\UpdateWorkExperience;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\WorkExperience;
use Modules\Auth\Transformers\WorkExperienceResource;

class WorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Account $account)
    {
        return WorkExperienceResource::collection($account->workExperiences);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateWorkExperience $request)
    {
        $work_experience = $request->account->workExperiences()->create($request->validated());

        return WorkExperienceResource::make($work_experience);
    }

    /**
     * Show the specified resource.
     */
    public function show(Account $account, WorkExperience $workExperience)
    {
        return WorkExperienceResource::make($workExperience);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkExperience $request, WorkExperience $workExperience)
    {
        $workExperience->update($request->validated());

        return WorkExperienceResource::make($workExperience->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkExperience $workExperience)
    {
        $workExperience->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
