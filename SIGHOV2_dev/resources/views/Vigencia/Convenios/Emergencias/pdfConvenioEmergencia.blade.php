<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONVENIO DE EMERGENCIA</title>
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
                    <img src="logo/ssu.png" width="80" height="80" alt="Logo">
                </td>

                <!-- Columna Central -->
                <td style="text-align: center;">
                    <h3 style="margin: 0; padding: 0;">
                        <ins style="color: #17A2B8;">SEGURO SOCIAL UNIVERSITARIO</ins>
                        <ins style="color: #DC3545;">LA PAZ</ins>
                    </h3>
                    <h4 style="margin: 0; padding: 0;">UNIDAD DE VIGENCIA DE DERECHOS</h4>
                    <h4 style="margin: 0; padding: 0;">CONVENIO DE EMERGENCIA</h4>
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
        <p style="color: #0f396f; text-align: right;"><b>CITE: </b>
            <span
                style="color: black;">C/EM/{{ $convenio->cod_convenio }}/{{ \Carbon\Carbon::parse($convenio->created_at)->format('Y') }}</span>
        </p>

        <table style="text-align: justify; width:100%; border-spacing: 0; margin: 0;">
            <!-- Primera fila: Fecha y CITE -->
            <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS
                DEL ASEGURADO
                POR CONVENIO</th>
            <tr>
                <td style="color: #0f396f; text-align: left;"><b>FECHA DE SOLICITUD: </b></td>
                <td>La Paz,
                    {{ \Carbon\Carbon::parse($convenio->fecha_inicio)->translatedFormat('d \d\e F \d\e Y ') }}
                </td>
            </tr>

            <!-- Segunda fila: Título SOLICITANTE y nombre en la misma línea -->
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>SOLICITANTE:</b>
                </td>
                <td>
                    {{ $convenio->personaConvenio->p_apellido . ' ' . $convenio->personaConvenio->s_apellido . ' ' . $convenio->personaConvenio->nombres }}
                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>CÉDULA DE IDENTIDAD:</b>
                </td>
                <td>
                    {{ $convenio->personaConvenio->ci }}

                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>MATRÍCULA:</b>
                </td>
                <td>
                    {{ $convenio->personaConvenio->matricula_seguro }}

                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>EDAD:</b>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($convenio->personaConvenio->fecha_nacimiento)->age }}

                </td>
            </tr>
        </table>
    </div>

    <div>
        <table style="text-align: justify; width:100%;">
            <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS DEL TITULAR
                DEPENDIENTE</th>
            <tr>
                <td style="color: #0f396f; text-align: left;"><b>NOMBRE COMPLETO: </b></td>
                <td><span style="color: black; text-align: left;">
                        {{ $convenio->titularAutorizaConvenio->titularPersona->p_apellido . ' ' . $convenio->titularAutorizaConvenio->titularPersona->s_apellido . ' ' . $convenio->titularAutorizaConvenio->titularPersona->nombres }}
                    </span>
                </td>
            </tr>

            <!-- Segunda fila: Título SOLICITANTE y nombre en la misma línea -->
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>MATRÍCULA:</b>
                </td>
                <td><span style="color: black;">
                        {{ $matricula_seguro }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>CÉDULA DE IDENTIDAD:</b>
                </td>
                <td><span style="color: black;">
                        {{ $carnet }}
                    </span>
                </td>
            </tr>
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>INSTITUCIÓN:</b>
                </td>
                <td><span style="color: black;">
                        @if ($institucional)
                            {{ $nombre_institucion }}
                        @else
                            APORTANTE VOLUNTARIO
                        @endif
                    </span>
                </td>
            </tr>

        </table>
    </div>

    <div>
        <table style="text-align: justify; width:100%;">
            <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">DATOS DE
                AUTORIZACIÓN</th>
            <tr>
                <td style="color: #0f396f; text-align: left;"><b>NOMBRE COMPLETO: </b></td>
                <td><span style="color: black; text-align: left;">
                        {{ $nombrePersonal }}
                    </span>
                </td>
            </tr>

            <!-- Segunda fila: Título SOLICITANTE y nombre en la misma línea -->
            <tr>
                <td style="color: #0f396f; text-align: left;">
                    <b>CARGO:</b>
                </td>
                <td><span style="color: black;">
                        {{ $cargoPersonal }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table
            style="text-align: justify; width:100%; border-collapse: collapse; border: 1px solid #0f396f; border-spacing: 0; margin: 0;">
            <!-- Primera fila: Fecha y CITE -->
            <th colspan="2" style="text-align: center; border: 1px solid #0f396f; color: #0f396f;">
                DESCRIPCIÓN DE SERVICIOS
            </th>
            <tr>
                <td colspan="2" style="color: #0f396f; text-align: left; border: 1px solid #0f396f;">Se autorizan
                    atenciones en:
                </td>
            </tr>

            <!-- Segunda fila: Título SOLICITANTE y nombre en la misma línea -->
            <tr>
                <td style="color: #0f396f; text-align: center; color: #0f396f; border: 1px solid #0f396f;">
                    <b>ESPECIALIDADES</b>
                </td>
                <td style="color: #0f396f; text-align: center; color: #0f396f; border: 1px solid #0f396f;">
                    <b>SERVICIOS</b>
                </td>
            </tr>
            <tr>
                @if ($especialidades)
                    <td style="border: 1px solid #0f396f;">
                        @foreach ($especialidades as $esp)
                            &bull; {{ $esp->especialidadConvenioEspecialidad->especialidad }}<br>
                        @endforeach
                    </td>
                @endif
                @if ($servicios)
                    <td style="border: 1px solid #0f396f;">
                        @foreach ($servicios as $serv)
                            &bull; {{ $serv->servicioConvenioServicio->servicio }}<br>
                        @endforeach
                    </td>
                @endif
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid #0f396f; color: #0f396f;">
                    @if ($convenio->medicamentos_liname && $convenio->medicamentos_extraliname)
                        Y medicamentos de acuerdo a <b>LINAME</b> y <b>EXTRALINAME</b>.
                    @elseif($convenio->medicamentos_liname)
                        Y medicamentos de acuerdo a <b>LINAME</b>.
                    @elseif($convenio->medicamentos_extraliname)
                        Y medicamentos de acuerdo a <b>EXTRALINAME</b>.
                    @else
                        Sin Medicamentos.
                    @endif
                </td>
            </tr>
            @php
                $lim = $convenio->otras_limitaciones != null && $convenio->otras_limitaciones !== '<p></p>';
                $obs = $convenio->observacion != null && $convenio->observacion !== '<p></p>';
            @endphp

            @if ($convenio->observaciones != null || $convenio->otras_limitaciones != null)
                <tr>
                    @if ($lim && $obs)
                        <th style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                            <b>OTRAS LIMITACIONES</b>
                        </th>
                        <th style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                            <b>OBSERVACIONES</b>
                        </th>
                    @elseif ($lim)
                        <th colspan="2" style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                            <b>OTRAS LIMITACIONES</b>
                        </th>
                    @elseif ($obs)
                        <th colspan="2" style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                            <b>OBSERVACIONES</b>
                        </th>
                    @else
                        <!-- En caso de que no se cumpla ninguna condición, mostrar fila con mensaje -->
                        <th colspan="2" style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                            <b>Sin Observaciones y limitaciones</b>
                        </th>
                    @endif

                </tr>
                <tr>
                    @if ($lim && $obs)
                        <td style="border: 1px solid #0f396f;">
                            <p>{!! $convenio->otras_limitaciones !!}</p>
                        </td>
                        <td style="border: 1px solid #0f396f;">
                            <p>{!! $convenio->observacion !!}</p>
                        </td>
                    @elseif ($lim)
                        <td colspan="2" style="border: 1px solid #0f396f;">
                            <p>{!! $convenio->otras_limitaciones !!}</p>
                        </td>
                    @elseif ($obs)
                        <td colspan="2" style="border: 1px solid #0f396f;">
                            <p>{!! $convenio->observacion !!}</p>
                        </td>
                    @else
                        <!-- En caso de que no se cumpla ninguna condición, mostrar fila con mensaje -->
                        <th colspan="2" style="color: #0f396f; border: 1px solid #0f396f; text-align: center;">
                        </th>
                    @endif
                </tr>
            @endif
        </table>

        <p>En caso de requerir otros servicios adicionales especializados, necesariamente deben ser reportados y
            autorizados por el Seguro Social Universitario La Paz, en base a diagnóstico médico.</p>

        <p>La presente tiene validez a partir de las fecha
            <b><i><u>{{ \Carbon\Carbon::parse($convenio->fecha_inicio)->translatedFormat('d \d\e F \d\e Y ') }}</u></i></b>
            hasta el
            <b><i><u>
                        {{ \Carbon\Carbon::parse($convenio->fecha_fin)->translatedFormat('d \d\e F \d\e Y ') }}
                    </u></i></b>, por un periodo de <b><i><u>{{ $diasValidez }} días</u></i>.</b>
        </p>
    </div>

    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>DERECHOS RESERVADOS {{ $año }} SSU</b><br>
                    Impreso en <b>Fecha: </b>{{ $hoy }}<br>
                    Creado el {{ \Carbon\Carbon::parse($convenio->created_at)->format('d/m/Y H:i:s') }}<br>
                    Por el <b>Usuario: </b>
                    {{ $convenio->usuarioCreador->personaUser->nombres . ' ' . $convenio->usuarioCreador->personaUser->p_apellido . ' ' . $convenio->usuarioCreador->personaUser->s_apellido }}

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
