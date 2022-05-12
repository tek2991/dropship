<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Driver\DriverInvoiceStatController;
use App\Http\Controllers\Api\v1\Driver\UpdateInvoiceController;
use App\Http\Controllers\Api\v1\Driver\PendingInvoiceController;
use App\Http\Controllers\Api\v1\Driver\UpdatedInvoiceController;
use App\Http\Controllers\Api\v1\Driver\UploadInvoicePhotoController;
use App\Http\Controllers\Api\v1\Auth\DriverApiAuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/driver-login', [DriverApiAuthenticatedSessionController::class, 'store']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [DriverApiAuthenticatedSessionController::class, 'destroy']);

    Route::group(['prefix' => 'driver', 'as' => 'driver.', 'middleware' => ['role:driver']], function () {
        Route::get('driver-invoice-stat', [DriverInvoiceStatController::class, 'index']);
        Route::get('pending-invoices', [PendingInvoiceController::class, 'index']);
        Route::get('updated-invoices', [UpdatedInvoiceController::class, 'index']);
        Route::put('update-invoice/{invoice}', [UpdateInvoiceController::class, 'update']);
        Route::post('upload-invoice-photo/{invoice}', [UploadInvoicePhotoController::class, 'store']);
    });
});
