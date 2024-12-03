<?php

namespace Modules\Chat\Tests\Feature\MessageController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Chat\Models\Channel;
use Modules\Chat\Models\Message;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetChatMessages()
    {
        $user = User::factory()->create();
        $channel = Channel::factory()->create();

        Sanctum::actingAs($user);

        Message::factory()->count(fake()->numberBetween(5, 10))->create(['channel_id' => $channel->id]);

        $this->getJson(route('api.chat.channels.messages.index', ['channel' => $channel]))
            ->assertSuccessful();
    }
}
