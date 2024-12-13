<?php

namespace Modules\Project\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Project\Models\Contract;

class ContractRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    /**
     * Create a new notification instance.
     */
    public function __construct(public Contract $contract)
    {
        $contract->load(['account.user', 'proposal' => ['project' => ['account.user']]]);
    }

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
            'title' => 'Contract Rejected',
            'message' => "Sorry! Contract has been rejected by the other party. \nIf you have any questions, the Artsycrowd Team is here to assist you.\n\nBest Regards,\nArtsycrowd Team",
            'data' => $this->contract,
        ];
    }
}
