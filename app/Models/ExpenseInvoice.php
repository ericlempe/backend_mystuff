<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExpenseInvoice extends Pivot
{
    use HasFactory;

    protected $fillable = ['expense_id', 'invoice_id', 'value', 'attachment_id', 'paid_in'];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
