<?php

namespace Modules\Auth\Tests\Feature\SaveAccountController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_save_freelancer()
    {
        $client = Account::factory()->client()->create();
        $freelancer = Account::factory()->freelancer()->create();

        Sanctum::actingAs($client->user);

        $this->postJson(route('api.account.save', ['account' => $freelancer]), [], [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'is_saved' => true,
            ]);
    }
}
