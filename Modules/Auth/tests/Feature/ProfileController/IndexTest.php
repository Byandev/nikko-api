<?php

namespace Modules\Auth\Tests\Feature\ProfileController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_get_profile(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->getJson(route('api.auth.profile.index'))
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_guess_cannot_get_profile()
    {
        $this->getJson(route('api.auth.profile.index'))
            ->assertUnauthorized();
    }
}
