<?php

namespace Modules\Project\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Project\Models\Proposal;

class ProposalSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Proposal $proposal)
    {
        $this->proposal->load(['account.user', 'project.account.user']);
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
            'title' => 'New Proposal Received',
            'message' => "Exciting news! You've received a new proposal for your posted job. Review the details and find the perfect freelancer for your project.\nIf you have any questions, the Artsycrowd Team is here to assist you.\n\nBest Regards,\nArtsycrowd Team",
            'proposal' => $this->proposal,
        ];
    }
}
