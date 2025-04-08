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

use App\Traits\Pedigree;


class DogController extends Controller
{

    use Pedigree;


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = auth()->user();
        $profile_id = $user->profile_id;
        $profile = $user->userprofile;
        $rol = $user->role;
        if ($user->role == 'admin' || $user->role =='customer') {

            if ($profile_id == $profile->profile_id) {

                $dogs = Dog::where('dogs.current_owner_id', $profile_id)
                ->whereIn('dogs.status', ['pending', 'completed'])
                ->leftJoin('dog_payments', 'dogs.dog_id', '=', 'dog_payments.dog_id')
                ->leftJoin('payments', 'dog_payments.payment_id', '=', 'payments.payment_id')
                ->select(
                    'dogs.dog_id',
                    DB::raw('MD5(dogs.dog_id) as dog_hash'),
                    'dogs.name',
                    'dogs.breed',
                    'dogs.color',
                    'dogs.sex',
                    'dogs.status',
                    DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
                    DB::raw('100 - COALESCE(SUM(payments.amount), 0) as amount_due')
                )
                ->groupBy('dogs.dog_id', 'dogs.name', 'dogs.breed', 'dogs.color', 'dogs.sex', 'dogs.status')
                ->get();

                
            }
            
        }
  
       
        return view('dogs.list-dogs',compact('dogs','rol'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dogs.create-dog');
    }


    public function search($reg_no)
    {

        $data = [
            'status' => null,
            'message' => '',
            'data'=>[],
            'errors' => null,
        ];

        $reg_no = trim($reg_no);

        // Buscar el perro por número de registro
        $dog = Dog::where('reg_no', $reg_no)->first();
        
        if ($dog) {
            $dog->dog_hash = md5($dog->dog_id);
            $data['status'] = 200;
            $data['data'] = $dog;
        } else {
            // Buscar por nombre si no se encontró por número de registro
            $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->get();
           
            if ($dogs->isNotEmpty()) {
                foreach ($dogs as $dog) {
                    $dog->dog_hash = md5($dog->dog_id);
                }
                $data['status'] = 200;
                $data['data'] = $dogs;

            }else{
                $data['status'] = 204;
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
            'status' => null,
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'data'=>[],
            'errors' => null,
        ];
        
        if ($validator->fails()) {

            $data['errors'] = $validator->errors();
            return response()->json($data, 422);
        }
        $validatedData = $validator->validated();
        
    
        $user = auth()->user();
        $profile = $user->userprofile;

        if ($user->role == 'admin' || $user->role =='customer') {

            $owner = $user->profile_id == $profile->profile_id ? $profile->profile_id: null;
            
        }

        $sire_id = (isset($validatedData['sire_id']) && !empty($validatedData['sire_id']) && $validatedData['sire_id'] != null) ? $validatedData['sire_id'] : null;
        $dam_id = (isset($validatedData['dam_id']) && !empty($validatedData['dam_id']) && $validatedData['dam_id'] != null) ? $validatedData['dam_id'] : null;
      
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
                'payment_method' => null // Método de pago (ajústalo según la lógica de pago)
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
        
        $dog = Dog::with(['currentOwner', 'breeder'])->whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();
        $dog->dog_hash = md5($dog->dog_id);
        $dog = ['dog'=>$dog];


        // $airport = Dog::whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();
        // $dog = Dog::where('dog_id', $id)->first();
        // $birthdate = $dog->birthdate;

        //$dog = Dog::with(['currentOwner', 'breeder'])->where('dog_id', $id)->first();
        return view('dogs/show-dog',compact('dog'));
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

        $dog = Dog::whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();

        if ($dog) {
            $dog->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
        
    }



    public function showPedigree($id)
    {
       
        
        $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])
            ->with([
                'sire', 'dam',
                'sire.sire', 'sire.dam',
                'dam.sire', 'dam.dam',
                'sire.sire.sire', 'sire.sire.dam',
                'sire.dam.sire', 'sire.dam.dam',
                'dam.sire.sire', 'dam.sire.dam',
                'dam.dam.sire', 'dam.dam.dam',
            ])
            ->firstOrFail();

        $pedigree = $this->findPedigree($dog);



        // dd($pedigree);  
        // return response()->json($pedigree);

        // $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])
        //     ->firstOrFail();  

        // Llamar al método findPedigree() para obtener el pedigree
        // $pedigree = $this->findPedigree($dog);

        return view('pedigree.show-pedigree', compact('pedigree'));
        // return response()->json($pedigree);
        
        
    }
}
