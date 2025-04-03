<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\DogsValidations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

use App\Mail\sendEmailDogs;
use Illuminate\Support\Facades\Mail;

//model
use App\Models\Dog;
use App\Models\Payment;
use App\Models\DogPayment;
use App\Models\UserProfile;

class DogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = auth()->user();
        $profile_id = $user->profile_id;
        $profile = $user->userprofile;

        if ($user->role == 'admin' || $user->role =='customer') {

            if ($profile_id == $profile->profile_id) {
                $dogs = Dog::where('dogs.current_owner_id', $profile_id) // Filtrar por dueño
                    ->whereIn('dogs.status', ['pending', 'completed']) // Filtrar por estado
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
                    ->groupBy('dogs.dog_id', 'dogs.name', 'dogs.breed', 'dogs.sex', 'dogs.status')
                    ->get();
                
            }
            

        }



 
    
       
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

                //send mails
                $datos = [
                    'from'=>'jorge06g92@gmail.com',
                    'subject' => 'Password change request | Airport Transportation',
                    'url'=>'http://www.caninepedigree-dev.com/register',
                    'dog'=>$dog
                ];
                Mail::to($sireEmail)->send(new sendEmailDogs($datos));
    
            }
            if ($dam_id == null ) {

                $damEmail = $validatedData['dam_email'];
                $descriptionDam = $validatedData['descriptionDam'];

                //send mails
                $datos = [
                    'from'=>'jorge06g92@gmail.com',
                    'subject' => 'Password change request | Airport Transportation',
                    'url'=>'http://www.caninepedigree-dev.com/register',
                    'dog'=>$dog
                ];
                Mail::to($damEmail)->send(new sendEmailDogs($datos));
            }
            $dog->dog_id_md = md5($dog->dog_id);


           
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
        
        
        $dog = Dog::where('dog_id', $id)->first();
        $birthdate = $dog->birthdate;
        return view('dogs/show-dog',compact('dog','birthdate'));
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
        $dog = Dog::find($id);
        if ($dog) {
            $dog->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
        
    }



    public function showPedigree($id)
    {
        $dog = DB::table('dogs')->where('dog_id', $id)->first();

        if (!$dog) {
            return redirect()->back()->with('error', 'El perro no existe.');
        }
        
        $pedigree = DB::select("
            WITH RECURSIVE Pedigree AS (
                SELECT 
                    d.dog_id, 
                    d.reg_no, 
                    d.name, 
                    d.breed, 
                    d.color, 
                    d.sex, 
                    d.birthdate, 
                    d.sire_id, 
                    d.dam_id, 
                    1 AS generation
                FROM dogs d
                WHERE d.dog_id = ?
        
                UNION ALL
        
                SELECT 
                    p.dog_id, 
                    p.reg_no, 
                    p.name, 
                    p.breed, 
                    p.color, 
                    p.sex, 
                    p.birthdate, 
                    p.sire_id, 
                    p.dam_id, 
                    Pedigree.generation + 1 AS generation
                FROM dogs p
                INNER JOIN Pedigree ON p.dog_id = Pedigree.sire_id OR p.dog_id = Pedigree.dam_id
                WHERE Pedigree.generation < 4  -- Limita a 4 generaciones
            )
            SELECT * FROM Pedigree 
            ORDER BY generation, 
                     CASE WHEN sire_id IS NOT NULL THEN 0 ELSE 1 END,  -- Prioriza sire (padre) antes que dam (madre)
                     sex
        ", [$id]);
        
        return view('pedigree.show-pedigree', compact('dog', 'pedigree'));
        
        
    }
}
