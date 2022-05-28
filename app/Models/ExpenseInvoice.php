<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

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

    public function getExpensesCurrentInvoice($user_id, $status = null)
    {
        $query = DB::table("expense_invoice as ei");
        $query->join("expenses as e", "ei.expense_id", "=", "e.id");
        $query->where("e.user_id", $user_id);
        if ($status === 'pending') {
            $query->whereNull("ei.paid_in");
        }
        $query->select([
            "ei.id",
            "ei.invoice_id",
            "ei.expense_id",
            "ei.value",
            "ei.paid_in",
            "e.name",
            "e.description",
            "e.expense_type",
            "e.expiration_day"
        ]);
        $query->orderBy("e.name");
        return $query->get();
    }
}
