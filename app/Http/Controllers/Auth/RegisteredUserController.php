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
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:user_profiles,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $text = preg_split('/\s+/', $request->last_name);


        // 1️⃣ Insertar en la tabla `user_profile` y obtener el ID generado
        $userProfile = UserProfile::create([
            'first_name' => $request->name,
            'last_name'=>count($text)> 0 ? $text[0] : null,
            'email' => $request->email,
            'status' => 1,
        ]);
 
        // 2️⃣ Generar un username único
        $username = $this->generateUniqueUsername($request->name);
    
        // 3️⃣ Insertar en la tabla `users` usando el `user_profile_id`
        $user = User::create([
            'profile_id' => $userProfile['profile_id'],  // Relacionar con `user_profile`
            'user_name' => $username,
            'password' => Hash::make($request->password),
            'role_id'=>1,
            'status' => 1, 
        ]);
    
        // 4️⃣ Registrar evento y autenticar usuario
        event(new Registered($user));
        Auth::login($user);
    
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
