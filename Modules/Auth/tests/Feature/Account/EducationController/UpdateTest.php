<?php

namespace Modules\Auth\Tests\Feature\Account\EducationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Modules\Auth\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_education()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $education = Education::factory()->create(['account_id' => $account->id]);

        $data = Arr::except(Education::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $this->putJson(route('api.account.educations.update', ['education' => $education]), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment($data);
    }

    public function test_guess_cannot_update_education()
    {
        $education = Education::factory()->create();

        $this->putJson(route('api.account.educations.update', ['education' => $education]))
            ->assertUnauthorized();
    }

    public function test_user_cannot_update_education_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $education = Education::factory()->create();

        $this->putJson(route('api.account.educations.update', ['education' => $education]))
            ->assertForbidden();
    }

    public function test_user_cannot_update_education_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $education = Education::factory()->create(['account_id' => $account->id]);

        $this->putJson(route('api.account.educations.update', ['education' => $education]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
