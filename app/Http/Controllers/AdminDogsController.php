<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\ValidationsAdminDogs;
use Illuminate\Support\Facades\DB;

//model
use App\Models\Dog;
use App\Models\Payment;
use App\Models\DogPayment;
use App\Models\UserProfile;
use App\Models\DogParentRequest;
use App\Models\BreedingRequest;

use Carbon\Carbon;

//Traits
use App\Traits\Pedigree;
class AdminDogsController extends Controller
{
    use Pedigree;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

       return view('adminDogs/create',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pedigree()
    {
        $user = auth()->user();
        $role = $user->role;

        $arrayRole =['Admin','Administrator','Employee'];
        if(in_array($role->name, $arrayRole) ){

            return view('pedigree/index-pedigree');
        }else{
            return redirect('/dogs');
        }
        
    }

    public function storePedigree(Request $request){

        $generations = $request->input('generations');
        $registeredDogs = [];

        DB::beginTransaction();

        try {
            foreach ($generations as $generationNumber => $dogs) {
                foreach ($dogs as $role => $dogData) {
                    $dogName = trim($dogData['name'] ?? '');
                    $sex = $dogData['sex'] ?? null;
                    $color = $dogData['color'] ?? null;

                    if (!$dogName) {
                        continue; // O lanzar una excepción si lo deseas
                    }

                    $regnum = $this->generarCodigoRegistro();
                    $orderReference = $this->getOrderReference();

                    // Verificar si ya existe (puedes ampliar lógica si deseas considerar sex y color también)
                    //$dog = Dog::where('name', $dogName)->first();
                    $dog = Dog::whereRaw('LOWER(name) = ?', [strtolower($dogName)])->first();

                    if (!$dog) {
                        $dog = Dog::create([
                            'reg_no' => $regnum,
                            'name' => $dogName,
                            'breed' => 'Pit Bull Terrier', // Si es fijo
                            'color' => $color,
                            'sex' => $sex,
                            'birthdate'=>'2006-06-12',
                            'sire_id'=>null,
                            'dam_id'=>null,
                            'breeder_id'=>1,
                            'current_owner_id'=>1,
                            'status'=>'completed',
                            'is_puppy'=>0,
                            'breeding_request_id'=>null
                        ]);
                    }

                    // Guardar agrupado por generación y rol
                    $registeredDogs[$generationNumber][$role] = $dog->dog_id;
                }
            }


            foreach ($registeredDogs as $key => $itemDog) {
                
                
                foreach ($itemDog as $roleKey => $value) {
                    
                    switch ($roleKey) {
                        
                        case 'dogOne':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[2]['father'],
                                'dam_id' => $registeredDogs[2]['mother'],
                            ]);
                            break;

                        case 'father':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[3]['fatherFather'],
                                'dam_id' => $registeredDogs[3]['fatherMother'],
                            ]);
                            break;

                        case 'mother':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[3]['motherFather'],
                                'dam_id' => $registeredDogs[3]['motherMother'],
                            ]);
                            break;

                        //3
                        case 'fatherFather':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[4]['bisabuelo1'],
                                'dam_id' => $registeredDogs[4]['bisabuela1'],
                            ]);
                            break;

                        case 'fatherMother':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[4]['bisabuelo2'],
                                'dam_id' => $registeredDogs[4]['bisabuela2'],
                            ]);
                            break;

                        case 'motherFather':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[4]['bisabuelo3'],
                                'dam_id' => $registeredDogs[4]['bisabuela3'],
                            ]);
                            break;

                        case 'motherMother':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[4]['bisabuelo4'],
                                'dam_id' => $registeredDogs[4]['bisabuela4'],
                            ]);
                            break;
                        //4

                        case 'bisabuelo1':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo1'],
                                'dam_id' => $registeredDogs[5]['tatarabuela1'],
                            ]);
                            break;

                        case 'bisabuela1':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo2'],
                                'dam_id' => $registeredDogs[5]['tatarabuela2'],
                            ]);
                            break;

                        case 'bisabuelo2':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo3'],
                                'dam_id' => $registeredDogs[5]['tatarabuela3'],
                            ]);
                            break;

                        case 'bisabuela2':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo4'],
                                'dam_id' => $registeredDogs[5]['tatarabuela4'],
                            ]);
                            break;
                        //
                        case 'bisabuelo3':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo5'],
                                'dam_id' => $registeredDogs[5]['tatarabuela5'],
                            ]);
                            break;

                        case 'bisabuela3':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo6'],
                                'dam_id' => $registeredDogs[5]['tatarabuela6'],
                            ]);
                            break;

                        case 'bisabuelo4':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo7'],
                                'dam_id' => $registeredDogs[5]['tatarabuela7'],
                            ]);
                            break;

                        case 'bisabuela4':
                            Dog::where('dog_id', $value)->update([
                                'sire_id' => $registeredDogs[5]['tatarabuelo8'],
                                'dam_id' => $registeredDogs[5]['tatarabuela8'],
                            ]);
                            break;

                        default:

                            break;
                    }
                }

            }

            DB::commit();

            return response()->json([
                'message' => 'Perros procesados correctamente.',
                'dog_ids' => $registeredDogs
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al procesar perros.',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Limpiar todos los campos antes de la validación
        $cleanedData = array_map('trim', $request->all());
        $request->merge($cleanedData); // Volver a fusionar los datos limpios 
        $validator = ValidationsAdminDogs::validate($request->all());

        if ($validator->fails()) {
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();


        $profile = UserProfile::where('profile_id',1)->first();
        $user =  $profile->user->first();
        
        DB::beginTransaction();

        $regnum = $this->generarCodigoRegistro();

        try {

            // Crea el registro sin reg_no
            $dog = Dog::create([
                'reg_no'=>$regnum,
                'name' => $validatedData['name'],
                'breed' => 'Pit Bull Terrier',
                'color' => $validatedData['color'],
                'sex' => $validatedData['sex'],
                'birthdate' => Carbon::parse('2026-01-01'),
                'sire_id' => null,
                'dam_id' => null,
                'breeder_id' => $profile->profile_id,
                'current_owner_id' => $profile->profile_id,
                'status' => 'exempt'
            ]);

            $dog->save();



            $orderReference = $this->getOrderReference();

            $payment = Payment::create([
                'user_id' => 1, 
                'order_reference'=>$orderReference,
                'amount' => 100.00, 
                'type' => 'registration', 
                'status' => 'pending', 
                'payment_method' => 'Paypal' 
            ]);

            // Registrar la relación en dog_payments
            DogPayment::create([
                'dog_id' => $dog->dog_id,
                'payment_id' => $payment->payment_id
            ]);

            DB::commit();
       
            return redirect()->back()->with('success', '¡El perro fue registrado con éxito!');
        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the pricing. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
            DB::rollBack();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

        return view('pedigree.edit-pedigree', compact('pedigree'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
  
        $generations = $request->input('generations');

        if (!is_array($generations)) {
            return response()->json(['error' => 'Formato inválido en generations.'], 400);
        }

        DB::beginTransaction();

        try {
            foreach ($generations as $generationNumber => $dogs) {
                foreach ($dogs as $role => $dogData) {

                    $dogName = trim($dogData['name'] ?? '');
                    $sex = $dogData['sex'] ?? null;
                    $color = $dogData['color'] ?? null;
                    $id = $dogData['invoice'] ?? null;

                    
                    if (!$id) {
                        return response()->json(['error' => "Falta el ID en $role de generación $generationNumber"], 400);
                    }

                    $dog = Dog::whereRaw('MD5(dog_id) = ?', [$id])->first();

                    if (!$dog) {
                        return response()->json([
                            'error' => "Perro no encontrado para $role en generación $generationNumber."
                        ], 404);
                    }
                    
                    $updates = [];

                    if ($dogName && $dog->name !== $dogName) {
                        $updates['name'] = $dogName;
                    }

                    if ($color && $dog->color !== $color) {
                        $updates['color'] = $color;
                    }

                    if (!empty($updates)) {
                        $dog->update($updates);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status'=>200,
                'message' => 'Pedigree actualizado correctamente.',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al procesar perros.',
                'message' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
