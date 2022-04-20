<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Opened = 'opened';
    case Paid = 'paid';
    case Overdue = 'overdue';

    public function title(): string
    {
        return match($this)
        {
            self::Opened => 'Em Aberto',
            self::Paid => 'Pago',
            self::Overdue => 'Vencido',
        };
    }
}
