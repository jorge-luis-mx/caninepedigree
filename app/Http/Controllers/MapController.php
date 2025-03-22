<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
//model
use App\Models\Airport;
use App\Models\Map;
use App\Models\Provider;
use Illuminate\Cache\RateLimiting\Limit;
// use Illuminate\Support\Facades\Artisan;
class MapController extends Controller
{

    public function index(Request $request)
    {
        
        $user = auth()->user();  
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
   
        $airports = Airport::where('pvr_id', $provider_auth)->pluck('pvr_airport_id');
        // Obtener los mapas que corresponden a esos aeropuertos
        $maps = Map::with('airport')->whereIn('pvr_airport_id', $airports)
        ->orderBy('pvr_map_id', 'desc')
        ->get();
        // Crear una nueva colección con la API ref de los aeropuertos
        $mapsWithApiRef = $maps->map(function ($map) {
            $airport = $map->airport; 

            $filePath = storage_path('app/public/' . $map->pvr_map_filename);
            $mapJson = null;
            if (File::exists($filePath)) {
                // Leer el contenido del archivo JSON
                $mapJson  = File::get($filePath);
                $mapJson = json_decode($mapJson, true);
            }
            $mapJson = json_decode($mapJson, true);
            $poligons = collect($mapJson['features']) // Convertir el array a una colección
            ->map(function ($poligon) {
                return $poligon['properties'];
            });
            $map->poligons = $poligons;
            $map->pvr_airport_api_ref = $airport ? $airport->pvr_airport_api_ref : null;
            $map->pvr_airport =$airport ? $airport->pvr_airport_alias : null;
            return $map;
        });

        //dd($mapsWithApiRef);
        return view('map/map-list',compact('mapsWithApiRef'));

    }


    public function create($id=null)
    {
        
        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;

        // Obtener aeropuertos que NO tienen un registro en la tabla maps
        $airports = null;
        $airport = null;
        $mapData = [];

        if ($id == null) {
            
            $airports = Airport::where('pvr_id', $provider_auth)
            ->whereDoesntHave('maps') // Excluye aeropuertos que ya tienen un mapa
            ->get()
            ->toArray();
            
        }else{
            
            $airport = Airport::whereRaw('md5(pvr_airport_id) = ?', [$id])->first();
            $id = $airport->pvr_airport_id;
            $findMap = $airport->maps;
            
            if ($findMap->isNotEmpty()) {
    
                $mapData['pvr_map_id'] = md5($findMap[0]->pvr_map_id);
                
            }
        }

        

        return view('map.map-form',compact('airports','provider_auth','id','mapData','airport'));
    }


    public function store(Request $request)
    {
        $maps = $request->all();
        $data = ['status'=>400,'data'=>null,'errors'=>null];
        try {
                
            $validatedData = Validator::make($maps, [
                'airport'=>'required|numeric',
                'aliasMap' => 'required|regex:/^[a-zA-Z\s]+$/'
            ])->validate();

            $existingMap = Map::where('pvr_airport_id', $validatedData['airport'])->first();
            if ($existingMap) {
                $data['message'] = 'An map already exists with the map details';
                $data['status'] = 409;
                return response()->json($data);
            }

            //create fileName
            $uniqueFileName = 'map_' . uniqid() . '.json';
            $filePath = storage_path('app/public/' . $uniqueFileName);
            // Guardar archivo JSON usando File::put
            File::put($filePath, json_encode($maps['colections'][0], JSON_PRETTY_PRINT));
            


            $mapData = Map::create([
                'pvr_airport_id' => $validatedData['airport'],
                'pvr_map_filename' => $uniqueFileName,
                'pvr_map_alias'=>$validatedData['aliasMap']
            ]);
            $lastInsertedId = $mapData->pvr_map_id;

            // Agregar resultados a la respuesta
            $data['data'] = [
                'id' => $lastInsertedId,
                'file' => $uniqueFileName
            ];


            $data['status'] = 200;
            $data['message'] = 'It was saved successfully';

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar errores de validación
            $data['errors'] = [
                'provider' => $maps,
                'errors' => $e->errors()
            ];
        } catch (\Exception $e) {
            // Manejar otros errores
            $data['errors'] = [
                'provider' => $maps,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($data);

    }

    public function show(string $id)
    {
        
    }

 
    public function edit($id)
    {
        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;

        //$airports = Airport::where('pvr_id', $provider_auth)->get();
        
        //$map = Map::where('pvr_map_id',$id)->get();
        $map = Map::whereRaw('md5(pvr_map_id) = ?',[$id])->get();
        $airports = Airport::where('pvr_airport_id', $map[0]->pvr_airport_id)->get();
        
       
        $filePath = storage_path('app/public/' . $map[0]['pvr_map_filename']);
        $mapJson = null;
        if (File::exists($filePath)) {
            // Leer el contenido del archivo JSON
            $mapJson  = File::get($filePath);

            $mapJson = json_decode($mapJson, true);

        }
        

        return view('map.map-form-edit',compact('airports','id','map','mapJson'));
    }


    public function update(Request $request,  $id)
    {

        $post = $request->all();


        $data = ['message' => 'Airport updated successfully','status' => 400,'errors' => null];

        $validator = Validator::make($post, [
            'airport' => 'required|integer',
            'file' => 'required',
            'aliasMap'=>'required|regex:/^[a-zA-Z\s]+$/'
        ]);

        if ($validator->fails()) {
            $data['status']= 422;
            $data['errors'] =$validator->errors();
            return response()->json($data, 422);
        }
        
        $validatedData = $validator->validated();
        
        //Find the airport by ID or return a 404 error
        //$map = Map::findOrFail($id);
        $map = Map::whereRaw(('md5(pvr_map_id) = ?'),[$id])->firstOrFail();
        //$airport = Airport::whereRaw('MD5(pvr_airport_id) = ?', [$id])->firstOrFail();
        try {


            $filePath = storage_path('app/public/' .$validatedData['file']);
           
            
            if (File::exists($filePath)) {
                // Leer el contenido del archivo JSON
                $fileContent  = File::get($filePath);
                $mapJson = json_decode($fileContent, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $mapJson = $post['colections'][0];
                    // Guardar el archivo actualizado
                    File::put($filePath, json_encode($mapJson, JSON_PRETTY_PRINT));
                    
                }
                
            }

            // Update the airport with the validated data
            $map->update([
                'pvr_airport_id' => $validatedData['airport'],
                'pvr_map_alias' =>$validatedData['aliasMap'],
            ]);

            $data['message'] = 'Map updated successfully';
            $data['status'] = 200;
        } catch (\Exception $e) {
            
            $data['message'] = 'Failed to update the map. Please check the data and try again';
            $data['status'] = 500;  
            $data['errors'] = $e->getMessage();  
        }


        return response()->json($data);

    }

   
    public function destroy($id)
    {
        
        try {
            // Start a transaction
            DB::beginTransaction();

            $map = Map::findOrFail($id);

            $prices = $map->pricing;
            foreach ($prices as  $price) {
                //delete operation
                $price->delete();
            }
            

            $filePath = storage_path('app/public/' .$map['pvr_map_filename']); 
            $map->delete(); 
            // If everything was successful, commit the transaction
            DB::commit();
            // Verificar si el archivo existe
            if (File::exists($filePath)) {
                // Eliminar el archivo
                File::delete($filePath);
            }

            return response()->json(['status' => 200, 'message' => 'Mpa deleted successfully']);
        } catch (ModelNotFoundException $e) {

            return response()->json(['status' => 404, 'message' => 'Map not found'], 404);
        } catch (\Exception $e) {

            return response()->json(['status' => 500, 'message' => 'Error deleting map'], 500);
        }
        
    }

    public function updateAlias(Request $request, $id)
    {
        
        $post = $request->all();
        $map = Map::findOrFail($id);

        $validator = Validator::make($post, ['alias' => 'required|string']);

        if ($validator->fails()) {
            $data['status']= 422;
            $data['errors'] =$validator->errors();
            return response()->json($data, 422);
        }
        
        $validatedData = $validator->validated();

        $map->update(['pvr_map_alias' => $validatedData['alias']]);
        return response()->json([ 'status'=>200,'message' => 'The status has been updated successfully.']);

    }

    public function updateStatus(Request $request, $id)
    {
        
        $map = Map::find($id);
  
        if ($map) {
            $map->pvr_map_status = $request->status;
            $map->save();

            return response()->json([ 'status'=>200,'message' => 'The status has been updated successfully.']);
        }

        return response()->json(['status'=>500,'message' => 'Map not found'], 404);
    }

    public function findMaps($id){

        
        $array = ['status' => 200,'message'=>null,'maps'=>null];
        try {

            $airports = Airport::where('pvr_airport_api_ref', $id)
            ->limit(20)
            ->get();
            foreach ($airports as $key => $value) {
                
                if (isset($value->provider['pvr_name']) && !empty($value->provider['pvr_name'])) {
                    $value->prv_name = $value->provider['pvr_name'];
                }
                if (isset($value->maps[0]['pvr_map_filename']) && !empty($value->maps[0]['pvr_map_filename'])) {
                    $filePath = storage_path('app/public/' . $value->maps[0]['pvr_map_filename']);
                    if (File::exists($filePath)) {
                        // Leer el contenido del archivo JSON
                        $fileContent  = File::get($filePath);
                        $mapJson = json_decode($fileContent, true);
                        $value->map = $mapJson;
                    }
                }
    
            }

            $dataAirport = [];
            foreach ($airports as $key => $airport) {
                if (isset($airport['map']) && !empty($airport['map'])) {
                    $data = [
                        'pvr_id'=>md5($airport['pvr_id']),
                        'pvr_airport_id'=>md5($airport['pvr_airport_id']),
                        'pvr_name'=>$airport['prv_name'],
                        'map'=>$airport['map']
                        
                    ];
                    array_push($dataAirport,$data);
                }
    
            }
    
            $array['status'] = 200;
            $array['message'] = 'Airport success';
            $array['maps'] =$dataAirport;
            
        } catch (ModelNotFoundException $e) {

            $array['status'] = 404; $array['message'] = 'Airport not found';
        } catch (\Exception $e) {

            $array['status'] = 500; $array['message'] = 'Error deleting airport';
        }

       
        return response()->json($array,$array['status']);
    }

    public function updateArea(Request $request,  $id){
        $post = $request->all();
        $map = Map::find($id);
        $filePath = storage_path('app/public/' . $map['pvr_map_filename']);

        if (File::exists($filePath)) {
            // Leer el contenido del archivo JSON
            $fileContent = File::get($filePath);
            $mapJson = json_decode($fileContent, true);
        
            if (is_string($mapJson)) {
                // Si sigue siendo una cadena, decodificar de nuevo
                $mapJson = json_decode($mapJson, true);
            }
        
            if (!is_array($mapJson)) {
                throw new \Exception("El archivo no contiene un JSON válido.");
            }
            
        
            // Recorrer todos los features
            foreach ($mapJson['features'] as &$feature) {
                // Verificar si el ID coincide
                if (isset($feature['properties']['id']) && $feature['properties']['id'] === $post['poligonId']) {
                    // Actualizar el valor de "name"
                    $feature['properties']['name'] = $post['value'];
                }
            }
            unset($feature); // Limpiar referencia
            $updatedJson = json_encode($mapJson, JSON_PRETTY_PRINT);
            $updatedJson = json_encode($updatedJson, JSON_PRETTY_PRINT);
            File::put($filePath, $updatedJson);
            
        }
        
        return response()->json([ 'status'=>200,'message' => 'The status has been updated successfully.']);
    }
}
