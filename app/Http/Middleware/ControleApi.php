<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Services\JWTService;

class ControleApi
{
    public function handle($request, Closure $next)
    {

        try {
            $jwtService = new JWTService;
            if (!$request->bearerToken()) {
                throw new Exception('Acesso não autorizado!', 401);
            }

            $jwtService->validarToken(base64_decode($request->bearerToken()));

            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }
}
