<?php

namespace Modules\Chat\Broadcasting;

use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Chat\Models\Channel;
use Modules\Chat\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithBroadcasting;

    /**
     * Create a new channel instance.
     */
    public function __construct(public Message $message)
    {
        $this->message->loadMissing(['attachments', 'sender.avatar']);
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
            new \Illuminate\Broadcasting\Channel('chat.channels.'.$this->message->channel_id),
        ];
    }
}
