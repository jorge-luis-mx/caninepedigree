<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//model
use App\Models\ServiceType;
use App\Models\Airport;
use App\Models\Map;
use App\Models\Service;


class ServiceTypeController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;

        $airports = Airport::where('pvr_id', $provider_auth)->get();
        $servicesType = ServiceType::where('ser_typ_status', 1)
            ->orderBy('ser_typ_capacity', 'asc')
            ->orderBy('ser_typ_alias', 'desc')
            ->get();


        $airportsWithServices = [];

        foreach ($airports as $airport) {

            // Filtra los servicios cuyo estado es 1
            $filteredServices = $airport->services->filter(function ($service) {
                return $service->pvr_ser_status === 1;
            })->pluck('ser_typ_id')->toArray();

            $airportsWithServices[] = [
                'id_airport' => $airport->pvr_airport_id,
                'id_provider' => $airport->pvr_id,
                'airport_alias' => $airport->pvr_airport_alias,
                'servicesExist' => $filteredServices, // Agrega solo los servicios filtrados
                'map' => $airport->maps,
                'services' => $servicesType,
                'urlImg'=>'https://platform.airporttransportation.com/api/images/'
            ];
        }

        return view('serviceType/servicetype-list', compact('airportsWithServices'));
    }


    public function create() {}


    public function store(Request $request) {}


    public function show(string $id) {}

    public function edit(string $id) {}


    public function update(Request $request, string $id) {}


    public function destroy(string $id) {}
}
