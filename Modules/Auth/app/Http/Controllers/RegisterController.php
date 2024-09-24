<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Auth\Http\Requests\RegistrationRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Transformers\UserResource;

class RegisterController extends Controller
{
    public function __invoke(RegistrationRequest $request)
    {
        $user = User::create($request->validated());

        $accessToken = $user->createToken($request->header('user-agent', config('app.name')));

        return response([
            'user' => new UserResource($user),
            'access_token' => $accessToken->plainTextToken,
        ]);
    }
}
