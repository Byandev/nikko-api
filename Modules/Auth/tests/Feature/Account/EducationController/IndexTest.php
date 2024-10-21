<?php

namespace Modules\Auth\Tests\Feature\Account\EducationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_account_educations()
    {
        $account = Account::factory()->create();

        Education::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id]);

        $this->getJson(route('api.account.educations.index', ['account' => $account]))
            ->assertSuccessful()
            ->assertJsonCount($count, 'data');
    }
}
