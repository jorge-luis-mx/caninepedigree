<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Traits\CountryTrait;

use App\Models\UserProfile;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    use CountryTrait;
    public function edit(Request $request): View
    {

        $countries = $this->getCountries();
        //acceso siempre con model user
        $user = auth()->user();
        $profileUser = UserProfile::where('profile_id',$user->profile_id)->first();
    
        return view('profile.edit', ['user' => $user,'profileUser'=>$profileUser,'countries'=>$countries]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        // validated
        $validatedData = $request->validated();

        $userProfile = $request->user()->UserProfile;
       
        if (!$userProfile) {
            return redirect()->back()->withErrors(['error' => 'No associated provider found.']);
        }

        $data = [
            'status' => 'success',
            'message' => 'Yeah! Your company information has been successfully updated.'
        ];
        
        if ($userProfile->status===1) {
             // update provider
             $userProfile->fill([
                'name' => $validatedData['fullname'] ?? $userProfile->name,
                'email' => $validatedData['email'] ?? $userProfile->email,
                'phone' => $validatedData['phone'] ?? $userProfile->phone,
                'address' => $validatedData['address'] ?? $userProfile->addres,
                'country' => $validatedData['country'] ?? $userProfile->country,
            ]);
    
            // Guardar los cambios en la base de datos
            $userProfile->save();
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
