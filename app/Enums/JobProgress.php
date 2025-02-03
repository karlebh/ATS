<?php

namespace App\Enums;

enum JobProgress: string
{
    case InQueue = 'in_queue';
    case InProgress = 'in_progress';
    case SecondaryOps = 'secondary_ops';
    case Done = 'done';
}
