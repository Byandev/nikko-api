<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\UpdateProfileRequest;
use Modules\Auth\Transformers\UserResource;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return UserResource::make($request->user()->loadMissing(['avatar', 'accounts']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        if ($request->has('avatar')) {
            $avatarId = $request->input('avatar');

            if (is_null($avatarId)) {
                $user->removeAvatar();
            } else {
                $user->setAvatarByMediaId($avatarId);
            }
        }

        return UserResource::make($user->loadMissing(['avatar', 'accounts']));
    }
}
