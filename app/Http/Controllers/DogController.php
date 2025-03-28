<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Validations\DogsValidations;

//model
use App\Models\Dog;
class DogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // if ($request->ajax()) {
        //     $users = Dog::select(['id', 'name', 'email'])->get();
    
        //     $data = $users->map(function ($user) {
        //         return [
        //             'id' => $user->id,
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'action' => '
        //                 <a href="' . route('users.show', $user->id) . '" class="btn btn-sm btn-info">Ver</a>
        //                 <a href="' . route('users.edit', $user->id) . '" class="btn btn-sm btn-warning">Editar</a>
        //                 <button class="btn btn-sm btn-danger deleteUser" data-id="' . $user->id . '">Eliminar</button>
        //             '
        //         ];
        //     });
        //     return response()->json(['data' => $data]);
        // }


        // $userProfileId = auth()->user()->profile_id;
        // $dogs = Dog::where('current_owner_id', $userProfileId)
        //     ->with(['payment' => function ($query) {
        //         $query->where('status', 'completado');
        //     }])
        //     ->get();

        // return view('dogs.list-dogs', compact('dogs'));

        return view('dogs.list-dogs');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dogs.create-dog');
    }


    public function searchDog($reg_no)
    {

        $data = [
            'status' => 400,
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'data'=>[],
            'errors' => null,
        ];

        $reg_no = trim($reg_no);

        // Buscar el perro por número de registro
        $dog = Dog::where('reg_no', $reg_no)->first();
        
        $data = ['status' => 400, 'data' => null]; // Valor por defecto
        
        if ($dog) {
            $data['status'] = 200;
            $data['data'] = $dog;
        } else {
            // Buscar por nombre si no se encontró por número de registro
            $dogs = Dog::where('name', 'LIKE', "%$reg_no%")->get();
            
            if ($dogs->isNotEmpty()) {
                $data['status'] = 200;
                $data['data'] = $dogs;
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
            'status' => 400,
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'data'=>[],
            'errors' => null,
        ];
        
        if ($validator->fails()) {

            $data['errors'] = $validator->errors();
            return response()->json($data, 400);
        }
        $validatedData = $validator->validated();
        
    
        $user = auth()->user();
        $profile = $user->userprofile;

        if ($user->role == 'admin' || $user->role =='customer') {

            $owner = $user->profile_id == $profile->profile_id ? $profile->profile_id: null;
            
        }

        $sire_id = (isset($dog->sire_id) && !empty($dog->sire_id) && $dog->sire_id != null) ? $dog->sire_id : null;
        $dam_id = (isset($dog->dam_id) && !empty($dog->dam_id) && $dog->dam_id != null) ? $dog->dam_id : null;
        
        try {

            // Crea el registro sin reg_no
            $dog = Dog::create([
                'name' => $validatedData['name'],
                'breed' => $validatedData['breed'],
                'color' => $validatedData['color'],
                'sex' => $validatedData['sex'],
                'birthdate' => $validatedData['birthdate'],
                'sire_id' => $sire_id,
                'dam_id' => $dam_id,
                'breeder_id' => $owner,
                'current_owner_id' => $owner,
                'status' => 1
            ]);

            // Asigna reg_no después de la creación
            $dog->reg_no = "DOG-" . str_pad($dog->dog_id, 5, '0', STR_PAD_LEFT);
            $dog->save();

            if ($sire_id == null ) {

                $sireEmail = $validatedData['sire_email'];
                $descriptionSire = $validatedData['descriptionSire'];
    
            }

            if ($dam_id == null ) {

                $damEmail = $validatedData['dam_email'];
                $descriptionDam = $validatedData['descriptionDam'];
    
            }

           

            $data['message'] = 'Pricing inserted successfully';
            $data['status'] = 200;

        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the pricing. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
        }

        
        return response()->json($data);
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
