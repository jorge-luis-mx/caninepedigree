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
use App\Models\UserProfile;

class PasswordResetLinkController extends Controller
{

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
        $existingUserProfile = UserProfile::where('email', $email)
        ->where('status', 1)
        ->first();

        if (!$existingUserProfile) {
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
            'subject' => 'Password change request | ',
            'url'=>$resetUrl
        ];

        Mail::to($email)->send(new SendEmail($datos));
    
 
        return redirect()->route('notify.link', ['id' => md5($existingUserProfile->profile_id)]);
    }


    public function index($id)
    {
        $userProfile = UserProfile::whereRaw('MD5(profile_id) = ?', [$id])->firstOrFail();
        $email = $userProfile->email;

        return view('auth.notify-reset-password',compact('email'));
    }

}
