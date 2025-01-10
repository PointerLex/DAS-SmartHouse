<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiSensorReadingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataLogsController;
use App\Http\Controllers\SensorStatusController;

// Ruta raíz redirigida al Dashboard
Route::get('/', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/datalogs', [DataLogsController::class, 'index'])->name('datalogs.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/check-disconnections', [ApiSensorReadingController::class, 'checkDisconnections']);

    Route::get('/check-sensors', [SensorStatusController::class, 'checkDisconnectedSensors']);


});

// Archivo de autenticación generado por Breeze
require __DIR__.'/auth.php';
