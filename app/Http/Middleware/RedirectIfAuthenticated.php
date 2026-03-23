<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                $params = [];

                if ($request->filled('token')) {
                    session(['pending_invite_token' => $request->input('token')]);
                    $params['token'] = $request->input('token');
                }

                if ($request->filled('invoice')) {
                    session(['pending_invite_invoice' => $request->input('invoice')]);
                    $params['invoice'] = $request->input('invoice');
                }

                // Si hay params → redirige con ellos
                if (!empty($params)) {
                    return redirect()->route('dogs.create', $params);
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
