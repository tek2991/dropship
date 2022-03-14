<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        Route::get('profile', function () {
            return response()->json(['user' => auth()->user()]);
        });
    });
});
