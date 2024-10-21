<?php

namespace Modules\Certificate\Tests\Feature\CertificateController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Account;
use Modules\Certificate\Models\Certificate;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_account_certificates()
    {
        $account = Account::factory()->create();

        Certificate::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id]);

        $this->getJson(route('api.account.certificates.index', ['account' => $account]))
            ->assertSuccessful()
            ->assertJsonCount($count, 'data');
    }
}
