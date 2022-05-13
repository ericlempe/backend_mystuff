<?php

namespace App\Http\Controllers;

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
}
