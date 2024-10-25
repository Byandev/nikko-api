<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\RegistrationRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Notifications\EmailVerificationNotification;
use Modules\Auth\Transformers\UserResource;

class RegisterController extends Controller
{
    public function __invoke(RegistrationRequest $request)
    {
        $user = User::create([
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'first_name' => $request->post('first_name'),
            'last_name' => $request->post('last_name'),
        ]);

        $user->accounts()->create([
            'type' => $request->post('account_type'),
        ]);

        $user->notify(new EmailVerificationNotification($user->generateEmailVerificationCode()));

        $accessToken = $user->createToken($request->header('user-agent', config('app.name')));

        return response([
            'user' => new UserResource($user->load([
                'avatar',
                'banner',
                'languages',
                'accounts' => [
                    'skills',
                    'tools',
                    'educations',
                    'workExperiences',
                    'portfolios',
                    'certificates',
                ],
            ])),
            'access_token' => $accessToken->plainTextToken,
        ])->header('Authorization', $accessToken->plainTextToken);
    }
}
