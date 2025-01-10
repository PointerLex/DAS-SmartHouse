<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Events\Dispatchable;

class SensorDisconnected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Collection $sensors;

    public function __construct(Collection $sensors)
    {
        $this->sensors = $sensors;
    }

    public function broadcastOn()
    {
        return new Channel('sensor-readings'); // Cambia a PrivateChannel si es necesario
    }

    public function broadcastWith()
    {
        return [
            'disconnected_sensors' => $this->sensors->map(function ($sensor) {
                return [
                    'sensor_type' => $sensor->sensor_type,
                    'last_seen' => $sensor->last_seen ? $sensor->last_seen->format('d/m/Y H:i:s') : 'Desconocido',
                ];
            }),
        ];
    }
}
