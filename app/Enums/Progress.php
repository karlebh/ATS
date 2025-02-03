<?php

namespace App\Enums;

enum Progress: string
{
    case InQueue = 'in_queue';
    case InProgress = 'in_progress';
    case SecondaryOps = 'secondary_ops';
    case OnHold = 'on_hold';
}
