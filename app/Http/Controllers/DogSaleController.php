<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuyerRegistrationLink;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Exception;

//model
use App\Models\Dog;
use App\Models\DogSale;
use App\Models\Payment;
use App\Models\DogPayment;

class DogSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $profile = $user->userprofile;

        $pendingSale = DogSale::where('buyer_email',$profile->email)->where('status', 'pending')->get();

        return view('dogSales.index',compact('pendingSale'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {

        
        $dog = Dog::whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();

        return view('dogSales.create', compact('dog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = $user->userprofile;

        $request->validate([
            'dog_id' => 'required|integer',
            'buyer_email' => 'required|email',
            'price' => 'nullable|numeric',
            'payment_method' => 'required|string|in:cash,online,transfer',
        ]);

        $dog = Dog::findOrFail($request->dog_id);

        if ($dog->current_owner_id !== $profile->profile_id) {
            return back()->with('error', 'You are not the owner of this dog.');
        }

        // Evitar que el dueño se venda a sí mismo
        if ($request->buyer_email === $profile->email) {
            return back()->with('error', 'You cannot sell or transfer a dog to yourself.');
        }

        $userExists = $profile::where('email', $request->buyer_email)->exists();

        $token = \Illuminate\Support\Str::random(40);
        $url = $userExists
                ? url(config('app.url') . "?ownership={$token}") 
                : url(config('app.url') . "register?ownership={$token}");

        $buyer_id = $userExists ? $profile->profile_id: null;

        $sale = DogSale::create([
            'dog_id' => $dog->dog_id,
            'seller_id' => $profile->profile_id,
            'buyer_id' => $buyer_id,
            'buyer_email' =>$request->buyer_email,
            'price' => $request->price,
            'payment_method' => $request->payment_method,
            'sale_date' => now(),
            'status' => 'pending',
        ]);

        $this->sendEmailSale($request,$url,$sale);

        return redirect()->route('dogs.index')
            ->with('success', 'Dog sale completed and ownership transferred.');


        // if (!$buyer) {
        //     // 👤 Comprador no registrado
        //     $sale = DogSale::create([
        //         'dog_id' => $dog->dog_id,
        //         'seller_id' => $profile->profile_id,
        //         'buyer_id' => null,
        //         'buyer_email' => $request->buyer_email,
        //         'price' => $request->price,
        //         'payment_method' => $request->payment_method,
        //         'sale_date' => now(),
        //         'status' => 'pending',
        //     ]);
            


        //     $encryptedId = Crypt::encrypt($sale->sale_id);
        //     $url = url('register?dog_sale=' . urlencode($encryptedId));


            
        //     $this->sendEmailSale($request,$url,$sale);

        //     return redirect()->route('dogs.index')
        //     ->with('success', 'Dog sale completed and ownership transferred.');
        // }

        // // 👥 Comprador ya registrado
        // $sale = DogSale::create([
        //     'dog_id' => $dog->dog_id,
        //     'seller_id' => $profile->profile_id,
        //     'buyer_id' => $buyer->profile_id,
        //     'buyer_email' => $buyer->email,
        //     'price' => $request->price,
        //     'payment_method' => $request->payment_method,
        //     'sale_date' => now(),
        //     'status' => 'pending',
        // ]);

        // Dog::where('dog_id', $request->dog_id)->update(['transfer_pending' => true]);
        


        // return redirect()->route('dogs.index')
        //     ->with('success', 'Dog sale completed and ownership transferred.');
    }

    /**
     * Display the specified resource.
     */
    public function sendEmailSale($request,$url,$sale)
    {
        Dog::where('dog_id', $request->dog_id)->update(['transfer_pending' => true]);

        $emailData = [
            'from' => config('mail.from.address'),
            'from_name' => config('mail.from.name', 'Canine'),
            'subject' => 'update owner request',
            'url' => $url,
            'sale' => $sale,
        ];
            
        Mail::to($request->buyer_email)->send(new BuyerRegistrationLink($emailData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function registerOwnership(Request $request)
    {

        try {

            DB::beginTransaction();

            $user = auth()->user();
            $profile = $user->userprofile;

            $saleIds = $request->input('sales', []);

            // Verificamos que el usuario esté autenticado
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado.'], 401);
            }

            // Validamos que se hayan enviado IDs de venta
            if (empty($saleIds)) {
                return response()->json(['error' => 'No se seleccionaron perros para registrar.'], 400);
            }

            // Obtenemos todas las ventas válidas pertenecientes a ese usuario
            $sales = DogSale::whereIn('sale_id', $saleIds)
                // ->where('buyer_id', $user->id) // puedes descomentar si quieres validar que el comprador sea el mismo usuario
                ->get();
            
            // Verificamos que existan ventas válidas
            if ($sales->isEmpty()) {
                return response()->json(['error' => 'No se encontraron registros válidos de venta.'], 404);
            }

            // Creamos la orden de pago
            $orderReference = $this->getOrderReference();

            // Si el total lo calculas a partir del número de perros comprados
            $total = $sales->count() * 100; // ejemplo: $100 por perro, puedes ajustar

            $payment = Payment::create([
                'user_id' => $profile->profile_id,
                'order_reference' => $orderReference,
                'amount' => $total,
                'type' => 'ownership_change', 
                'status' => 'pending',
                'payment_method' => 'paypal'
            ]);

            // Puedes vincular las ventas al pago si tu tabla lo permite
            foreach ($sales as $sale) {
                DogPayment::create([
                    'dog_id' => $sale->dog_id,
                    'payment_id' => $payment->payment_id
                ]);
            }

            // Confirmar la transacción
            DB::commit();

            $paymentProtected = $payment->toArray();
            $paymentProtected['id_hash'] = md5($payment->payment_id);

            return response()->json([
                'status' => 200,
                'message' => 'Solicitud de registro procesada correctamente.',
                'data' => $paymentProtected,
                'errors' => null
            ]);

        } catch (Exception $e) {
            // Revertir todos los cambios en caso de error
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Ocurrió un error al procesar la solicitud.',
                'data' => null,
                'errors' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }

    }

    public function getOrderReference(){

        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $orderReference = '';

        for ($i = 0; $i < 12; $i++) {
            $orderReference .= $cadena[random_int(0, strlen($cadena) - 1)];
        }

        return $orderReference;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
