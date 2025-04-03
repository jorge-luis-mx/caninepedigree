<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;



use Illuminate\Support\Str;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:user_profiles,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        // 1️⃣ Insertar en la tabla `user_profile` y obtener el ID generado
        $userProfile = UserProfile::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => 1,
        ]);
 
        // 2️⃣ Generar un username único
        $username = $this->generateUniqueUsername($request->name);
    
        // 3️⃣ Insertar en la tabla `users` usando el `user_profile_id`
        $user = User::create([
            'username' => $username,
            'password' => Hash::make($request->password),
            'profile_id' => $userProfile['profile_id'],  // Relacionar con `user_profile`
            'status' => 1, 
        ]);
    
        // 4️⃣ Registrar evento y autenticar usuario
        event(new Registered($user));
        Auth::login($user);
    
        return redirect(RouteServiceProvider::HOME);

        
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }


    private function generateUniqueUsername(string $name): string
    {
        $baseUsername = Str::slug($name, '_'); // Convierte el nombre en un formato válido
        $username = $baseUsername;
        $count = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $count;
            $count++;
        }

        return $username;
    }
}
