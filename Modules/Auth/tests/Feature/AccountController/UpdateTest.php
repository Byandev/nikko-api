<?php

namespace Modules\Auth\Tests\Feature\AccountController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Enums\LanguageProficiencyType;
use Modules\Auth\Models\Account;
use Modules\Auth\Models\Education;
use Modules\Auth\Models\User;
use Modules\Auth\Models\WorkExperience;
use Modules\Skill\Models\Skill;
use Modules\Tool\Models\Tool;
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

    public function test_user_can_update_languages()
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

    public function test_user_can_update_account_skills()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $skills = Skill::factory()->count(fake()->numberBetween(5, 10))->create();

        Sanctum::actingAs($user);

        $count = fake()->numberBetween(2, $skills->count());

        $data = [
            'skills' => $skills->random($count)->map(fn ($skill) => $skill->id)->toArray(),
        ];

        $this->putJson(route('api.auth.account.update', ['account' => $account]), $data)
            ->assertSuccessful()
            ->assertJsonCount($count, 'data.skills');
    }

    public function test_user_can_update_account_tools()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $tools = Tool::factory()->count(fake()->numberBetween(5, 10))->create();

        Sanctum::actingAs($user);

        $count = fake()->numberBetween(2, $tools->count());

        $data = [
            'tools' => $tools->random($count)->map(fn ($tool) => $tool->id)->toArray(),
        ];

        $this->putJson(route('api.auth.account.update', ['account' => $account]), $data)
            ->assertSuccessful()
            ->assertJsonCount($count, 'data.tools');
    }

    public function test_user_can_update_account_work_experiences()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $data = [
            'work_experiences' => WorkExperience::factory()
                ->count($count = fake()->numberBetween(2, 5))
                ->make(['account_id' => $account->id]),
        ];

        $this->putJson(route('api.auth.account.update', ['account' => $account]), $data)
            ->assertSuccessful()
            ->assertJsonCount($count, 'data.work_experiences');
    }

    public function test_user_can_update_account_educations()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $data = [
            'educations' => Education::factory()
                ->count($count = fake()->numberBetween(2, 5))
                ->make(['account_id' => $account->id]),
        ];

        $this->putJson(route('api.auth.account.update', ['account' => $account]), $data)
            ->assertSuccessful()
            ->assertJsonCount($count, 'data.educations');
    }
}
