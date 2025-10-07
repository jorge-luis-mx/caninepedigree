<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\DogsValidations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Exception;
use RuntimeException;

use Illuminate\Support\Facades\Mail;
use App\Mail\sendEmailDogs;
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
        $permissions = $role->permissions->where('pivot.status', 1);
        // $permissions = $user->role->permissions; // Permisos del rol del usuario
        // $hasPermission = $permissions->contains('name', 'create_post');

        $arrayRole =['Admin'];

        if (in_array($role->name, $arrayRole)) {

            $dogs = Dog::whereIn('dogs.status', ['completed','exempt'])
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

            return view('dogs.list-dogs',compact('dogs','role','permissions'));
        }

        $arrayOwner = ['Administrator','Employee'];
        // $ownerProfile = UserProfile::find(1);
        // dd($profile);
        $owner = in_array($role->name, $arrayOwner) ? 2 : $profile_id;

    

        $dogs = Dog::where('dogs.current_owner_id', $owner)
            ->whereIn('dogs.status', ['completed','exempt'])
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

        return view('dogs.list-dogs',compact('dogs','role','permissions'));
    }


    public function create()
    {
        return view('dogs.create-dog');
    }

    public function find($reg_no,$sex){

        $sex = $sex =='sire'?'M':'F';

        $data = ['status' => 204,'message' => '','data' => [],'errors' => null];

        if (!empty($reg_no)) {

            $dogs = Dog::where('sex', $sex)
                ->whereIn('status', ['completed', 'exempt'])
                ->where('name', 'LIKE', "%$reg_no%")
                ->orderByRaw("
                    CASE 
                        WHEN name = ? THEN 1
                        WHEN name LIKE ? THEN 2
                        WHEN name LIKE ? THEN 3
                        ELSE 4
                    END
                ", [$reg_no, "$reg_no%", "%$reg_no"])
                ->get();


            if ($dogs->isNotEmpty()) {
                // Recorre cada perro en la colección $dogs y asigna un valor hash único basado en su dog_id
                $dogs->each(function ($dog) {
                    // Genera un hash MD5 de 32 caracteres utilizando el dog_id y lo asigna a la propiedad dog_hash del objeto dog
                    $dog->dog_hash = md5($dog->dog_id);
                });

                $data['status'] = 200;
                $data['data'] = $dogs;
            }
        }

        return response()->json($data);
    }

    public function search($reg_no,$breedingSearch=null)
    {

        $reg_no = trim($reg_no);
        $data = [
            'status' => 204,
            'message' => '',
            'data' => [],
            'errors' => null,
        ];

        if ($breedingSearch!=null ) {
        // Buscar el perro por número de registro
            $dog = Dog::where('reg_no', $reg_no)->where('sex','M')->whereIn('status', ['completed','exempt'])->first();

            if ($dog) {
                $dog->dog_hash = md5($dog->dog_id);
                $data['status'] = 200;
                $data['data'] = $dog;
            } else {
                // Buscar por nombre si no se encontró por número de registro
                $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->where('sex','M')->whereIn('status', ['completed','exempt'])->get();

                if ($dogs->isNotEmpty()) {
                    $dogs->each(function ($dog) {
                        $dog->dog_hash = md5($dog->dog_id);
                    });

                    $data['status'] = 200;
                    $data['data'] = $dogs;
                }
            }
            return response()->json($data);
        }

        // Buscar el perro por número de registro
        $dog = Dog::where('reg_no', $reg_no)->whereIn('status', ['completed','exempt'])->first();

        if ($dog) {
            $dog->dog_hash = md5($dog->dog_id);
            $data['status'] = 200;
            $data['data'] = $dog;
        } else {
            // Buscar por nombre si no se encontró por número de registro
            $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->whereIn('status', ['completed','exempt'])->get();

            if ($dogs->isNotEmpty()) {
                $dogs->each(function ($dog) {
                    $dog->dog_hash = md5($dog->dog_id);
                });

                $data['status'] = 200;
                $data['data'] = $dogs;
            }
        }
        return response()->json($data);
    }


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
        
        $dog = Dog::whereRaw('LOWER(name) = ?', [strtolower($validatedData['name'])])->first();


        if ($dog) {

            $data['message'] = 'A dog with this name already exists in the system.';
            return response()->json($data, 422);
        }
    
        $user = auth()->user();
        $profile = $user->userprofile;
        

        $role = $user->role;
        $permissions = $role->permissions; 
        
        $arrayRole =['Employee','Admin'];
        
        $ownerProfile = UserProfile::find(2);
        $owner = in_array($role->name, $arrayRole) ? $ownerProfile->profile_id : $profile->profile_id;


        $sire_id = (isset($validatedData['sire_id']) && !empty($validatedData['sire_id']) && $validatedData['sire_id'] != null) ? $validatedData['sire_id'] : null;
        $dam_id = (isset($validatedData['dam_id']) && !empty($validatedData['dam_id']) && $validatedData['dam_id'] != null) ? $validatedData['dam_id'] : null;
      

        $regnum = $this->generarCodigoRegistro();
        $orderReference = $this->getOrderReference();

        try {

            DB::beginTransaction();

            $dogStatus = ['Admin','Administrator','Employee'];

            $payment = Payment::create([
                'user_id' => $owner, 
                'order_reference'=>$orderReference,
                'amount' => 100.00, 
                'type' => 'registration', 
                'status' => 'pending', 
                'payment_method' => null 
            ]);

            $paymentProtected = $payment->toArray();
            $paymentProtected['id_hash'] = md5($payment->payment_id);
            $paymentProtected['rol'] = $role->name;
            
            $dog = Dog::create([
                'reg_no'=>$regnum,
                'name' => $validatedData['name'],
                'breed' => 'Pit Bull Terrier',
                'color' => $validatedData['color'],
                'sex' => $validatedData['sex'],
                'birthdate' => $validatedData['birthdate'],
                'sire_id' => $sire_id,
                'dam_id' => $dam_id,
                'breeder_id' => $owner,
                'current_owner_id' => $owner,
                'created_by_user_id'=>$user->user_id,
                'status'=> in_array($role->name, $dogStatus ) ? 'exempt':'pending'
            ]);
            $dog->save();

            DogPayment::create([
                'dog_id' => $dog->dog_id,
                'payment_id' => $payment->payment_id
            ]);


            if ($sire_id == null ) {

                try {

                    $sireEmail = $validatedData['sire_email'];
                    $descriptionSire = $validatedData['descriptionSire'] ?? '';

                    // Validate email before sending
                    if (!filter_var($sireEmail, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('The sire email address is not valid');
                    }

                    // Configure email data
                    $emailData = [
                        'from' => config('mail.from.address'),
                        'from_name' => config('mail.from.name', 'Canine'),
                        'subject' => 'Dog registration request',
                        'url' => config('app.url') . 'register',
                        'dog' => $dog,
                        'description' => $descriptionSire
                    ];

                    // Send email with error handling
                    Mail::to($sireEmail)->send(new SendEmailDogs($emailData));

                    // Log successful sending
                    Log::info('Dog registration email sent successfully', [
                        'recipient' => $sireEmail,
                        'dog_id' => $dog->id ?? null
                    ]);

                } catch (Exception $e) {
                        // Log the error
                        Log::error('Error sending dog registration email', [
                            'error' => $e->getMessage(),
                            'recipient' => $sireEmail ?? 'undefined',
                            'dog_id' => $dog->id ?? null
                        ]);
                        
                        // Optional: throw custom exception or handle error according to requirements
                        throw new RuntimeException('Could not send notification email', 0, $e);
                }

                // $sireEmail = $validatedData['sire_email'];
                // $descriptionSire = $validatedData['descriptionSire'];

                // //send mails
                // $datos = [
                //     'from'=>'info@devscun.com',
                //     'subject' => 'Dog registration request',
                //     'url'=>'http://www.caninepedigree-dev.com/register',
                //     'dog'=>$dog
                // ];
                // Mail::to($sireEmail)->send(new sendEmailDogs($datos));
    
            }

            if ($dam_id == null ) {

                try {
                    $damEmail = $validatedData['dam_email'];
                    $descriptionDam = $validatedData['descriptionDam'] ?? '';

                    // Validate email before sending
                    if (!filter_var($damEmail, FILTER_VALIDATE_EMAIL)) {
                        throw new Exception('The dam email address is not valid');
                    }

                    // Configure email data
                    $emailData = [
                        'from' => config('mail.from.address', 'info@devscun.com'),
                        'from_name' => config('mail.from.name', 'Canine'),
                        'subject' => 'Dog registration request',
                        'url' => config('app.url') . 'register',
                        'dog' => $dog,
                        'description' => $descriptionDam
                    ];

                    // Send email with error handling
                    Mail::to($damEmail)->send(new SendEmailDogs($emailData));
                    
                    // Log successful sending
                    Log::info('Dog registration email sent successfully to dam', [
                        'recipient' => $damEmail,
                        'dog_id' => $dog->id ?? null
                    ]);

                } catch (Exception $e) {
                    // Log the error
                    Log::error('Error sending dog registration email to dam', [
                        'error' => $e->getMessage(),
                        'recipient' => $damEmail ?? 'undefined',
                        'dog_id' => $dog->id ?? null
                    ]);
                    
                    // Optional: throw custom exception or handle error according to requirements
                    throw new RuntimeException('Could not send notification email to dam', 0, $e);
                }

                // $damEmail = $validatedData['dam_email'];
                // $descriptionDam = $validatedData['descriptionDam'];

                // //send mails
                // $datos = [
                //     'from'=>'info@devscun.com',
                //     'subject' => 'Dog registration request',
                //     'url'=>'http://www.caninepedigree-dev.com/register',
                //     'dog'=>$dog
                // ];
                // Mail::to($damEmail)->send(new sendEmailDogs($datos));
            }

            $dog->dog_id_md = md5($dog->dog_id);
            $dog->rol = $role->name;
            $paymentProtected['rol'] = $role->name;
            
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
                    'status' =>$role->name == 'Admin'? 'completed':'pending',
                ]);

                // Eliminar solicitud previa
                $parentRequest->delete();

                // (Opcional) Notificar al solicitante original
                // $requestingUser = Dog::find($parentRequest->dog_id)->user;
                // Mail::to($requestingUser->email)->send(new Breeding($dog));
            }

            $data['message'] = 'CANINE inserted successfully';
            $data['status'] = 200;
            $data['data'] = $paymentProtected;
            
            DB::commit();
            
        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the CANINE. Please check the data and try again';
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
    

    public function getOrderReference(){

        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $orderReference = '';

        for ($i = 0; $i < 12; $i++) {
            $orderReference .= $cadena[random_int(0, strlen($cadena) - 1)];
        }

        return $orderReference;
    }


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


    public function edit(string $id)
    {
        $dog = Dog::whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();
        $dogs =  $dogs = Dog::all(); 
        return view('dogs.edit-dog', compact('dog','dogs'));
    }


    public function update(Request $request, string $id)
    {
        $dog = Dog::find($id);

        if (!$dog) {
            return response()->json(['status' => 404, 'message' => 'Perro no encontrado.'], 404);
        }

        $dog->update($request->only([
            'name', 'breed', 'color', 'sex','url', 'birthdate', 'sire_id', 'dam_id'
        ]));

        return response()->json(['status' => 200, 'message' => 'Perro actualizado correctamente.']);
    }


    public function destroy(string $id)
    {

        $dog = Dog::whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();

        if ($dog) {
            $dog->status = 'delete';
            $dog->save();
    
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
