<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuyerRegistrationLink;
use Illuminate\Support\Facades\Crypt;
//model
use App\Models\Dog;
use App\Models\DogSale;

class DogSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {

        
        $dog = Dog::whereRaw('MD5(dog_id) = ?', $id)->firstOrFail();

        return view('dogSales.create', compact('dog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = $user->userprofile;

        $request->validate([
            'dog_id' => 'required|integer',
            'buyer_email' => 'required|email',
            'price' => 'nullable|numeric',
            'payment_method' => 'required|string|in:cash,online,transfer',
        ]);

        $dog = Dog::findOrFail($request->dog_id);

        if ($dog->current_owner_id !== $profile->profile_id) {
            return back()->with('error', 'You are not the owner of this dog.');
        }

        // Evitar que el dueño se venda a sí mismo
        if ($request->buyer_email === $profile->email) {
            return back()->with('error', 'You cannot sell or transfer a dog to yourself.');
        }

        $buyer = $profile::where('email', $request->buyer_email)->first();

        if (!$buyer) {
            // 👤 Comprador no registrado
            $sale = DogSale::create([
                'dog_id' => $dog->dog_id,
                'seller_id' => $profile->profile_id,
                'buyer_id' => null,
                'buyer_email' => $request->buyer_email,
                'price' => $request->price,
                'payment_method' => $request->payment_method,
                'sale_date' => now(),
                'status' => 'pending',
            ]);
            
            Dog::where('dog_id', $request->dog_id)->update(['transfer_pending' => true]);

            $encryptedId = Crypt::encrypt($sale->sale_id);
            $url = url('register?dog_sale=' . urlencode($encryptedId));

            $emailData = [
                'from' => config('mail.from.address'),
                'from_name' => config('mail.from.name', 'Canine'),
                'subject' => 'update owner request',
                'url' => $url,
                'sale' => $sale,
            ];
            
            Mail::to($request->buyer_email)->send(new BuyerRegistrationLink($emailData));
            

            return redirect()->route('dogs.index')
            ->with('success', 'Dog sale completed and ownership transferred.');
        }

        // 👥 Comprador ya registrado
        $sale = DogSale::create([
            'dog_id' => $dog->dog_id,
            'seller_id' => $profile->profile_id,
            'buyer_id' => $buyer->profile_id,
            'buyer_email' => $buyer->email,
            'price' => $request->price,
            'payment_method' => $request->payment_method,
            'sale_date' => now(),
            'status' => 'completed',
        ]);

        // 🔁 Transferencia automática
        $dog->update(['current_owner_id' => $buyer->profile_id]);

        return redirect()->route('dogs.index')
            ->with('success', 'Dog sale completed and ownership transferred.');
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
