<?php

namespace App\Constants;

class JobProgress
{
    const CREATED = 'created';
    // this is the default state. Here

    const IN_QUEUE = 'in_queue';
    const IN_PROGRESS = 'in_progress';
    const SECONDARY_OPS = 'secondary_ops';
    const COMPLETED = 'completed';

    const CREATED_PERCENT = 0;
    const IN_QUEUE_PERCENT = 10;
    const IN_PROGRESS_PERCENT = 40;
    const SECONDARY_OPS_PERCENT = 70;
    const COMPLETED_PERCENT = 100;

    public static function getPercentage($status)
    {
        $progressMap = [
            self::CREATED => self::CREATED_PERCENT,
            self::IN_QUEUE => self::IN_QUEUE_PERCENT,
            self::IN_PROGRESS => self::IN_PROGRESS_PERCENT,
            self::SECONDARY_OPS => self::SECONDARY_OPS_PERCENT,
            self::COMPLETED => self::COMPLETED_PERCENT,
        ];

        return $progressMap[$status] ?? 0;
    }
}
