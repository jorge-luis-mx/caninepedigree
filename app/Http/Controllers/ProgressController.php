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
use App\Traits\ProgressTrait;


class ProgressController extends Controller
{
    use PricingTrait,ProgressTrait;
    

    public function index()
    {
        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
        
        $progress = $this->findProgress($provider_auth);

        return view('progress/progress-list', compact('progress'));

    }


    public function search()
    {
      
        $user = auth()->user();
        $user_auth = $user->pvr_auth_id;
        $provider_auth = $user->pvr_id;
        
        $progress = $this->findProgress($provider_auth);

        return response()->json($progress);
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
