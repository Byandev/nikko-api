<?php

namespace Modules\Auth\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEducationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'degree' => 'sometimes|string',
            'country' => 'sometimes|string',
            'description' => 'sometimes|string',
            'start_month' => 'sometimes|nullable|numeric|max:12|min:1',
            'start_year' => 'sometimes|nullable|numeric|max:2024|min:2000',
            'end_month' => 'sometimes|nullable|numeric|max:12|min:1',
            'end_year' => 'sometimes|nullable|numeric|max:2024|min:2000',
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
