<?php

namespace Modules\Project\Tests\Feature\ProposalController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Media\Models\Media;
use Modules\Project\Models\Project;
use Modules\Project\Models\Proposal;
use Modules\Project\Notifications\ProposalSubmitted;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_freelancer_can_submit_proposal_to_a_project(): void
    {
        Notification::fake();

        $account = Account::factory()->freelancer()->create();

        Sanctum::actingAs($account->user);

        $project = Project::factory()->create();

        $data = Proposal::factory()->make(['project_id' => $project->id])->toArray();

        $data['attachments'] = Media::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create()->map(fn (Media $media) => $media->id)
            ->toArray();

        $this->postJson(route('api.account.proposals.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();

        Notification::assertSentTo($project->account->user, ProposalSubmitted::class);
    }
}
