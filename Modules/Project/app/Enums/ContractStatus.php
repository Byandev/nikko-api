<?php

namespace Modules\Project\Enums;

enum ContractStatus: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case REJECTED = 'REJECTED';
    case COMPLETED = 'COMPLETED';
}
