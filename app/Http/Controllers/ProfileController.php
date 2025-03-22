<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Traits\CountryTrait;

use App\Models\Provider;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    use CountryTrait;
    public function edit(Request $request): View
    {
        $countries = $this->getCountries();

        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
        $provider = Provider::where('pvr_id',$provider_auth)->get();
        return view('profile.edit', ['user' => $user,'provider'=>$provider,'countries'=>$countries]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        // validated
        $validatedData = $request->validated();

        $provider = $request->user()->provider;
        if (!$provider) {
            return redirect()->back()->withErrors(['error' => 'No associated provider found.']);
        }

        $data = [
            'status' => 'success',
            'message' => 'Yeah! Your company information has been successfully updated.'
        ];
        
        if ($provider->pvr_status===1) {
             // update provider
             $provider->fill([
                'pvr_name' => $validatedData['companyName'] ?? $provider->pvr_name,
                'pvr_contact' => $validatedData['contactName'] ?? $provider->pvr_contact,
                'pvr_email' => $validatedData['email'] ?? $provider->pvr_email,
                'pvr_phone' => $validatedData['phone'] ?? $provider->pvr_phone,
                'pvr_country' => $validatedData['country'] ?? $provider->pvr_country,
            ]);
    
            // Guardar los cambios en la base de datos
            $provider->save();
        }else{
            
            $data['status']  = 'error';
            $data['message'] = 'Provider update failed';
        }
        // Redirigir con un mensaje de éxito
        return redirect()->route('profile.edit')->with($data);


    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
