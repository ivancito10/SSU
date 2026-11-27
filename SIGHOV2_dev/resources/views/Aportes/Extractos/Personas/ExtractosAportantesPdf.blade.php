<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE KARDEX APORTANTE</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("{{ asset('Images/SSU2019.png') }}") no-repeat center center fixed;

        }



        body {
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            height: 100px;
        }

        .header img {
            float: right;
        }

        h2,
        h1 {
            margin: 0;
            text-align: center;
        }

        h2 ins {
            text-align: center;
            text-decoration: underline;
        }

        h1 ins {
            text-align: center;
            text-decoration: underline;
        }


        .titulo {
            margin-left: 80px;
            padding-top: 20px;
        }

        .qr {
            position: absolute;
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .document-title {
            font-size: 19px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Pie de página */
        /* .footer {
            background-color: #f8f9fa;
            padding-top: 3px;
            text-align: center;
            font-size: 14px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 85px;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin: 0 auto;
            margin-left: 20px;
            margin-right: 20px;
        } */

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 100px;
            font-size: 10px;
            left: 20px;
            right: 20px;
            background-color: #f8f9fa;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }

        .contenido {
            margin-bottom: 50px;
        }
    </style>
</head>

<body>
    <div class="footer">
        <table width="100%" style="margin-top:10px; font-size:10px;">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ \Carbon\Carbon::Now()->toDateTimeString() }}<br>
                    <b>Usuario: </b>{{ ucwords(mb_strtolower($nombreUsuario)) }}<br>

                </td>
                <td align="left" style="font-size: 9px; vertical-align: top;">

                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630 , La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em>
                </td>
                <td align="left">

                    <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="Logo"
                        style="opacity: 0.7;">

                </td>
            </tr>
        </table>
    </div>
    <img src="logo/ssu.png" width="80" height="90" alt="Logo" class="logo-left">
    <div class="header">

        <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="85" height="85"
            class="qr" />

        <h2 class="titulo"><ins>SEGURO SOCIAL UNIVERSITARIO - LA PAZ</ins></h2>
    </div>
    <div class="contenido">

        <table class="table table-bordered" style=" width: 100%; font-size: 11px; text-align:left;">
            <tr>
                <td style="padding-left: 20px;"><b> Nombre Completo:</b></td>
                <td style="padding-left: 20px;"> {{ $nombreCompleto }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;"><b>Número de Documento:</b></td>
                <td style="padding-left: 20px;">{{ $carnet }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;"><b>Fecha de Nacimiento:</b></td>
                <td style="padding-left: 20px;">{{ $fechaNacimiento }}</td>
            </tr>
            <tr>
                <td style="padding-left: 20px;"><b>Edad:</b></td>
                <td style="padding-left: 20px;">{{ $edad }}</td>
            </tr>
        </table>
        <div style=" vertical-align: top;">
            <h3 class="document-title"><ins>KARDEX DE APORTES DEL {{ $fechaInicioExtracto }} AL
                    {{ $fechaFinExtracto }}</ins></h3>

            <table class="table table-bordered" style=" width: 100%;text-align:center; font-size: 11px;">
                <thead style="background-color:#f2f2f2 ;">
                    <tr>
                        <th>MES</th>
                        <th>GESTIÓN</th>
                        <th>SALARIO COTIZABLE</th>
                        <th>APORTE</th>
                        <th>TIPO APORTE</th>
                        <th>TIPO TRABAJADOR</th>
                        <th>INSTITUCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datosExtracto as $datoExtracto)
                        <tr>
                            <td>{{ $datoExtracto['mes'] }}</td>
                            <td>{{ $datoExtracto['gestion'] }}</td>
                            <td>{{ number_format($datoExtracto['salario_cotizable'], 2, ',', '.') }}</td>
                            <td>{{ number_format($datoExtracto['aporte'], 2, ',', '.') }}</td>
                            @if ($datoExtracto['tipo_aporte'] == 'APORTE VOLUNTARIO')
                                <td colspan="3">{{ $datoExtracto['tipo_aporte'] }}</td>
                            @else
                                <td>{{ $datoExtracto['tipo_aporte'] }}</td>
                                <td>{{ $datoExtracto['tipo_trabajador'] }}</td>
                                <td>{{ $datoExtracto['institucion'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</body>

</html>
