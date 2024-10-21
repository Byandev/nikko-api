<?php

namespace Modules\Portfolio\Tests\Feature\PortfolioController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Media\Models\Media;
use Modules\Portfolio\Models\Portfolio;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_portfolio()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);
        $portfolio = Portfolio::factory()->create(['account_id' => $account->id]);

        $data = Arr::except(Portfolio::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $data['images'] = Media::factory()
            ->count(fake()->numberBetween(2, 5))
            ->create()->map(fn (Media $media) => $media->id)
            ->toArray();

        $this->putJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_update_portfolio()
    {
        $portfolio = Portfolio::factory()->create();

        $this->putJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]))
            ->assertUnauthorized();
    }

    public function test_user_cannot_update_portfolio_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $portfolio = Portfolio::factory()->create();

        $this->putJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]))
            ->assertForbidden();
    }

    public function test_user_cannot_update_portfolio_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);
        $portfolio = Portfolio::factory()->create(['account_id' => $account->id]);

        $this->putJson(route('api.account.portfolios.update', ['portfolio' => $portfolio]), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
