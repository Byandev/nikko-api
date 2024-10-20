<?php

namespace Modules\Auth\Tests\Feature\PortfolioController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Modules\Media\Models\Media;
use Modules\Portfolio\Models\Portfolio;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_new_portfolio()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->freelancer()->create(['user_id' => $user->id]);

        $data = Arr::except(Portfolio::factory()->make(['account_id' => $account->id])->toArray(), ['account_id']);

        $data['images'] = Media::factory()
            ->count(fake()->numberBetween(2, 5))
            ->create()->map(fn (Media $media) => $media->id)
            ->toArray();

        $this->postJson(route('api.account.portfolios.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }

    public function test_guess_cannot_create_portfolio()
    {
        $this->postJson(route('api.account.portfolios.store'))
            ->assertUnauthorized();
    }

    public function test_user_cannot_create_portfolio_if_account_is_not_specified()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.account.portfolios.store'))
            ->assertForbidden();
    }

    public function test_user_cannot_create_portfolio_for_a_client_account()
    {
        Sanctum::actingAs($user = User::factory()->create());
        $account = Account::factory()->client()->create(['user_id' => $user->id]);

        $this->postJson(route('api.account.portfolios.store'), [], [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertForbidden();
    }
}
