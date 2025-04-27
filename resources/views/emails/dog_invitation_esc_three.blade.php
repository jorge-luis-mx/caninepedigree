<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Cruza</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px;">
    <h2 style="color: #4CAF50;">¡Hola!</h2>

    @php
        $dog = $datos['data'];
    @endphp

    <p>Has recibido una solicitud para participar en una cruza de perros a través de nuestra plataforma.</p>

    <p>
        La perra <strong>{{ $dog['dogName'] }}</strong> está interesada en cruzarse con tu perro.
    </p>

    <p><strong>Nota:</strong> Actualmente solo contamos con el nombre de la perra que ha solicitado la cruza. Para avanzar con el proceso, es necesario que registres a tu perro en la plataforma.</p>
    <p><strong>. Solo falta que registres a tu perro para completar la solicitud de cruza.</p>
    <p>
        Por favor registra a tu perro haciendo clic en el siguiente botón:
    </p>

    <p style="text-align: center;">
        <a href="{{ $dog['url'] }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Registrar a mi perro</a>
    </p>

    <p>Si ya has registrado a tu perro, puedes ignorar este mensaje.</p>

    <p>Gracias por confiar en nuestra plataforma. Si tienes alguna pregunta o necesitas ayuda, estamos aquí para asistirte.</p>

    <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Cruzas</strong></p>
</body>
</html>
