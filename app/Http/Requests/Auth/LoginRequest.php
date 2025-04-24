<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use App\Models\UserProfile;
use App\Models\User;
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    // public function rules(): array
    // {
    //     return [
    //         'email' => ['required', 'string', 'email'],
    //         'password' => ['required', 'string'],
    //     ];
    // }


    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function authenticate(): void
    // {
    //     $this->ensureIsNotRateLimited();

    //     if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
    //         RateLimiter::hit($this->throttleKey());

    //         throw ValidationException::withMessages([
    //             'email' => trans('auth.failed'),
    //         ]);
    //     }

    //     RateLimiter::clear($this->throttleKey());
    // }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function ensureIsNotRateLimited(): void
    // {
    //     if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
    //         return;
    //     }

    //     event(new Lockout($this));

    //     $seconds = RateLimiter::availableIn($this->throttleKey());

    //     throw ValidationException::withMessages([
    //         'email' => trans('auth.throttle', [
    //             'seconds' => $seconds,
    //             'minutes' => ceil($seconds / 60),
    //         ]),
    //     ]);
    // }

    /**
     * Get the rate limiting throttle key for the request.
     */
    // public function throttleKey(): string
    // {
    //     return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    // }




    // public function authenticate(): void
    // {
    //     $this->ensureIsNotRateLimited();
       
    //     // Intentar autenticación con username o email
    //     $credentials = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL)
    //         ? ['pvr_auth_email' => $this->input('login'), 'password' => $this->input('password')]
    //         : ['pvr_auth_username' => $this->input('login'), 'password' => $this->input('password')];

    //     if (Auth::attempt($credentials, $this->boolean('remember'))) {
    //         RateLimiter::hit($this->throttleKey());

    //         throw ValidationException::withMessages([
    //             'login' => trans('auth.failed'),
    //         ]);
    //     }
    //     RateLimiter::clear($this->throttleKey());
    // }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // Puede ser username o email
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        /// obtener password hash 
        //dd(Hash::make($this->input('password')));

        // Validar input con reglas estrictas
        $credentials = $this->validate([
            'login' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $value)) {
                        $fail('The field must be a valid email address or a username (3-20 characters, containing only letters, numbers, and underscores).');
                    }
                },
            ],
            'password' => ['required', 'min:8'], // La contraseña debe tener al menos 8 caracteres
        ]);

        // Verifica si el login es un email
        $isEmail = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL);
       
        $findUser = $isEmail ? UserProfile::where('email', $this->input('login'))->first(): User::where('user_name', $this->input('login'))->first();
    
        if (!$findUser) {
            throw ValidationException::withMessages([
                'login' => 'No account was found associated with the provided username or email address. Please verify your input and try again.',
            ]);
        }
        
        // Autenticación estándar con username o email en provider_auth
        $credentials = $isEmail ? ['profile_id' => $findUser->profile_id, 'password' => $this->input('password')] : ['user_name' => $this->input('login'), 'password' => $this->input('password')];
       
        // Auth default de laravel lo define siempre el modelo User o que debe exister model user
        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
    
            // Lanza excepción si la autenticación falla
            throw ValidationException::withMessages([
                'password' => trans('auth.failed'),
            ]);
        }

        //verify si no esta deslogueado
        
        $user = Auth::user();

        if (!$user->userprofile || (int) $user->userprofile->status !== 1) {
            Auth::logout();
            throw ValidationException::withMessages([
                'login' => trans('inactive_account'),
            ]);
        }
        
        // if ($user->status !== 1) { 
        //     Auth::logout(); 
        //     throw ValidationException::withMessages([
        //         'login' => trans('inactive_account'),
        //     ]);
        // }
        // Limpia los intentos de login fallidos si la autenticación es exitosa
        RateLimiter::clear($this->throttleKey());
    }



    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::lower($this->input('login')) . '|' . $this->ip();
    }

}
