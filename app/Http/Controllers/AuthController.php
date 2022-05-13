<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $service)
    {
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->service->login($request->all());
            return response()->json($token);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $this->service->register($request->all());
            return response()->json(['message' => 'Usuário criado com sucesso'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getUser(Request $request)
    {
        try {
            $user = $this->service->getUser($request->bearerToken());
            return response()->json(['user' => $user]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
