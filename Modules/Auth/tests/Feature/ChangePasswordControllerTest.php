<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class ChangePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_password()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.auth.change-password'), [
            'old_password' => 'password',
            'new_password' => $password = fake()->password(),
            'new_password_confirmation' => $password,
        ])
            ->assertSuccessful();
    }

    public function test_user_cannot_change_password_with_same_password()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.auth.change-password'), [
            'old_password' => $password = 'password',
            'new_password' => $password,
            'new_password_confirmation' => $password,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('new_password');
    }
}
