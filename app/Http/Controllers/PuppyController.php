<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreedingRequest;
use App\Validations\PuppiesValidations;

use App\Models\Dog;
use Illuminate\Support\Facades\DB;

class PuppyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $owner = $profile->profile_id;

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
            // Obtener solo los datos validados
            $validated = $validator->validated();

            foreach ($validated['puppies'] as $puppy) {

                Dog::create([
                    'reg_no'    =>$regnum,
                    'name'      => $puppy['name'],
                    'breed'      => 'Pit Bull Terrier',
                    'color'     => $puppy['color'],
                    'sex'       => $puppy['sex'],
                    'birthdate' => $puppy['birthdate'],
                    'sire_id'   => $validated['sire_id'], 
                    'dam_id'    => $validated['dam_id'], 
                    'breeder_id'=>$owner,
                    'current_owner_id'=>$owner,
                    'is_puppy'    => 1, 
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Cachorros registrados exitosamente.'], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al registrar cachorros.',
                'error' => $e->getMessage()
            ], 500);
        }



        // $breeding = BreedingRequest::findOrFail($breedingId);

        // if ($breeding->maleDog->current_owner_id !== auth()->user()->userprofile->profile_id) {
        
        //     abort(403, 'No autorizado');
        // }

        // $request->validate([
        //     'puppies' => 'required|array|min:1',
        //     'puppies.*' => 'required|string|max:255',
        // ]);

        // $puppies = $request->puppies;
        // $totalAmount = count($puppies) * 100;

        // Aquí simulas el pago.
        // Puedes integrar real pago con PayPal, Stripe o similar.

        // foreach ($puppies as $puppyName) {
        //     Puppy::create([
        //         'name' => $puppyName,
        //         'breeding_id' => $breeding->request_id,
        //         'female_dog_id' => $breeding->female_dog_id,
        //         'male_dog_id' => $breeding->male_dog_id,
        //     ]);
        // }

        // return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('puppies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

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
