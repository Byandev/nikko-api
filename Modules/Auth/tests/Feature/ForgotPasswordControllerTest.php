<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\User;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_forgot_password()
    {
        $user = User::factory()->create();

        $this->postJson(route('api.auth.forgot-password'), ['email' => $user->email])
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'expires_at',
                ],
            ]);
    }

    public function test_non_existing_user_cannot_forgot_password()
    {
        $this->postJson(route('api.auth.forgot-password'), ['email' => fake()->email()])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('email');
    }
}
