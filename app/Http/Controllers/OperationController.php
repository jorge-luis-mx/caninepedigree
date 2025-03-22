<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//models
use App\Models\Provider;
use App\Models\Sale;
use App\Models\Operation;


class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($date = null)
    {

        date_default_timezone_set('America/cancun');
        $date = $date != null? $date :  date('Y-m-d');

        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;


        $provider = Provider::find($provider_auth);
        $sales = $provider->sales()
            ->where('sale_status', 'Completed')
            ->with('client')
            ->with('operations') // Eager loading de operaciones
            ->with('salesInfo')
            ->with('payments')
            ->with('channel')
            ->get();
        
        $saleOperations = [];
        foreach ($sales as $sale) {
            // Verifica si existen operaciones en la venta
            if ($sale->operations->isNotEmpty()) {

                
                $filterPayments = $sale->payments->first(function ($payment) {
                    return $payment->pay_status == 'COMPLETED'; 
                });

                $data = [
                    'sale'=>$sale,
                    'saleInfo'=>$sale->salesInfo,
                    'payment'=>$filterPayments,
                    'channel'=>$sale->channel,
                    'client'=>$sale->client
                ];

                
                $filterOperations = $sale->operations->filter(function ($operation) use ($date) {
                    // Filtra las operaciones según la fecha (comparando como fecha y no como string)
                    return $operation->op_date->format('Y-m-d') === $date; // Comparar la fecha correctamente
                });
            
                // Verifica si existen filteroperaciones
                if ($filterOperations->isNotEmpty()) {
                    // Usamos map() para devolver todas las operaciones relacionadas con esta venta
                    $saleOperations = array_merge($saleOperations, $filterOperations->map(function ($operation) use ($data) {
                        return [
                            'sale' => $data['sale'], 
                            'operation' => $operation, // Asigna la operación
                            'salesInfo'=>$data['saleInfo'],
                            'payments'=>$data['payment'],
                            'channel'=>$data['channel'],
                            'client'=>$data['client']
                        ];
                    })->toArray());
                }
            }

        }

        // Convertimos a una colección y ordenamos por la hora de la operación
        $saleOperations = collect($saleOperations)->sortBy(function ($item) {
            return $item['operation']->op_time->format('H:i'); // Ordena por hora:minuto
        });
        
    


        return view('operation/operation-list',compact('saleOperations','date'));
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
    public function search(Request $request)
    {
 
        // Validar la fecha
        $validator = Validator::make($request->all(), [
            'operaDate' => 'required|date',
        ]);

        
        if ($validator->fails()) {
            date_default_timezone_set('America/cancun');
            $date = date('Y-m-d');
            return redirect()->route('operation.index',['date' => $date]);
        }
        // Obtener la fecha enviada
        $date = $request->input('operaDate');
    
        return redirect()->route('operation.index', ['date' => $date]);
        
    }


    public function show($id)
    {

        date_default_timezone_set('America/cancun');
        $date = date('Y-m-d');
        try {

            $sale = Sale::whereRaw('MD5(sale_id) = ?', [$id])->with('client', 'operations', 'salesInfo', 'payments', 'channel')->firstOrFail(); 
            if (!empty($sale)) {

                $filterPayments = $sale->payments->first(function ($payment) {
                    return $payment->pay_status == 'COMPLETED'; 
                });

                $detailsOperations = (object) [
                    'sale'=>$sale,
                    'operations'=>$sale->operations,
                    'salesInfo'=>$sale->salesInfo,
                    'payments'=>$filterPayments,
                    'channel'=>$sale->channel,
                    'client'=>$sale->client
                ];

            }
        
        } catch (\Exception $e) {
            
            $data['message'] = 'Failed to insert the airport. Please check the data and try again';
            $data['status'] = 500;  
            $data['errors'] = $e->getMessage();  
        }

        
        return view('operation/operation-show',compact('detailsOperations'));
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
