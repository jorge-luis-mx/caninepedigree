<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreedingRequest;
use App\Validations\PuppiesValidations;

use App\Models\Dog;
use App\Models\Payment;
use App\Models\DogPayment;
use App\Models\UserProfile;
use App\Models\Litter;
use Illuminate\Support\Facades\DB;

class PuppyController extends Controller
{

    public function index($id)
    {
        $user = auth()->user();
        $profile = $user->userprofile;
   
       
        $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])
            ->whereIn('status', ['completed', 'exempt'])
            ->where('sex', 'F')
            ->firstOrFail();

        return view('puppies.create',compact('dog'));

    }

    public function register()
    {

        return view('puppies.register');  // Aquí se pasa directamente la vista del formulario
    }

    public function showRegisterForm($breedingId)
    {
        $breeding = BreedingRequest::findOrFail($breedingId);

        if ($breeding->maleDog->current_owner_id !== auth()->user()->userprofile->profile_id) {
            abort(403, 'No autorizado');
        }

        return view('puppies.register', compact('breeding'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = $user->userprofile;


        $role = $user->role;
        $permissions = $role->permissions; 
        
        $arrayRole =['Employee','Admin'];
        
        $ownerProfile = UserProfile::find(2);
        $owner = in_array($role->name, $arrayRole) ? $ownerProfile->profile_id : $profile->profile_id;

        // Limpiar todos los campos (incluso los anidados dentro de 'puppies')
        $data = $request->all();

        // Función recursiva para aplicar trim incluso en arrays anidados
        $cleanedData = array_map_recursive(function ($value) {
            return is_string($value) ? trim($value) : $value;
        }, $data);

        // Fusionar los datos limpios al request
        $request->merge($cleanedData);

        // Validar usando la clase centralizada
        $validator = PuppiesValidations::validate($request->all());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        $regnum = generarCodigoRegistro();

        try {

            $dogStatus = ['Admin','Administrator','Employee'];
            // Obtener solo los datos validados
            $validated = $validator->validated();
            $orderReference = $this->getOrderReference();
            $total = $validated['totalPuppies'] * 100;

            $payment = Payment::create([
                'user_id' => $owner, 
                'order_reference'=>$orderReference,
                'amount' => $total, 
                'type' => 'registration', 
                'status' => 'pending', 
                'payment_method' => 'paypal' 
            ]);

            $paymentProtected = $payment->toArray();
            $paymentProtected['id_hash'] = md5($payment->payment_id);
            $paymentProtected['rol'] = $role->name;

            $dam_id = $validated['dam_id'];
            $sire_id = $validated['dog_id'];

            //Buscar camada pendiente existente
            $litter = Litter::where('dam_id', $dam_id)
                                ->where('sire_id', $sire_id)
                                ->whereNull('birth_date')
                                ->first();

            //Si no existe, crear nueva camada
            if (!$litter) {
                
                // Opcional: calcular el número de camada
                $lastLitter = Litter::where('dam_id', $validated['dam_id'])
                                    ->orderBy('litter_number', 'desc')
                                    ->first();
              
                $litterNumber = $lastLitter ? $lastLitter->litter_number + 1 : 1;

                $litter = Litter::create([
                    'breeding_request_id' => null, // si quieres vincular a solicitud, pasa $request_id
                    'dam_id'              => $dam_id,
                    'sire_id'             => $sire_id,
                    'birth_date'          => $validated['puppies'][0]['birthdate'],
                    'total_puppies'       => count($validated['puppies']),
                    'surviving_puppies'   => count($validated['puppies']),
                    'litter_number'       => $litterNumber,
                    'created_at'          => now(),
                ]);
                
            }

            foreach ($validated['puppies'] as $puppy) {

                $dog = Dog::create([
                    'reg_no'    =>$regnum,
                    'name'      => $puppy['name'],
                    'breed'      => 'Pit Bull Terrier',
                    'color'     => $puppy['color'],
                    'sex'       => $puppy['sex'],
                    'birthdate' => $puppy['birthdate'],
                    'sire_id'   => $sire_id, 
                    'dam_id'    => $dam_id, 
                    'breeder_id'=>$owner,
                    'current_owner_id'=>$owner,
                    'created_by_user_id'=>$profile->profile_id,
                    'is_puppy'    => 1, 
                    'status'=> in_array($role->name, $dogStatus ) ? 'exempt':'pending'
                ]);

                DogPayment::create([
                    'dog_id' => $dog->dog_id,
                    'payment_id' => $payment->payment_id
                ]);
            }

            DB::commit();
            $data = [
                'status' => 200,
                'message' => 'Cachorros registrados exitosamente',
                'data'=>$paymentProtected,
                'errors' => null,
            ];
            
            return response()->json($data);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar cachorros.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function getOrderReference(){

        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $orderReference = '';

        for ($i = 0; $i < 12; $i++) {
            $orderReference .= $cadena[random_int(0, strlen($cadena) - 1)];
        }

        return $orderReference;
    }

    public function create()
    {
        // return view('puppies.create');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
