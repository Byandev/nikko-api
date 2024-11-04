<?php

namespace Modules\Auth\Tests\Feature\AccountController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Enums\AccountType;
use Modules\Auth\Models\Account;
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
}
