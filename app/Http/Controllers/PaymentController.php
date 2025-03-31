<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\Request;


//BD
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use App\PaypalConfig\Helpers\PayPalHelper;

use App\Traits\ConfigPaypal;

class PaymentController extends Controller
{
    use ConfigPaypal;
    
    protected $paypalHelper;

    public function __construct()
    {
       require base_path('app/PaypalConfig/Config.php');
       $this->paypalHelper = new PayPalHelper;
      
    }

    public function pay($id)
    {
        $paypal = $this->configPaypal();

        $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])
        ->firstOrFail();
        $dog->payment = $dog->payments;
        $dog->invoice = md5($dog->dog_id);

        return view('payments/payment',compact('paypal','dog'));
    }

    public function createOrder(Request $request){
        
        $post = $request->all();
       
        $dog = Dog::whereRaw('MD5(dog_id) = ?', $post['invoice'])
        ->firstOrFail();
        $dog->payment = $dog->payments;
   

            $sale = [];
            $sale['keyReservation'] = $dog->reg_no;
            $sale['cye'] = 'USD';
            $sale['total'] = 100;
            
                $item = 'Dog Registration with Payment ';
                $ivoice_Id = $sale['keyReservation'].'-'.time();
                $name = 'Dog Registration';

                $order = '{
                "intent" : "CAPTURE",
                "application_context" : {
                    "return_url" : "",
                    "cancel_url" : ""
                },
                "purchase_units" : [ 
                    {
                        "reference_id" : "' . $sale['keyReservation']. '",
                        "description" : "'.$item.'",
                        "invoice_id" : "' . $ivoice_Id  . '",
                        "custom_id" : "' . $sale['keyReservation']. '",
                        "amount" : {
                            "currency_code" : "' . $sale['cye']. '",
                            "value" : "' . $sale['total'] . '",
                            "breakdown": {
                                "item_total": {
                                    "currency_code": "' . $sale['cye']. '",
                                    "value": "' .  $sale['total'] . '"
                                },
                                "tax_total": {
                                    "currency_code": "' . $sale['cye']. '",
                                    "value": "0"
                                },
                                "discount": {
                                    "currency_code": "' . $sale['cye']. '",
                                    "value": "0"
                                }
                            }
                        },
                        "items": [
                            {
                                "sku": "' . $sale['keyReservation'] . '", 
                                "name": " ' . $name . '", 
                                "quantity": "1",
                                "unit_amount": {
                                    "currency_code": "' . $sale['cye']. '",
                                    "value": "' . $sale['total'] . '"
                                }
                            }
                        ]
                    }
                ]
                }';

            

            $createOrder = $this->paypalHelper->orderCreate($order);

            //update payMethod pendiente
            // $dataPay = [
            //     'payMethod'=>$post['method'],
            //  ];
            // $endpoint = 'orders/update/method/'.$sale['idReservation'];
            // $response = $this->requestApi('put', $token, $endpoint, $dataPay);

            return response()->json($createOrder);
    }


    public function captureOrder($id){
        
        $orderCapture = $this->paypalHelper->orderCapture($id);
        
        //update sale status 
        // $idReservation = $orderCapture['data']['purchase_units'][0]['reference_id'];
        // $data = ['status'=> $orderCapture['data']['status']];
        
        // $token = Session::get('api_token');

        // $endpoint = 'sale/cn/'.md5($idReservation);
        // $response = $this->requestApi('get', $token, $endpoint);
        // $sale = null;
        // if ($response['success']) {
        //     $sale = $response['data']['data']['0'];
        // }
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $token,
        //     'Accept' => 'application/json',
        // ])->put(env('API_URL') . '/api/v1/orders/update/'.$sale['idReservation'], $data);

        return response()->json($orderCapture);
        
    }


        // Payed order 
    public function payedOrder(Request $request)
    {

        $post = (object)$request->all();
    
        $data = ['error' => false, 'cause' => 'Success'];
        
        if (!$post) {
            return response()->json([
                'error' => true,
                'cause' => 'Request invalid'
            ]);
        }

        if (isset($post->invoice) && !empty($post->invoice)) {

            // $dog = Dog::whereRaw('MD5(dog_id) = ?', $post->invoice)
            // ->with('payments') 
            // ->firstOrFail(); 


            // if ($dog->payments->isNotEmpty()) {

            //     $dog->payments()->update(['status' => 'completed']);
            //     $dog->update(['status' => 'completed']);
            // }

            DB::beginTransaction();
            try {
                $dog = Dog::whereRaw('MD5(dog_id) = ?', [$post->invoice])
                    ->with('payments')
                    ->firstOrFail(); 
            
                if ($dog->payments->isNotEmpty()) {
                    
                    $dog->payments()->update(['status' => 'completed']);
                    $dog->update(['status' => 'completed']);
                }
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => 'Error al actualizar: ' . $e->getMessage()], 500);
            }


        }


        return response()->json($data);
    }


    public function completed()
    {
        return view('payments/complited');
    }

}
