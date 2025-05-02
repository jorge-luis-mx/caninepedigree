<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreedingRequest;

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

    public function store(Request $request, $breedingId)
    {
        $breeding = BreedingRequest::findOrFail($breedingId);

        if ($breeding->maleDog->current_owner_id !== auth()->user()->userprofile->profile_id) {
        
            abort(403, 'No autorizado');
        }

        $request->validate([
            'puppies' => 'required|array|min:1',
            'puppies.*' => 'required|string|max:255',
        ]);

        $puppies = $request->puppies;
        $totalAmount = count($puppies) * 100;

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

        return response()->json(['success' => true]);
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
