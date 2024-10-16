<?php

namespace Modules\Auth\Enums;

enum EmploymentType: string
{
    case FULL_TIME = 'FULL_TIME';
    case PART_TIME = 'PART_TIME';
    case INTERN = 'INTERN';
    case CONTRACT = 'CONTRACT';
}
