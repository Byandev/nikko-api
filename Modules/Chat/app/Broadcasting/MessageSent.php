<?php

namespace Modules\Chat\Broadcasting;

use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Chat\Models\Channel;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithBroadcasting;

    /**
     * Create a new channel instance.
     */
    public function __construct(public Channel $channel)
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(): array|bool
    {
        return true;
    }

    public function broadcastOn(): array
    {
        return [
            new \Illuminate\Broadcasting\Channel('chat.channels.'.$this->channel->id),
        ];
    }
}
