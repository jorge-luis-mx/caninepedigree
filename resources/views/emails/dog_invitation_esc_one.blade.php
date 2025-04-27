<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Cruza - Registro Requerido</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; padding: 20px;">
    <h2 style="color: #4CAF50;">¡Hola!</h2>

    @php
        $dog = $datos['data'];
    @endphp

    <p>Te han invitado a participar en una cruza de perros a través de nuestra plataforma.</p>

    <p>
        La perra <strong>{{ $dog['dogName'] }}</strong> está interesada en cruzarse con tu perro.
    </p>

    <p>Para continuar con el proceso de cruza, primero debes crear una cuenta en nuestra plataforma y luego registrar a tu perro.</p>

    <p>Una vez registrado, podrás aceptar la solicitud de cruza.</p>


    <p style="text-align: center; margin: 30px 0;">
        <a href="{{ $dog['url'] }}" style="background-color: #4CAF50; color: white; padding: 12px 25px; text-decoration: none; font-size: 16px; border-radius: 5px;">Crear cuenta y registrar a mi perro</a>
    </p>

    <p>Gracias por confiar en nosotros. Si tienes alguna duda o necesitas asistencia, nuestro equipo está disponible para ayudarte.</p>

    <p style="margin-top: 30px;">Saludos cordiales,<br><strong>El equipo de Cruzas</strong></p>
</body>
</html>
