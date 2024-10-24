<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\EmailVerificationRequest;
use Modules\Auth\Notifications\EmailVerificationNotification;
use Modules\Auth\Transformers\UserResource;

class EmailVerificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        $user->notify(new EmailVerificationNotification($user->generateEmailVerificationCode()));

        return response()->json(['message' => 'Email verification sent.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $user = $request->user();

        $user->update(['email_verified_at' => now()]);

        return UserResource::make($user->fresh()->loadMissing([
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
        ]));
    }
}
