<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Modules\Auth\Http\Requests\UpdateAccountRequest;
use Modules\Auth\Models\Account;
use Modules\Auth\Transformers\AccountResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Account::class)
            ->when($request->account, function (Builder $query) use ($request) {
                $query->appendIsSavedBy($request->account);
            })
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('has_skills'),
                AllowedFilter::scope('user_countries'),
                AllowedFilter::callback('is_saved', function (Builder $query) use ($request) {
                    $query->when($request->account, function (Builder $query) use ($request) {
                        $query->onlySavedBy($request->account);
                    });
                }),
            ])
            ->allowedIncludes([
                'user',
                'user.avatar',
                'user.languages',
                'skills',
                'tools',
                'workExperiences',
                'educations',
                'portfolios',
                'certificates',
            ])
            ->paginate();

        return AccountResource::collection($data);
    }

    public function show(Account $account)
    {
        return AccountResource::make($account->load([
            'skills',
            'tools',
            'educations',
            'workExperiences',
            'portfolios',
            'certificates',
            'user' => [
                'avatar',
                'banner',
                'languages',
            ],
        ]));
    }

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

        if ($request->has('work_experiences')) {
            $account->workExperiences()->delete();

            $account->workExperiences()->createMany($request->validated('work_experiences'));
        }

        if ($request->has('educations')) {
            $account->educations()->delete();

            $account->educations()->createMany($request->validated('educations'));
        }

        if ($request->has('skills')) {
            $account->skills()->sync($request->post('skills', []));
        }

        if ($request->has('tools')) {
            $account->tools()->sync($request->post('tools', []));
        }

        return AccountResource::make($account->fresh()->load([
            'tools',
            'skills',
            'educations',
            'certificates',
            'workExperiences',
            'user' => [
                'avatar',
                'banner',
                'languages',
            ],
        ]));
    }
}
