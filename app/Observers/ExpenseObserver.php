<?php

namespace App\Observers;

use App\Models\Expense;
use App\Services\AuthService;

class ExpenseObserver
{
    public function creating(Expense $expense)
    {
        $token = request()->bearerToken();
        $user = (new AuthService())->getUser($token);
        $expense->user_id = $user->id;
    }
}
