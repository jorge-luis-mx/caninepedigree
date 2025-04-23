<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\DogsValidations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

use App\Mail\sendEmailDogs;
use Illuminate\Support\Facades\Mail;
use App\Mail\Breeding;

//model
use App\Models\Dog;
use App\Models\Payment;
use App\Models\DogPayment;
use App\Models\UserProfile;
use App\Models\DogParentRequest;
use App\Models\BreedingRequest;

//Traits
use App\Traits\Pedigree;

class DogController extends Controller
{

    use Pedigree;



    public function index(Request $request)
    {

        $user = auth()->user();
        $profile_id = $user->profile_id;
        $profile = $user->userprofile;

        $role = $user->role;
        $permissions = $role->permissions; 
        

        // $permissions = $user->role->permissions; // Permisos del rol del usuario
        // $hasPermission = $permissions->contains('name', 'create_post');

       
        if ($role->name == 'admin' || $role->name =='customer') {

            if ($profile_id == $profile->profile_id) {
                
                $dogs = Dog::where('dogs.current_owner_id', $profile_id)
                ->whereIn('dogs.status', ['completed'])
                ->leftJoin('dog_payments', 'dogs.dog_id', '=', 'dog_payments.dog_id')
                ->leftJoin('payments', 'dog_payments.payment_id', '=', 'payments.payment_id')
                ->select(
                    'dogs.dog_id',
                    DB::raw('MD5(dogs.dog_id) as dog_hash'),
                    'dogs.name',
                    'dogs.status',
                    DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
                    DB::raw('100 - COALESCE(SUM(payments.amount), 0) as amount_due')
                )
                ->groupBy('dogs.dog_id', 'dogs.name', 'dogs.breed', 'dogs.color', 'dogs.sex', 'dogs.status')
                ->get();

                
            }
            
        }
  
       
        return view('dogs.list-dogs',compact('dogs','role'));
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
        $role = $user->role;
        $permissions = $role->permissions; 

        if ($role->name == 'admin' || $role->name =='customer') {

            $owner = $user->profile_id == $profile->profile_id ? $profile->profile_id: null;
            
        }

        $sire_id = (isset($validatedData['sire_id']) && !empty($validatedData['sire_id']) && $validatedData['sire_id'] != null) ? $validatedData['sire_id'] : null;
        $dam_id = (isset($validatedData['dam_id']) && !empty($validatedData['dam_id']) && $validatedData['dam_id'] != null) ? $validatedData['dam_id'] : null;
      
        DB::beginTransaction();

        $regnum = $this->generarCodigoRegistro();
        
        try {
            $text = preg_split('/\s+/', $profile->lastName);
            // Crea el registro sin reg_no
            $dog = Dog::create([
                'reg_no'=>$regnum,
                'name' => $text[0].' '.$validatedData['name'],
                'breed' => $validatedData['breed'],
                'color' => $validatedData['color'],
                'sex' => $validatedData['sex'],
                'birthdate' => $validatedData['birthdate'],
                'sire_id' => $sire_id,
                'dam_id' => $dam_id,
                'breeder_id' => $owner,
                'current_owner_id' => $owner,
                'status'=>$role->name == 'admin'? 'completed':'pending'
            ]);

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
                    'from'=>'canine@aquacaribbeantravel.com',
                    'subject' => 'Dog registration request',
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
                    'from'=>'canine@aquacaribbeantravel.com',
                    'subject' => 'Dog registration request',
                    'url'=>'http://www.caninepedigree-dev.com/register',
                    'dog'=>$dog
                ];
                Mail::to($damEmail)->send(new sendEmailDogs($datos));
            }

            $dog->dog_id_md = md5($dog->dog_id);
            $dog->rol = $role->name;

            $parent_type = $request->sex === 'M' ? 'sire' : 'dam';

            // Verificamos si hay una solicitud de cruza pendiente para este usuario
            $parentRequest = DogParentRequest::where('email', $profile->email)
                            ->where('parent_type',$parent_type)
                            ->first();

            if ($parentRequest) {
                // Crear la solicitud de cruza automáticamente
                BreedingRequest::create([
                    'female_dog_id' => $request->sex === 'F' ? $dog->dog_id : $parentRequest->dog_id,
                    'male_dog_id' => $request->sex === 'M' ? $dog->dog_id : $parentRequest->dog_id,
                    'requester_id'=>$profile->profile_id,
                    'owner_id'=>$profile->profile_id,
                    'status' => 'pending',
                ]);

                // Eliminar solicitud previa
                $parentRequest->delete();

                // (Opcional) Notificar al solicitante original
                // $requestingUser = Dog::find($parentRequest->dog_id)->user;
                // Mail::to($requestingUser->email)->send(new Breeding($dog));
            }

           
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
        $dog = $pedigree['dog'];


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

        return view('pedigree.show-pedigree', compact('pedigree'));
       
    }
}
