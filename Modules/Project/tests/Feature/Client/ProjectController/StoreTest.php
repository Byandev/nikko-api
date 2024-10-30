<?php

namespace Modules\Project\Tests\Feature\Client\ProjectController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Project\Models\Project;
use Modules\Skill\Models\Skill;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_client_can_create_project(): void
    {
        $account = Account::factory()->client()->create();
        $skills = Skill::factory()->count(fake()->numberBetween(5, 10))->create();

        $data = Project::factory()
            ->make(['account_id' => $account->id])
            ->toArray();

        $skillsCount = fake()->numberBetween(2, $skills->count());

        $data['languages'] = collect(range(1, fake()->numberBetween(2, 5)))
            ->map(fn () => [
                'name' => fake()->languageCode,
            ])
            ->toArray();

        $data['skills'] = $skills->random($skillsCount)->map(fn ($skill) => $skill->id)->toArray();

        Sanctum::actingAs($account->user);

        $this->postJson(route('api.client.projects.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])->assertSuccessful();

    }
}
