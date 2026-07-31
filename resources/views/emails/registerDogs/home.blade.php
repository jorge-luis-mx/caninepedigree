<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>Registro de Perro</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* Reset styles */
        * {
            box-sizing: border-box;
        }

        body, table, td, p, h1, h2, h3 {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
            border: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 32px 24px;
            text-align: center;
        }

        .header h1 {
            font-size: 26px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .header p {
            font-size: 15px;
            margin: 8px 0 0 0;
            opacity: 0.9;
            color: #ffffff;
        }

        .content {
            padding: 40px 32px;
            color: #374151;
            line-height: 1.7;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            padding-bottom: 24px;
        }

        .content p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #4b5563;
        }

        .highlight-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }

        .highlight-name {
            font-size: 17px;
            font-weight: 700;
            color: #1e40af;
            padding-bottom: 8px;
        }

        .highlight-text {
            font-size: 14px;
            color: #6b7280;
        }

        .btn {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
            border: none;
            letter-spacing: 0.25px;
        }

        .btn:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            line-height: 1px;
            font-size: 1px;
        }

        .footer {
            background-color: #f9fafb;
            padding: 24px 32px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-content {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        .footer-links {
            padding-top: 16px;
        }

        .footer-links a {
            color: #4f46e5;
            text-decoration: none;
            margin: 0 12px;
            font-weight: 500;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .company-info {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
        }

        .company-info a {
            color: #6b7280;
            text-decoration: underline;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .container {
                margin: 0 10px;
                border-radius: 8px;
            }

            .header {
                padding: 24px 20px;
            }

            .header h1 {
                font-size: 22px;
            }

            .content {
                padding: 32px 24px;
            }

            .btn {
                width: 100%;
                padding: 16px;
                font-size: 16px;
            }

            .footer {
                padding: 20px 24px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .container {
                background-color: #ffffff;
            }
        }
    </style>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="container" cellpadding="0" cellspacing="0" role="presentation" width="600">
                    <!-- Header -->
                    <tr>
                        <td class="header" align="center">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center">
                                        <h1>Registro Canino</h1>
                                        <p>Sistema de Trazabilidad y Genealogía</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="greeting">Estimado/a propietario/a,</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>Esperamos que se encuentre bien. Le escribimos desde el <strong>Sistema de Registro Canino</strong> para informarle sobre la genealogía de su mascota.</p>
                                    </td>
                                </tr>

                                <!-- Highlight box -->
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <table class="highlight-box" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td align="center">
                                                    <div class="highlight-name">Registro pendiente de confirmación</div>
                                                    <div class="highlight-text">Se ha registrado un descendiente con el nombre <strong>{{ $datos['dog']['name'] ?? '' }}</strong> que indica a su perro como progenitor</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <p>Para mantener la integridad de nuestros registros genealógicos, le invitamos a completar el registro oficial de su perro en nuestro sistema, cuando le sea conveniente.</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <p style="margin-bottom: 8px;">Este proceso nos permite:</p>
                                        <ul style="text-align: left; margin: 0 0 20px 0; padding-left: 20px; color: #4b5563; font-size: 16px;">
                                            <li>Verificar la información genealógica</li>
                                            <li>Mantener registros precisos y confiables</li>
                                            <li>Proteger la integridad del pedigree</li>
                                            <li>Facilitar futuras consultas y certificaciones</li>
                                        </ul>
                                    </td>
                                </tr>

                                <!-- CTA button -->
                                <tr>
                                    <td align="center" style="padding: 24px 0 32px 0;">
                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td align="center" style="border-radius: 8px;" bgcolor="#059669">
                                                    <a href="{{ $datos['url'] ?? '#' }}" class="btn" target="_blank">
                                                        Completar registro
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Additional Notes Section (solo se muestra si hay contenido) -->
                                @if(isset($datos['description']) && !empty(trim($datos['description'])))
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <div style="font-size: 16px; font-weight: 600; color: #0c4a6e; margin-bottom: 12px;">
                                                        Notas adicionales
                                                    </div>
                                                    <div style="font-size: 15px; color: #374151; line-height: 1.6; white-space: pre-wrap;">{{ $datos['description'] }}</div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif

                                <!-- Divider -->
                                <tr>
                                    <td class="divider" style="padding: 0;">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td style="padding-top: 32px;">
                                        <p style="margin-bottom: 8px;"><strong>¿Necesita ayuda?</strong></p>
                                        <p style="font-size: 14px; margin-bottom: 0;">Nuestro equipo de soporte está disponible para asistirle. Puede responder directamente a este correo o escribirnos a <a href="mailto:{{ $datos['support_email'] ?? 'contact@iddr.com.mx' }}" style="color: #4f46e5;">{{ $datos['support_email'] ?? 'contact@iddr.com.mx' }}</a>.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="footer-content" align="center">
                                        <p><strong>Gracias por confiar en nuestro sistema</strong></p>
                                        <p>Registro Canino — Comprometidos con la excelencia en genealogía canina</p>

                                        @if(!empty($datos['help_url']) || !empty($datos['contact_url']) || !empty($datos['privacy_url']))
                                        <table cellpadding="0" cellspacing="0" role="presentation" style="margin: 16px auto 0 auto;">
                                            <tr>
                                                <td class="footer-links">
                                                    @if(!empty($datos['help_url']))<a href="{{ $datos['help_url'] }}">Centro de Ayuda</a>@endif
                                                    @if(!empty($datos['contact_url']))<a href="{{ $datos['contact_url'] }}">Contacto</a>@endif
                                                    @if(!empty($datos['privacy_url']))<a href="{{ $datos['privacy_url'] }}">Política de Privacidad</a>@endif
                                                </td>
                                            </tr>
                                        </table>
                                        @endif

                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" class="company-info">
                                            <tr>
                                                <td style="padding-top: 16px; border-top: 1px solid #e5e7eb;" align="center">
                                                    <!-- <p>{{ $datos['company_name'] ?? 'Registro Canino' }} — {{ $datos['company_address'] ?? '[Dirección física de la empresa]' }}</p> -->
                                                    <p>© 2026 Registro Canino. Todos los derechos reservados.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>