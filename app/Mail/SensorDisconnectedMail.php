<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SensorReading;

class SensorDisconnectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sensor;

    public function __construct(SensorReading $sensor)
    {
        $this->sensor = $sensor;
    }

    public function build()
    {
        return $this->subject('Sensor Desconectado')
                    ->view('emails.sensor-disconnected')
                    ->with(['sensor' => $this->sensor]);
    }
}
