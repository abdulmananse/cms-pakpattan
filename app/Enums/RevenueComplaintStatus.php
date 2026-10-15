<?php

namespace App\Enums;

enum RevenueComplaintStatus: string
{
    case OPEN = 'open';
    case IN_PROCESS = 'in_process';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROCESS => 'In Process',
            self::CLOSED => 'Closed',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::OPEN => 'badge-info',
            self::IN_PROCESS => 'badge-warning',
            self::CLOSED => 'badge-success',
        };
    }
}
