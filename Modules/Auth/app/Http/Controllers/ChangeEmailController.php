<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Http\Requests\ChangeEmail\ChangeEmailRequest;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Models\ChangeRequest;

class ChangeEmailController extends Controller
{
    public function change(ChangeEmailRequest $request)
    {
        $user = Auth::user();

        ChangeRequest::create([
            'from' => $user->email,
            'to' => $request->post('email'),
        ]);

        return response([
            'message' => 'Your password has been changed.',
        ]);
    }

    public function verify(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->post('new_password')),
        ]);

        return response([
            'message' => 'Your password has been changed.',
        ]);
    }
}
