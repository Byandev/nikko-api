<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes',
            'last_name' => 'sometimes',
            'avatar' => 'nullable',
            'banner' => 'nullable',
            'street_address' => 'sometimes|nullable',
            'city' => 'sometimes|nullable',
            'state_code' => 'sometimes|nullable',
            'country_code' => 'sometimes|nullable',
            'postal' => 'sometimes|nullable',
            'phone_number' => 'sometimes|nullable',
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
