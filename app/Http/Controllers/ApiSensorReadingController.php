<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;
use App\Events\SensorReadingUpdated;
use App\Events\SensorDisconnected;

use Illuminate\Support\Facades\Mail;


class ApiSensorReadingController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'sensor_type' => 'required|string|in:gas,luz', // Solo tipos válidos
            'value' => 'required|numeric',
            'status' => 'required|string',
        ]);

        // Crear un nuevo registro en la base de datos
        $reading = SensorReading::create([
            'sensor_type' => $validated['sensor_type'],
            'value' => $validated['value'],
            'status' => $validated['status'],
            'last_seen' => now(),
        ]);

        // Emitir el evento de actualización
        event(new \App\Events\SensorReadingUpdated($reading));

        // Enviar correo si el estado es "Peligro" y el sensor es de tipo gas
        if ($validated['sensor_type'] === 'gas' && $validated['status'] === 'Peligro') {
            Mail::raw(
                "¡Alerta de nivel peligroso detectado!\n\n"
                . "Tipo de sensor: {$validated['sensor_type']}\n"
                . "Nivel: {$validated['value']}\n"
                . "Estado: {$validated['status']}\n\n"
                . "Por favor, tome las medidas necesarias.",
                function ($message) {
                    $message->to(env('ADMIN_EMAIL'))
                            ->subject('Smarthouse alert - ¡Alerta de gas peligroso!');
                }
            );
        }

        // Respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Lectura almacenada correctamente',
            'data' => $reading,
        ], 201);
    }



    public function handleSensorDisconnection($disconnectedSensors)
    {
        // Enviar correo
        foreach ($disconnectedSensors as $sensor) {
            Mail::raw(
                "El sensor {$sensor->sensor_type} está desconectado.",
                function ($message) use ($sensor) {
                    $message->to(env('ADMIN_EMAIL')) // Cambia esto al correo real del administrador
                        ->subject("Alerta: Sensor Desconectado ({$sensor->sensor_type})");
                }
            );
        }

        // Emitir evento para SweetAlert2 en el frontend
        event(new SensorDisconnected($disconnectedSensors));
    }

    public function checkDisconnections()
    {
        // Aquí simulas sensores desconectados
        $disconnectedSensors = [
            ['sensor_type' => 'gas', 'last_seen' => now()->subMinutes(5)->format('d/m/Y H:i:s')],
            ['sensor_type' => 'luz', 'last_seen' => now()->subMinutes(10)->format('d/m/Y H:i:s')],
        ];

        // Devuelve los sensores desconectados como respuesta JSON
        return response()->json([
            'disconnected_sensors' => $disconnectedSensors,
        ]);
    }




    public function simulateDisconnection()
    {
        // Ejemplo de sensores desconectados
        $disconnectedSensors = collect([
            (object) ['sensor_type' => 'gas'],
            (object) ['sensor_type' => 'luz']
        ]);

        // Llama al método que maneja la desconexión
        $this->handleSensorDisconnection($disconnectedSensors);


        return response()->json(['message' => 'Correo enviado por desconexión simulada'], 200);
    }
}
