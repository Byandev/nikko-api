<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_logout(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.auth.logout'))
            ->assertSuccessful();
    }

    public function test_guess_cannot_logout()
    {
        $this->postJson(route('api.auth.logout'))
            ->assertUnauthorized();
    }
}
