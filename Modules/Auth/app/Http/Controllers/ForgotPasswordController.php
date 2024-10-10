<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Models\PasswordReset;
use Modules\Auth\Models\User;
use Modules\Auth\Notifications\ResetPasswordNotification;
use Modules\Auth\Transformers\PasswordResetResource;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        $user = User::whereEmail($request->post('email'))->first();

        PasswordReset::where('user_id', $user->id)->delete();

        $passwordReset = PasswordReset::create([
            'user_id' => $user->id,
            'token' => Str::random(6),
            'expires_at' => now()->addMinutes(15),
        ]);

        $user->notify(new ResetPasswordNotification($passwordReset->token));

        return PasswordResetResource::make($passwordReset);
    }
}
