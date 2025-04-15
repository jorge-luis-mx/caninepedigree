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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dogs = Dog::where('status','completed')->get();

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
        
            $data = [
                'status' => 'success',
                'message' => 'Failed to insert the breeding. Please check the data and try again',
                'data'=>[],
                'errors' => null,
            ];
            
            if ($validator->fails()) {

                $data['errors'] = $validator->errors();
                return response()->json($data, 422);
            }
            $validatedData = $validator->validated();


            $myDog = Dog::findOrFail($request->my_dog_id);
            $myOwner = auth()->user()->userprofile;
            $isMyDogFemale = $myDog->sex === 'M';

            $otherType = $isMyDogFemale ? 'sire' : 'dam';
            $token = Str::uuid();

            // Verificamos si el otro dueño ya tiene cuenta
            $otherOwner = UserProfile::where('email', $request->other_owner_email)->first();
            $otherDog = null;

            if ($otherOwner) {
                // Buscamos el perro del otro dueño por nombre, si lo proporciona
                $otherDog = Dog::where('current_owner_id', $otherOwner->profile_id)
                            ->where('name', 'LIKE', '%' . $request->other_dog_name . '%')
                            ->first();
            }

            // Validamos que no sean del mismo sexo
            if ($otherDog && $otherDog->sex === $myDog->sex) {
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se puede realizar una cruza entre perros del mismo sexo.',
                    'data'=>[],
                    'errors' => null,
                ], 422);
            }

            if ($otherOwner && $otherDog) {
                //Escenario 1: Ambos dueños tienen cuenta y ambos perros están registrados
                BreedingRequest::create([
                    'female_dog_id' => $myDog->sex === 'F' ? $myDog->dog_id : $otherDog->dog_id,
                    'male_dog_id' => $myDog->sex === 'M' ? $myDog->dog_id : $otherDog->dog_id,
                    'requester_id'=>$otherOwner->profile_id,
                    'owner_id'=> $otherOwner->profile_id,
                    'status' => 'pending',
                ]);

                $data = [
                    'dogName'=>$myDog->name,
                    'other_dog_name'=>$request->other_dog_name,
                    'otherType'=>$otherType,
                    'token'=>$token,
                    'owner'=>$myOwner->name.' '.$myOwner->lastName,
                    'subject'=>'Has recibido una solicitud de cruza para tu perro',
                    'view'=>'dog_invitation_esc_one',
                    'url'=>$baseUrl,
                ];

                $this->sendEmail($data,$validatedData);

                // enviar el correo de invitacion para la otra persona 
                return response()->json([
                    'status' => 'success',
                    'message' => 'Solicitud de cruza enviada correctamente.',
                    'data'=>[],
                    'errors' => null,
                ],200);
            }

            if ($otherOwner && !$otherDog) {
                // Escenario 2: El dueño ya tiene cuenta pero no ha registrado (o mal escrito) su perro
                DogParentRequest::firstOrCreate([
                    'dog_id' => $myDog->dog_id,
                    'parent_type' => $otherType,
                    'email' => $request->other_owner_email,
                    'token' => $token,
                ]);

                $data = [
                    'dogName'=>$myDog->name,
                    'other_dog_name'=>$request->other_dog_name,
                    'otherType'=>$otherType,
                    'token'=>$token,
                    'owner'=>$myOwner->name.' '.$myOwner->lastName,
                    'subject'=>'Alguien quiere cruzar su perro con el tuyo — Regístralo para continuar',
                    'view'=>'dog_invitation_esc_two',
                    'url'=>$baseUrl,
                ];

                // $this->sendEmail($data,$validatedData);
                // enviar el correo de invitacion que registre su perro ya alguien le solicita cruza
                return response()->json([
                    'status' => 'success',
                    'message' => 'El dueño del otro perro ya tiene cuenta. Se notificará para que registre su mascota.',
                    'data'=>[],
                    'errors' => null,
                ],200);
            }

            // Crear solicitud de registro del otro perro si no existe aún
            DogParentRequest::create([
                'dog_id' => $myDog->dog_id,
                'parent_type' => $otherType,
                'email' => $request->other_owner_email,
                'token' => $token,
            ]);

            $data = [
                'dogName'=>$myDog->name,
                'other_dog_name'=>$request->other_dog_name,
                'otherType'=>$otherType,
                'token'=>$token,
                'owner'=>$myOwner->name.' '.$myOwner->lastName,
                'subject'=>'Invitación para cruzar tu perro — Regístralo en nuestra plataforma',
                'view'=>'dog_invitation_esc_three',
                'url'=>$baseUrl.'register',
            ];

            $this->sendEmail($data,$validatedData);

            //send mails
            // $data = [
            //     'from'=>'jorge06g92@gmail.com',
            //     'subject' => '',
            //     'url'=>'http://www.caninepedigree-dev.com/register',
            //     'data'=>[
            //         'dogName'=>$myDog->name,
            //         'other_dog_name'=>$request->other_dog_name,
            //         'otherType'=>$otherType,
            //         'token'=>$token,
            //         'owner'=>$myOwner->name.' '.$myOwner->lastName
            //     ]
            // ];

            // Mail::to($request->other_owner_email)->send(new DogInvitationMail($data));

            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud enviada. El dueño del otro perro debe registrar su mascota para continuar.',
                'data'=>[],
                'errors' => null,
            ],200);
            
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
                'from'=>'jorge06g92@gmail.com',
                'subject' => $data['subject'],
                'view'=>$data['view'],
                'data'=>[
                    'dogName'=>$data['dogName'],
                    'other_dog_name'=>$data['other_dog_name'],
                    'otherType'=>$data['otherType'],
                    'token'=>$data['token'],
                    'owner'=>$data['owner'],
                    'url'=>$data['url'],
                ],
        ];

        Mail::to($validatedData['other_owner_email'])->send(new DogInvitationMail($array));
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
