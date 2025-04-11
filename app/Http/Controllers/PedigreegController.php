<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
//model
use App\Models\Dog;

use App\Traits\Pedigree;
class PedigreegController extends Controller
{
 
    use Pedigree;

    public function index()
    {
        return view('pedigree/show-all-pedigree');
    }

   

    public function generatePDF($id,$type)
    {
        // Obtén la data desde la base de datos o cualquier otro origen
     
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

        if ($type =='pedigree') {
            // Cargar la vista con los datos
            $pdf = PDF::loadView('dogPDF.pdf_view_pedigree',  compact('pedigree')); 
            $pdf->setPaper('a4', 'portrait');
        }else{

            $pdf = PDF::loadView('dogPDF.pdf_view_registration',  compact('pedigree')); 
            $pdf->setPaper('a4', 'landscape');
        }


        // Configurar la orientación horizontal (landscape)
        // $pdf->setPaper('a4', 'landscape');
        

        // Descargar el PDF
        // return $pdf->download('your_filename.pdf');
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
