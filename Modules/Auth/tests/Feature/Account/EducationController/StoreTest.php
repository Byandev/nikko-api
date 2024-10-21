<?php

namespace Modules\Auth\Tests\Feature\Account\EducationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Modules\Auth\Models\User;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_education()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);

        $data = Arr::except(Education::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $this->postJson(route('api.account.educations.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_create_education()
    {
        $this->postJson(route('api.account.educations.store'))
            ->assertUnauthorized();
    }

    public function test_user_cannot_create_education_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.account.educations.store'))
            ->assertForbidden();
    }

    public function test_user_cannot_create_education_using_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);

        $this->postJson(route('api.account.educations.store'), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
