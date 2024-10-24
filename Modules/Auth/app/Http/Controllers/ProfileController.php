<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Auth\Http\Requests\UpdateProfileRequest;
use Modules\Auth\Transformers\UserResource;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return UserResource::make(
            $request->user()->load([
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
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->update(Arr::except($request->validated(), ['avatar', 'banner']));

        if ($request->has('avatar')) {
            $avatarId = $request->input('avatar');

            is_null($avatarId) ? $user->removeAvatar() : $user->setAvatarByMediaId($avatarId);
        }

        if ($request->has('banner')) {
            $bannerId = $request->input('banner');

            is_null($bannerId) ? $user->removeBanner() : $user->setBannerByMediaId($bannerId);
        }

        return UserResource::make(
            $user->load([
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
        );
    }
}
