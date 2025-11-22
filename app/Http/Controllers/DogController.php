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
use Illuminate\Support\Str;

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
use App\Models\PendingDogRelation;
use App\Models\DogSale;

//Traits
use App\Traits\Pedigree;

class DogController extends Controller
{

    use Pedigree;



    public function index(Request $request)
    {

        $user = auth()->user();
        $profile = $user->userprofile;
        $role = $user->role;

        $profileAdminstrador = UserProfile::find(2);
        
       
        //Trae los permisos que tiene un usuario auntenticado
        $permissions = $role->permissions->where('pivot.status', 1);

        

        if ($role->name=='Admin') {

            $dogs = Dog::whereIn('dogs.status', ['completed','exempt'])
            ->where('transfer_pending', 0)
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

            return view('dogs.list-dogs',compact('dogs','role','permissions'));
        }

        $arrayOwner = ['Administrator','Employee'];
        $owner = in_array($role->name, $arrayOwner) ? $profileAdminstrador->profile_id: $user->profile_id;


        $dogs = Dog::with(['creator.userprofile'])
            ->where('dogs.current_owner_id', $owner)
            ->whereIn('dogs.status', ['completed', 'exempt'])
            ->where('transfer_pending', 0)
            ->leftJoin('dog_payments', 'dogs.dog_id', '=', 'dog_payments.dog_id')
            ->leftJoin('payments', 'dog_payments.payment_id', '=', 'payments.payment_id')
            ->select(
                'dogs.*',
                DB::raw('MD5(dogs.dog_id) as dog_hash'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as total_paid'),
                DB::raw('100 - COALESCE(SUM(payments.amount), 0) as amount_due')
            )
            ->groupBy('dogs.dog_id')
            //->limit(5)
            ->get();

        foreach ($dogs as $dog) {
            
            $creatorProfile = $dog->creator->userprofile ?? null;

            if ($creatorProfile) {

                if (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 1) {
                    
                    $dog->aliasDog = $creatorProfile->kennel_name . ' ' . $dog->name;
                }

                if (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 0) {

                    $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;
                }
                if (empty($creatorProfile->kennel_name) && !empty($creatorProfile->last_name)) {

                    $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;
                    
                }


            } else {
                $dog->aliasDog = $dog->name; // fallback si no hay perfil
            }
        }




        $pendingRequests = BreedingRequest::where('owner_id', $user->profile_id)
            ->where('status', 'pending')
            ->with(['femaleDog', 'maleDog']) // si tienes relaciones definidas
            ->get();


        $pendingSale = DogSale::where('buyer_email',$profile->email)->where('status', 'pending')->get();

        return view('dogs.list-dogs',compact('dogs','role','permissions','pendingRequests','pendingSale'));
    }

    public function create(Request $request)
    {
       
        $user = auth()->user();
        $profile = $user->userprofile;
        
        $token = $request->get('token') ?? session('pending_invite_token');
        $invoice = $request->get('invoice') ?? session('pending_invite_invoice');

        if ($token) {
            
            $pending = PendingDogRelation::where('token', $token)
            ->where('status','pending')
            ->first();

            if (!$pending) {
                return redirect()->route('dogs.index');
            }

            // Si el usuario no está autenticado, redirige al login con el token
            if (!auth()->check()) {
                return redirect()->route('login', ['token' => $token]);
            }

            // Verifica que el email del usuario coincida con el correo pendiente
            if ($profile->email !== $pending->pending_email) {
                return redirect()->route('dogs.index');
            }
            // if (session()->has('pending_invite_token')) {
            //     session()->forget('pending_invite_token');
            // }
          
            // Renderiza la vista con los datos del registro pendiente
            return view('dogs.create-dog', [
                'pending_token' => $token,
                'pending_name' => $pending->temp_dog_name,
                'relation_type' => $pending->relation_type=='sire'?'M':'F',
                
            ]);
        }


        if ($invoice) {
            
            $parentRequest = DogParentRequest::where('token', $invoice)->first();
            //->where('status','pending')
            
           
            if (!$parentRequest) {
                
                return redirect()->route('dogs.index');
            }
            // Si el usuario no está autenticado, redirige al login con el token
            if (!auth()->check()) {
                return redirect()->route('login', ['invoice' => $invoice]);
            }
            // Verifica que el email del usuario coincida con el correo pendiente
            if ($profile->email !== $parentRequest->email) {
                return redirect()->route('dogs.index');
            }
            // if (session()->has('pending_invite_invoice')) {
            //     session()->forget('pending_invite_invoice');
            // }
            // Renderiza la vista con los datos del registro pendiente
            return view('dogs.create-dog', [
                'pending_invoice' => $invoice,
                'pending_name' => $parentRequest->temp_dog_name,
                'relation_type' => $parentRequest->parent_type =='sire'? 'M':'F'
            ]);
        }


        return view('dogs.create-dog');
    }

    public function find($reg_no,$sex){

        $sex = $sex =='sire'?'M':'F';

        $data = ['status' => 204,'message' => '','data' => [],'errors' => null];

        if (!empty($reg_no)) {

            $dogs = Dog::with(['creator.userprofile'])
                ->where('sex', $sex)
                ->whereIn('status', ['completed', 'exempt'])
                ->where(function ($query) use ($reg_no) {
                    $reg_no = trim($reg_no);
                    $words = preg_split('/\s+/', $reg_no); // divide por espacios

                    // Buscar coincidencias en dogs.name
                    $query->where('dogs.name', 'LIKE', "%$reg_no%")

                    // Buscar coincidencias en kennel_name o last_name
                    ->orWhereHas('creator.userprofile', function ($subquery) use ($reg_no) {
                        $subquery->where('kennel_name', 'LIKE', "%$reg_no%")
                                ->orWhere('last_name', 'LIKE', "%$reg_no%");
                    })

                    // 🔥 Buscar por cada palabra dentro de la concatenación kennel_name + name
                    ->orWhere(function ($q) use ($words) {
                        foreach ($words as $word) {
                            $q->whereRaw("
                                EXISTS (
                                    SELECT 1
                                    FROM user_profiles up
                                    WHERE up.profile_id = dogs.created_by_user_id
                                    AND CONCAT(
                                        COALESCE(up.kennel_name, ''), ' ',
                                        COALESCE(dogs.name, '')
                                    ) LIKE ?
                                )
                            ", ["%{$word}%"]);
                        }
                    });
                })
                ->orderByRaw("
                    CASE 
                        WHEN dogs.name = ? THEN 1
                        WHEN dogs.name LIKE ? THEN 2
                        WHEN dogs.name LIKE ? THEN 3
                        ELSE 4
                    END
                ", [$reg_no, "$reg_no%", "%$reg_no"])
                ->get();




            if ($dogs->isNotEmpty()) {
                // Recorre cada perro en la colección $dogs y asigna un valor hash único basado en su dog_id
                $dogs->each(function ($dog) {
                    // Genera un hash MD5 de 32 caracteres utilizando el dog_id y lo asigna a la propiedad dog_hash del objeto dog
                    $dog->dog_hash = md5($dog->dog_id);
                    // Obtiene perfil del creador
                    $creatorProfile = $dog->creator->userprofile ?? null;

                    if ($creatorProfile) {
                        if (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 1) {
                            $dog->aliasDog = $creatorProfile->kennel_name . ' ' . $dog->name;
                        } elseif (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 0) {
                            $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;
                        } elseif (empty($creatorProfile->kennel_name) && !empty($creatorProfile->last_name)) {
                            $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;
                        } else {
                            $dog->aliasDog = $dog->name;
                        }
                    } else {
                        $dog->aliasDog = $dog->name; // Fallback si no hay perfil
                    }
                    
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
                // $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->where('sex','M')->whereIn('status', ['completed','exempt'])->get();

                // if ($dogs->isNotEmpty()) {
                //     $dogs->each(function ($dog) {
                //         $dog->dog_hash = md5($dog->dog_id);
                //     });

                //     $data['status'] = 200;
                //     $data['data'] = $dogs;
                // }

                //  Buscar por nombre (cuando no se encuentra por número de registro)
                $dogs = Dog::with(['creator.userprofile'])
                    ->where('sex', 'M')
                    ->whereIn('status', ['completed', 'exempt'])
                    ->where(function ($query) use ($reg_no) {
                        $reg_no = trim($reg_no);
                        $words = preg_split('/\s+/', $reg_no); // divide por espacios

                        // Buscar coincidencia directa en el nombre
                        $query->where('dogs.name', 'LIKE', "%$reg_no%")

                        // Buscar coincidencia en kennel_name o last_name
                        ->orWhereHas('creator.userprofile', function ($subquery) use ($reg_no) {
                            $subquery->where('kennel_name', 'LIKE', "%$reg_no%")
                                    ->orWhere('last_name', 'LIKE', "%$reg_no%");
                        })

                        // Buscar por cada palabra dentro de kennel_name + dog name
                        ->orWhere(function ($q) use ($words) {
                            foreach ($words as $word) {
                                $q->orWhereRaw("
                                    EXISTS (
                                        SELECT 1
                                        FROM user_profiles up
                                        WHERE up.profile_id = dogs.created_by_user_id
                                        AND CONCAT(
                                            COALESCE(up.kennel_name, ''), ' ',
                                            COALESCE(dogs.name, '')
                                        ) LIKE ?
                                    )
                                ", ["%{$word}%"]);
                            }
                        });
                    })
                    ->orderByRaw("
                        CASE 
                            WHEN dogs.name = ? THEN 1
                            WHEN dogs.name LIKE ? THEN 2
                            WHEN dogs.name LIKE ? THEN 3
                            ELSE 4
                        END
                    ", [$reg_no, "$reg_no%", "%$reg_no"])
                    ->get();

                // 🔹 Si encontró perros, procesar resultados
                if ($dogs->isNotEmpty()) {
                    $dogs->each(function ($dog) {
                        // Genera hash MD5 basado en dog_id
                        $dog->dog_hash = md5($dog->dog_id);

                        // Carga perfil del creador
                        $creatorProfile = $dog->creator->userprofile ?? null;

                        if ($creatorProfile) {
                            if (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 1) {
                                $dog->aliasDog = $creatorProfile->kennel_name . ' ' . $dog->name;
                            } elseif (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 0) {
                                $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;
                            } elseif (empty($creatorProfile->kennel_name) && !empty($creatorProfile->last_name)) {
                                $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;
                            } else {
                                $dog->aliasDog = $dog->name;
                            }
                        } else {
                            $dog->aliasDog = $dog->name; // fallback sin perfil
                        }
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
            // $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->whereIn('status', ['completed','exempt'])->get();

            // if ($dogs->isNotEmpty()) {
            //     $dogs->each(function ($dog) {
            //         $dog->dog_hash = md5($dog->dog_id);
            //     });

            //     $data['status'] = 200;
            //     $data['data'] = $dogs;
            // }

            $dogs = Dog::with(['creator.userprofile'])
                ->whereIn('status', ['completed', 'exempt'])
                ->where(function ($query) use ($reg_no) {

                    $reg_no = trim($reg_no);
                    $words = preg_split('/\s+/', $reg_no);

                    // Coincidencia directa por nombre
                    $query->where('dogs.name', 'LIKE', "%$reg_no%")

                    // Coincidencia con kennel_name o last_name
                    ->orWhereHas('creator.userprofile', function ($subquery) use ($reg_no) {
                        $subquery->where('kennel_name', 'LIKE', "%$reg_no%")
                                ->orWhere('last_name', 'LIKE', "%$reg_no%");
                    })

                    // Buscar por cada palabra en CONCAT(kennel_name + name)
                    ->orWhere(function ($q) use ($words) {
                        foreach ($words as $word) {
                            $q->orWhereRaw("
                                EXISTS (
                                    SELECT 1
                                    FROM user_profiles up
                                    WHERE up.profile_id = dogs.created_by_user_id
                                    AND CONCAT(
                                        COALESCE(up.kennel_name, ''), ' ',
                                        COALESCE(dogs.name, '')
                                    ) LIKE ?
                                )
                            ", ["%{$word}%"]);
                        }
                    });

                })
                ->orderByRaw("
                    CASE 
                        WHEN dogs.name = ? THEN 1        -- coincidencia exacta
                        WHEN dogs.name LIKE ? THEN 2     -- comienza igual
                        WHEN dogs.name LIKE ? THEN 3     -- contiene
                        ELSE 4
                    END
                ", [$reg_no, "$reg_no%", "%$reg_no"])
                ->get();


            // Si encontró perros – procesar resultados
            if ($dogs->isNotEmpty()) {

                $dogs->each(function ($dog) {

                    // Hash único
                    $dog->dog_hash = md5($dog->dog_id);

                    // Perfil del creador
                    $creatorProfile = $dog->creator->userprofile ?? null;

                    if ($creatorProfile) {
                        if (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 1) {
                            $dog->aliasDog = $creatorProfile->kennel_name . ' ' . $dog->name;

                        } elseif (!empty($creatorProfile->kennel_name) && $creatorProfile->kennel_name_status == 0) {
                            $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;

                        } elseif (empty($creatorProfile->kennel_name) && !empty($creatorProfile->last_name)) {
                            $dog->aliasDog = $creatorProfile->last_name . ' ' . $dog->name;

                        } else {
                            $dog->aliasDog = $dog->name;
                        }

                    } else {
                        $dog->aliasDog = $dog->name;
                    }
                });

                $data['status'] = 200;
                $data['data'] = $dogs;

            } else {

                $data['status'] = 404;
                $data['message'] = "Oops! No matches found for your search.";
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
            'message' => 'Failed to insert the dog. Please check the data and try again',
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

            $data['message'] = 'A dog with the name '.$dog->name.' already exists in the system';
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
                'breeder_id' => $ownerProfile->profile_id,
                'current_owner_id' => $owner,
                'created_by_user_id'=>$user->user_id,
                'status'=> in_array($role->name, $dogStatus ) ? 'exempt':'pending'
            ]);
            $dog->save();

            // Si viene desde una invitación
            if ($request->filled('pending_token')) {
                $pending = PendingDogRelation::where('token', $request->pending_token)->first();

                if ($pending) {
                    // Actualiza la relación del perro principal
                    Dog::where('dog_id', $pending->main_dog_id)->update([
                        $pending->relation_type . '_id' => $dog->dog_id,
                    ]);

                    // Marca la relación pendiente como completada
                    $pending->update([
                        'status' => 'completed',
                        'updated_at' => now(),
                    ]);
                }
            }

            DogPayment::create([
                'dog_id' => $dog->dog_id,
                'payment_id' => $payment->payment_id
            ]);


            if ($sire_id==null) {

                try {

                    $sireEmail = $validatedData['sire_email'];
                    $sireName = $validatedData['sire_name'];
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
                        'url' => '',
                        'dog' => $dog,
                        'description' => $descriptionSire
                    ];

                    $this->createPendingRelation($emailData,'sire',$sireEmail,$sireName);

                    //Log successful sending
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
    
            }

            if ($dam_id==null) {

                try {
                    
                    $damEmail = $validatedData['dam_email'];
                    $damName = $validatedData['dam_name'];
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
                        'url' =>'',
                        'dog' => $dog,
                        'description' => $descriptionDam
                    ];

                    $this->createPendingRelation($emailData,'dam',$damEmail,$damName);


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

            }

            $dog->dog_id_md = md5($dog->dog_id);
            $dog->rol = $role->name;
            $paymentProtected['rol'] = $role->name;
            
            $parent_type = $request->sex === 'M' ? 'sire' : 'dam';

            // Si viene desde una invitación
            if ($request->filled('pending_invoice')) {
                $parentRequest = DogParentRequest::where('token', $request->pending_invoice)->first();
                
                $userExists = UserProfile::where('email', $parentRequest->email)->first();
                if ($parentRequest) {
                    // Crear la solicitud de cruza automáticamente
                    BreedingRequest::create([
                        'female_dog_id' => $request->sex === 'F' ? $dog->dog_id : $parentRequest->dog_id,
                        'male_dog_id' => $request->sex === 'M' ? $dog->dog_id : $parentRequest->dog_id,
                        'requester_id'=>$profile->profile_id,
                        'owner_id'=>$userExists->profile_id,
                        'status' =>$role->name == 'Admin'? 'completed':'pending',
                    ]);
                    $parentRequest->delete();
                }
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

    private function createPendingRelation($emailData, $relationType, $email,$name)
    {
        try {

            $token = \Illuminate\Support\Str::random(40);

            PendingDogRelation::create([
                'main_dog_id'   => $emailData['dog']->dog_id,
                'relation_type' => $relationType,
                'pending_email' => $email,
                'temp_dog_name' => $name,
                'token'         => $token,
                'status'        => 'pending',
            ]);
            // Verificar si el correo ya tiene cuenta
            $userExists = UserProfile::where('email', $email)->exists();

            $url = $userExists
                ? url(config('app.url') . "?token={$token}") 
                : url(config('app.url') . "register?token={$token}");
            $emailData['url'] = $url;
            // Enviar correo
            Mail::to($email)->send(new SendEmailDogs($emailData));
            return true;
        } catch (\Exception $e) {
            Log::error("Error in createPendingRelation: " . $e->getMessage());
            return false;
        }
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

        // Todas las cruzas completadas (como macho o hembra)
        $completedBreedings = BreedingRequest::with(['photos', 'maleDog.creator.userprofile', 'femaleDog.creator.userprofile'])
            ->where(function ($q) use ($dog) {
                $q->where('male_dog_id', $dog->dog_id)
                ->orWhere('female_dog_id', $dog->dog_id);
            })
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc') // opcional, para mostrar primero las más recientes
            ->get();

        $pedigree = $this->findPedigree($dog);
        $dog = $pedigree['dog'];



        return view('dogs/show-dog',compact('dog','completedBreedings'));
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
