<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\App; 
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Auth;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check()) {
            // Si el usuario está autenticado, obtén su idioma preferido
            $locale = Auth::user()->locale;
        } else {
            // Si no está autenticado, usa el idioma almacenado en la sesión
            $locale = Session::get('locale', config('app.locale'));
        }

        // Establece el idioma para la aplicación
        App::setLocale($locale);
        return $next($request);
    }
}
