<?php

namespace Modules\Chat\Tests\Feature\MessageController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Models\User;
use Modules\Chat\Models\Channel;
use Modules\Media\Models\Media;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSendMessage()
    {
        $user = User::factory()->create();
        $channel = Channel::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson(route('api.chat.channels.messages.store', ['channel' => $channel]), ['content' => fake()->paragraph])
            ->assertSuccessful();
    }

    public function testUserCanSendMessageAttachments()
    {
        $user = User::factory()->create();
        $channel = Channel::factory()->create();

        Sanctum::actingAs($user);

        $attachments = Media::factory()->count(fake()->numberBetween(2, 3))->create();

        $this->postJson(route('api.chat.channels.messages.store', ['channel' => $channel]),
            [
                'attachment_ids' => $attachments->map(fn (Media $media) => $media->id)->toArray(),
            ])
            ->dump()
            ->assertSuccessful();
    }
}
