<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PREAFILIACIÓN</title>
    <style>
        header {
            margin: 20px 50px 0 50px;
            /* Márgenes específicos para el encabezado */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        div {
            font-size: 12px;
        }

        html,
        body {
            margin: 0;
            padding-top: 110px;
            padding-bottom: 80px;
            padding-left: 70px;
            padding-right: 70px;
            font-family: Arial, sans-serif;
            background: url("{{ asset('Images/SSU2019.png') }}") no-repeat center center fixed;
            background-size: cover;

        }

        /* Pie de página */
        footer {
            margin: 0;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f9fa;
            text-align: center;
            font-size: 14px;
            width: 100%;
            height: 70px;
            padding-left: 5px;
            padding-right: 10px;
        }
    </style>
</head>

<body>

    <header>
        <table style="width: 100%; border-collapse: collapse">
            <tr>
                <!-- Columna Izquierda -->
                <td style="text-align: center; vertical-align: middle;">
                    <img src="logo/ssu.png" width="114" height="80" alt="Logo">
                </td>

                <!-- Columna Central -->
                <td style="text-align: center;">
                    <h3 style="margin: 0; padding: 0;">
                        <ins style="color: #17A2B8;">SEGURO SOCIAL UNIVERSITARIO</ins>
                        <ins style="color: #DC3545;">LA PAZ</ins>
                    </h3>
                    {{--<h4 style="margin: 0; padding: 0;">UNIDAD DE VIGENCIA DE DERECHOS</h4>--}}
                    <h3 style="margin: 0; padding: 0;">PREAFILIACIÓN</h3>
                </td>

                <!-- Columna Derecha -->
                <td style="text-align: center; vertical-align: middle;">
                    {{-- <img src="logo/logo2.png" width="95" height="60" alt="Logo"> --}}
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="100"
                        height="100" />
                </td>
            </tr>
        </table>
    </header>
    <hr>
    <div>
        <h3 style="color: #0f396f; text-align: right;"><b>NRO. PREAFILIACIÓN: </b>
            <span style="color: black;">{{ $nroPreafiliacion }}</span>
        </h3>

        <table style="text-align: justify; width:100%; border-spacing: 0; margin: 0;">
            <!-- Primera fila: Fecha y CITE -->
            <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS
                DE LA PREAFILIACIÓN</th>
            <tr>
                <td style="color: #0f396f; text-align: left;"><b>FECHA DE SOLICITUD: </b></td>
                <td>
                    {{ $fechaSolicitud }}
                </td>
            </tr>

            <tr>
                <td style="color: #0f396f; text-align: left;"><b>FECHA FIN PREAFILIACIÓN: </b></td>
                <td>
                    {{ $fechaFin }}
                </td>
            </tr>
        </table>

        <table style="text-align: justify; width:100%; border-spacing: 0; margin: 0;">
            <!-- Primera fila: Fecha y CITE -->
            <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS
                DEL PREAFILIADO</th>
            <!-- Segunda fila: Título SOLICITANTE y nombre en la misma línea -->
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>PREAFILIADO:</b>
                </td>
                <td>
                    {{ $nombreCompleto }}
                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>CÉDULA DE IDENTIDAD:</b>
                </td>
                <td>
                    {{ $carnet }}

                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>MATRÍCULA:</b>
                </td>
                <td>
                    {{ $matricula }}

                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>FECHA NACIMIENTO:</b>
                </td>
                <td>
                    {{ $fechaNacimiento }}

                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>EDAD:</b>
                </td>
                <td>
                    {{ $edad }}

                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>TIPO DE PREAFILIACIÓN:</b>
                </td>
                <td>
                    {{ $tipoPreafiliacion }}

                </td>
            </tr>
        </table>
    </div>

    @if ($tipoPreafiliacion === 'PREAFILIACION TITULAR')
        <div>
            <table style="text-align: justify; width:100%;">
                <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS DE LA
                    INSTITUCIÓN</th>
                <tr>
                    <td style="color: #0f396f; text-align: left;"><b>INSTITUCIÓN: </b></td>
                    <td><span style="color: black; text-align: left;">
                            {{ $institucion }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    @if ($tipoPreafiliacion === 'PREAFILIACION BENEFICIARIO')
        <div>
            <table style="text-align: justify; width:100%;">
                <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS DEL
                    TITULAR
                    DEPENDIENTE</th>
                <tr>
                    <td style="color: #0f396f; text-align: left;"><b>NOMBRE COMPLETO: </b></td>
                    <td><span style="color: black; text-align: left;">
                            {{ $nombreTitular }}
                        </span>
                    </td>
                </tr>

                <!-- Segunda fila: Título SOLICITANTE y nombre en la misma línea -->
                <tr>
                    <td style="color: #0f396f; text-align: left;">
                        <b>MATRÍCULA:</b>
                    </td>
                    <td><span style="color: black;">
                            {{ $matriculaTitular }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="color: #0f396f; text-align: left;">
                        <b>INSTITUCIÓN:</b>
                    </td>
                    <td><span style="color: black;">
                            {{ $institucion }}
                        </span>
                    </td>
                </tr>

            </table>
        </div>
    @endif

    @if ($tipoPreafiliacion === 'PREAFILIACION TITULAR - APORTANTE VOLUNTARIO')
        <div>
            <table style="text-align: justify; width:100%;">
                <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS DE LA
                    INSTITUCIÓN</th>
                <tr>
                    <td style="color: #0f396f; text-align: left;"><b>INSTITUCIÓN: </b></td>
                    <td><span style="color: black; text-align: left;">
                            {{ $institucion }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <div>
        <table
            style="text-align: justify; width:100%; border-collapse: collapse; border: 1px solid #0f396f; border-spacing: 0; margin: 0;">
            <tr>
                <th style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                    <b>OBSERVACIONES</b>
                </th>
            </tr>
            <tr>
                @if ($obs)
                    <td style="border: 1px solid #0f396f;">
                        <p>{!! $observaciones !!}</p>
                    </td>
                @else
                    <!-- En caso de que no se cumpla ninguna condición, mostrar fila con mensaje -->
                    <th colspan="2" style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                        Sin observaciones
                    </th>
                @endif
            </tr>
        </table>


    </div>

    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>DERECHOS RESERVADOS {{ $año }} SSU</b><br>
                    Impreso en <b>Fecha: </b>{{ $hoy }}<br>
                    Creado el {{ $fechaHoraCreacion }}<br>
                    Por el <b>Usuario: </b>
                    {{ $usuarioCreacion }}

                </td>

                <td class="text-left" style="font-size: 9px; vertical-align: top;">

                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630, La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em>

                </td>
                <td>
                    <img src="images/logo_fondo_blanco.png" width="110" height="70" alt="Logo">
                </td>
            </tr>
        </table>
    </footer>


    {{-- <div style="font-size: 60%; position: absolute; bottom: 0; left: 0; right: 0; text-align: left;">
        <p style="margin: 0;">Derechos Reservados {{ $año }} SIGHOV2 SSU impreso con fecha
            {{ $hoy }} creado el
            {{ \Carbon\Carbon::parse($convenio->created_at)->format('d/m/Y H:i:s') }}</p>
        <p style="margin: 0;">Creado por el usuario
            {{ $convenio->usuarioCreador->personaUser->nombres . ' ' . $convenio->usuarioCreador->personaUser->p_apellido . ' ' . $convenio->usuarioCreador->personaUser->s_apellido }}
        </p><br>
    </div> --}}

</body>

</html>
