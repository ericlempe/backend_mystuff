<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Expense;
use App\Models\ExpenseInvoice;
use App\Models\Invoice;
use phpDocumentor\Reflection\Types\Collection;

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

    public function listExpenses($request)
    {
        $usuario = (new AuthService())->getUser($request->bearerToken());
        return (new ExpenseInvoice())->getExpensesCurrentInvoice($usuario->id, $request->status);
    }

    public function store($user_id, $month)
    {
        return $this->model->create([
            'month' => intval($month),
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
            $this->createExpenses($invoice);
        }
    }

    public function setExpensesInvoice($user_id)
    {
        # Cria a fatura do mês atual
        $invoice = $this->store($user_id, date("m"));
        # Cria tbm fatura do próximo mês
        $invoice = $this->store($user_id, date("m", strtotime("+1 month")));
        # Registra as despesas na fatura
        $this->createExpenses($invoice);
    }

    public function createExpenses($invoice)
    {
        $expenses = (new Expense())->getNotExistAtInvoice($invoice->id);
        if ($expenses->count() > 0) {
            $invoice->expenses()->attach($expenses->pluck('id')->toArray());
        }
    }

    public function getTotal($request)
    {
        $usuario = (new AuthService())->getUser($request->bearerToken());
        $totais = $this->model->getTotal($usuario->id);
        if (!is_null($totais)) {
            $VlrPorcentagem = ($totais->total_paid / $totais->total_item) * 100;
            $total = $totais->total_value;
        } else {
            $VlrPorcentagem = 0;
            $total = 0;
        }

        return [
            'vlrPorcentagem' => $VlrPorcentagem,
            'total' => $total
        ];
    }

    public function nextDues($request)
    {
        $usuario = (new AuthService())->getUser($request->bearerToken());
        $nextDues = $this->model->nextDues($usuario->id);

        $data = [];
        if ($nextDues->count() > 0) {
            $data = $nextDues->map(function ($item, $key) {
                return date("{$item->expiration_day}/m");
            });
        } else {
            $nextDues = $this->model->nextDues($usuario->id, 'next');
            $data = $nextDues->map(function ($item, $key) {
                return date("{$item->expiration_day}/m");
            });
        }

        return $data;
    }
}
