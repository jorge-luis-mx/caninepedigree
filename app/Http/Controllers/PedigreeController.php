<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//model
use App\Models\Dog;
//Traits
use App\Traits\Pedigree;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PedigreeController extends Controller
{

    use Pedigree;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $role = $user->role;
        return view('pedigree/show-all-pedigree',compact('role'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

        return view('pedigree.show-pedigree', compact('pedigree'));
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
