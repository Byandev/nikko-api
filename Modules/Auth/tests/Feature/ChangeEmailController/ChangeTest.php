<?php

namespace Modules\Auth\Tests\Feature\ChangeEmailController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class ChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_to_change_email()
    {
        Notification::fake();

        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.auth.change-email'), [
            'email' => fake()->email(),
        ])
            ->assertSuccessful();
    }
}
