<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use App\Models\Provider;
use App\Models\PasswordReset;
use App\Models\UserProfile;
class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verificamos si el token es válido
        $record = PasswordReset::where('token', $request->token)
            ->where('created_at', '>=', now()->subMinutes(60)) // Token válido por 60 minutos
            ->first();

        if (!$record) {
            // Si el token no es válido, retornamos con un mensaje de error
            return back()->withErrors(['email' => 'Expired token.']);
        }

        // Encontramos al usuario basado en su email
        $userProfile = UserProfile::where('email', $record->email)->where('status', 1)
        ->first();

        if (!$userProfile) {
            // Si el usuario no existe, retornamos con un error
            return back()->withErrors(['email' => 'A user with this email could not be found.']);
        }

        $prv_auth = $userProfile->user()->first();

        //Actualizamos la contraseña del usuario
        $prv_auth->forceFill([
            'password' => Hash::make($request->password),
            'auth_token' => Str::random(60), // Generamos un nuevo token de recordatorio
        ])->save();


        // Eliminamos el token de restablecimiento de la tabla personalizada para evitar su reutilización
        // PasswordReset::where('email', $record->email)->delete();

        //Redirigimos al usuario a la página de login con un mensaje de éxito
        return redirect()->route('login')->with('status', 'Your password has been successfully reset!');
    

        // // Here we will attempt to reset the user's password. If it is successful we
        // // will update the password on an actual user model and persist it to the
        // // database. Otherwise we will parse the error and return the response.
        // $status = Password::reset(
        //     $request->only('email', 'password', 'password_confirmation', 'token'),
        //     function ($user) use ($request) {
        //         $user->forceFill([
        //             'password' => Hash::make($request->password),
        //             'remember_token' => Str::random(60),
        //         ])->save();

        //         event(new PasswordReset($user));
        //     }
        // );

        // // If the password was successfully reset, we will redirect the user back to
        // // the application's home authenticated view. If there is an error we can
        // // redirect them back to where they came from with their error message.
        // return $status == Password::PASSWORD_RESET
        //             ? redirect()->route('login')->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
    }
}
