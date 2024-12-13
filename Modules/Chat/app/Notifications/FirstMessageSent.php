<?php

namespace Modules\Chat\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Chat\Models\Message;

class FirstMessageSent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Message $message) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Message',
            'message' => "You've got a new message. Check your inbox and continue the conversation to ensure a successful collaboration.\nIf you have any questions, the Artsycrowd Team is here to assist you.\n\nBest Regards,\nArtsycrowd Team",
            'chat_message' => $this->message,
        ];
    }
}
