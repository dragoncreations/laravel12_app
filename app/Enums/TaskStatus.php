<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TO_DO = 'to_do';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function label(): string
    {
        return match ($this) {
            self::TO_DO => "To Do",
            self::IN_PROGRESS => "In Progress",
            self::DONE => "Done",
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TO_DO => 'info',
            self::IN_PROGRESS => 'warning text-dark',
            self::DONE => 'success',
        };
    }
}
