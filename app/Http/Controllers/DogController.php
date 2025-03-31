<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\DogsValidations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
//model
use App\Models\Dog;
use App\Models\Payment;
use App\Models\DogPayment;
class DogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $dogs = Dog::whereIn('dogs.status', ['pending', 'completed']) // Especificar la tabla en la condición
        ->leftJoin('dog_payments', 'dogs.dog_id', '=', 'dog_payments.dog_id')
        ->leftJoin('payments', 'dog_payments.payment_id', '=', 'payments.payment_id')
        ->select(
            'dogs.dog_id',
            'dogs.name',
            'dogs.breed',
            'dogs.sex',
            'dogs.status',
            DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
            DB::raw('100 - COALESCE(SUM(payments.amount), 0) as amount_due')
        )
        ->groupBy('dogs.dog_id', 'dogs.name', 'dogs.sex', 'dogs.status')
        ->get();
    
    
    


        // $dogs = Dog::whereHas('payments', function ($query) {
        //     $query->whereIn('status', ['completado', 'pendiente']);
        // })
        // ->with('payments') // Cargar pagos relacionados
        // ->get();
        
        

       
        return view('dogs.list-dogs',compact('dogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dogs.create-dog');
    }


    public function searchDog($reg_no)
    {

        $data = [
            'status' => 400,
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'data'=>[],
            'errors' => null,
        ];

        $reg_no = trim($reg_no);

        // Buscar el perro por número de registro
        $dog = Dog::where('reg_no', $reg_no)->first();
        
        $data = ['status' => 400, 'data' => null]; // Valor por defecto
        
        if ($dog) {
            $data['status'] = 200;
            $data['data'] = $dog;
        } else {
            // Buscar por nombre si no se encontró por número de registro
            $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->get();
            
            if ($dogs->isNotEmpty()) {
                $data['status'] = 200;
                $data['data'] = $dogs;
            }
        }
        

        
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Limpiar todos los campos antes de la validación
        $cleanedData = array_map('trim', $request->all());
        $request->merge($cleanedData); // Volver a fusionar los datos limpios 
        $validator = DogsValidations::validate($request->all());
    
        $data = [
            'status' => 400,
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'data'=>[],
            'errors' => null,
        ];
        
        if ($validator->fails()) {

            $data['errors'] = $validator->errors();
            return response()->json($data, 400);
        }
        $validatedData = $validator->validated();
        
    
        $user = auth()->user();
        $profile = $user->userprofile;

        if ($user->role == 'admin' || $user->role =='customer') {

            $owner = $user->profile_id == $profile->profile_id ? $profile->profile_id: null;
            
        }

        $sire_id = (isset($dog->sire_id) && !empty($dog->sire_id) && $dog->sire_id != null) ? $dog->sire_id : null;
        $dam_id = (isset($dog->dam_id) && !empty($dog->dam_id) && $dog->dam_id != null) ? $dog->dam_id : null;
        
        DB::beginTransaction();

        try {
            
            // Crea el registro sin reg_no
            $dog = Dog::create([
                'name' => $validatedData['name'],
                'breed' => $validatedData['breed'],
                'color' => $validatedData['color'],
                'sex' => $validatedData['sex'],
                'birthdate' => $validatedData['birthdate'],
                'sire_id' => $sire_id,
                'dam_id' => $dam_id,
                'breeder_id' => $owner,
                'current_owner_id' => $owner,
                'status' => 1
            ]);
            // Asigna reg_no después de la creación
            $dog->reg_no = "DOG-" . str_pad($dog->dog_id, 5, '0', STR_PAD_LEFT);
            $dog->save();

            // Crear el pago
            $payment = Payment::create([
                'user_id' => $owner, // Usuario que realiza el pago
                'amount' => 100.00, // Monto del pago (puedes ajustarlo según corresponda)
                'type' => 'registration', // Tipo de pago (registro del perro)
                'status' => 'pending', // Estado del pago (aún no completado)
                'payment_method' => 'card' // Método de pago (ajústalo según la lógica de pago)
            ]);

            // Registrar la relación en dog_payments
            DogPayment::create([
                'dog_id' => $dog->dog_id,
                'payment_id' => $payment->payment_id
            ]);

            if ($sire_id == null ) {

                $sireEmail = $validatedData['sire_email'];
                $descriptionSire = $validatedData['descriptionSire'];
    
            }
            if ($dam_id == null ) {

                $damEmail = $validatedData['dam_email'];
                $descriptionDam = $validatedData['descriptionDam'];
            }
            $dog->dog_id_md = md5($dog->dog_id);
            //insert payments
           
            $data['message'] = 'Pricing inserted successfully';
            $data['status'] = 200;
            $data['data'] = $dog;
            // Confirma la transacción
            DB::commit();
        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the pricing. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
            DB::rollBack();
        }

        
        return response()->json($data);
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
