<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Middleware\HandleCors;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

//Custom controllers
use App\Http\Controllers\AirportController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProfileAuthenticationController;
use App\Http\Controllers\ProfilePaymentController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\UserGuideController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DogController;

use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//lang
Route::middleware('auth')->get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        if (auth()->check()) {
            $user = auth()->user();
            // Intentamos actualizar el campo locale
            $user->update(['locale' => $locale]);
        } else {
            // Si no está autenticado, guarda el idioma en la sesión
            Session::put('locale', $locale);
        }

        // Establece el idioma para la aplicación
        App::setLocale($locale);
    }
    return back();  // Redirige a la página anterior
});

//admin
Route::get('/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //prifile payments
    Route::get('/profile/payment', [ProfilePaymentController::class, 'edit'])->name('profilePayment.edit');
    Route::put('/profile/payment', [ProfilePaymentController::class, 'update'])->name('profilePayment.update');

    //authentication
    Route::get('/profile/authentication', [ProfileAuthenticationController::class, 'edit'])->name('profileAuthentication.edit');
    Route::patch('/profile/authentication', [ProfileAuthenticationController::class, 'update'])->name('profileAuthentication.update');
    Route::delete('/profile/authentication', [ProfileAuthenticationController::class, 'destroy'])->name('profileAuthentication.destroy');

    //Register a Dog
    Route::get('/dogs', [DogController::class, 'index'])->name('dogs.index');
    Route::get('/dogs/create', [DogController::class, 'create'])->name('dogs.create');
    Route::post('/dogs', [DogController::class, 'store'])->name('dogs.store');
    // Route::get('/dogs/{dog}/edit', [DogController::class, 'edit'])->name('dogs.edit'); 
    // Route::put('/dogs/{dog}', [DogController::class, 'update'])->name('dogs.update'); 
    Route::get('/dogs/{dog}/show', [DogController::class, 'show'])->name('dogs.show');
    Route::delete('/dogs/{dog}', [DogController::class, 'destroy'])->name('dogs.destroy');

    

    //serch
    Route::get('/dogs/search/{reg_no}', [DogController::class, 'searchDog'])->name('dogs.search');

    //payments
    Route::get('/payments/pay/{id}', [PaymentController::class,'pay']);
    Route::post('/payments/paypal/create', [PaymentController::class,'createOrder']);
    Route::get('/payments/paypal/capture/{id}', [PaymentController::class,'captureOrder']);
    Route::post('/payments/paypal/payed', [PaymentController::class,'payedOrder']);
    Route::post('/payments/paypal/pending', [PaymentController::class,'payedOrderPending']);
    Route::get('/payments/paypal/paid', [PaymentController::class,'completed']);












    //airport
    Route::get('/airport', [AirportController::class, 'index'])->name('airport.index');
    Route::get('/airport/create', [AirportController::class, 'create'])->name('airport.create');
    Route::post('/airport/store', [AirportController::class, 'store'])->name('airport.store');
    Route::post('/airport/update-status/{id}', [AirportController::class, 'updateStatus'])->name('airport.updateStatus');
    Route::get('/airport/edit/{id}', [AirportController::class, 'edit'])->name('airport.edit');
    Route::put('/airport/update/{id}', [AirportController::class, 'update'])->name('airport.update');
    Route::delete('/airport/{id}', [AirportController::class, 'destroy'])->name('airport.destroy');
    //map 
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/map/create/{id?}', [MapController::class, 'create'])->name('map.create');
    Route::post('/map/store', [MapController::class, 'store'])->name('map.store');
    Route::get('/map/edit/{id}', [MapController::class, 'edit'])->name('map.edit');
    Route::put('/map/update/{id}', [MapController::class, 'update'])->name('map.update');
    Route::delete('/map/destroy/{id}', [MapController::class, 'destroy'])->name('map.destroy');
    Route::post('/map/update-status/{id}', [MapController::class, 'updateStatus'])->name('map.updateStatus');
    //map update alias
    Route::post('/map/update-alias/{id}', [MapController::class, 'updateAlias'])->name('map.updateAlias');
    Route::put('/map/update/area/{id}', [MapController::class, 'updateArea'])->name('map.updateArea');
    //service
    Route::post('/service/store', [ServiceController::class, 'store'])->name('service.store');

    //serviceType
    Route::get('/service/type', [ServiceTypeController::class, 'index'])->name('serviceType.index');

    //Pricing
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
    Route::get('/prices', [PricingController::class, 'rates'])->name('pricing.rates');
    Route::post('/pricing/store', [PricingController::class, 'store'])->name('pricing.store');

    //operations
    Route::get('/operation/{date?}', [OperationController::class, 'index'])->name('operation.index');
    Route::post('/operations/search', [OperationController::class, 'search'])->name('operations.search');
    Route::get('/operations/show/{id}', [OperationController::class, 'show'])->name('operations.show');

    //sales
    Route::get('/sales/{date?}', [SalesController::class, 'index'])->name('sales.index');
    Route::post('/sales/search', [SalesController::class, 'search'])->name('sales.search');
    Route::get('/sales/view/{id}', [SalesController::class, 'show'])->name('sales.show');
    
    //Progress
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/serch', [ProgressController::class, 'search'])->name('progress.search');

    //guide user
    Route::get('/user-guide', [UserGuideController::class, 'index'])->name('guide.index');

});

require __DIR__ . '/auth.php';
