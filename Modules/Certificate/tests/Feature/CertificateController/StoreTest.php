<?php

namespace Modules\Certificate\Tests\Feature\CertificateController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Certificate\Models\Certificate;
use Modules\Media\Models\Media;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_new_certificate()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);

        $data = Arr::except(Certificate::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $data['image'] = Media::factory()->create()->id;

        $this->postJson(route('api.account.certificates.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_create_certificate()
    {
        $this->postJson(route('api.account.certificates.store'))
            ->assertUnauthorized();
    }

    public function test_user_cannot_create_certificate_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.account.certificates.store'))
            ->assertForbidden();
    }

    public function test_user_cannot_create_certificate_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);

        $this->postJson(route('api.account.certificates.store'), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
