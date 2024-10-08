<?php

namespace Modules\Auth\Tests\Feature\EmailVerificationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_verify_email()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $code = $user->generateEmailVerificationCode();

        $this->postJson(route('api.auth.email-verification.verify'), ['code' => $code])
            ->assertSuccessful();
    }

    public function test_user_cannot_verify_email_with_invalid_code()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.auth.email-verification.verify'), ['code' => fake()->lexify('??????')])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('code');
    }
}
