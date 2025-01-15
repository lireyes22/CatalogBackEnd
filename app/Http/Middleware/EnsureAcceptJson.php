<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAcceptJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el método es uno de los que deseas validar
        if (in_array($request->getMethod(), ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Verificar si el encabezado "Accept" es "application/json"
            if ($request->header('Accept') !== 'application/json') {
                return response()->json([
                    'error' => 'Encabezado Accept -> application/json no encontrado',
                ], 406); // Código 406 Not Acceptable
            }
        }

        // Continuar con la solicitud
        return $next($request);
    }
}
