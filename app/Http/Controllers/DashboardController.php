<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorReading;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener lecturas recientes
        $readings = SensorReading::orderBy('created_at', 'desc')->paginate(10);

        // Obtener sensores desconectados
        $disconnectedSensors = SensorReading::where('status', 'Desconectado')->get();

        // Pasar ambas variables a la vista
        return view('dashboard', compact('readings', 'disconnectedSensors'));
    }
}
