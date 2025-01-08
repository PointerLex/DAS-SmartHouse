<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\SensorReading;
use App\Mail\SensorDisconnectedMail;

class CheckSensorStatus extends Command
{
    protected $signature = 'sensors:check-status';
    protected $description = 'Check if sensors are disconnected';

    public function handle()
    {
        $sensors = SensorReading::all();

        foreach ($sensors as $sensor) {
            if ($sensor->last_seen && $sensor->last_seen->diffInMinutes(now()) > 5) {
                // Enviar correo
                Mail::to(env('ADMIN_EMAIL'))->send(new SensorDisconnectedMail($sensor));

                // Opcional: Actualizar estado del sensor
                $sensor->status = 'Desconectado';
                $sensor->save();
            }
        }

        $this->info('Sensor status checked successfully.');
    }

}
