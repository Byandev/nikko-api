<?php

namespace Modules\Auth\Tests\Feature\AccountController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Models\Account;
use Modules\Save\Models\Save;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_list_of_accounts()
    {
        Account::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create();

        $this->getJson(route('api.account.index'))
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }

    public function test_user_can_get_list_of_accounts_filtered_by_type()
    {
        $filter = collect(AccountType::cases())->random()->value;
        $unFilter = $filter === AccountType::CLIENT->value ? AccountType::FREELANCER->value : AccountType::CLIENT->value;

        Account::factory()
            ->count($filteredCount = fake()->numberBetween(2, 5))
            ->create(['type' => $filter]);

        Account::factory()
            ->count(fake()->numberBetween(2, 5))
            ->create(['type' => $unFilter]);

        $this->getJson(route('api.account.index', [
            'filter[type]' => $filter,
        ]))
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $filteredCount]);
    }

    public function test_user_can_get_list_of_accounts_with_relations()
    {
        Account::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create();

        $this->getJson(route('api.account.index', [
            'include' => [
                'user',
                'user.avatar',
                'user.languages',
                'skills',
                'tools',
                'workExperiences',
                'educations',
                'portfolios',
                'certificates',
            ],
        ]))
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }

    public function test_user_can_get_list_of_accounts_with_is_saved_tag()
    {
        $client = Account::factory()->client()->create();

        Sanctum::actingAs($client->user);

        Account::factory()
            ->freelancer()
            ->count($count = fake()->numberBetween(2, 5))
            ->create()
            ->each(function (Account $freelancer) use ($client) {
                Save::factory()->create([
                    'saver_id' => $client->id,
                    'saver_type' => Account::class,
                    'savable_id' => $freelancer->id,
                    'savable_type' => Account::class,
                ]);
            });

        $this->getJson(route('api.account.index', [
            'filter[type]' => AccountType::FREELANCER->value,
        ]), [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count])
            ->assertJsonFragment(['is_saved' => true]);
    }

    public function test_user_can_get_filtered_accounts_that_is_saved()
    {
        $client = Account::factory()->client()->create();

        Sanctum::actingAs($client->user);

        $savedAccounts = Account::factory()
            ->freelancer()
            ->count($savedAccountsCount = fake()->numberBetween(2, 5))
            ->create();

        $savedAccounts->each(function (Account $freelancer) use ($client) {
            Save::factory()->create([
                'saver_id' => $client->id,
                'saver_type' => Account::class,
                'savable_id' => $freelancer->id,
                'savable_type' => Account::class,
            ]);
        });

        Account::factory()
            ->freelancer()
            ->count(fake()->numberBetween(2, 5))
            ->create();

        $this->getJson(route('api.account.index', [
            'filter[type]' => AccountType::FREELANCER->value,
            'filter[is_saved]' => true,
        ]), [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $savedAccountsCount])
            ->assertJsonFragment(['is_saved' => true]);
    }
}
