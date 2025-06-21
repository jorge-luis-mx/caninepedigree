<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\ValidationsAdminDogs;
use Illuminate\Support\Facades\DB;

//model
use App\Models\Dog;
use App\Models\Payment;
use App\Models\DogPayment;
use App\Models\UserProfile;
use App\Models\DogParentRequest;
use App\Models\BreedingRequest;

use Carbon\Carbon;

class AdminDogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

       return view('adminDogs/create',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Limpiar todos los campos antes de la validación
        $cleanedData = array_map('trim', $request->all());
        $request->merge($cleanedData); // Volver a fusionar los datos limpios 
        $validator = ValidationsAdminDogs::validate($request->all());

        if ($validator->fails()) {
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();


        $profile = UserProfile::where('profile_id',1)->first();
        $user =  $profile->user->first();
        
        DB::beginTransaction();

        $regnum = $this->generarCodigoRegistro();

        try {

            // Crea el registro sin reg_no
            $dog = Dog::create([
                'reg_no'=>$regnum,
                'name' => $validatedData['name'],
                'breed' => 'Pit Bull Terrier',
                'color' => $validatedData['color'],
                'sex' => $validatedData['sex'],
                'birthdate' => Carbon::parse('2026-01-01'),
                'sire_id' => null,
                'dam_id' => null,
                'breeder_id' => $profile->profile_id,
                'current_owner_id' => $profile->profile_id,
                'status' => 'exempt'
            ]);

            $dog->save();



            $orderReference = $this->getOrderReference();

            $payment = Payment::create([
                'user_id' => 1, 
                'order_reference'=>$orderReference,
                'amount' => 100.00, 
                'type' => 'registration', 
                'status' => 'pending', 
                'payment_method' => 'Paypal' 
            ]);

            // Registrar la relación en dog_payments
            DogPayment::create([
                'dog_id' => $dog->dog_id,
                'payment_id' => $payment->payment_id
            ]);

            DB::commit();
       
            return redirect()->back()->with('success', '¡El perro fue registrado con éxito!');
        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the pricing. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
            DB::rollBack();
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
    public function generarCodigoRegistro() {
        $letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numeros = "0123456789";
    
        $codigo = 'REG-';
    
        // Letras (3 caracteres)
        for ($i = 0; $i < 3; $i++) {
            $codigo .= $letras[random_int(0, strlen($letras) - 1)];
        }
    
        // Números (6 dígitos)
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $numeros[random_int(0, strlen($numeros) - 1)];
        }
    
        return $codigo;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
