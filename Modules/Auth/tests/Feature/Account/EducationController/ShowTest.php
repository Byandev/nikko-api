<?php

namespace Modules\Auth\Tests\Feature\Account\EducationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_account_education_details()
    {
        $account = Account::factory()->create();

        $education = Education::factory()->create(['account_id' => $account->id]);

        $this->getJson(route('api.account.educations.show', ['account' => $account, 'education' => $education]))
            ->assertSuccessful();
    }
}
