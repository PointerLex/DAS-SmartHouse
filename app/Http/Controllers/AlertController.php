<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AlertController extends Controller
{
    public function sendAlert(Request $request)
    {
        $validated = $request->validate([
            'sensor_type' => 'required|string',
            'value' => 'required|numeric',
            'status' => 'required|string',
        ]);

        // Solo enviar correo si el estado es "nivel peligroso"
        if ($validated['status'] === 'nivel peligroso') {
            $this->sendAlertEmail($validated);
        }

        return response()->json(['message' => 'Alerta procesada correctamente']);
    }

    private function sendAlertEmail($data)
    {
        $to = env('ADMIN_EMAIL');
        $subject = '¡Alerta de Gas Peligroso!';
        $message = "Se ha detectado un nivel peligroso de gas.\n\n"
                 . "Tipo de sensor: {$data['sensor_type']}\n"
                 . "Nivel de gas: {$data['value']}\n"
                 . "Estado: {$data['status']}\n\n"
                 . "Por favor, tome las medidas necesarias.";

        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to)
                 ->subject($subject);
        });
    }
}
