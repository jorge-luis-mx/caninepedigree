<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Perro</title>
    <style>
        body {
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
        }
        table {
            border-spacing: 0;
            width: 100%;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #28a745;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            text-align: center;
            color: #333333;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin-top: 20px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #218838;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }
    </style>
</head>

<body>
    <table class="container" role="presentation" align="center">
        <tr>
            <td>
                <table width="100%" role="presentation">
                    <tr>
                        <td class="header">Registro de su perro</td>
                    </tr>
                    <tr>
                        <td class="content">
                            <p>Estimado propietario,</p>
                            <p>Le informamos que su perro tiene un hijo registrado en nuestro sistema con el nombre <strong>name-hijo</strong>. Para mantener la trazabilidad y autenticidad de los registros, le solicitamos amablemente que registre a su perro.</p>
                            <p>Por favor, haga clic en el siguiente enlace para completar el registro:</p>
                            <a href="{{ $datos['url']}}" class="btn">Registrar ahora</a>
                            <p>Gracias por su colaboración.</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">© 2025 Registro Canino. Todos los derechos reservados.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
