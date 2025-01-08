<?php
use App\Models\SensorReading;

SensorReading::create([
    'sensor_type' => 'gas',
    'value' => 200,
    'status' => 'Peligro'
]);

SensorReading::create([
    'sensor_type' => 'luz',
    'value' => 400,
    'status' => 'Luz suficiente'
]);
