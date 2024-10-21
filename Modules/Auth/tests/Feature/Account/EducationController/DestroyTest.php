<?php

namespace Modules\Auth\Tests\Feature\Account\EducationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Modules\Auth\Models\User;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_education()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $education = Education::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.educations.destroy', ['education' => $education]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_delete_education()
    {
        $education = Education::factory()->create();

        $this->deleteJson(route('api.account.educations.update', ['education' => $education]))
            ->assertUnauthorized();
    }

    public function test_user_can_delete_education_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $education = Education::factory()->create();

        $this->deleteJson(route('api.account.educations.update', ['education' => $education]))
            ->assertForbidden();
    }

    public function test_user_can_delete_education_for_using_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $education = Education::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.educations.update', ['education' => $education]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
