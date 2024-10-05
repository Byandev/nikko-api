<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\PasswordReset;
use Modules\Auth\Models\User;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password()
    {
        $user = User::factory()->create();

        $passwordReset = PasswordReset::factory()->create(['user_id' => $user->id]);

        $this->postJson(route('api.auth.reset-password'), [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $passwordReset->token,
        ])
            ->assertSuccessful();
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create();

        $this->postJson(route('api.auth.reset-password'), [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => 'invalid-token',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('token');
    }

    public function test_user_cannot_reset_password_with_expired_token()
    {
        $user = User::factory()->create();

        $passwordReset = PasswordReset::factory()
            ->expired()
            ->create(['user_id' => $user->id]);

        $this->postJson(route('api.auth.reset-password'), [
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $passwordReset->token,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('token');
    }
}
