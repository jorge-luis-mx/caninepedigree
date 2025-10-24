<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//model
use App\Models\Dog;
//Traits
use App\Traits\Pedigree;

use Barryvdh\DomPDF\Facade\Pdf as PDF;


class CertificateController extends Controller
{

    use Pedigree;


    public function index()
    {
        //
    }

    public function pdf($id,$type, Request $request)
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
    
        $user = auth()->user();
        $profile = $user->userprofile;

        if ($dog->current_owner_id !== $profile->profile_id) {
            abort(403, 'Unauthorized access.');
        }

        $pedigree = $this->findPedigree($dog);
    
        if ($type =='pedigree') {
            $includeFlag = $request->query('flag', 0);
            $pdf = PDF::loadView('dogPDF.pdf_view_pedigree',  compact('pedigree','includeFlag')); 
            $pdf->setPaper('a4', 'portrait');
            
        }else{

            $includeFlag = $request->query('flag', 0);
            $pdf = PDF::loadView('dogPDF.pdf_view_registration',  compact('pedigree','includeFlag')); 
            $pdf->setPaper('a4', 'landscape');
        }
    
        return $pdf->stream('your_filename.pdf');
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
