<?php

namespace App\Models;

use App\Enums\ExpenseType;
use App\Observers\ExpenseObserver;
use App\Scopes\ExpenseScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        static::addGlobalScope(new ExpenseScope);
        static::observe(ExpenseObserver::class);
    }

    protected $fillable = [
        'name',
        'description',
        'expiration_day',
        'expense_type',
        'user_id',
    ];

    protected $casts = [
        'expense_type' => ExpenseType::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }

    public function list()
    {
        $query = Expense::select('id', 'name', 'description', 'expiration_day', 'expense_type');
        $query->whereNull('deleted_at');
        $query->orderBy('name');
        return $query->get();
    }

    public function getNotExistAtInvoice($invoice_id)
    {
        $query = Expense::whereNotExists(function ($query) use ($invoice_id) {
            $query->select('*')
                ->from('expense_invoice')
                ->whereColumn('expense_invoice.invoice_id', "expenses.id")
                ->where('expense_invoice.invoice_id', $invoice_id);
        });
        return $query->get();
    }
}
