<?php

namespace Modules\Auth\Tests\Feature\SaveAccountController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Save\Models\Save;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_un_save_freelancer()
    {
        $client = Account::factory()->client()->create();
        $freelancer = Account::factory()->freelancer()->create();

        Save::factory()->create([
            'saver_id' => $client->id,
            'saver_type' => Account::class,
            'savable_id' => $freelancer->id,
            'savable_type' => Account::class,
        ]);

        Sanctum::actingAs($client->user);

        $this->deleteJson(route('api.account.un-save', ['account' => $freelancer]), [], [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment([
                'is_saved' => false,
            ]);
    }
}
