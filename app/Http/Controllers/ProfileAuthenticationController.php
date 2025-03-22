<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Http\Requests\ProfileUpdateRequestUser;
use Illuminate\Support\Facades\Auth;

//models
use App\Models\User;
use App\Models\Provider;
use App\Models\Airport;
use App\Models\Sale;
use App\Models\Client;

class ProfileAuthenticationController extends Controller
{


    public function edit(Request $request)
    {
        
        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
        $provider = Provider::where('pvr_id',$provider_auth)->get();
        
        return view('profile.editAuthentication',['user' => $user,'provider'=>$provider]);
    }


    public function update(ProfileUpdateRequestUser $request):RedirectResponse
    {
        
        // Validar los datos ingresados
        $validatedData = $request->validated();
        $userName = $validatedData['userName'];
        $userEmail = $validatedData['email'];
        
        $data = [
            'status' => 'success',
            'message' => 'Your platform login information has been successfully updated.'
        ];

        try {
            DB::beginTransaction();
        
            $user = auth()->user();
            if ($user->provider) {

                $user->update(['pvr_auth_username' => $userName]);
                $user->provider->update(['pvr_email' => $userEmail]);
                // Confirma la transacción
                DB::commit();
                return redirect()->route('profileAuthentication.edit')->with($data);
            }

        } catch (\Exception $e) {
            
            DB::rollBack();
        
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
            return redirect()->route('profileAuthentication.edit')->withErrors($data);
        }
        
       
    }



    public function destroy(Request $request)
    {


        $user = auth()->user();
        $provider = $user->provider;
        $provider_auth = $provider->pvr_id;

        try {

            // Start a transaction
            DB::beginTransaction();

            $airports = Airport::where('pvr_id', $provider_auth)->get();
            $sales = Sale::where('sale_provider_id',$provider_auth)->get();
            $auths = $provider->auth;


            if ($airports->isNotEmpty()) {

                foreach ($airports as $airport) {

                    $services = $airport->services;
                    foreach ($services as $service) {
                        //delete pricing
                        $service->pricing()->delete(); 
                    }
    
                    // Delete the related services
                    $airport->services()->delete();
    
                    //find file map
                    $map = $airport->maps; 
                    
                    $maps = $airport->maps; 
                    if ($maps->isNotEmpty()) {
                        foreach ($maps as $map) {
                            // Delete map file
                            $filePath = storage_path('app/public/' .$map->pvr_map_filename); 
                            
                            if (File::exists($filePath)) {
                                File::delete($filePath);
                            }
                        }
                    }
    
                    // Delete the related maps
                    $airport->maps()->delete(); 
    
                    // Delete the airport
                    $airport->delete(); 
                }

            }


            if ($sales->isNotEmpty()) {

                $client = $sales[0]->sale_client_id;
                //delete client
                Client::where('cli_id',$client)->delete();
             
                foreach ($sales as  $sale) {

                    
                    $operations =  $sale->operations;
                    foreach ($operations as  $operation) {
                        //delete operation
                        $operation->delete();
                    }
                    //delete details info
                    $sale->salesInfo->delete();

                    $payments = $sale->payments;
                    foreach ($payments as $payment) {
                        //delete payment
                       $payment->delete();
                    }
                    //delete sale
                    $sale->delete();
                   
                }

            }

            if ($auths->isNotEmpty()) {
                //delete users o auths
                if ($auths->count() > 1) {
                    foreach ($auths as  $auth) {
                       $auth->delete();
                    }
                }else{
                    $auths[0]->delete();
                }
            }

            //delete provider
            $provider->delete();

            // If everything was successful, commit the transaction
            DB::commit();
           
            
        } catch (ModelNotFoundException $e) {
            // Roll back the transaction if the airport is not found
            DB::rollBack();
            return redirect()->route('profileAuthentication.edit')->with(['status' => 404, 'message' => 'User not found']);
            
        } catch (\Exception $e) {
            // Roll back the transaction if an error occurs
            DB::rollBack();
            //return response()->json(['status' => 500, 'message' => 'Error deleting airport'], 500);
            return redirect()->route('profileAuthentication.edit')->with(['status' => 500, 'message' => 'Error deleting user']);
        }



        Auth::logout();

        // $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}
