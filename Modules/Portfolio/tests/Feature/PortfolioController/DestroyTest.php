<?php

namespace Modules\Auth\Tests\Feature\PortfolioController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Portfolio\Models\Portfolio;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_deletePortfolio()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $portfolio = Portfolio::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.portfolios.destroy', ['portfolio' => $portfolio]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_update_portfolio()
    {
        $portfolio = Portfolio::factory()->create();

        $this->deleteJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]))
            ->assertUnauthorized();
    }

    public function test_user_cannot_delete_portfolio_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $portfolio = Portfolio::factory()->create();

        $this->deleteJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]))
            ->assertForbidden();
    }

    public function test_user_cannot_delete_portfolio_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $portfolio = Portfolio::factory()->create(['account_id' => $account->id]);

        $this->deleteJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
