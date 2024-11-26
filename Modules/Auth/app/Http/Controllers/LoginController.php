<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Transformers\UserResource;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $user = User::whereEmail($request->post('email'))
            ->doesntHave('roles')
            ->with([
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
            ])
            ->first();

        $accessToken = $user->createToken($request->header('user-agent', config('app.name')));

        return response([
            'user' => new UserResource($user),
            'access_token' => $accessToken->plainTextToken,
        ])->header('Authorization', $accessToken->plainTextToken);
    }
}
