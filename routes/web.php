<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LogSheetController;
use App\Http\Controllers\Admin\TransporterController;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('docs/postman' , function () {
    return Storage::download('scribe/collection.json');
})->name('scribe.postman');

Route::get('docs/openapi' , function () {
    return Storage::download('scribe/openapi.yaml');
})->name('scribe.openapi');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('user', UserController::class)->only('show', 'edit', 'update');
    Route::put('user/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin']], function () {
    Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
        'transporters' => TransporterController::class,
        'clients' => ClientController::class,
    ], ['except' => ['destroy']]);

    Route::resource('invoices', InvoiceController::class)->only('index', 'show', 'update', 'edit', 'destroy');
    Route::delete('invoices/{invoice}/delete-image/', [InvoiceController::class, 'destroyImage'])->name('invoices.image.destroy');
    
    Route::resource('log-sheets', LogSheetController::class)->only('index', 'show', 'update', 'edit');
    Route::resource('imports', ImportController::class)->only('index', 'create', 'store');
    Route::get('imports/download', [ImportController::class, 'download'])->name('imports.download');

    Route::put('drivers/{driver}/update-password', [DriverController::class, 'updatePassword'])->name('drivers.update-password');
    Route::put('transporters/{transporter}/update-password', [TransporterController::class, 'updatePassword'])->name('transporters.update-password');
    Route::put('clients/{client}/update-password', [ClientController::class, 'updatePassword'])->name('clients.update-password');

    Route::post('uploads', [UploadController::class, 'store'])->name('uploads.store');
    Route::delete('uploads', [UploadController::class, 'destroy'])->name('uploads.destroy');
});

require __DIR__ . '/auth.php';
