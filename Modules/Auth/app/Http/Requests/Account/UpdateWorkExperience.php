<?php

namespace Modules\Auth\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Auth\Enums\EmploymentType;

class UpdateWorkExperience extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'job_title' => 'sometimes|string',
            'company' => 'sometimes|string',
            'website' => 'sometimes|string|url',
            'country' => 'sometimes|string',
            'description' => 'sometimes|string',
            'start_month' => 'sometimes|numeric|max:12|min:1',
            'start_year' => 'sometimes|numeric|max:2024|min:2000',
            'end_month' => 'sometimes|numeric|max:12|min:1',
            'end_year' => 'sometimes|numeric|max:2024|min:2000',
            'employment' => ['sometimes', Rule::enum(EmploymentType::class)],
            'is_current' => 'sometimes|boolean',
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
