<?php

namespace Modules\Project\Tests\Feature\ProjectController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Modules\Project\Models\Proposal;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_list_projects(): void
    {
        Project::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['status' => ProjectStatus::ACTIVE->value]);

        $this->getJson(route('api.projects.index', [
            'include' => 'account.user.avatar',
        ]))
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }

    public function test_list_projects_with_proposal_id(): void
    {
        $freelancer = Account::factory()->freelancer()->create();

        Project::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create(['status' => ProjectStatus::ACTIVE->value])
            ->each(function (Project $project) use ($freelancer) {
                Proposal::factory()->create([
                    'project_id' => $project->id,
                    'account_id' => $freelancer->id,
                ]);
            });

        Sanctum::actingAs($freelancer->user);

        $this->getJson(route('api.projects.index', [
            'include' => 'account.user.avatar,myProposal',
        ]), [
            'X-ACCOUNT-ID' => $freelancer->id,
        ])
            ->assertSuccessful()
            ->dump()
            ->assertJsonFragment(['total' => $count]);
    }
}
