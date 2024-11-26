<?php

namespace Modules\Auth\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Models\User;
use Modules\Auth\Transformers\UserResource;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $user = User::whereEmail($request->post('email'))
            ->whereHas('roles', fn (Builder $builder) => $builder->where('name', 'ADMIN'))
            ->with([
                'avatar',
            ])
            ->first();

        $accessToken = $user->createToken($request->header('user-agent', config('app.name')));

        return response([
            'user' => new UserResource($user),
            'access_token' => $accessToken->plainTextToken,
        ])->header('Authorization', $accessToken->plainTextToken);
    }
}
