<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlertController;

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

Route::middleware('api')->group(function () {
    Route::post('/sensor-readings', [\App\Http\Controllers\ApiSensorReadingController::class, 'store']);
    Route::post('/send-alert', [AlertController::class, 'sendAlert']);

});
