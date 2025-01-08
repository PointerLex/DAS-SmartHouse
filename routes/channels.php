<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('sensor-readings', function () {
    // Puedes implementar lógica de autenticación aquí si es necesario
    return true; // Permitir que cualquier usuario escuche el canal
});
