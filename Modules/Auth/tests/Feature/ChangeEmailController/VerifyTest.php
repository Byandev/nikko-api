<?php

namespace Modules\Auth\Tests\Feature\ChangeEmailController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\ChangeRequest;
use Modules\Auth\Models\User;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_change_email_request()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        ChangeRequest::factory()->create(['changeable_type' => User::class, 'changeable_id' => $user->id]);

        $this->postJson(route('api.auth.change-email.verify'), [
            'token' => '000000',
        ])
            ->assertSuccessful();
    }

    public function test_user_cannot_verify_change_email_if_theres_no_change_request()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.auth.change-email.verify'), [
            'token' => '000000',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('token');
    }

    public function test_user_cannot_verify_change_email_with_incorrect_token()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        ChangeRequest::factory()->create(['changeable_type' => User::class, 'changeable_id' => $user->id]);

        $this->postJson(route('api.auth.change-email.verify'), [
            'token' => 'incorrect-token',
        ])
            ->assertJsonValidationErrorFor('token');
    }
}
