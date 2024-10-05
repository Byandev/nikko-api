<?php

namespace Modules\Auth\Tests\Feature\ProfileController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_update_own_profile(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $data = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->firstName(),
        ];

        $this->putJson(route('api.auth.profile.update'), $data)
            ->assertSuccessful()
            ->assertJsonFragment($data);
    }

    public function test_guess_cannot_get_profile()
    {
        $this->putJson(route('api.auth.profile.update'))
            ->assertUnauthorized();
    }
}