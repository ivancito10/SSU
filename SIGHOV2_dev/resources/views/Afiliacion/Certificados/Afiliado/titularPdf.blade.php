<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> CERTIFICADO TITULAR</title>

    <style>
        body {

            margin: 0px 0px;
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
            /* Habilita posicionamiento relativo para el contenedor */
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Times New Roman', Times, serif
        }

        .document-content {
            line-height: 1, 1;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        .highlight {
            font-weight: bold;
        }

        .recommendation {
            margin-top: 20px;
            font-style: italic;
            color: #555;
        }

        #header {
            position: fixed;
            left: 0px;
            top: -30px;
            right: 0px;
            height: 150px;
            background-color: white;
            text-align: center;
            margin-left: 35px;
        }

        #watermark {
            position: fixed;
            bottom: 0px;
            left: 50px;
            top: 120px;
            opacity: 0.5;
            width: 15cm;
            height: 15cm;
            z-index: -1000;
        }

        i.subrayado {
            text-decoration: overline;
        }

        .interlineado {
            line-height: 80%;
            text-align: center;
        }

        .logo-right {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            right: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }
    </style>
</head>

<body>

    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0;">
        <img src="logo/ssu.png" class="logo-left" width="112" height="80" alt="Logo" style="margin: 0;">
        <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">
            <h1 style="text-decoration: underline; margin: 0;">CERTIFICADO</h1>
            <h2 style="text-align:center; margin: 0;">AFILIADO TITULAR</h2>
        </div>
        <img src="logo/logo2.png" class="logo-right" width="150" height="80" alt="Logo" style="margin: 0;">
    </div>
    <div class="document-content" style="text-align: justify; margin: 50px 50px;">
        <h4 align="left">LA UNIDAD DE AFILIACIÓN DEL SEGURO SOCIAL UNIVERSITARIO.</h4>
        <h2 align="left">CERTIFICA:</h2>
        <p align="right" style="font-weight: bold; font-size: 17px;">
            CERTF.SSU-LP/AFL/{{ $nro_cite }}/{{ $gestion }} </p>

        <p style="text-align: justify; font-size: 15px; font-style: italic;">
            A solicitud del interesado(a) y previa revisión de la Base de Datos de Afiliación del Seguro Social
            Universitario La Paz,
            se evidencia que el(la) Sr(a). detallada específicamente bajo los siguientes
            datos:
        </p>

        <!-- Tabla de información -->
        <table class="table" align="center"
            style="border-collapse: separate; border-spacing: 0 10px; border: 1px solid black; border-radius: 8px; padding: 15px;">
            <tbody>
                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Nombre(s):</td>
                    <td style="font-size: 15px;">{{ $nombre }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Apellidos:</td>
                    <td style="font-size: 15px;">{{ $p_apellido }} {{ $s_apellido }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Fecha de nacimiento:</td>
                    <td style="font-size: 15px;"> {{ $fecha_nacimiento }} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Cédula de identidad:</td>
                    <td style="font-size: 15px;">{{ $ci }} {{ $complemento }} {{ $sigla }} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 15px;">Matrícula:</td>
                    <td style="font-size: 15px;"> {{ $matricula }} </td>
                </tr>
            </tbody>
        </table>

        <p style="text-align: justify; font-size: 15px;  ">
            @if ($nombre_institucion == 'VOLUNTARIO')
                Se encuentra <b>AFILIADO(A) </b> a nuestro Ente Gestor de Salud, en calidad de <b>TITULAR</b>
                desde el <b> {{ $fecha_afiliacion }}</b>, perteneciente como aportante
                <b>{{ $nombre_institucion }}</b>.
                <br>
                Es en cuanto se certifica, para los fines consiguientes.
            @else
                Se encuentra <b>AFILIADO(A) </b> a nuestro Ente Gestor de Salud, en calidad de
                <b>{{ $tipo }}</b>
                desde el <b> {{ $fecha_afiliacion }}</b>, perteneciente a la institución
                <b>{{ $nombre_institucion }}</b>.
                <br>
                Es en cuanto se certifica, para los fines consiguientes.
            @endif
        </p>


        <p>La Paz, {{ $fechaEmision }}.</p>
    </div>

    <div class="document-content"
        style="text-align: center; display: flex; justify-content: space-between; margin: 0 50px; ">
        <table align="left">
            <tr>
                <td align="center" style="margin: 0;">
                    <p style="margin: 0;"><span style="text-decoration: underline;"><b>Seguridad Incorporada
                                :</b></span>
                        <br>
                        <em style="font-size: 10px; text-align:center;"><b> {{ $codigo_seguridad }} </b></em>
                    </p>
                </td>
                <td align="right" style="margin: 0; ">
                    <p style="margin: 0;"><img src="data:image/jpeg;base64,{{ $qrCode }}" alt="Código QR"
                            width="130" height="130"></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="document-content" style="text-align: justify; margin: 0 50px;">
        <p style="font-size: 15px;"><b>Válido hasta:</b>

            {{ \Carbon\Carbon::parse($fecha_caducidad)->Format('d /m/ Y') }}</p>
        <p style="margin: 0;"><b>NOTA :</b>
            <br>La validez del presente documento es de 15 días a partir de la fecha de emisión.
            Este documento puede ser verificado en nuestra página web
            <em style="color:black;"><a href="https://ssulapaz.org/">"https://ssulapaz.org/"</a></em>.
        </p>
    </div>


</body>

</html>
