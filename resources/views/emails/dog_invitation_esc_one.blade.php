<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Cruza</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>¡Hola!</h2>

    @php
    
        $dog = $datos['data'];
    @endphp

    <p>Te informamos que un usuario ha solicitado una cruza con tu perro a través de nuestra plataforma.</p>

    <p>
        El perro <strong>{{ $dog['dogName'] }}</strong> ha sido propuesto para una cruza con <strong>{{ $dog['other_dog_name'] }}</strong>.
    </p>

    <p>
        Puedes revisar la solicitud ingresando a tu cuenta y accediendo a la sección de solicitudes de cruza.
    </p>

    <p>Gracias por usar nuestro sistema. Si tienes preguntas, no dudes en contactarnos.</p>

    <p>Saludos,<br><strong>El equipo de Cruzas</strong></p>
</body>
</html>
