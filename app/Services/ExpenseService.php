<?php

namespace App\Services;

use App\Enums\ExpenseType;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(private Expense $expense)
    {
    }

    public function list()
    {
        return $this->expense->list();
    }

    public function store($request)
    {
        DB::transaction(function () use ($request) {
            $expense = $this->expense->create([
                'name' => $request->name,
                'description' => $request->description ?? null,
                'expiration_day' => $request->expiration_day,
                'expense_type' => ExpenseType::regular
            ]);
            (new InvoiceService())->includeExpense($expense->user_id);
        });
    }

    public function update($request, $id)
    {
        return $this->expense->where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description ?? null,
            'expiration_day' => $request->expiration_day,
        ]);
    }

    public function destroy($id)
    {
        return $this->expense->where('id', $id)->delete();
    }
}
