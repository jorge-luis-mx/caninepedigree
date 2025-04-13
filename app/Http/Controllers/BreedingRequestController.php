<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DogInvitationMail;
use Illuminate\Support\Str;

//model
use App\Models\Dog;
use App\Models\UserProfile;
use App\Models\DogParentRequest;

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
        $request->validate([
            'my_dog_id' => 'required|exists:dogs,dog_id',
            'other_dog_name' => 'required|string',
            'other_owner_email' => 'required|email',
        ]);

        $myDog = Dog::findOrFail($request->my_dog_id);

        $myOwner = auth()->user()->userprofile;

        $isMyDogFemale = $myDog->sex === 'M';

        if ($isMyDogFemale) {
            // Caso 1: Tengo una perra y busco un perro
            $otherType = 'sire'; // padre
        } else {
            // Caso 2: Tengo un perro y propongo cruza a una perra
            $otherType = 'dam'; // madre
        }

        // Crear solicitud de registro del otro perro si no existe aún
        $token = Str::uuid();
        DogParentRequest::create([
            'dog_id' => $myDog->dog_id,
            'parent_type' => $otherType,
            'email' => $request->other_owner_email,
            'token' => $token,
        ]);

        //send mails
        $data = [
            'from'=>'jorge06g92@gmail.com',
            'subject' => '',
            'url'=>'http://www.caninepedigree-dev.com/register',
            'data'=>[
                'dogName'=>$myDog->name,
                'other_dog_name'=>$request->other_dog_name,
                'otherType'=>$otherType,
                'token'=>$token,
                'owner'=>$myOwner->name.' '.$myOwner->lastName
            ]
        ];

        Mail::to($request->other_owner_email)->send(new DogInvitationMail($data));

        return redirect()->back()->with('success', 'Solicitud enviada. El dueño del otro perro debe registrar su mascota para continuar.');
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
