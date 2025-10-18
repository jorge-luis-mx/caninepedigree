<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
//custom
use Illuminate\Validation\ValidationException;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request)
    {

        // Si el usuario YA está autenticado...
        if (Auth::check()) {

            // ... y viene un token por la URL
            if ($request->filled('token')) {
                // Redirigir directamente al formulario de registro del perro
                return redirect()->route('dogs.create', ['token' => $request->input('token')]);
            }

            // Si no hay token → ir al dashboard normal
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
       
        $request->authenticate();

        $request->session()->regenerate();
        $user = auth()->user();
        if ($user->username =='admin' && $user->role =='admin') {
            // Redirige a ruta personalizada (por ejemplo: /admin/dashboard)
            // Si viene token, redirigir después del login
            if ($request->filled('token')) {
                session(['pending_invite_token' => $request->input('token')]);
                return redirect()->route('dogs.create', ['token' => $request->input('token')]);
            }
            return redirect('/admin/dogs');
        }

        // Si viene token, redirigir después del login
        if ($request->filled('token')) {
            session(['pending_invite_token' => $request->input('token')]);
            return redirect()->route('dogs.create', ['token' => $request->input('token')]);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
