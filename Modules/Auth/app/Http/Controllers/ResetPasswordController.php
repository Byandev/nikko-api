<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ResetPassword\CheckResetPasswordRequest;
use Modules\Auth\Http\Requests\ResetPassword\ResetPasswordRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Transformers\PasswordResetResource;

class ResetPasswordController extends Controller
{
    public function check(CheckResetPasswordRequest $request)
    {
        $user = User::whereEmail($request->post('email'))
            ->with('passwordReset')
            ->first();

        return PasswordResetResource::make($user->passwordReset);
    }

    public function store(ResetPasswordRequest $request)
    {
        $user = User::whereEmail($request->post('email'))->first();

        $user->update([
            'password' => Hash::make($request->post('password')),
        ]);

        $user->passwordReset()->delete();

        return response([
            'message' => 'Your password has been reset.',
        ]);
    }
}
