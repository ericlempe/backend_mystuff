<?php

namespace App\Enums;

enum ExpenseType: string
{
    case regular = 'regular';
    case extraordinary = 'extraordinary';

    public function title(): string
    {
        return match($this)
        {
            self::regular => 'Regulares',
            self::extraordinary => 'Extraordinárias',
        };
    }
}
