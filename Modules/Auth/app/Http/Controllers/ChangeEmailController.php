<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Auth\Http\Requests\ChangeEmail\ChangeEmailRequest;
use Modules\Auth\Http\Requests\ChangeEmail\ChangeEmailVerifyRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Notifications\ChangeEmailNotification;
use Modules\Auth\Transformers\UserResource;

class ChangeEmailController extends Controller
{
    public function change(ChangeEmailRequest $request)
    {
        $user = User::find(Auth::id());

        $user->changedRequests()->updateOrCreate(
            ['field_name' => 'email'],
            [
                'from' => $user->email,
                'to' => $request->post('email'),
//                'token' => Hash::make($token = Str::random(6)),
                'token' => Hash::make($token = '000000'),
            ],
        );

        $user->notify(new ChangeEmailNotification($token));

        return response([
            'message' => 'We send a verification code to your target email.',
        ]);
    }

    public function verify(ChangeEmailVerifyRequest $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $user = User::find(Auth::id());

        $changeRequest = $user->changedRequests()->where('field_name', 'email')->first();

        $user->update([
            'email' => $changeRequest->to,
        ]);

        $changeRequest->delete();

        return UserResource::make($user->fresh()->load(['avatar', 'accounts', 'banner']));
    }
}
