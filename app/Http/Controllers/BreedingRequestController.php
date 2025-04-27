<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Validations\BreedingRequestRule;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DogInvitationMail;
use Illuminate\Support\Str;

//model
use App\Models\Dog;
use App\Models\UserProfile;
use App\Models\DogParentRequest;
use App\Models\BreedingRequest;
//traits
use App\Traits\Pedigree;

class BreedingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $profile = $user->userprofile;

        $breedings = BreedingRequest::where('requester_id', $profile->profile_id)
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('breeding.list-breeding',compact('breedings'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $profile = $user->userprofile;

        $dogs = Dog::where('current_owner_id', $profile->profile_id)->where('status','completed')->where('sex','F')->get();


        return view('breeding.create-breeding',compact('dogs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $baseUrl = config('app.url');

        try {

            // Limpiar todos los campos antes de la validación
            $cleanedData = array_map('trim', $request->all());
            $request->merge($cleanedData); // Volver a fusionar los datos limpios 
            $validator = BreedingRequestRule::validate($request->all());
        
            $dataResponse = [
                'status' => 'error',
                'message' => 'El dueño del otro perro ya tiene cuenta. Se notificará para que registre su mascota.',
            ];

            if ($validator->fails()) {

                $dataRespons['errors'] = $validator->errors();
                return response()->json($dataRespons, 422);
            }
            $validatedData = $validator->validated();
           
            $myDog = Dog::where('dog_id', $validatedData['my_dog_id'])->first();
            $myOwner = auth()->user()->userprofile;
            $isMyDogMale = $myDog->sex === 'M';
            $otherType = $isMyDogMale ? 'dam' : 'sire';
            $token = Str::uuid();

            if ($myDog) {
                
                if($validatedData['dog_id']=='' || $validatedData['dog_id']==null){
                    $otherOwner = UserProfile::where('email', $validatedData['dog_email'])->first();
                    if ($otherOwner) {
    
                        $data = [
                            'dogName'=>$myDog->name,
                            'message'=>$validatedData['dogDetails'],
                            'subject'=>'You have a new breeding request!',
                            'view'=>'dog_invitation_esc_three',
                            'url'=>$baseUrl,
                        ];
        
                        $this->sendEmail($data,$validatedData);
                        $parentRequest = $this->createParentRequest($myDog,$otherType,$validatedData,$token);
                        if ($parentRequest) {
                            $dataResponse['status'] = 'success';
                            $dataResponse['message'] = 'Your breeding request has been successfully submitted. We have notified the owner of the other dog to continue the process.';

                            return response()->json($dataResponse,200);
                        }
                        
                    }
    
                    $data = [
                        'dogName'=>$myDog->name,
                        'message'=>$validatedData['dogDetails'],
                        'subject'=>'You have a new breeding request!',
                        'view'=>'dog_invitation_esc_one',
                        'url'=>$baseUrl,
                    ];
    
                    $this->sendEmail($data,$validatedData);
                    $parentRequest = $this->createParentRequest($myDog,$otherType,$validatedData,$token);
                    if ($parentRequest) {
                        $dataResponse['status'] = 'success';
                        $dataResponse['message'] = 'Your breeding request has been successfully submitted. We have notified the owner of the other dog to continue the process.';

                        return response()->json($dataResponse,200);
                    }
                }
                
                $otherDog = Dog::where('dog_id',$validatedData['dog_id'])->first();
                $userProfile = $otherDog->breeder;
                $owner = $otherDog->currentOwner;

                $breedingRequest = BreedingRequest::create([
                    'female_dog_id' => $myDog->sex === 'F' ? $myDog->dog_id : $otherDog->dog_id,
                    'male_dog_id' => $myDog->sex === 'M' ? $myDog->dog_id : $otherDog->dog_id,
                    'requester_id'=>$userProfile->profile_id,
                    'owner_id'=> $owner->profile_id,
                    'status' => 'pending',
                ]);

                $dataResponse['status'] = 'success';
                $dataResponse['message'] = 'Crossbreeding request registered successfully';
                return response()->json($dataResponse,200);
            }



            





            // $myDog = Dog::findOrFail($request->my_dog_id);
            // $myOwner = auth()->user()->userprofile;
            
            // $isMyDogMale = $myDog->sex === 'M';
            // $otherType = $isMyDogMale ? 'dam' : 'sire';
            // $token = Str::uuid();

            // // Verificamos si el otro dueño ya tiene cuenta
            // $otherOwner = UserProfile::where('email', $request->other_owner_email)->first();
            // $otherDog = null;

            // if ($otherOwner) {
            //     // Buscamos el perro del otro dueño por nombre, si lo proporciona
            //     $otherDog = Dog::where('current_owner_id', $otherOwner->profile_id)
            //     ->where(function ($query) use ($request) {
            //         $query->where('name', 'LIKE', '%' . $request->other_dog_name . '%')
            //               ->orWhere('reg_no', 'LIKE', '%' . $request->other_dog_name . '%');
            //     })
            //     ->first();
            

            // }

            // // Validamos que no sean del mismo sexo
            // if ($otherDog && $otherDog->sex === $myDog->sex) {
                
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'No se puede realizar una cruza entre perros del mismo sexo.',
            //         'data'=>[],
            //         'errors' => null,
            //     ], 422);
            // }

            // if ($otherOwner && $otherDog) {
            //     //Escenario 1: Ambos dueños tienen cuenta y ambos perros están registrados
            //     BreedingRequest::create([
            //         'female_dog_id' => $myDog->sex === 'F' ? $myDog->dog_id : $otherDog->dog_id,
            //         'male_dog_id' => $myDog->sex === 'M' ? $myDog->dog_id : $otherDog->dog_id,
            //         'requester_id'=>$otherOwner->profile_id,
            //         'owner_id'=> $otherOwner->profile_id,
            //         'status' => 'pending',
            //     ]);

            //     $data = [
            //         'dogName'=>$myDog->name,
            //         'other_dog_name'=>$otherDog->name,
            //         'otherType'=>$otherType,
            //         'token'=>$token,
            //         'owner'=>$myOwner->name.' '.$myOwner->lastName,
            //         'subject'=>'Has recibido una solicitud de cruza para tu perro',
            //         'view'=>'dog_invitation_esc_one',
            //         'url'=>$baseUrl,
            //     ];

            //     $this->sendEmail($data,$validatedData);

            //     // enviar el correo de invitacion para la otra persona 
            //     return response()->json([
            //         'status' => 'success',
            //         'message' => 'Solicitud de cruza enviada correctamente.',
            //         'data'=>[],
            //         'errors' => null,
            //     ],200);
            // }

            // if ($otherOwner && !$otherDog) {
            //     // Escenario 2: El dueño ya tiene cuenta pero no ha registrado (o mal escrito) su perro
            //     DogParentRequest::firstOrCreate([
            //         'dog_id' => $myDog->dog_id,
            //         'parent_type' => $otherType,
            //         'email' => $request->other_owner_email,
            //         'token' => $token,
            //     ]);

            //     $data = [
            //         'dogName'=>$myDog->name,
            //         'other_dog_name'=>$request->other_dog_name,
            //         'otherType'=>$otherType,
            //         'token'=>$token,
            //         'owner'=>$myOwner->name.' '.$myOwner->lastName,
            //         'subject'=>'Alguien quiere cruzar su perro con el tuyo — Regístralo para continuar',
            //         'view'=>'dog_invitation_esc_two',
            //         'url'=>$baseUrl,
            //     ];

            //     $this->sendEmail($data,$validatedData);
            //     // enviar el correo de invitacion que registre su perro ya alguien le solicita cruza
            //     return response()->json([
            //         'status' => 'success',
            //         'message' => 'El dueño del otro perro ya tiene cuenta. Se notificará para que registre su mascota.',
            //         'data'=>[],
            //         'errors' => null,
            //     ],200);
            // }

            // // Crear solicitud de registro del otro perro si no existe aún
            // DogParentRequest::create([
            //     'dog_id' => $myDog->dog_id,
            //     'parent_type' => $otherType,
            //     'email' => $request->other_owner_email,
            //     'token' => $token,
            // ]);

            // $data = [
            //     'dogName'=>$myDog->name,
            //     'other_dog_name'=>$request->other_dog_name,
            //     'otherType'=>$otherType,
            //     'token'=>$token,
            //     'owner'=>$myOwner->name.' '.$myOwner->lastName,
            //     'subject'=>'Invitación para cruzar tu perro — Regístralo en nuestra plataforma',
            //     'view'=>'dog_invitation_esc_three',
            //     'url'=>$baseUrl.'register',
            // ];

            // $this->sendEmail($data,$validatedData);


            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Solicitud enviada. El dueño del otro perro debe registrar su mascota para continuar.',
            //     'data'=>[],
            //     'errors' => null,
            // ],200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error. Please try again later.',
                'data' => [],
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function sendEmail($data,$validatedData){

        $array = [
                'from'=>'canine@aquacaribbeantravel.com',
                'subject' => $data['subject'],
                'view'=>$data['view'],
                'data'=>$data,
        ];

        Mail::to($validatedData['dog_email'])->send(new DogInvitationMail($array));
    }


    public function createParentRequest($myDog,$otherType,$validatedData,$token)
    {
        $dogParentRequest = DogParentRequest::create([
            'dog_id' => $myDog->dog_id,
            'parent_type' => $otherType,
            'email' => $validatedData['dog_email'],
            'token' => $token,
        ]);

        return $dogParentRequest;
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
