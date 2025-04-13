<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitación para Cruza</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>¡Hola!</h2>
    @php

        $dog = $datos['data'];

    @endphp

    <p>Te han invitado a participar en una cruza de perros a través de nuestra plataforma.</p>

    <p>
        El perro <strong>{{ $dog['dogName'] }}</strong> ha sido propuesto para una cruza con <strong>{{ $dog['other_dog_name'] }}</strong>.
    </p>

    <p>
        Para continuar con el proceso, por favor registra a tu perro en el sistema haciendo clic en el siguiente enlace:
    </p>

    <p>

    </p>

    <p>Gracias por usar nuestro sistema. Si tienes preguntas, no dudes en contactarnos.</p>

    <p>Saludos,<br><strong>El equipo de Cruzas</strong></p>
</body>
</html>
