<?php

namespace Modules\Auth\Tests\Feature\Account\WorkExperienceController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Auth\Models\WorkExperience;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_new_work_experience()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);

        $data = Arr::except(WorkExperience::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $this->postJson(route('api.account.work-experiences.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_create_work_experience()
    {
        $this->postJson(route('api.account.work-experiences.store'))
            ->assertUnauthorized();
    }

    public function test_user_cannot_create_work_experience_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.account.work-experiences.store'))
            ->assertForbidden();
    }

    public function test_user_cannot_create_work_experience_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);

        $this->postJson(route('api.account.work-experiences.store'), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
