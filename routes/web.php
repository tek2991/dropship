<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\TransporterController;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('user', UserController::class)->only('show', 'edit', 'update');

    Route::put('user/{user}/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
});


Route::group(['middleware' => ['role:admin']], function () {
    Route::resources([
        'drivers' => DriverController::class,
        'vehicles' => VehicleController::class,
        'transporters' => TransporterController::class,
        'clients' => ClientController::class,
    ]);
});

require __DIR__ . '/auth.php';
