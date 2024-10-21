<?php

namespace Modules\Portfolio\Tests\Feature\PortfolioController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Portfolio\Models\Portfolio;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_account_portfolio()
    {
        $portfolio = Portfolio::factory()->create();

        $this->getJson(
            route('api.account.portfolios.show', [
                'account' => $portfolio->account,
                'portfolio' => $portfolio,
            ])
        )
            ->assertSuccessful();
    }
}
