<?php

namespace App\Events;

use App\Models\SensorReading;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorReadingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reading;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SensorReading $reading)
    {
        $this->reading = $reading;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('sensor-readings');
    }

    public function broadcastWith()
    {
        return [
            'sensor_type' => $this->reading->sensor_type,
            'value' => $this->reading->value,
            'status' => $this->reading->status,
            'last_seen' => $this->reading->last_seen,
        ];
    }


}
