<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading;

class DataLogsController extends Controller
{
    public function index()
    {
        // Obtener todos los registros de sensores con paginación
        $readings = SensorReading::orderBy('created_at', 'desc')->paginate(10);

        // Retornar la vista con los datos
        return view('datalogs.index', compact('readings'));
    }
}
