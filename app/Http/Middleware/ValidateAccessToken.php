<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Access-Token');

        // Comparar el token con el valor configurado en .env
        if ($token !== config('app.access_token_secret')) {
            // Si el token no coincide, retorna un error de acceso denegado
            abort(403, 'Acceso denegado.');
        }

        // Si el token es correcto, permitir el acceso
        return $next($request);
        // return $next($request);
    }
}
