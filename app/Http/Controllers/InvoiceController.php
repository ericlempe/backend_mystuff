<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseCollection;
use App\Services\InvoiceService;
use Exception;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $service)
    {
    }

    public function list(Request $request)
    {
        try {
            $data = $this->service->list($request);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao listar faturas',
                'log' => $e->getMessage()
            ]);
        }
    }

    public function listExpenses(Request $request)
    {
        try {
            return new ExpenseCollection($this->service->listExpenses($request));
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao listar despesas da fatura',
                'log' => $e->getMessage()
            ]);
        }
    }
}
