<?php

namespace Modules\Auth\Tests\Feature\Account\WorkExperienceController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Auth\Models\WorkExperience;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_work_experience()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $work_experience = WorkExperience::factory()->create(['account_id' => $account->id]);

        $data = Arr::except(WorkExperience::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $this->putJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment($data);
    }

    public function test_guess_cannot_update_certificate()
    {
        $work_experience = WorkExperience::factory()->create();

        $this->putJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]))
            ->assertUnauthorized();
    }

    public function test_user_cannot_update_portfolio_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $work_experience = WorkExperience::factory()->create();

        $this->putJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]))
            ->assertForbidden();
    }

    public function test_user_cannot_update_portfolio_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $work_experience = WorkExperience::factory()->create(['account_id' => $account->id]);

        $this->putJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
