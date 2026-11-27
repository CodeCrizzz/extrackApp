<?php

namespace App\Enums;

enum DebtStatus: string
{
    case Active = 'active';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Paid => 'Paid',
        };
    }
}
