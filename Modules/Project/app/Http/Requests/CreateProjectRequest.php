<?php

namespace Modules\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Project\Enums\ExperienceLevel;
use Modules\Project\Enums\ProjectLength;
use Modules\Project\Enums\ProjectStatus;

class CreateProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'string|required|max:255',
            'description' => 'string|required',
            'estimated_budget' => 'required|numeric',
            'status' => [
                'required',
                Rule::in(
                    ProjectStatus::DRAFT->value,
                    ProjectStatus::ACTIVE->value
                ),
            ],
            'length' => [
                'required', Rule::in(
                    ProjectLength::SHORT_TERM->value,
                    ProjectLength::MEDIUM_TERM->value,
                    ProjectLength::LONG_TERM->value,
                    ProjectLength::EXTENDED->value,
                )],
            'experience_level' => [
                'required', Rule::in(
                    ExperienceLevel::ANY->value,
                    ExperienceLevel::ENTRY->value,
                    ExperienceLevel::INTERMEDIATE->value,
                    ExperienceLevel::EXPERT->value,
                )],

            'languages' => 'required|array|min:1',
            'languages.*.name' => 'required',

            'skills' => 'required|array|min:1',
            'skills.*' => 'required|exists:skills,id',

            'images' => 'required|array',
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
