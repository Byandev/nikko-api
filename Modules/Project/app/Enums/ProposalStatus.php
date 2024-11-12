<?php

namespace Modules\Project\Enums;

enum ProposalStatus: string
{
    case SUBMITTED = 'SUBMITTED';
    case ACTIVE = 'ACTIVE';
    case PENDING_OFFER = 'PENDING_OFFER';
}
