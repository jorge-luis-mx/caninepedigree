<?php

namespace App\Traits;

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

trait ProgressTrait {

    use PricingTrait;

    public function findProgress($provider_auth){

        $retentions = Retention::where('pvr_id', $provider_auth)
        ->where('app_fee_status', 1)
        ->get();
        
        $airports = Airport::where('pvr_id', $provider_auth)
            ->where('pvr_airport_status', 1)
            ->get();


        $processedAirports = [];

        if ($airports->isNotEmpty()) {
                
            foreach ($airports as $airport) {

                //find maps
                $map = $airport->maps->filter(function ($map) {
                    return $map->pvr_map_status == 1;
                });


                $processServices = null;
                $propertyMap = null;

                if ($map->isNotEmpty()) {
                   
                    //get poliigons
                    $poligons = $this->getPoligons($map);
                    
                    $propertyMap = $this->createMapProperty($map);
                    $propertyMap['poligons'] = $poligons;

                    //find services
                    $services = $airport->services->filter(function ($service) {
                        return $service->pvr_ser_status == 1;
                    });
                    
                    
                    if ($services->isNotEmpty()) {

                        $processServices = $services->map(function ($service) use ($retentions, $airport, $poligons) {

                            if ($retentions->isNotEmpty()) {

                                $retentions =  $this->resolveRetentions($retentions,$airport);
                            }

                            $prices = null;
                            // Verifica si hay precios disponibles
                            if ($service->pricing->isNotEmpty() && $service->pricing->every(fn($price) => $price->pricing_status == 1)) {
                            
                                $prices = $this->getPricing($service->pricing,$retentions);
                            
                            }
                            $servicesType = $this->getServicesType($service);
                            $retentionFee = $this->resolveCustomerRetention($retentions,$poligons,$service);
                            
                            $progressRates =  $this->findProgressRates($prices);

                            return [
                                'pvr_ser_id' => $service->pvr_ser_id,
                                'ser_typ_id' => $service->ser_typ_id,
                                'status' => $service->pvr_ser_status,
                                'servicesType' => $servicesType,
                                'prices' => $prices,
                                'retentionFee'=>$retentionFee,
                                'progressPrices'=>$progressRates 

                            ];

                        })->toArray();
                        
                    }

                }

                $resolveProgres = [
                    'progressAirport'=>$airports->isNotEmpty()?25:null,
                    'progressMap'=> ($map->isNotEmpty() && !empty($poligons) && count($poligons) > 0) ? 50: null,
                    'progressService'=>null,
                    'progressPrices'=>null
                ];

                if (!empty($processServices)) {
                    $resolveProgres['progressService'] = 75;
                    $response = $this->resolveProgress($processServices);
                    if ($response ==true) {
                        $resolveProgres['progressPrices'] = 100;
                    }
                }


                $processedAirports[] = [
                    'airport'=>$airport,
                    'maps' => $propertyMap,
                    'services' => $processServices,
                    'progress'=>$resolveProgres,

                ];

            }


        }


        return $processedAirports;
    }

    public function findProgressRates($prices){

        $progressPrices = null;
        if (!empty($prices)) {

            $statusPrices = false;
  
            foreach ($prices as $key => $value) {

                if (!empty($value['oneWay']) && !empty($value['roundTrip']) && $statusPrices == false) {
    
                    if ($value['oneWay'] >= 1.00 && $value['roundTrip'] >= 1.00) {
    
                        $statusPrices = true;
                        $progressPrices = 100;
                        break;
                    }
                }
                                    
    
            }
        }

        return $progressPrices;
    }

    public function resolveProgress($processServices){
     
        $progress = false;
        foreach ($processServices as $key => $value) {
            if (!empty($value['progressPrices'])) {
                $progress = true;
                break;
            }
            
        }
        return $progress;
        
    }
}