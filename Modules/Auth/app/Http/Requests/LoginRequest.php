<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Modules\Auth\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $user = User::whereEmail($this->post('email'))->first();

                if (! $user) {
                    $validator->errors()->add('email', 'Invalid credentials.');
                }

                if ($user && ! Hash::check($this->post('password'), $user->password)) {
                    $validator->errors()->add('email', 'Invalid credentials.');
                }
            },
        ];
    }
}
