<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\LogSheetController;
use App\Http\Controllers\Admin\TransporterController;

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

Route::get('/link-storage', function () {
    Artisan::call('storage:link'); // this will do the command line job
    return('Storage Link Successfull');
});


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

    Route::get('manual-update-1', function () {
        // $invoices = \App\Models\Invoice::with('logSheet')->get();
        // foreach ($invoices as $invoice) {
        //     $invoice->update([
        //         'transporter_id' => $invoice->logSheet->transporter_id,
        //         'vehicle_id' => $invoice->logSheet->vehicle_id,
        //         'destination' => $invoice->logSheet->destination,
        //         'driver_id' => $invoice->logSheet->driver_id,
        //     ]);
        // }

        return 'No updates for now!';
    });
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin|manager']], function () {
    Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
        'transporters' => TransporterController::class,
        'clients' => ClientController::class,
        'locations' => LocationController::class,
        'managers' => ManagerController::class,
    ], ['except' => ['destroy']]);

    Route::resource('invoices', InvoiceController::class)->only('index', 'show', 'update', 'edit', 'destroy');
    Route::delete('invoices/{invoice}/delete-image/', [InvoiceController::class, 'destroyImage'])->name('invoices.image.destroy');
    
    Route::resource('log-sheets', LogSheetController::class)->only('index', 'show');
    Route::resource('imports', ImportController::class)->only('index', 'create', 'store');
    Route::get('imports/download', [ImportController::class, 'download'])->name('imports.download');

    Route::put('drivers/{driver}/update-password', [DriverController::class, 'updatePassword'])->name('drivers.update-password');
    Route::put('transporters/{transporter}/update-password', [TransporterController::class, 'updatePassword'])->name('transporters.update-password');
    Route::put('clients/{client}/update-password', [ClientController::class, 'updatePassword'])->name('clients.update-password');
    Route::put('managers/{manager}/update-password', [ManagerController::class, 'updatePassword'])->name('managers.update-password');


    Route::put('locations/{location}/add-manager', [LocationController::class, 'addManager'])->name('locations.add.manager');
    Route::delete('locations/{location}/remove-manager', [LocationController::class, 'removeManager'])->name('locations.remove.manager');

    Route::put('drivers/{driver}/add-location', [DriverController::class, 'addLocation'])->name('drivers.add.location');
    Route::delete('drivers/{driver}/remove-location', [DriverController::class, 'removelocation'])->name('drivers.remove.location');
    Route::put('vehicles/{vehicle}/add-location', [VehicleController::class, 'addLocation'])->name('vehicles.add.location');
    Route::delete('vehicles/{vehicle}/remove-location', [VehicleController::class, 'removelocation'])->name('vehicles.remove.location');
    Route::put('transporters/{transporter}/add-location', [TransporterController::class, 'addLocation'])->name('transporters.add.location');
    Route::delete('transporters/{transporter}/remove-location', [TransporterController::class, 'removelocation'])->name('transporters.remove.location');



    Route::post('uploads', [UploadController::class, 'store'])->name('uploads.store');
    Route::delete('uploads', [UploadController::class, 'destroy'])->name('uploads.destroy');
});

require __DIR__ . '/auth.php';
