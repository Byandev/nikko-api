<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Auth\Models\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['user', 'access_token']);
    }

    public function test_user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'invalid_password',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        $this->postJson('/api/v1/auth/register', [
            'email' => fake()->email(),
            'password' => fake()->password(),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }
}
