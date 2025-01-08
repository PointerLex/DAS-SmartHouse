<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use App\Events\SensorDisconnected;
use Carbon\Carbon;

class SensorStatusController extends Controller
{
    public function checkDisconnectedSensors()
    {
        $threshold = Carbon::now()->subMinutes(5); // Tiempo límite para detectar desconexión
        $disconnectedSensors = SensorReading::where('last_seen', '<', $threshold)->get();

        if ($disconnectedSensors->isNotEmpty()) {
            // Lanza el evento para manejar la desconexión
            event(new SensorDisconnected($disconnectedSensors));
        }

        return response()->json(['message' => 'Revisión completada', 'disconnected_sensors' => $disconnectedSensors]);
    }
}
