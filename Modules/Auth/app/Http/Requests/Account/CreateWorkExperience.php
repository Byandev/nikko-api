<?php

namespace Modules\Auth\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Auth\Enums\EmploymentType;

class CreateWorkExperience extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'job_title' => 'required|string',
            'company' => 'required|string',
            'website' => 'required|string|url',
            'country' => 'required|string',
            'description' => 'required|string',
            'start_month' => 'required|numeric|max:12|min:1',
            'start_year' => 'required|numeric|max:2024|min:2000',
            'end_month' => 'required_if:is_current,false|numeric|max:12|min:1',
            'end_year' => 'required_if:is_current,false|numeric|max:2024|min:2000',
            'employment' => ['required', Rule::enum(EmploymentType::class)],
            'is_current' => 'required|boolean',
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
