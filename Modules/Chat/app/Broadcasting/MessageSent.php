<?php

namespace Modules\Chat\Broadcasting;

use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Chat\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithBroadcasting;

    /**
     * Create a new channel instance.
     */
    public function __construct(public Message $message)
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
            new PrivateChannel('chat.messages.'.$this->message->id),
        ];
    }
}
