<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->belongsToMany(Expense::class);
    }


    public function list()
    {
        $query = Invoice::select('id', 'month', 'year', 'status');
        $query->orderBy('year');
        $query->orderBy('month');
        return $query->get();
    }

    public function checkIfExistInvoice($user_id, $year, $month)
    {
        $query = Invoice::where("user_id", $user_id);
        $query->where("year", $year);
        $query->where("month", $month);
        $invoice = $query->first();
        return !is_null($invoice);
    }

    public function getCurrentByUser($user_id)
    {
        $query = Invoice::where("user_id", $user_id);
        $query->whereRaw("month = month(now())");
        $query->whereRaw("year = year(now())");
        return $query->first();
    }

    public function nextDues($user_id, $month = 'current')
    {
        $query = DB::table("invoices as i");
        $query->join("expense_invoice as ie", "i.id", "=", "ie.invoice_id");
        $query->join("expenses as e", "ie.expense_id", "=", "e.id");
        $query->where("i.user_id", $user_id);
        $query->whereNull("ie.paid_in");
        if ($month == 'current') {
            $query->whereRaw("i.month = MONTH(now())");
            $query->whereRaw("i.year = YEAR(now())");
        } else if ($month == 'next') {
            $query->whereRaw("i.month = MONTH(DATE_ADD(now(), INTERVAL 1 MONTH))");
            $query->whereRaw("i.year = YEAR(DATE_ADD(now(), INTERVAL 1 MONTH))");
        }
        $query->select("e.expiration_day");
        $query->orderBy("e.expiration_day");
        $query->take(3);
        return $query->get();
    }

    public function getTotal($user_id)
    {
        $query = DB::table("invoices as i");
        $query->where("i.user_id", $user_id);
        $query->whereRaw("i.month = month(now())");
        $query->whereRaw("i.year = year(now())");
        $query->select([
            DB::raw("(SELECT SUM(ei.value) FROM expense_invoice as ei WHERE i.id = ei.invoice_id AND ei.paid_in IS NOT NULL) as total_value"),
            DB::raw("(SELECT COUNT(ei.id) FROM expense_invoice as ei WHERE i.id = ei.invoice_id) as total_item"),
            DB::raw("(SELECT COUNT(ei.id) FROM expense_invoice as ei WHERE i.id = ei.invoice_id AND ei.paid_in IS NOT NULL) as total_paid"),
        ]);
        return $query->first();
    }
}
