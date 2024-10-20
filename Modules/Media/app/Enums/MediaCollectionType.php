<?php

namespace Modules\Media\Enums;

enum MediaCollectionType: string
{
    case UNASSIGNED = 'UNASSIGNED';
    case AVATAR = 'AVATAR';
    case BANNER = 'BANNER';

    case PORTFOLIO_IMAGES = 'PORTFOLIO_IMAGES';
}
