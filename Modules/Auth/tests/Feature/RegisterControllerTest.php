<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Models\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_register(): void
    {
        Notification::fake();

        $data = [
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'password' => $password = fake()->password(8),
            'password_confirmation' => $password,
            'account_type' => collect(AccountType::cases())->random()->value,
        ];

        $this->postJson('/api/v1/auth/register', $data)
            ->assertSuccessful()
            ->assertJsonStructure(['user', 'access_token']);

        $this->assertDatabaseHas('users', Arr::except($data, ['password', 'password_confirmation', 'account_type']));
    }

    public function test_user_cannot_register_with_already_registered_email()
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => $password = fake()->password(8),
            'password_confirmation' => $password,
        ];

        $this->postJson('/api/v1/auth/register', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_unconfirmed_password()
    {
        $data = [
            'email' => fake()->email(),
            'password' => $password = fake()->password(),
            'password_confirmation' => "not_{$password}",
        ];

        $this->postJson('/api/v1/auth/register', $data)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }
}
