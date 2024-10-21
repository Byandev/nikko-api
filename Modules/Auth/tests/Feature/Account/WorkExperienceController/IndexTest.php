<?php

namespace Modules\Auth\Tests\Feature\Account\WorkExperienceController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\WorkExperience;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_account_work_experiences()
    {
        $account = Account::factory()->create();

        WorkExperience::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id]);

        $this->getJson(route('api.account.work-experiences.index', ['account' => $account]))
            ->assertSuccessful()
            ->assertJsonCount($count, 'data');
    }
}
