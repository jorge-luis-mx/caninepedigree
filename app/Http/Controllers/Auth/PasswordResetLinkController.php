<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

//Controllers Add
use App\Models\Provider;
use App\Models\PasswordReset;
class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');
        $existingProvider = Provider::where('pvr_email', $email)
        ->where('pvr_status', 1)
        ->first();

        if (!$existingProvider) {
            return back()->withErrors(['email' => 'No encontramos ningún usuario con ese correo.']);
        }
        // Generar el token de restablecimiento manualmente
        $token = Str::random(60);
        // Construir el enlace de restablecimiento personalizado
        $resetUrl = url("/reset-password/{$token}");
        PasswordReset::upsert([
            [
                'email' => $email,
                'token' => $token,
                'created_at' =>  now()->toDateTimeString(),
            ]
        ],
        ['email'], // Columnas para identificar duplicados
        ['token', 'created_at'] // Columnas que se actualizarán si el registro existe
        );

        $datos = [
            'from'=>'notify@airporttransportation.com',
            'subject' => 'Password change request | Airport Transportation',
            'url'=>$resetUrl
        ];

        Mail::to($email)->send(new SendEmail($datos));
    
        // return view('auth.notify-reset-password',compact('email'));
        return redirect()->route('notify.link', ['id' => md5($existingProvider->pvr_id)]);
    }


    public function index($id)
    {
        
        $provider = Provider::whereRaw('MD5(pvr_id) = ?', [$id])->firstOrFail();
        $email = $provider->pvr_email;

        return view('auth.notify-reset-password',compact('email'));
    }
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'email' => ['required', 'email'],
    //     ]);

    //     // We will send the password reset link to this user. Once we have attempted
    //     // to send the link, we will examine the response then see the message we
    //     // need to show to the user. Finally, we'll send out a proper response.
    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status == Password::RESET_LINK_SENT
    //                 ? back()->with('status', __($status))
    //                 : back()->withInput($request->only('email'))
    //                         ->withErrors(['email' => __($status)]);
    // }
}
