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


use App\Models\Payment;

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

        $payment = Payment::whereRaw('MD5(payment_id) = ?', [$id])->firstOrFail();
        $dogs = $payment->dogs;

        if ($dogs->count() === 1 && !$dogs->first()->is_puppy) {
            $dog = $dogs->first();
            
            return view('payments/payment',compact('paypal','dog','payment'));
        } else {
            $puppies = $dogs->where('is_puppy', true);
            return view('payments/payment',compact('paypal','puppies', 'payment'));
        }




        // $payment = Payment::whereRaw('MD5(payment_id) = ?', [$id])->firstOrFail();

        // if ($payment) {
        //     $dogs = $payment->dogs;

        //     if ($dogs->count() === 1 && !$dogs->first()->is_puppy) {
        //         // Un solo perro adulto
        //         $dog = $dogs->first();
        //         $dog->invoice = md5($dog->dog_id);

        //         // Puedes usar $dog en la vista
        //     } else {
        //         // Varios cachorros
        //         $puppies = $dogs->where('is_puppy', true);

        //         foreach ($puppies as $puppy) {
        //             $puppy->invoice = md5($puppy->dog_id);
        //         }

        //         // Puedes usar $puppies en la vista
        //     }
        // }




        // $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])
        // ->firstOrFail();
        // $dog->payment = $dog->payments;
        // $dog->invoice = md5($dog->dog_id);

        return view('payments/payment',compact('paypal','dog','puppies', 'payment'));
    }

    public function createOrder(Request $request){
        
        $post = $request->all();

        $payment =  Payment::where('order_reference', $post['invoice'])->first();
        $type = $payment->type;

        $description = match ($type) {
            'puppy_registration' => 'Registro de cachorros',
            'registration'   => 'Registro de perro',
            'mating_request'     => 'Solicitud de cruza',
            'ownership_transfer' => 'Transferencia de propiedad',
            'pedigree_certificate' => 'Certificado de pedigree',
            default              => 'Servicio relacionado con perros'
        };


        $total = $payment->amount; // monto total
        $currency = 'MXN';
        $userId = $payment->order_reference;
        $referenceId = $payment->order_reference;

        $order = [
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => "",
                "cancel_url" => ""
            ],
            "purchase_units" => [
                [
                    "reference_id" => $referenceId,
                    "description" => $description,
                    "invoice_id" => $referenceId,
                    "custom_id" => $userId,
                    "amount" => [
                        "currency_code" => $currency,
                        "value" => number_format($total, 2, '.', ''),
                        "breakdown" => [
                            "item_total" => [
                                "currency_code" => $currency,
                                "value" => number_format($total, 2, '.', '')
                            ],
                            "tax_total" => [
                                "currency_code" => $currency,
                                "value" => "0.00"
                            ],
                            "discount" => [
                                "currency_code" => $currency,
                                "value" => "0.00"
                            ]
                        ]
                    ],
                    "items" => [
                        [
                            "sku" => $referenceId,
                            "name" => $description,
                            "quantity" => 1,
                            "unit_amount" => [
                                "currency_code" => $currency,
                                "value" => number_format($total, 2, '.', '')
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $orderJson = json_encode($order);
        $createOrder = $this->paypalHelper->orderCreate($order);
        return response()->json($createOrder);


        
       
        $dog = Dog::whereRaw('MD5(dog_id) = ?', $post['invoice'])
        ->firstOrFail();
        $dog->payment = $dog->payments;
   

            $sale = [];
            $sale['keyReservation'] = $dog->reg_no;
            $sale['cye'] = 'MXN';
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

            return response()->json($createOrder);
    }


    public function captureOrder($id){
        
        $orderCapture = $this->paypalHelper->orderCapture($id);
        return response()->json($orderCapture);
        
    }


        // Payed order 
    public function payedOrder(Request $request)
    {

        $post = (object)$request->all();

        $payment = Payment::where('order_reference', $post->invoice)->first();

        if (!$payment) {
            return response()->json(['error' => 'Pago no encontrado'], 404);
        }

        $payment->update(['status' => 'completed']);
        $dogs = $payment->dogs;
        if ($dogs->count() == 1 && !$dogs->first()->is_puppy) {
            // Caso: registro de perro individual
            $dogs->first()->update(['status' => 'completed']);
        } else {
            // Caso: camada de cachorros
            foreach ($dogs as $dog) {
                $dog->update(['status' => 'completed']);
            }
        }

        // $payment->dogs()->update(['status' => 'completed']);
        // // Actualizar los perros
        // foreach ($payment->dogs as $dog) {
        //     $dog->update([
        //         'status' => 'completed'
        //     ]);
        // }
 
        $data = ['error' => false, 'cause' => 'Success'];
        return response()->json($data);

        if (!$post) {
            return response()->json([
                'error' => true,
                'cause' => 'Request invalid'
            ]);
        }

        if (isset($post->invoice) && !empty($post->invoice)) {


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
