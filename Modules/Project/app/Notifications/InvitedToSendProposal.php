<?php

namespace Modules\Project\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Project\Models\ProposalInvitation;

class InvitedToSendProposal extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ProposalInvitation $proposalInvitation)
    {
        $this->proposalInvitation->load(['project.account.user']);
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
            'title' => 'Project Invitation Received',
            'message' => "Exciting news! Client has invited you to submit a proposal for their project. Check out the details and submit your proposal to showcase your skills.\nIf you have any questions, the Artsycrowd Team is here to assist you.\n\nBest Regards,\nArtsycrowd Team",
            'proposal_invitation' => $this->proposalInvitation,
        ];
    }
}
