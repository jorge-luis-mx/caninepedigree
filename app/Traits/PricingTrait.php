<?php

namespace App\Traits;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\progress;

trait PricingTrait {

    private function resolveRetentions($retentions,$airport){
        $data = [
            'poligonRetentions'=>[],
            'providerRetentions'=>[]
        ];
        foreach ($retentions as $key => $retention) {

            if (!empty($retention->pvr_airport_id) && $retention->app_fee_status == 1 && $retention->pvr_airport_id == $airport->pvr_airport_id) {
                array_push($data['poligonRetentions'],$retention);
            }

            if (!empty($retention->app_fee_pvr_share) && $retention->app_fee_status == 1) {

                array_push($data['providerRetentions'],$retention);
            }
        
        }

        return $data;
        
    }

    private function getPricing($pricings,$retentions = null){

        
        $data = [];
        foreach ($pricings as $pricing) {


            $retentionRate = $this->resolveRetentionRate($retentions,$pricing);

            $retentionOneWay = !empty($pricing->pricing_oneway) ?  $pricing->pricing_oneway - ($pricing->pricing_oneway * $retentionRate) : '';
            $retentionroundTrip = !empty($pricing->pricing_roundtrip) ?  $pricing->pricing_roundtrip - ($pricing->pricing_roundtrip * $retentionRate): '';

            $array = [
                'pricing_id' => $pricing->pricing_id,
                'pvr_airport_id' => $pricing->pvr_airport_id,
                'pvr_ser_id' => $pricing->pvr_ser_id,
                'pricing_polygon_id' => $pricing->pricing_polygon_id,
                'oneWay' => $pricing->pricing_oneway, 
                'roundTrip' => $pricing->pricing_roundtrip, 
                'retentionAmountOneWay'=>$retentionOneWay,
                'retentionAmountRoundTrip'=>$retentionroundTrip,
                'retentionFee'=>$retentionRate

            ];
            array_push($data,$array);
        }

        return $data;

    }

    private function resolveRetentionRate($retentions,$pricing){

        $poligonShare = null;
        if (!empty($retentions['poligonRetentions'])) {
            foreach ($retentions['poligonRetentions'] as $key => $poligonRetention) {
                if ($pricing->pricing_polygon_id == $poligonRetention->app_fee_polygon_id) {
                    $poligonShare = $poligonRetention->app_fee_polygon_share;
                }
            }
        }

        $providerShare = null;
        if (!empty($retentions['providerRetentions'])) {
            foreach ($retentions['providerRetentions'] as $key => $providerRetention) {
                $providerShare = $providerRetention->app_fee_pvr_share;
            }
        }

        $retentionRate = !empty($poligonShare) ? $poligonShare : (!empty($providerShare) ? $providerShare : env('RETENTION_FEE'));
        $retentionRate = $retentionRate / 100;
        return $retentionRate;
    }

    private function resolveCustomerRetention($retentions,$poligons,$service){
        
        $data = [];

        foreach ($poligons as $key => $poligon) {
                    
            $retentionFee = $this->calculateResolvedRetention($retentions,$poligon);

            $array = [

                'pvr_ser_id' => $service->pvr_ser_id,
                'pricing_polygon_id' => $poligon['id'],
                'retentionFee'=>$retentionFee

            ];
            array_push($data,$array);

        }

        return $data;


    }

    private function calculateResolvedRetention($retentions,$poligon){

        $poligonShare = null;

        if (!empty($retentions['poligonRetentions'])) {
            foreach ($retentions['poligonRetentions'] as $key => $poligonRetention) {
                if ($poligon['id'] == $poligonRetention->app_fee_polygon_id) {

                    $poligonShare = $poligonRetention->app_fee_polygon_share;
                }
            }
        }

        $providerShare = null;
        if (!empty($retentions['providerRetentions'])) {
            foreach ($retentions['providerRetentions'] as $key => $providerRetention) {
                $providerShare = $providerRetention->app_fee_pvr_share;
            }
        }

        $retentionRate = !empty($poligonShare) ? $poligonShare : (!empty($providerShare) ? $providerShare : env('RETENTION_FEE'));
        $retentionRate = $retentionRate / 100;
        return $retentionRate;
    }

    private function getPoligons($map){

        $poligons = [];
        if (!empty($map) && (isset($map[0]['pvr_map_status']) && $map[0]['pvr_map_status'] == 1)) {
            if (isset($map[0]['pvr_map_filename'])) {

                $filePath = storage_path('app/public/' . $map[0]['pvr_map_filename']);
                if (File::exists($filePath)) {
                    // Leer el contenido del archivo JSON
                    $mapJson  = File::get($filePath);
                    $mapJson = json_decode($mapJson, true);
                    if (!empty($mapJson)) {
                        $obj = json_decode($mapJson);
                        $features = $obj->features;

                        foreach ($features  as $key => $value) {
                            $dataPoligons = [
                                'id' => $value->properties->id,
                                'name' => $value->properties->name
                            ];
                            array_push($poligons, $dataPoligons);
                        }
                    }
                }
            }
        }
        return $poligons;

    }

    private function createMapProperty($map){

        $data = [
            'pvr_map_id' => (isset($map[0]['pvr_map_id']) && !empty($map[0]['pvr_map_id'])) ? $map[0]['pvr_map_id'] : '',
            'pvr_map_alias' => (isset($map[0]['pvr_map_alias']) && !empty($map[0]['pvr_map_alias'])) ? $map[0]['pvr_map_alias'] : '',
        ];
        return $data;
    }

    private function getServicesType($service){

        $servicesType = [
            'ser_typ_id' => $service->serviceType ? $service->serviceType->ser_typ_id : 'N/A',
            'ser_typ_alias' => $service->serviceType ? $service->serviceType->ser_typ_alias : 'N/A',
            'ser_typ_capacity' => $service->serviceType ? $service->serviceType->ser_typ_capacity : 'N/A',
        ];

        return $servicesType;
    }

    
}