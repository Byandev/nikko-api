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
            ->appendTotalSpent()
            ->appendTotalEarnings()
            ->when($request->account, function (Builder $query) use ($request) {
                $query->appendIsSavedBy($request->account);
            })
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('skills'),
                AllowedFilter::scope('user_countries'),
                AllowedFilter::callback('is_saved', function (Builder $query) use ($request) {
                    $query->when($request->account, function (Builder $query) use ($request) {
                        $query->onlySavedBy($request->account);
                    });
                }),
                AllowedFilter::callback('can_be_invited_to_project', function (Builder $builder, $value) use ($request) {
                    $builder->when($request->account, function (Builder $query) use ($value) {
                        $query->where(function (Builder $subQuery) use ($value) {
                            $subQuery->whereDoesntHave('proposals', fn (Builder $proposalQuery) => $proposalQuery->where('project_id', $value))
                                ->whereDoesntHave('proposalInvitations', fn (Builder $proposalInvitationQuery) => $proposalInvitationQuery->where('project_id', $value));
                        });
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
                'proposalInvitationToProject',
            ])
            ->paginate($request->input('per_page', 10));

        $savedCount = QueryBuilder::for(Account::class)
            ->when($request->account, function (Builder $query) use ($request) {
                $query->onlySavedBy($request->account);
            })
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('skills'),
                AllowedFilter::scope('user_countries'),
                AllowedFilter::callback('is_saved', function (Builder $query) {
                    $query->whereNotNull('id');
                }),
            ])
            ->count();

        $totalCount = QueryBuilder::for(Account::class)
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('skills'),
                AllowedFilter::scope('user_countries'),
                AllowedFilter::callback('is_saved', function (Builder $query) {
                    $query->whereNotNull('id');
                }),
            ])
            ->count();

        return AccountResource::collection($data)
            ->additional([
                'meta' => [
                    'total_count' => $totalCount,
                    'total_saved_count' => $request->account ? $savedCount : 0,
                ],
            ]);
    }

    public function show(Request $request, Account $account)
    {
        $account->load([
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
        ]);

        if ($request->account) {
            $account->is_saved = $account->isSavedBy($request->account);
        }

        $account->total_spent = $account->getTotalSpent();
        $account->total_earnings = $account->getTotalEarnings();

        return AccountResource::make($account);
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
