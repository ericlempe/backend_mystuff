<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\StoreRequest;
use App\Http\Requests\Expense\UpdateRequest;
use App\Services\ExpenseService;
use Exception;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(private ExpenseService $service)
    {
    }

    public function list(Request $request)
    {
        try {
            $data = $this->service->list();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao listar despesas',
                'log' => $e->getMessage()
            ]);
        }
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->service->store($request);
            return response()->json(['message' => 'Despesa cadastrada com sucesso!']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao cadastrar despesa',
                'log' => $e->getMessage()
            ]);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            $this->service->update($request, $id);
            return response()->json(['message' => 'Despesa atualizada com sucesso!']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao atualizar despesa',
                'log' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->destroy($id);
            return response()->json(['message' => 'Despesa removida com sucesso!']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Falha ao remover despesa',
                'log' => $e->getMessage()
            ]);
        }
    }
}
