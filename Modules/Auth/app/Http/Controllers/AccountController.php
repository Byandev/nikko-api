<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Modules\Auth\Http\Requests\UpdateAccountRequest;
use Modules\Auth\Models\Account;
use Modules\Auth\Transformers\AccountResource;

class AccountController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account->load('user');

        if ($request->has('title') || $request->has('bio')) {
            $account->update(Arr::only($request->validated(), ['title', 'bio']));
        }

        if ($request->has('languages')) {
            $account->user->languages()->delete();

            $account->user->languages()->createMany($request->post('languages'));
        }

        return AccountResource::make($account->fresh()->load(['user' => ['languages']]));
    }
}
