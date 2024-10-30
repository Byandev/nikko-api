<?php

namespace Modules\Project\Enums;

enum ProjectLength: string
{
    case SHORT_TERM = 'SHORT_TERM';
    case MEDIUM_TERM = 'MEDIUM_TERM';
    case LONG_TERM = 'LONG_TERM';
    case EXTENDED = 'EXTENDED';
}
