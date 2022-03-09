<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('/drivers', function () {
        return view('drivers');
    })->name('drivers');
    Route::get('/vehicles', function () {
        return view('vehicles');
    })->name('vehicles');
    Route::get('/transporters', function () {
        return view('transporters');
    })->name('transporters');
    Route::get('/clients', function () {
        return view('clients');
    })->name('clients');
});

require __DIR__.'/auth.php';
