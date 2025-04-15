<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Cruza</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>¡Hola!</h2>

    @php
        $dog = $datos['data'];
    @endphp

    <p>Te han invitado a participar en una cruza de perros a través de nuestra plataforma.</p>

    <p>
        El perro <strong>{{ $dog['dogName'] }}</strong> ha sido propuesto para una cruza con tu perro <strong>{{ $dog['other_dog_name'] }}</strong>.
    </p>

    <p>
        Para confirmar o rechazar esta solicitud, por favor ingresa a tu cuenta haciendo clic en el siguiente botón:
    </p>

    <p>
        <a href="{{ $dog['login_url'] }}" style="display: inline-block; padding: 12px 24px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Ingresar a mi cuenta
        </a>
    </p>

    <p>Una vez dentro, podrás ver todos los detalles y tomar una decisión.</p>

    <p>Gracias por usar nuestro sistema. Si tienes preguntas, no dudes en contactarnos.</p>

    <p>Saludos,<br><strong>El equipo de Cruzas</strong></p>
</body>
</html>

