<?php

namespace Modules\Project\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Project\Models\Contract;

class ContractCreated extends Notification
{
    use Queueable;

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
            'title' => 'Contract To Review',
            'message' => "Exciting news! Client has created a contract for you. \nIf you have any questions, the Artsycrowd Team is here to assist you.\n\nBest Regards,\nArtsycrowd Team",
            'contract' => $this->contract,
        ];
    }
}
