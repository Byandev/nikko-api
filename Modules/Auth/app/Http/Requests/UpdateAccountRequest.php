<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Auth\Enums\EmploymentType;
use Modules\Auth\Enums\LanguageProficiencyType;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|nullable',
            'bio' => 'sometimes|nullable',

            'languages' => 'sometimes|array',
            'languages.*.name' => 'required',
            'languages.*.proficiency' => ['required',  Rule::enum(LanguageProficiencyType::class)],

            'skills' => 'sometimes|array',
            'skills.*' => 'required|exists:skills,id',

            'tools' => 'sometimes|array',
            'tools.*' => 'required|exists:tools,id',

            'work_experiences' => 'sometimes|array',
            'work_experiences.*.job_title' => 'required|string',
            'work_experiences.*.company' => 'required|string',
            'work_experiences.*.website' => 'required|string|url',
            'work_experiences.*.country' => 'required|string',
            'work_experiences.*.description' => 'required|string',
            'work_experiences.*.start_month' => 'required|numeric|max:12|min:1',
            'work_experiences.*.start_year' => 'required|numeric|max:2024|min:2000',
            'work_experiences.*.end_month' => 'required_if:work_experiences.*.is_current,false|numeric|max:12|min:1',
            'work_experiences.*.end_year' => 'required_if:work_experiences.*.is_current,false|numeric|max:2024|min:2000',
            'work_experiences.*.employment' => ['required', Rule::enum(EmploymentType::class)],
            'work_experiences.*.is_current' => 'required|boolean',

            'educations' => 'sometimes|array',
            'educations.*.degree' => 'required|string',
            'educations.*.country' => 'required|string',
            'educations.*.description' => 'required|string',
            'educations.*.start_month' => 'nullable|numeric|max:12|min:1',
            'educations.*.start_year' => 'nullable|numeric|max:2024|min:2000',
            'educations.*.end_month' => 'nullable|numeric|max:12|min:1',
            'educations.*.end_year' => 'nullable|numeric|max:2024|min:2000',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
