<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseCollection;
use App\Models\Invoice;
use App\Services\AuthService;
use App\Services\InvoiceService;
use Exception;
use Illuminate\Database\QueryException;
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
            ], 400);
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
            ], 400);
        }
    }

    public function nextDues(Request $request)
    {
        try {
            $data = $this->service->nextDues($request);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao listar próximos vencimentos',
                'log' => $e->getMessage()
            ], 400);
        }
    }

    public function getTotal(Request $request)
    {
        try {
            $data = $this->service->getTotal($request);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao obter total da fatura',
                'log' => $e->getMessage()
            ], 400);
        }
    }
}
