<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CERTIFICADO</title>

    <style>
        body {
            margin: 0px;
            background-color: #f4f4f4;
            color: #333;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
        }

        .document-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Times New Roman', Times, serif;
        }

        .document-content {
            line-height: 1.1;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .logo-right {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .logo-left {
            position: absolute;
            top: 10px;
            left: 20px;
        }
    </style>
</head>

<body>

    <!-- ENCABEZADO -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0;">
        <img src="{{ public_path('logo/ssu.png') }}" class="logo-left" width="112" height="80">

        <div class="document-title" style="flex-grow: 1; margin: 0;">
            <h1 style="text-decoration: underline; margin: 0;">CERTIFICADO</h1>
            <h2 style="margin: 0;">NO AFILIACIÓN</h2>
            <h3 style="text-align:center; margin: 0;">SEGURO SOCIAL UNIVERSITARIO LA PAZ</h3>
        </div>

        <img src="{{ public_path('logo/logo2.png') }}" class="logo-right" width="150" height="80">
    </div>

    <!-- CONTENIDO -->
    <div class="document-content" style="text-align: justify; margin: 50px 50px;">

        <p align="right" style="font-weight: bold; font-size: 17px;">
            {{-- {{ $datos['codigo_certificado'] }} --}}
        </p>

        <p style="font-size: 15px; font-style: italic;">
            La Unidad de Afiliaciones del Seguro Social Universitario La Paz, en uso de sus
            facultades y a solicitud del interesado(a), certifica que según la revisión de la
            Base de Datos de Afiliación:
        </p>
        <br><br>
        <!-- DATOS PERSONALES -->
        <table align="center"
            style="border-collapse: separate; border-spacing: 0 10px; border: 1px solid black;
            border-radius: 8px; padding: 15px;">

            <tbody>
                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Nombre(s):</td>
                    <td style="font-size: 15px;text-transform: uppercase;">
                        {{ $datos['nombres'] }}
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Apellidos:</td>
                    <td style="font-size: 15px;text-transform: uppercase;">
                        {{ $datos['apellido_paterno'] }} {{ $datos['apellido_materno'] }}
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Fecha de nacimiento:</td>
                    <td style="font-size: 15px;">
                        {{ \Carbon\Carbon::parse($datos['fecha_nacimiento'])->format('d/m/Y') }}
                    </td>
                </tr>

                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Cédula de identidad:</td>
                    <td style="font-size: 15px;">
                        {{ $datos['ci'] }}
                    </td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <!-- TEXTO PRINCIPAL -->
        <p style="font-size: 15px; font-style: italic; margin-top: 20px;">
            Se verifica que el(la) Sr(a).
            <b style="text-transform: uppercase">
                {{ $datos['apellido_paterno'] }} {{ $datos['apellido_materno'] }} {{ $datos['nombres'] }}
            </b>,
            <b>NO se encuentra afiliado(a)</b> en nuestra Institución.
        </p>

        <p style="font-size: 15px;">
            Es cuanto se certifica, para los fines consiguientes.
        </p>
        <br><br>
        <p style="font-size: 15px;" align="right"><b><em>
                    La Paz, {{ \Carbon\Carbon::now()->format('d') }} de
                    {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('F') }} de
                    {{ \Carbon\Carbon::now()->format('Y') }}.</em></b>
        </p>

        <!-- BLOQUE FINAL: SEGURIDAD + QR -->
        <table width="100%" style="margin-top: 30px;">
            <tr>
                <td align="left" style="vertical-align: top;">
                    <p style="margin: 0;">
                        <span style="text-decoration: underline; font-weight: bold;">
                            Seguridad Incorporada :
                        </span><br>
                        <em style="font-size: 11px;">
                            <b>{{ $codigo_seguridad ?? '' }}</b>
                        </em>
                    </p>
                </td>

                <td align="right">
                    <img src="data:image/png;base64,{{ $qrCode ?? '' }}"
                         width="130" height="130" alt="QR">
                </td>
            </tr>
        </table>

        <p style="font-size: 15px; margin-top: 10px;">
            <b>Válido hasta:</b>
            {{ \Carbon\Carbon::now()->addDays(15)->format('d /m/ Y') }}
        </p>

        <p style="font-size: 14px; text-align: justify;">
            <b>NOTA:</b> La validez del presente documento es de 15 días a partir de la fecha
            de emisión. Este documento puede ser verificado en Nuestra Página Web
            <a href="http://www.ssulapaz.org" style="color: blue;">
                http://www.ssulapaz.org
            </a>.
        </p>

    </div>

</body>

</html>
