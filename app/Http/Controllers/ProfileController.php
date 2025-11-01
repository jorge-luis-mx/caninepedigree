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
        $location = $profileUser->location;
        
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
            return redirect()->back()->withErrors(['error' => 'No matching profile found.']);
        }

        $data = [
            'status' => 'success',
            'message' => 'Your details have been successfully updated.'
        ];

        if ($userProfile->status==1) {
             // update provider
             $userProfile->fill([
                'first_name' => $validatedData['first_name'] ?? $userProfile->first_name,
                'last_name' => $validatedData['last_name'] ?? $userProfile->last_name,
                'middle_name' => $validatedData['middle_name'] ?? $userProfile->middle_name,
                'email' => $validatedData['email'] ?? $userProfile->email,
                'phone' => $validatedData['phone'] ?? $userProfile->phone,
                'kennel_name' => $validatedData['kennel_name'] ?? $userProfile->kennel_name
            ]);
    
            // Guardar los cambios en la base de datos
            $userProfile->save();
        }else{
            
            $data['status']  = 'error';
            $data['message'] = 'Profile update failed';
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
