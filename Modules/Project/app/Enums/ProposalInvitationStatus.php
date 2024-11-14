<?php

namespace Modules\Project\Enums;

enum ProposalInvitationStatus: string
{
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case PROPOSAL_SUBMITTED = 'PROPOSAL_SUBMITTED';
}
