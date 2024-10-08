<?php

namespace Modules\Auth\Tests\Feature\EmailVerificationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class ResendTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_resend_email_verification()
    {
        $user = User::factory()->create();

        Notification::fake();

        Sanctum::actingAs($user);

        $this->postJson(route('api.auth.email-verification.resend'))
            ->assertSuccessful();
    }

    public function test_guess_cannot_resend_email_verification()
    {
        $this->postJson(route('api.auth.email-verification.resend'))
            ->assertUnauthorized();
    }
}
