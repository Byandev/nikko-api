<?php

namespace Modules\Auth\Http\Requests\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Auth\Models\PasswordReset;
use Modules\Auth\Models\User;

class CheckResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users',
            'token' => 'required|exists:password_resets',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'token.exists' => 'Invalid token.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $user = User::whereEmail($this->post('email'))->first();

                $tokenIsValid = PasswordReset::where('user_id', $user->id)
                    ->where('token', $this->post('token'))
                    ->where('expires_at', '>', now())
                    ->exists();

                if (! $tokenIsValid) {
                    $validator->errors()->add('token', 'Invalid token.');
                }
            },
        ];
    }
}
