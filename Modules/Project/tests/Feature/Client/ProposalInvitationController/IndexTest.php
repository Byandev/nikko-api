<?php

namespace Feature\Client\ProposalInvitationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Modules\Project\Models\ProposalInvitation;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_list_proposal_invitations(): void
    {
        $client = Account::factory()->client()->create();

        Sanctum::actingAs($client->user);

        Project::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create([
                'account_id' => $client->id,
                'status' => ProjectStatus::ACTIVE->value,
            ])
            ->each(function (Project $project) {
                ProposalInvitation::factory()->create(['project_id' => $project->id]);
            });

        $this->getJson(route('api.client.proposal-invitations.index', [
            'include' => [
                'project',
                'account.user.avatar',
                'account.user.languages',
                'account.skills',
            ],
        ]), [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['total' => $count]);
    }
}
