<?php

namespace Modules\Auth\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\Account\CreateEducationRequest;
use Modules\Auth\Http\Requests\Account\UpdateEducationRequest;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Modules\Auth\Transformers\EducationResource;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Account $account)
    {
        return EducationResource::collection($account->educations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEducationRequest $request)
    {
        $education = $request->account->educations()->create($request->validated());

        return EducationResource::make($education);
    }

    /**
     * Show the specified resource.
     */
    public function show(Account $account, Education $education)
    {
        return EducationResource::make($education);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEducationRequest $request, Education $education)
    {
        $education->update($request->validated());

        return EducationResource::make($education->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Education $education)
    {
        $education->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
