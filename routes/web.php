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
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\PuppyController;

use Illuminate\Support\Facades\Artisan;
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
Route::get('/clear-cache', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');

    return '✔️ Cachés limpiadas correctamente';
});

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

    Route::get('/admin/pedigree', [AdminDogsController::class, 'pedigree'])->name('adminDogs.pedigree');
    Route::post('/admin/pedigree', [AdminDogsController::class, 'storePedigree'])->name('admin.pedigree');
    Route::get('/admin/pedigree/{dog}/edit', [AdminDogsController::class, 'edit'])->name('admin.pedigree.edit');
    Route::put('/admin/pedigree/edit', [AdminDogsController::class, 'update'])->name('admin.pedigree.update');


    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


    //Register a Dog
    Route::get('/dogs', [DogController::class, 'index'])->name('dogs.index');
    Route::get('/dogs/create', [DogController::class, 'create'])->name('dogs.create');
    Route::post('/dogs', [DogController::class, 'store'])->name('dogs.store');
    Route::get('/dogs/show/{dog}', [DogController::class, 'show'])->name('dogs.show');
    Route::delete('/dogs/{dog}', [DogController::class, 'destroy'])->name('dogs.destroy');
    Route::get('/dog/{dog}/edit', [DogController::class, 'edit'])->name('dogs.edit'); 
    Route::put('/dog/{dog}', [DogController::class, 'update'])->name('dogs.update'); 
    
    //serch
    Route::get('/dogs/search/{reg_no}/{breedingSearch?}', [DogController::class, 'search'])->name('dogs.search');

    //payments
    Route::get('/payments/pay/{id}/{puppies?}', [PaymentController::class,'pay']);
    Route::post('/payments/paypal/create', [PaymentController::class,'createOrder']);
    Route::get('/payments/paypal/capture/{id}', [PaymentController::class,'captureOrder']);
    Route::post('/payments/paypal/payed', [PaymentController::class,'payedOrder']);
    Route::post('/payments/paypal/pending', [PaymentController::class,'payedOrderPending']);
    Route::get('/payments/paypal/paid', [PaymentController::class,'completed']);

    Route::get('/pedigrees', [PedigreeController::class, 'index'])->name('pedigree.index');
    Route::get('/pedigrees/create', [PedigreeController::class, 'create'])->name('pedigree.create');
    Route::get('/pedigrees/{id}', [PedigreeController::class, 'show'])->name('pedigree.show');
    

    Route::get('/certificates/{id}/pdf/{type}', [CertificateController::class, 'pdf'])->name('certificates.pdf');

    // Route::get('/pedigree', [PedigreegController::class, 'index'])->name('pediree.index');
    //  Route::get('/dogs/pedigree/{id}', [DogController::class, 'showPedigree'])->name('pediree.showPedigree');
    // Route::get('/generate-pdf/{id}/{type}', [PedigreegController::class, 'generatePDF'])->name('pediree.generatePDF');

    //Route::post('/breeding-request', [BreedingController::class, 'store'])->name('breeding.index');
    Route::get('/breeding/request', [BreedingRequestController::class, 'index'])->name('breeding.index');
    Route::get('/breeding/request/create/{id?}', [BreedingRequestController::class, 'create'])->name('breeding.create');
    Route::post('/breeding/request', [BreedingRequestController::class, 'store'])->name('breeding.store');
    Route::post('/breeding/complete/{id}', [BreedingRequestController::class, 'complete'])->name('breeding.complete');

    
    // Listar completadas
    Route::get('/breeding/completed', [BreedingRequestController::class, 'listCompleted'])->name('breeding.listCompleted');
    // Formulario de subir fotos para una cruza específica
    Route::get('/breeding/{breeding}/upload-photos', [BreedingRequestController::class, 'uploadPhotos'])->name('breeding.uploadPhotos');
    // Guardar fotos
    Route::post('/breeding/{breeding}/store-photos', [BreedingRequestController::class, 'storePhotos'])->name('breeding.storePhotos');

    Route::get('/breeding/sent', [BreedingRequestController::class, 'listSent'])->name('breeding.listSent');
    
    // Route::get('/register-puppies', [PuppyController::class, 'register'])->name('puppies.register');
    // Route::post('/breeding/{breeding}/store-puppies', [PuppyController::class, 'store'])->name('puppies.store');


    Route::get('/puppies/register', [PuppyController::class, 'index'])->name('puppies.index');
    Route::post('/puppies/validate-breeding', [PuppyController::class, 'validateBreeding'])->name('puppies.validate');
    Route::post('/puppies/register', [PuppyController::class, 'store'])->name('puppies.store');
    

    Route::get('/send-test', function () {
        try {
            // Verificar configuración
            $fromAddress = config('mail.from.address');
            $mailer = config('mail.default');
            $mailgunDomain = config('services.mailgun.domain');
            
            // Verificar que el FROM sea del dominio correcto
            if (!str_contains($fromAddress, 'devscun.com')) {
                return response()->json([
                    'error' => 'FROM address debe ser del dominio devscun.com',
                    'current_from' => $fromAddress,
                    'expected_domain' => 'devscun.com'
                ]);
            }
            
            // Verificar que esté usando Mailgun
            if ($mailer !== 'mailgun') {
                return response()->json([
                    'error' => 'Mailer debe ser mailgun',
                    'current_mailer' => $mailer,
                    'check_env' => 'Verifica MAIL_MAILER=mailgun en .env'
                ]);
            }
            
            Mail::raw('Correo de prueba desde Laravel usando Mailgun con el dominio devscun.com', function ($message) {
                $message->to('jorge06g92@gmail.com')
                        ->subject('Prueba Mailgun - devscun.com verificado');
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Correo enviado correctamente con Mailgun',
                'from_address' => $fromAddress,
                'mailer' => $mailer,
                'mailgun_domain' => $mailgunDomain,
                'timestamp' => now(),
                'to_email' => 'jorge06g92@gmail.com'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'config_debug' => [
                    'mailer' => config('mail.default'),
                    'mailgun_domain' => config('services.mailgun.domain'),
                    'from_address' => config('mail.from.address'),
                    'mailgun_secret_set' => config('services.mailgun.secret') ? 'YES' : 'NO'
                ]
            ]);
        }
    });

});

require __DIR__ . '/auth.php';
