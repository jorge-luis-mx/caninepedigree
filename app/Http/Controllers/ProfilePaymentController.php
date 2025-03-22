<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ProfilePaymentRequest;
use App\Models\Billing;
use Illuminate\Support\Facades\Validator;

class ProfilePaymentController extends Controller
{


    public function edit(Request $request)
    {

        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;

        $billing = Billing::where('pvr_id',$provider_auth) ->first();


        return view('profile.editPayment',compact('provider_auth','billing'));
    }


    public function update(Request $request)
    {

         

        $validator = Validator::make($request->all(), (new ProfilePaymentRequest())->rules(), (new ProfilePaymentRequest())->messages());

        $response = ['status' => 422, 'message' => 'The operation was successfully completed.','errors'=>null ];

        if ($validator->fails()) {

            $response['errors'] = $validator->errors();

        }else{

            $validatedData = $validator->validated();
            
            $mappedData = [
                'pvr_id' => $validatedData['id'],
                'bill_email_account' => $validatedData['paypal_email'],
                'bill_bank' => $validatedData['bank_name'],
                'bill_account' => $validatedData['accounNumber'],
                'bill_usa_bank_account' => $validatedData['banckCountUs'],
                'bill_swift' => $validatedData['swift'],
                'bill_routing' => $validatedData['routing'],
                'bill_platform' => 1,
            ];
            

            $billing = Billing::updateOrCreate(
                ['pvr_id' => $validatedData['id'] ?: null],  
                $mappedData                      
            );

            $response['status'] = 200;
        
        }

        return response()->json($response);

    }

}
