<?php

namespace App\Enums;

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case FLOOR_TEAM = 'floor_team';
    case INSPECTION_TEAM = 'inspection_team';
}
