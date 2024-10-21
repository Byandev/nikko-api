<?php

namespace Modules\Portfolio\Tests\Feature\PortfolioController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Account;
use Modules\Portfolio\Models\Portfolio;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_user_portfolios()
    {
        $account = Account::factory()->create();

        Portfolio::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id]);

        $this->getJson(route('api.account.portfolios.index', ['account' => $account]))
            ->assertSuccessful()
            ->assertJsonCount($count, 'data');
    }
}
