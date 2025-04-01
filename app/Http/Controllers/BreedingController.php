<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreedingController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'female_id' => 'required|exists:dogs,id',
            'male_id' => 'required|exists:dogs,id',
            'owner_id' => 'required|exists:users,id',
        ]);

        // $breedingRequest = BreedingRequest::create([
        //     'female_id' => $request->female_id,
        //     'male_id' => $request->male_id,
        //     'owner_id' => $request->owner_id,
        //     'status' => 'pending', // Estado inicial
        // ]);

        // Notificar al dueño del macho
        //Notification::send(User::find($request->owner_id), new BreedingRequestNotification($breedingRequest));

        return response()->json([
            'message' => 'Solicitud de monta enviada con éxito.',
            'data' => $breedingRequest,
        ], 201);
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
