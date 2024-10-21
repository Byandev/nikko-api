<?php

namespace Modules\Certificate\Tests\Feature\CertificateController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Certificate\Models\Certificate;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_certificate()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $certificate = Certificate::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.certificates.destroy', ['certificate' => $certificate]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_update_certificate()
    {
        $certificate = Certificate::factory()->create();

        $this->deleteJson(route('api.account.certificates.update', ['certificate' => $certificate]))
            ->assertUnauthorized();
    }

    public function test_user_cannot_delete_portfolio_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $certificate = Certificate::factory()->create();

        $this->deleteJson(route('api.account.certificates.update', ['certificate' => $certificate]))
            ->assertForbidden();
    }

    public function test_user_cannot_delete_portfolio_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $certificate = Certificate::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.certificates.update', ['certificate' => $certificate]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
