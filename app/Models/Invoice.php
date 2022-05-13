<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $query->where("year", date('Y'));
        $query->where("month", intval(date("m")));
        return $query->first();
    }
}
