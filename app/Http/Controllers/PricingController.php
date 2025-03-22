<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

//model
use App\Models\ServiceType;
use App\Models\Airport;
use App\Models\Map;
use App\Models\Service;
use App\Models\Pricing;
use App\Models\Retention;

//traits
use App\Traits\PricingTrait;

class PricingController extends Controller
{
    use PricingTrait;

    public function index()
    {

        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
    
        $retentions = Retention::where('pvr_id', $provider_auth)
        ->where('app_fee_status', 1)
        ->get();
        
        $airports = Airport::where('pvr_id', $provider_auth)
            ->where('pvr_airport_status', 1)
            ->get();

        $airportsWithServices = [];

        foreach ($airports as $airport) {

            //find maps
            if ($airport->maps->isNotEmpty()) {
                $map = $airport->maps;
                //get poliigons
                $poligons = $this->getPoligons($map);
   
            
            
                //Check if the airport has services
                if ($airport->services->isNotEmpty()) {
                    // Mapea los servicios con sus 'ser_typ_id' y 'status'
                    $servicesWithTypeservices = $airport->services
                        ->filter(function ($service) {
                            return $service->pvr_ser_status == 1;
                        })
                        ->map(function ($service) use($retentions,$airport,$poligons){

                            if ($retentions->isNotEmpty()) {

                                $retentions =  $this->resolveRetentions($retentions,$airport);
                            }

                            $prices = null;
                            // Verifica si hay precios disponibles
                            if ($service->pricing->isNotEmpty() && $service->pricing->every(fn($price) => $price->pricing_status == 1)) {
                            
                                $prices = $this->getPricing($service->pricing,$retentions);
                            
                            }
                            
                            $retentionFee = $this->resolveCustomerRetention($retentions,$poligons,$service);
                            
                            //service type
                            $servicesType = $this->getServicesType($service);
                            
                            return [
                                'pvr_ser_id' => $service->pvr_ser_id,
                                'ser_typ_id' => $service->ser_typ_id,
                                'status' => $service->pvr_ser_status,
                                'retentionFee'=>$retentionFee,
                                'servicesType' => $servicesType,
                                'prices' => $prices

                            ];

                        })->toArray();


                    if (!empty($servicesWithTypeservices)) {

                        $propertyMap = $this->createMapProperty($map);
                        $propertyMap['poligons'] = $poligons;

                        $airportsWithServices[] = [
                            'id_airport' => $airport->pvr_airport_id,
                            'id_provider' => $airport->pvr_id,
                            'airport_alias' => $airport->pvr_airport_alias,
                            'services' => $servicesWithTypeservices,
                            'maps' => $propertyMap,
                        ];
                        
                    }
                }

            }
        }
        //dd($airportsWithServices);
        return view('pricing/pricing-list', compact('airportsWithServices'));
    }

    public function create() {}

    public function store(Request $request)
    {


        $data = [
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'status' => 400,
            'errors' => null
        ];

        $rules = [
            'airport' => 'required|integer', // El aeropuerto es requerido y debe ser un número
            'service' => 'required|integer', // El aeropuerto es requerido y debe ser un número
            'map' => 'required|integer', // El aeropuerto es requerido y debe ser un número
            'poligon' => 'required|uuid',
            'inputs.oneWay' => 'nullable|regex:/^\d+(\.\d{1,2})?$/', // Si tiene valor, debe ser un número entero o decimal con hasta dos decimales
            'inputs.roundTrip' => 'nullable|regex:/^\d+(\.\d{1,2})?$/', // Si tiene valor, debe ser un número entero o decimal con hasta dos decimales
        ];

        $messages = [
            'airport.required' => 'El campo aeropuerto es obligatorio.',
            'service.required' => 'El campo servicio es obligatorio.',
            'map.required' => 'El campo aeropuerto es obligatorio.',
            'inputs.oneWay.regex' => 'El valor de One Way debe ser un número entero o un decimal con hasta dos decimales.',
            'inputs.roundTrip.regex' => 'El valor de Round Trip debe ser un número entero o un decimal con hasta dos decimales.',

        ];

        // Validamos la request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Si pasa la validación, puedes seguir procesando los datos
        $validatedData = $validator->validated();


        try {


            // Check if  already exists
            $existingPricing = Pricing::where('pvr_ser_id', $validatedData['service'])
                ->where('pricing_polygon_id', $validatedData['poligon'])
                ->first();

            if (!$existingPricing) {

                $pricing = Pricing::create([
                    'pvr_airport_id' => $validatedData['airport'],
                    'pvr_ser_id' => $validatedData['service'],
                    'pricing_polygon_id' => $validatedData['poligon'],
                    'pvr_map_id' => $validatedData['map'],
                    'pricing_oneway' => $validatedData['inputs']['oneWay'],
                    'pricing_roundtrip' => $validatedData['inputs']['roundTrip'],
                    'pricing_currency' => 'USD',
                ]);
                $data['message'] = 'Pricing inserted successfully';
            } else {
                // Update the airport with the validated data
                $existingPricing->update([
                    'pricing_oneway' => $validatedData['inputs']['oneWay'],
                    'pricing_roundtrip' => $validatedData['inputs']['roundTrip']
                ]);
                $data['message'] = 'Pricing status updated';
            }

            $data['status'] = 200;
        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the pricing. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
        }

        return response()->json($data);
    }


    public function show(string $id) {}


    public function edit(string $id) {}


    public function update(Request $request, string $id) {}


    public function destroy(string $id) {}
}
