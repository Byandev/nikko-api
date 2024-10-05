<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Models\User;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        $user = User::whereEmail($request->post('email'))->first();

        $user->update([
            'password' => Hash::make($request->post('password')),
        ]);

        return response([
            'message' => 'Your password has been reset.',
        ]);
    }
}
