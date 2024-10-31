<?php

namespace Modules\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Project\Enums\ExperienceLevel;
use Modules\Project\Enums\ProjectLength;
use Modules\Project\Enums\ProjectStatus;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|required|max:255',
            'description' => 'sometimes|string|required',
            'estimated_budget' => 'sometimes|required|numeric',
            'status' => [
                'sometimes',
                'required',
                Rule::in(
                    ProjectStatus::DRAFT->value,
                    ProjectStatus::ACTIVE->value
                ),
            ],
            'length' => [
                'sometimes',
                'required',
                Rule::in(
                    ProjectLength::SHORT_TERM->value,
                    ProjectLength::MEDIUM_TERM->value,
                    ProjectLength::LONG_TERM->value,
                    ProjectLength::EXTENDED->value,
                ),
            ],
            'experience_level' => [
                'sometimes',
                'required', Rule::in(
                    ExperienceLevel::ANY->value,
                    ExperienceLevel::ENTRY->value,
                    ExperienceLevel::INTERMEDIATE->value,
                    ExperienceLevel::EXPERT->value,
                )],

            'languages' => 'sometimes|array',
            'languages.*.name' => 'required',

            'skills' => 'sometimes|array',
            'skills.*' => 'required|exists:skills,id',

            'images' => 'sometimes|array',
            'images.*' => 'required|numeric|exists:media,id',
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
