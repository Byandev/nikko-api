<?php

namespace Modules\Chat\Tests\Feature\ChannelController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Project\Models\Proposal;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanCreateChannel()
    {
        $proposal = Proposal::factory()->create();

        Sanctum::actingAs($proposal->project->account->user);

        $this->postJson(route('api.chat.channels.store'), ['subject_proposal_id' => $proposal->id])
            ->assertSuccessful();
    }
}
