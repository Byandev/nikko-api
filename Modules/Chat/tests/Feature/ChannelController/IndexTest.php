<?php

namespace Feature\ChannelController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Chat\Models\Channel;
use Modules\Project\Models\Proposal;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetChatMessages()
    {
        $users = User::factory()->count(fake()->numberBetween(2, 5))->create();
        $proposal = Proposal::factory()->create();

        $channel = Channel::factory()->create(['subject_type' => Proposal::class, 'subject_id' => $proposal->getKey()]);

        $channel->members()->sync($users->map(fn (User $user) => $user->id)->toArray());

        Sanctum::actingAs($users->random());

        $this->getJson(route('api.chat.channels.index'))
            ->assertSuccessful()
            ->dump();
    }
}
