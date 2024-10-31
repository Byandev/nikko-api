<?php

namespace Modules\Project\Tests\Feature\Client\ProjectController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_client_can_list_own_listed_projects(): void
    {
        $account = Account::factory()->client()->create();

        Project::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id]);

        Sanctum::actingAs($account->user);

        $this->getJson(route('api.client.projects.index'), [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }

    /**
     * @dataProvider projectStatuses
     */
    public function test_client_can_list_own_listed_projects_filtered_by_status($filteredStatus, $unfilteredStatus): void
    {
        $account = Account::factory()->client()->create();

        Project::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id, 'status' => $filteredStatus]);

        Project::factory()
            ->count(fake()->numberBetween(2, 5))
            ->create(['account_id' => $account->id, 'status' => $unfilteredStatus]);

        Sanctum::actingAs($account->user);

        $this->getJson(route('api.client.projects.index', [
            'filter[status]' => $filteredStatus,
        ]), [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }

    public static function projectStatuses(): array
    {
        return [
            [ProjectStatus::ACTIVE->value, ProjectStatus::DRAFT->value],
            [ProjectStatus::DRAFT->value, ProjectStatus::ACTIVE->value],
        ];
    }
}
