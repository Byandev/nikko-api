<?php

namespace Modules\Auth\Http\Requests\ChangeEmail;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Modules\Auth\Models\User;

class ChangeEmailVerifyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
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
                $user = User::find(Auth::id());

                $changeRequest = $user->changedRequests()->where('field_name', 'email')->first();

                if (is_null($changeRequest) || ($changeRequest && ! $changeRequest->isTokenValid($this->input('token')))) {
                    $validator->errors()->add('token', 'The verification code was invalid.');
                }
            },
        ];
    }
}
