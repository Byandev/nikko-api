<?php

namespace Modules\Auth\Tests\Feature\Account\WorkExperienceController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\WorkExperience;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_account_work_experience_details()
    {
        $account = Account::factory()->create();

        $work_experience = WorkExperience::factory()->create(['account_id' => $account->id]);

        $this->getJson(route('api.account.work-experiences.show', ['account' => $account, 'work_experience' => $work_experience]))
            ->assertSuccessful();
    }
}
