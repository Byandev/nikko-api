<?php

namespace Feature\Client\ProposalInvitationController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Project\Enums\ProjectStatus;
use Modules\Project\Models\Project;
use Modules\Project\Models\ProposalInvitation;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_delete_proposal_invitation(): void
    {
        $client = Account::factory()->client()->create();

        Sanctum::actingAs($client->user);

        $project = Project::factory()
            ->create([
                'account_id' => $client->id,
                'status' => ProjectStatus::ACTIVE->value,
            ]);

        $proposalInvitation = ProposalInvitation::factory()->create(['project_id' => $project->id]);

        $this->deleteJson(route('api.client.proposal-invitations.destroy', [
            'invitation' => $proposalInvitation,
        ]), [], [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertSuccessful();
    }

    /**
     * A basic test example.
     */
    public function test_delete_proposal_invitation_by_different_client(): void
    {
        $client = Account::factory()->client()->create();

        Sanctum::actingAs($client->user);

        $project = Project::factory()
            ->create([
                'status' => ProjectStatus::ACTIVE->value,
            ]);

        $proposalInvitation = ProposalInvitation::factory()->create(['project_id' => $project->id]);

        $this->deleteJson(route('api.client.proposal-invitations.destroy', [
            'invitation' => $proposalInvitation,
        ]), [], [
            'X-ACCOUNT-ID' => $client->id,
        ])
            ->assertForbidden();
    }
}
