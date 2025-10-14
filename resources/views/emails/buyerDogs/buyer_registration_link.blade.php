<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dog Transfer Invitation</title>
</head>
<body>
    <h2>Hello!</h2>
    <p>You have been sold or gifted a dog through our system.</p>
    <p>Click the link below to complete your registration and claim ownership:</p>
    <p><a href="{{ $datos['url'] ?? '#' }}">Completar Registro Ahora</a></p>
    <p>If you already have an account, please ignore this email.</p>
</body>
</html>
