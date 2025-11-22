<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;


use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

use App\Models\DogSale;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Dog;
use App\Models\PendingDogRelation;
use App\Models\DogParentRequest;

use Illuminate\Support\Str;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(?string $role = null): View
    {
        return view('auth.register',['role' => $role]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['nullable', 'string', 'in:admin', 'regex:/^\S+$/'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'kennel_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9\s]+$/u'  // Solo letras, números y espacios
            ],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:user_profiles,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dog_sale' => ['nullable', 'string'], // 🆕 agregar este campo opcional
        ]);

        // Verificar si viene token en la URL
        if ($request->filled('token')) {
            $token = $request->input('token');

            // Buscar registro pendiente
            $pending = PendingDogRelation::where('token', $token)->first();

            if (!$pending) {
                abort(404, 'Invalid or expired registration token.');
            }
            
            // Si hay sesión iniciada, usar el email del usuario actual
            if (auth()->check()) {
                $user = auth()->user();
                $profile = $user->userprofile;
                
                if (strtolower($profile->email) !== strtolower($pending->pending_email)) {
                    abort(403, 'You are not authorized to register this dog.');
                }
            }
            // Guardar token en sesión para usar después (tras crear usuario si es necesario)
            session(['pending_invite_token' => $token]);
        }

        // Verificar si viene token en la URL
        if ($request->filled('invoice')) {
            $invoice = $request->input('invoice');

            // Buscar registro pendiente
            $parentRequest = DogParentRequest::where('token', $invoice)->first();

            if (!$parentRequest) {
                abort(404, 'Invalid or expired registration token.');
            }
            
            // Si hay sesión iniciada, usar el email del usuario actual
            if (auth()->check()) {
                $user = auth()->user();
                $profile = $user->userprofile;
                
                if (strtolower($profile->email) !== strtolower($parentRequest->email)) {
                    abort(403, 'You are not authorized to register this dog.');
                }
            }
            // Guardar token en sesión para usar después (tras crear usuario si es necesario)
            session(['pending_invite_invoice' => $invoice]);
        }


        $role = $validated['role'] ?? null;
        $text = preg_split('/\s+/', $request->last_name);

        // 1️⃣ Crear el perfil del usuario
        $userProfile = UserProfile::create([
            'first_name' => $request->name,
            'last_name' => count($text) > 0 ? $text[0] : null,
            'kennel_name'=>$request->kennel_name,
            'kennel_name_status'=>$request->filled('kennel_name') ? 1 : 0,
            'email' => $request->email,
            'status' => 1,
        ]);

        // 2️⃣ Generar un username único
        $username = $this->generateUniqueUsername($request->name);

        // 3️⃣ Crear el usuario
        $user = User::create([
            'profile_id' => $userProfile->profile_id,
            'user_name' => $username,
            'password' => Hash::make($request->password),
            'role_id' => $role === 'admin' ? 3 : 4,
            'status' => 1,
        ]);

        if ($request->filled('ownership')) {
            
            $ownership = $request->input('ownership');

            DogSale::where('token', $ownership)->update(['buyer_id' => $userProfile->profile_id]);

        }


        // 5️⃣ Autenticar y redirigir
        event(new Registered($user));
        Auth::login($user);

        // Si el registro viene de una invitación pendiente
        if ($request->filled('token')) {
            // Guardar el token en la sesión para usarlo después si es necesario
            session(['pending_invite_token' => $request->input('token')]);
            // Redirigir al formulario de registro del perro con el token
            return redirect()->route('dogs.create', ['token' => $request->input('token')]);
        }


        // Si el registro viene con invoice
        if ($request->filled('invoice')) {
            // Guardar el invoice en la sesión para usarlo después si es necesario
            session(['pending_invite_invoice' => $request->input('invoice')]);
            // Redirigir al formulario de registro del perro con el invoice
            return redirect()->route('dogs.create', ['invoice' => $request->input('invoice')]);
        }
        
        // Si no hay invitación pendiente, ir al dashboard (HOME)
        return redirect(RouteServiceProvider::HOME);
    }


    private function generateUniqueUsername(string $name): string
    {
        $baseUsername = Str::slug($name, '_'); // Convierte el nombre en un formato válido
        $username = $baseUsername;
        $count = 1;

        while (User::where('user_name', $username)->exists()) {
            $username = $baseUsername . '_' . $count;
            $count++;
        }

        return $username;
    }
}
