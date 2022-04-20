<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ExpenseType;

class Expense extends Model
{
    use HasFactory;

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
}
