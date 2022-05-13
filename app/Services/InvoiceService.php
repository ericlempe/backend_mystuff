<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;

class InvoiceService
{
    private Invoice $model;

    public function __construct()
    {
        $this->model = new Invoice();
    }

    public function list($request)
    {
        return $this->model->list();
    }

    public function store($user_id)
    {
        return $this->model->create([
            'month' => intval(date("m")),
            'year' => date('Y'),
            'user_id' => $user_id,
            'status' => InvoiceStatus::Opened
        ]);
    }

    public function includeExpense($user_id)
    {
        # Verifica se existe fatura no mês atual.
        $checkExist = $this->model->checkIfExistInvoice($user_id, date('Y'), date('m'));
        if (!$checkExist) {
            $this->setExpensesInvoice($user_id);
        } else {
            $invoice = $this->model->getCurrentByUser($user_id);
            $this->createExpenses($invoice, $user_id);
        }
    }

    public function setExpensesInvoice($user_id)
    {
        # Cria a fatura do mês atual
        $invoice = $this->store($user_id);
        # Registra as despesas na fatura
        $this->createExpenses($invoice, $user_id);
    }

    public function createExpenses($invoice, $user_id)
    {
        $expenses = (new Expense())->getNotExistAtInvoice($invoice->id, $user_id);
        if ($expenses->count() > 0) {
            $invoice->expenses()->attach($expenses->pluck('id')->toArray());
        }
    }
}
