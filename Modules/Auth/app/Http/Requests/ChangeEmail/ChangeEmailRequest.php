<?php

namespace Modules\Auth\Http\Requests\ChangeEmail;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Models\User;

class ChangeEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users',
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
