<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart House - Sensor desconectado</title>
</head>

<body>
    <p>Hola,</p>
    <p>El sensor de tipo <strong>{{ $sensor->sensor_type }}</strong> se ha desconectado.</p>
    <p>Última conexión: {{ $sensor->last_seen }}</p>
    <p>Por favor, verifica el sistema de inmediato.</p>
</body>

</html>
