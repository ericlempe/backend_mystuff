<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Exception;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        try {
            if (!$token = auth()->attempt($request->only('email', 'password'))) {
                throw new AuthenticationException('Credenciais inválidas');
            }
            return response()->json(['token' => $token], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function getUser(Request $request)
    {
        try {
            return response()->json($request->user());
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
