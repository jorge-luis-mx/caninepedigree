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

use App\Models\BreedingPhoto;

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

        $breedings = BreedingRequest::whereHas('maleDog', function($query) use ($profile) {
            $query->where('owner_id', $profile->profile_id);
        })
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->with(['femaleDog', 'maleDog']) // Carga la perra y el perro
        ->get();

        return view('breeding.list-breeding', compact('breedings'));
        

    }

    public function complete($id)
    {
        $breeding = BreedingRequest::findOrFail($id);

        if(auth()->user()->userprofile->profile_id == $breeding->maleDog->current_owner_id ){

            $breeding->status = 'completed';
            $breeding->save();

            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'No autorizado']);
 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        
        $user = auth()->user();
        $profile = $user->userprofile;
        $role = $user->role;

        $owner = $role->name == 'Admin'? 2 : $profile->profile_id;
        
        if($id){

            $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])->where('status','completed')->where('sex','F')->firstOrFail();

            return view('breeding.create-breeding',compact('dog'));
        }

        $dogs = Dog::where('current_owner_id', $owner)->where('status','completed')->where('sex','F')->get();


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

                // Identificar cuál es la hembra
                $femaleDogId = $myDog->sex === 'F' ? $myDog->dog_id : $otherDog->dog_id;

                // Buscar la última solicitud de cruza (pendiente o completada) para esta perra
                $lastBreeding = BreedingRequest::where('female_dog_id', $femaleDogId)
                    ->whereIn('status', ['pending', 'completed'])
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($lastBreeding) {
                    // Si está pendiente, denegar nueva solicitud
                    if ($lastBreeding->status === 'pending') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'This female already has a pending breeding request.',
                        ], 403);
                    }

                    // Si está completada, verificar si han pasado al menos 12 meses
                    $monthsSinceLast = $lastBreeding->created_at->diffInMonths(now());
                    if ($monthsSinceLast < 12) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'This female was recently bred. At least 12 months must pass between breedings.',
                        ], 403);
                    }
                }


                $breedingRequest = BreedingRequest::create([
                    'female_dog_id' => $myDog->sex === 'F' ? $myDog->dog_id : $otherDog->dog_id,
                    'male_dog_id' => $myDog->sex === 'M' ? $myDog->dog_id : $otherDog->dog_id,
                    'requester_id'=>$myOwner->profile_id, //$userProfile->profile_id,
                    'owner_id'=> $owner->profile_id,
                    'status' => 'pending',
                ]);

                $dataResponse['status'] = 'success';
                $dataResponse['message'] = 'Crossbreeding request registered successfully';
                return response()->json($dataResponse,200);
            }
            
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


    // Mostrar la lista de cruzas completadas
    public function listCompleted()
    {
        $user = auth()->user();
        $profile = $user->userprofile;

        $breedings = BreedingRequest::whereHas('maleDog', function($query) use ($profile) {
                $query->where('requester_id', $profile->profile_id);
            })
            ->where('status', 'completed')
            ->with(['femaleDog', 'maleDog'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('breeding.completed', compact('breedings'));
    }

    // Mostrar formulario para subir fotos de una cruza
    public function uploadPhotos($breedingId)
    {
        $breeding = BreedingRequest::findOrFail($breedingId);
        return view('breeding.upload-photos', compact('breeding'));
    }

    // Guardar las fotos
    public function storePhotos(Request $request, $breedingId)
    {
        
        $breeding = BreedingRequest::findOrFail($breedingId);

        // Validar que se envíen fotos (array) y cada una sea imagen < 2MB
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|max:2048',
        ]);

        // Verificar si ya existe foto principal para esta cruza
        $hasMain = BreedingPhoto::where('breeding_request_id', $breedingId)
            ->where('is_main', 1)
            ->exists();

        foreach ($request->file('photos') as $index => $photo) {
            $path = $photo->store('breeding_photos', 'public');

            BreedingPhoto::create([
                'breeding_request_id' => $breedingId,
                'photo_url' => $path,
                // Si no hay principal y esta es la PRIMERA foto (index=0), marcar como principal
                'is_main' => (!$hasMain && $index === 0) ? 1 : 0,
            ]);
        }

        return redirect()->route('breeding.listCompleted')->with('success', 'Fotos subidas correctamente.');
    }

    public function listSent(){

        $send_request = BreedingRequest::with(['femaleDog', 'maleDog'])->where('status', 'pending')->get();
        return view('breeding.list-sent-request', compact('send_request'));
    }

}
