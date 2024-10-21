<?php

namespace Modules\Auth\Tests\Feature\Account\WorkExperienceController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Auth\Models\WorkExperience;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_work_experience()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $work_experience = WorkExperience::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.work-experiences.destroy', ['work_experience' => $work_experience]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_delete_work_experience()
    {
        $work_experience = WorkExperience::factory()->create();

        $this->deleteJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]))
            ->assertUnauthorized();
    }

    public function test_user_can_delete_work_experience_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $work_experience = WorkExperience::factory()->create();

        $this->deleteJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]))
            ->assertForbidden();
    }

    public function test_user_can_delete_work_experience_for_using_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $work_experience = WorkExperience::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.work-experiences.update', ['work_experience' => $work_experience]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
