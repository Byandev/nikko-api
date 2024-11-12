<?php

namespace Modules\Project\Tests\Feature\ProposalController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\Account;
use Modules\Media\Models\Media;
use Modules\Project\Models\Proposal;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_freelancer_can_submit_proposal_to_a_project(): void
    {
        $account = Account::factory()->freelancer()->create();

        Sanctum::actingAs($account->user);

        $data = Proposal::factory()->make()->toArray();

        $data['attachments'] = Media::factory()
            ->count($count = fake()->numberBetween(2, 5))
            ->create()->map(fn (Media $media) => $media->id)
            ->toArray();

        $this->postJson(route('api.account.proposals.store'), $data, [
            'X-ACCOUNT-ID' => $account->id,
        ])
            ->assertSuccessful();
    }
}
