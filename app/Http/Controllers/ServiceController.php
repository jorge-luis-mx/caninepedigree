<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
//models
use App\Models\Service;
use Illuminate\Auth\Events\Validated;

class ServiceController extends Controller
{

    public function index()
    {
        
    }


    public function create()
    {
        
    }


    public function store(Request $request)
    {
        
        $data = ['message' => '','status' => null,'errors' => null];

        try {

   
            $validated = $request->validate([
                'provider' => 'required|string',
                'services' => 'required|array|min:1',  // Asegúrate de que es un array y que tiene al menos 1 servicio
                'services.*.serviceType' => 'required|string',  // Cada servicio debe tener un serviceType que sea string
                'services.*.status' => 'required|in:0,1',       // El status debe ser 0 o 1
                'airport' => 'required|string',
            ]);
            
            
            if ($validated) {
                foreach ($validated['services'] as $key => $value) {

                    // Check if  already exists
                    $existingService = Service::where('ser_typ_id', $value['serviceType'])
                    ->where('pvr_conf_airport_id',$validated['airport'])
                    ->first();

                    if ($existingService) {
                        
                        // Verificar si existen precios para este servicio
                        $prices = Pricing::where('pvr_ser_id', $existingService->pvr_ser_id)->get();

                        // Update the airport with the validated data
                        $existingService->update(['pvr_ser_status' =>$value['status']]);

                        // Actualiza el estatus de los precios asociados
                        if ($prices->isNotEmpty()) {
                            foreach ($prices as $price) {
                                $price->update(['pricing_status' => $value['status']]); 
                            }
                        }
                        
                        $data['message'] = 'Service status updated to inactive';
                        $data['status'] = 200;
                        
                    } else {

                        if ($value['status']=='1') {

                            Service::create([
                                'pvr_id' => $validated['provider'],
                                'ser_typ_id' => $value['serviceType'],
                                'pvr_conf_airport_id' => $validated['airport'],
                            ]);
                            $data['message'] = 'Airport inserted successfully';
                            $data['status'] = 200;
                        }

                    }

                }

                
            }


        } catch (ValidationException $e) {
            // Si la validación falla, devolverá los errores en formato JSON
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        }

        return response()->json($data);
    }


    public function show(string $id)
    {
        
    }


    public function edit(string $id)
    {
        
    }


    public function update(Request $request, string $id)
    {
        
    }

 
    public function destroy(string $id)
    {
        
    }
}
