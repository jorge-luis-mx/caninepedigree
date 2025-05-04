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
use App\Http\Controllers\BreedingRequestController;
use App\Http\Controllers\PedigreegController;
use App\Http\Controllers\PedigreeController;
use App\Http\Controllers\CertificateController;


use App\Http\Controllers\AdminDogsController;


use App\Http\Controllers\PuppyController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/admin/dogs', [AdminDogsController::class, 'index'])->name('adminDogs.index');
Route::post('/admin/dogs', [AdminDogsController::class, 'store'])->name('adminDogs.store');

Route::middleware('auth')->group(function () {

    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


    //Register a Dog
    Route::get('/dogs', [DogController::class, 'index'])->name('dogs.index');
    Route::get('/dogs/create', [DogController::class, 'create'])->name('dogs.create');
    Route::post('/dogs', [DogController::class, 'store'])->name('dogs.store');
    Route::get('/dogs/show/{dog}', [DogController::class, 'show'])->name('dogs.show');
    Route::delete('/dogs/{dog}', [DogController::class, 'destroy'])->name('dogs.destroy');
    // Route::get('/dogs/{dog}/edit', [DogController::class, 'edit'])->name('dogs.edit'); 
    // Route::put('/dogs/{dog}', [DogController::class, 'update'])->name('dogs.update'); 
    
    //serch
    Route::get('/dogs/search/{reg_no}/{breedingSearch?}', [DogController::class, 'search'])->name('dogs.search');

    //payments
    Route::get('/payments/pay/{id}', [PaymentController::class,'pay']);
    Route::post('/payments/paypal/create', [PaymentController::class,'createOrder']);
    Route::get('/payments/paypal/capture/{id}', [PaymentController::class,'captureOrder']);
    Route::post('/payments/paypal/payed', [PaymentController::class,'payedOrder']);
    Route::post('/payments/paypal/pending', [PaymentController::class,'payedOrderPending']);
    Route::get('/payments/paypal/paid', [PaymentController::class,'completed']);

    Route::get('/pedigrees', [PedigreeController::class, 'index'])->name('pedigree.index');
    Route::get('/pedigrees/{id}', [PedigreeController::class, 'show'])->name('pedigree.show');


    Route::get('/certificates/{id}/pdf/{type}', [CertificateController::class, 'pdf'])->name('certificates.pdf');

    // Route::get('/pedigree', [PedigreegController::class, 'index'])->name('pediree.index');
    //  Route::get('/dogs/pedigree/{id}', [DogController::class, 'showPedigree'])->name('pediree.showPedigree');
    // Route::get('/generate-pdf/{id}/{type}', [PedigreegController::class, 'generatePDF'])->name('pediree.generatePDF');



    //Route::post('/breeding-request', [BreedingController::class, 'store'])->name('breeding.index');
    Route::get('/breeding/request', [BreedingRequestController::class, 'index'])->name('breeding.index');
    Route::get('/breeding/request/create', [BreedingRequestController::class, 'create'])->name('breeding.create');
    Route::post('/breeding/request', [BreedingRequestController::class, 'store'])->name('breeding.store');
    Route::post('/breeding/complete/{id}', [BreedingRequestController::class, 'complete'])->name('breeding.complete');

    
    // Listar completadas
    Route::get('/breeding/completed', [BreedingRequestController::class, 'listCompleted'])->name('breeding.listCompleted');
    // Formulario de subir fotos para una cruza específica
    Route::get('/breeding/{breeding}/upload-photos', [BreedingRequestController::class, 'uploadPhotos'])->name('breeding.uploadPhotos');
    // Guardar fotos
    Route::post('/breeding/{breeding}/store-photos', [BreedingRequestController::class, 'storePhotos'])->name('breeding.storePhotos');


    
    Route::get('/register-puppies', [PuppyController::class, 'register'])->name('puppies.register');
    Route::post('/breeding/{breeding}/store-puppies', [PuppyController::class, 'store'])->name('puppies.store');


    Route::get('/puppies/register', [PuppyController::class, 'create'])->name('puppies.create');
    Route::post('/puppies/validate-breeding', [PuppyController::class, 'validateBreeding'])->name('puppies.validate');
    Route::post('/puppies/register', [PuppyController::class, 'store'])->name('puppies.store');
    

});

require __DIR__ . '/auth.php';
