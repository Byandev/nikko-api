<?php

namespace Modules\Auth\Tests\Feature\AccountController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Enums\LanguageProficiencyType;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_account()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $data = [
            'title' => fake()->jobTitle,
            'bio' => fake()->paragraph,
        ];

        $this->putJson(route('api.auth.account.update', ['account' => $account]), $data)
            ->assertSuccessful()
            ->assertJsonFragment($data);
    }

    public function test_user_can_update_account_languages()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $languages = collect(range(1, fake()->numberBetween(2, 5)))
            ->map(fn () => [
                'name' => fake()->languageCode,
                'proficiency' => fake()->randomElement([
                    LanguageProficiencyType::BEGINNER->value,
                    LanguageProficiencyType::INTERMEDIATE->value,
                    LanguageProficiencyType::FLUENT->value,
                ]),
            ])
            ->toArray();

        $data = [
            'languages' => $languages,
        ];

        $this->putJson(route('api.auth.account.update', ['account' => $account]), $data)
            ->assertSuccessful()
            ->assertJsonCount(count($languages), 'data.user.languages');
    }
}
