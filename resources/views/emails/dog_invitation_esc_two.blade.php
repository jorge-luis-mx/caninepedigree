<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registro de tu perro solicitado</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>¡Hola!</h2>

    @php

        $dog = $datos['data'];

    @endphp

    <p>Un usuario ha solicitado una cruza con tu perro en nuestra plataforma.</p>

    <p>
        El perro <strong>{{ $dog['dogName'] }}</strong> ha sido propuesto para cruzarse con tu mascota <strong>{{ $dog['other_dog_name'] }}</strong>.
    </p>

    <p>
    Para poder continuar con el proceso, necesitamos que registres a tu perro en el sistema. Por favor, haz clic en el siguiente enlace para completar el registro:
    <a href="{{ $dog['url'] }}">aqui</a>
</p>


    <p>Gracias por usar nuestro sistema. Si tienes preguntas, no dudes en contactarnos.</p>

    <p>Saludos,<br><strong>El equipo de Cruzas</strong></p>
</body>
</html>
