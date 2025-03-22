<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

//models
use App\Models\Provider;
use App\Models\Sale;
use App\Models\Operation;

class SalesController extends Controller
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
            ->whereRaw('DATE(sale_created) = ?', $date)
            ->with('client')
            ->with('operations') 
            ->with('salesInfo')
            ->with('payments')
            ->with('channel')
            ->get();

            $salesData = [];

            foreach ($sales as $sale) {
               
               
                if ($sale->operations->isNotEmpty()) {

                    $data = [
                        'sale'=>$sale,
                        'saleInfo'=>$sale->salesInfo,
                        'operations'=>$sale->operations,
                        'channel'=>$sale->channel,
                        'client'=>$sale->client
                    ];
                    $data['sale']['sale_md_id'] = md5($sale['sale_id']); 
                    array_push($salesData,$data);
                }
    
            }


        return view('sales/sales-list',compact('salesData','date'));
    }


    public function search(Request $request)
    {
 
        
        // Validar la fecha
        $validator = Validator::make($request->all(), [
            'saleDate' => 'required|date',
        ]);

        if ($validator->fails()) {

            date_default_timezone_set('America/cancun');
            $date = date('Y-m-d');

            return redirect()->route('sales.index',['date' => $date]);
        }
        // Obtener la fecha enviada
        $date = $request->input('saleDate');
    
        return redirect()->route('sales.index', ['date' => $date]);
        
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
