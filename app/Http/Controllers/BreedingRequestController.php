<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Validations\BreedingRequestRule;
use Carbon\Carbon;


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
use App\Models\Litter;


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
            $query->where('requester_id', $profile->profile_id);
        })
        ->where('status', 'approved')
        ->orderBy('created_at', 'desc')
        ->with(['femaleDog', 'maleDog']) // Carga la perra y el perro
        ->get();

        return view('breeding.list-breeding', compact('breedings'));
        
    }
    public function receibed()
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

    
        return view('breeding.receibed', compact('breedings'));
        
    }

    public function confirm(Request $request)
    {

        $post = $request->all();

        BreedingRequest::where('request_id', $post['request_id'])
            ->update(['status' => $post['status']]);

        // Mensajes según el estado
        $message = match ($post['status']) {
            'approved' => 'The request has been approved successfully.',
            'rejected' => 'The request has been rejected successfully.',
            default => 'Status updated successfully.'
        };

        return response()->json(['success' => true,'message'=>$message]);
        
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

            $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])
            ->whereIn('status', ['completed', 'exempt'])
            ->where('sex', 'F')
            ->firstOrFail();

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

            // Buscar solicitudes de cría activas para la hembra
            $hasActiveRequests = BreedingRequest::where('female_dog_id', $validatedData['my_dog_id'])
                ->whereIn('status', ['pending', 'approved']) // Considerar también 'approved' como activo
                ->exists();
            
            if ($hasActiveRequests) {

                return response()->json([
                    'status' => 'error',
                    'message' => 'This female dog already has an active breeding request.',
                ], 403);
            }

            // Verificar si es la primera cría
            $isFirstBreeding = BreedingRequest::where('female_dog_id', $validatedData['my_dog_id'])
                ->whereIn('status', ['completed', 'approved']) // Solo contar las que realmente sucedieron
                ->doesntExist();

            if ($isFirstBreeding) {
                // Lógica para primera cría
                return $this->createBreedingRequest($validatedData);
            }

            // Para crías subsiguientes, verificar restricciones
            $dog = Dog::select('breeding_approved', 'next_available_breeding')
                ->where('dog_id', $validatedData['my_dog_id']) 
                ->first();

            if (!$dog) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The specified dog does not exist.',
                ], 404);
            }

            // Verificar aprobación para cría
            if (!$dog->breeding_approved) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Breeding is not approved for this dog.',
                ], 403);
            }

            // Verificar disponibilidad por fecha
            // if ($dog->next_available_breeding) {
            //     $nextAvailableDate = \Carbon\Carbon::parse($dog->next_available_breeding);
            //     $today = \Carbon\Carbon::today(); // Usar today() en lugar de now() para comparar solo fechas

            //     if ($today->lessThan($nextAvailableDate)) {
            //         return response()->json([
            //             'status' => 'error',
            //             'message' => 'This dog is not yet available for breeding. Next available date: ' . $nextAvailableDate->toDateString(),
            //         ], 403);
            //     }
            // }

            // Todas las validaciones pasaron - crear la solicitud
            return $this->createBreedingRequest($validatedData);

            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error. Please try again later.',
                'data' => [],
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function complete(Request $request)
    {

        $post = $request->all();
        $mating = Carbon::now('America/Cancun');
        $expectedBirth = $mating->copy()->addDays(63);

        BreedingRequest::where('request_id', $post['request_id'])
            ->update(['status' => $post['status'],'miting_date'=>$mating->toDateString(),'delivery_date'=>$expectedBirth->toDateString()]);

        // Mensajes según el estado
        $message = match ($post['status']) {
            'completed' => 'The request has been completed successfully.',
            'cancelled' => 'The request has been cancelled successfully.',
            default => 'Status updated successfully.'
        };

        return response()->json(['success' => true,'message'=>$message]);

 
    }

    private function createBreedingRequest($validatedData)
    {

        $baseUrl = config('app.url');

        try {
 
            $user = auth()->user();
            $profile = $user->userprofile;

            $femaleDog = Dog::where('dog_id', $validatedData['my_dog_id'])->first();
            $sexDog = $femaleDog->sex === 'M'? 'dam':'sire';
            //get token uuid
            $token = Str::uuid();

            if(!empty($femaleDog) ){
                
                if (empty($validatedData['dog_id'])) {
               
                    $this->sendEmail($validatedData,$femaleDog,$token);
                    
                    $parentRequest = $this->createParentRequest($femaleDog,$sexDog,$validatedData,$token);

                    if ($parentRequest) {
                        $dataResponse['status'] = 'success';
                        $dataResponse['message'] = 'Your breeding request has been successfully submitted. We have notified the owner of the other dog to continue the process.';

                        return response()->json($dataResponse,200);
                    }

                }

                $maleDog = Dog::where('dog_id',$validatedData['dog_id'])->first();

                // Buscar la última solicitud de cruza (pendiente o completada) para esta perra
                $lastBreeding = BreedingRequest::where('female_dog_id', $femaleDog->dog_id)
                ->whereIn('status', ['pending', 'completed'])
                ->orderBy('created_at', 'desc')
                ->first();


                if (!empty($lastBreeding) && $lastBreeding->status === 'pending') {

                    return response()->json([
                        'status' => 'error',
                        'message' => 'This female already has a pending breeding request.',
                    ], 403);
                }

                if (!empty($lastBreeding)&& $lastBreeding->status === 'completed') {
                    
                    if ($lastBreeding->created_at->diffInMonths(now()) < 12) {

                        return response()->json([
                            'status' => 'error',
                            'message' => 'This female was recently bred. At least 12 months must pass between breedings.',
                        ], 403);

                    }
                }

            
                $breedingRequest = BreedingRequest::create([
                    'female_dog_id' =>$femaleDog->dog_id,
                    'male_dog_id' => $maleDog->dog_id,
                    'requester_id'=>$femaleDog->currentOwner->profile_id,
                    'owner_id'=>$maleDog->currentOwner->profile_id,
                    'status' => 'pending',
                ]);


                $dataResponse['status'] = 'success';
                $dataResponse['message'] = 'Crossbreeding request registered successfully';
                return response()->json($dataResponse,200);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create breeding request.',
            ], 500);
        }
    }



    public function sendEmail($validatedData,$dog,$token){

        // Configure email data
        $emailData = [
            'from' => config('mail.from.address', 'info@devscun.com'),
            'from_name' => config('mail.from.name', 'Canine'),
            'subject' => 'You have a new breeding request!',
            'url' =>null,
            'dog' => $dog,
            'description' => $validatedData['dogDetails']
        ];
        // Verificar si el correo ya tiene cuenta
        $userExists = UserProfile::where('email', $validatedData['dog_email'])->exists();


        $url = $userExists
            ? url(config('app.url') . "?invoice={$token}") 
            : url(config('app.url') . "register?invoice={$token}");
        $emailData['url'] = $url;
        // Enviar correo
        Mail::to($validatedData['dog_email'])->send(new DogInvitationMail($emailData));


    }


    public function createParentRequest($femaleDog,$sexDog,$validatedData,$token)
    {
        
        $dogParentRequest = DogParentRequest::create([
            'dog_id' => $femaleDog->dog_id,
            'parent_type' => $sexDog,
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
        $role = $user->role;

        $arrayRole =['Employee','Admin'];
        
       //Dueño que inicia la solicitud
        $requester_id = in_array($role->name, $arrayRole) ? 2 : $profile->profile_id;


        $breedings = BreedingRequest::whereHas('maleDog', function ($query) use ($requester_id) {
                $query->where('requester_id', $requester_id);
            })
            ->where('status', 'completed')
            ->whereDoesntHave('photos') // 🔹 Solo los que NO tienen fotos
            ->with(['femaleDog', 'maleDog'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->hash_id = md5($item->request_id);
                return $item;
            });
        

        return view('breeding.completed', compact('breedings'));
    }

    // Mostrar formulario para subir fotos de una cruza
    public function uploadPhotos($breedingId)
    {
        
        $breeding = BreedingRequest::whereRaw('MD5(request_id) = ?', [$breedingId])
        ->with(['femaleDog', 'maleDog'])
        ->firstOrFail();


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

        $user = auth()->user();
        $profile = $user->userprofile;

        $send_request = BreedingRequest::where('requester_id', $profile->profile_id)
            ->orderBy('created_at', 'desc')
            ->with(['maleDog', 'femaleDog', 'owner'])
            ->get();


        // $send_request = BreedingRequest::with(['femaleDog', 'maleDog'])->get();
        return view('breeding.list-sent-request', compact('send_request'));
    }

}
