<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

//Controllers Add
use App\Models\Provider;
use App\Models\Airport;
use App\Models\Service;
use App\Models\Map;

class AirportController extends Controller
{

    public function index(Request $request)
    {

        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
        $airports = Airport::where('pvr_id', $provider_auth)
            ->orderBy('pvr_airport_id', 'desc')
            ->get();

        return view('airport/airport-list', compact('airports', 'provider_auth'));
    }


    public function create()
    {
        $user = auth()->user();
        $provider_auth = $user->pvr_id;
        // $providers = Provider::all();
        // var_dump($providers);exit;

        return view('airport.airport-form', compact('provider_auth'));
    }


    public function store(Request $request)
    {

        $post = $request->all();
        $data = [
            'message' => 'Failed to insert the airport. Please check the data and try again',
            'status' => 400,
            'errors' => null
        ];

        $validator = Validator::make($post, [
            'provider' => 'required',
            'api_alias' => 'required',
            'api_ref' => 'required',
            'aliasAirport' => 'required|regex:/^[a-zA-Z\s]+$/'
        ]);

        if ($validator->fails()) {
            $data['errors'] = $validator->errors();
            return response()->json($data, 400);
        }


        $validatedData = $validator->validated();
        //create alias
        $alias = Str::slug($validatedData['api_alias'], ' ');
        $alias = Str::limit($alias, 45, '');

        try {

            // Check if 'pvr_airport_api_ref' already exists
            $existingAirport = Airport::where('pvr_airport_api_ref', $validatedData['api_ref'])
                ->where('pvr_id', $validatedData['provider'])
                ->first();
            if ($existingAirport) {
                $data['message'] = 'An airport already exists with the provided details';
                $data['status'] = 409;
                return response()->json($data);
            }


            $provider = Airport::create([
                'pvr_id' => $validatedData['provider'],
                'pvr_airport_alias' => $validatedData['aliasAirport'],
                'pvr_airport_api_ref' => $validatedData['api_ref'],
            ]);

            $data['message'] = 'Airport inserted successfully';
            $data['status'] = 200;
        } catch (\Exception $e) {

            $data['message'] = 'Failed to insert the airport. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
        }

        return response()->json($data);
    }


    public function show(string $id) {}

    public function edit($id)
    {

        //$airport = Airport::findOrFail($id);
        $airport = Airport::whereRaw('MD5(pvr_airport_id) = ?', [$id])->firstOrFail();


        return view('airport.airport-form-edit', compact('airport'));
    }


    public function update(Request $request, $id)
    {


        $post = $request->all();
        $data = [
            'message' => 'Airport updated successfully',
            'status' => 400,
            'errors' => null
        ];

        $validator = Validator::make($post, [
            'api_airport' => 'required|integer',
            'api_alias' => 'required',
            'api_ref' => 'required',
            'aliasAirport' => 'required|regex:/^[a-zA-Z\s]+$/'
        ]);

        if ($validator->fails()) {
            $data['status'] = 422;
            $data['errors'] = $validator->errors();
            return response()->json($data, 422);
        }
        $validatedData = $validator->validated();

        //Find the airport by ID or return a 404 error
        $airport = Airport::findOrFail($id);
        $alias = Str::slug($validatedData['api_alias'], ' ');
        $alias = Str::limit($alias, 45, '');

        try {

            // Update the airport with the validated data
            $airport->update([
                'pvr_airport_id' => $validatedData['api_airport'],
                'pvr_airport_alias' => $validatedData['aliasAirport'],
                'pvr_airport_api_ref' => $validatedData['api_ref'],
            ]);

            $data['message'] = 'Airport updated successfully';
            $data['status'] = 200;
        } catch (\Exception $e) {

            $data['message'] = 'Failed to update the airport. Please check the data and try again';
            $data['status'] = 500;
            $data['errors'] = $e->getMessage();
        }


        return response()->json($data);
    }


    public function destroy($id): JsonResponse
    {

        // Start a transaction
        DB::beginTransaction();

        try {

            $airport = Airport::findOrFail($id);
            $services = $airport->services;

            foreach ($services as $service) {
                //delete pricing
                $service->pricing()->delete();
            }
            // Delete the related services
            $airport->services()->delete();

            $maps = $airport->maps;
            if ($maps->isNotEmpty()) {
                foreach ($maps as $map) {
                    // Delete map file
                    $filePath = storage_path('app/public/' . $map->pvr_map_filename);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }
            }

            // Delete the related maps
            $airport->maps()->delete();
            // Delete the airport
            $airport->delete();

            // If everything was successful, commit the transaction
            DB::commit();

            return response()->json(['status' => 200, 'message' => 'Airport deleted successfully']);
        } catch (ModelNotFoundException $e) {
            // Roll back the transaction if the airport is not found
            DB::rollBack();
            return response()->json(['status' => 404, 'message' => 'Airport not found'], 404);
        } catch (\Exception $e) {
            // Roll back the transaction if an error occurs
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Error deleting airport'], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $airport = Airport::find($id);

        if ($airport) {
            $airport->pvr_airport_status = $request->status;
            $airport->save();

            return response()->json(['status' => 200, 'message' => 'The status has been updated successfully.']);
        }

        return response()->json(['status' => 500, 'message' => 'Airport not found'], 404);
    }
}
