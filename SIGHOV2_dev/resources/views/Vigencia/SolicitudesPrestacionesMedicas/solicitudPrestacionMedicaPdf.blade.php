<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title> SOLICITUD DE PRESTACIONES MEDICAS</title>
    <style>
        body {
            margin: 0;
            padding: 0;

        }

        .document-medio {
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            padding: 0;
        }

        p,
        td {
            font-family: "Times New Roman";
            font-size: 90%;
        }

        .document-title {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 0;
            font-family: 'Times New Roman', Times, serif
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 0px;
            left: 10px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-right {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            right: 5px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0; ">
        <img src="logo/ssu.png" class="logo-left" width="80" height="80" alt="Logo" style="margin: 6;">
        <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">
            <br>
            <p style=" margin: 0; color:#0f396f"><b>SEGURO SOCIAL UNIVERSITARIO</b> </p>
            <p style=" margin: 0; color:#0f396f">LA PAZ - BOLIVIA </p>
            <h5 style=" margin: 0; color:#772005">AUTORIZACIÓN DE PRESTACIONES MÉDICAS </h5>
        </div>
        <img src="data:image/png;base64,{{ $qrCode }}" class="logo-right" width="100" height="100"
            alt="Logo" style="margin: -8 0 1 0;">
    </div>
    <hr>
    <div class="document-cabeza" style="text-align: center; flex-grow: 1; margin: 2;">
        <table width="100%">
            <tr>
                <td style="color: #0f396f; text-align: right;"><b>A: </b></td>
                <td style="text-align: left;font-size:90% ">
                    {{ $solicitud->seguroInteriorSolicitudPMedica->nombre_seguro }} </td>
                <td> </td>
                <td style="color: #0f396f; text-align: right;"><b>CITE:</b> </td>
                <td style="text-align: left; font-size:90%"> VVDD/GS/{{ $solicitud->cod_cite }}/{{ $añoCreated }}
                </td>

            </tr>
            <tr>
                <td style="color: #0f396f; text-align: right;"><b>DE:</b> </td>
                <td style="text-align: left;font-size:90% "> SEGURO SOCIAL UNIVERSITARIO LA PAZ</td>
                <td> </td>
                <td style="color: #0f396f; text-align: right;"><b>FECHA:</b> </td>
                <td style="text-align: left; font-size:90%"> La Paz,
                    {{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->translatedFormat('d \d\e F \d\e Y ') }}</td>
            </tr>
        </table>
    </div>
    <hr>

    <div class="document-medio">
        <strong style="color:#0f396f">SOLICITUD PARA: </strong>
        @foreach ($solicitantes as $index => $sol)
            <p style="margin: 5px 0; font-size:90%">
                @if ($titularSwitch == 0)
                    &bull;
                    <b>{{ $sol->personaAseguradoSolicitante->nombres . ' ' . $sol->personaAseguradoSolicitante->p_apellido . ' ' . $sol->personaAseguradoSolicitante->s_apellido }}</b>
                    con matricula <b>{{ $sol->personaAseguradoSolicitante->matricula_seguro }}</b> -
                    {{ $sol->personaAseguradoSolicitante->tipoAseguradoPersona->tipo_asegurado }}
                @else
                    @if ($index > 0)
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                    @else
                        &bull;
                    @endif
                    <b>{{ $sol->personaAseguradoSolicitante->nombres . ' ' . $sol->personaAseguradoSolicitante->p_apellido . ' ' . $sol->personaAseguradoSolicitante->s_apellido }}</b>
                    con matricula <b>{{ $sol->personaAseguradoSolicitante->matricula_seguro }}</b> -
                    {{ $sol->personaAseguradoSolicitante->tipoAseguradoPersona->tipo_asegurado }}
                @endif

            </p>
        @endforeach
        @if ($titularSwitch === 0)
            <p>Beneficiario(s) del Titular:
                <b><i>{{ $sol->personaAseguradoTitular->nombres . ' ' . $sol->personaAseguradoTitular->p_apellido . ' ' . $sol->personaAseguradoTitular->s_apellido }}</i></b>
                con matricula <b><i>{{ $sol->personaAseguradoTitular->matricula_seguro }}</i></b>
            </p>
        @endif
        <strong style="color:#0f396f">DESCRIPCIÓN DE SERVICIOS: </strong>
        <p> Con cargo al Seguro Social Universitario La Paz, agradecemos a usted se realice la atención en:</p>
        <table width="100%" align="center" style="border-collapse: collapse;" class="no-break">
            <tr>
                <th style="border: 1px solid black; text-align:center;font-size:80%"><b>ESPECIALIDADES</b></th>
                <th style="border: 1px solid black;text-align:center;font-size:80%"><b>SERVICIOS</b></th>
            </tr>
            <tr>
                <td style="border: 1px solid black; font-size:80%">
                    @foreach ($especialidades as $esp)
                        &bull; {{ $esp->especialidadSolMedicaEspecialidad->especialidad }}<br>
                    @endforeach
                </td>
                <td style="border: 1px solid black; font-size:80%">
                    @foreach ($servicios as $ser)
                        &bull; {{ $ser->servicioSolMedicaServicio->servicio }}<br>
                    @endforeach
                </td>
            </tr>
        </table>

        <p>
            @if ($solicitud->medicamentos_liname && $solicitud->medicamentos_extraliname)
                Y medicamentos de acuerdo a <b>LINAME</b> y <b>EXTRALINAME</b>.
            @elseif($solicitud->medicamentos_liname)
                Y medicamentos de acuerdo a <b>LINAME</b>.
            @elseif($solicitud->medicamentos_extraliname)
                Y medicamentos de acuerdo a <b>EXTRALINAME</b>.
            @endif
        </p>

        <table width="100%"; align="center" style="border-collapse: collapse;" class="no-break">
            <tr>
                @if ($solicitud->otras_limitaciones == null || $solicitud->otras_limitaciones === '<p></p>')
                @else
                    <th style="border: 1px solid black;text-align:center;font-size:80% "><b>OTRAS LIMITACIONES</b></th>
                @endif
                @if ($solicitud->observacion == null || $solicitud->observacion === '<p></p>')
                @else
                    <th style="border: 1px solid black; text-align:center;font-size:80%"><b>OBSERVACIONES</b></th>
                @endif
            </tr>
            <tr>
                @if ($solicitud->otras_limitaciones == null || $solicitud->otras_limitaciones === '<p></p>')
                @else
                    <td style="border: 1px solid black; ">
                        <p>{!! $solicitud->otras_limitaciones !!}</p>
                    </td>
                @endif
                @if ($solicitud->observacion == null || $solicitud->observacion === '<p></p>')
                @else
                    <td style="border: 1px solid black; ">
                        <p>{!! $solicitud->observacion !!}</p>

                    </td>
                @endif
            </tr>
        </table>
        <p>La presente tiene una validez de <b><i><u>{{ $diasValidez }} días</u></i></b> a partir de la fecha
            <b><i><u>{{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->translatedFormat('d \d\e F \d\e Y ') }}</u></i></b>
            al
            <b><i><u>{{ \Carbon\Carbon::parse($solicitud->fecha_fin)->translatedFormat('d \d\e F \d\e Y ') }}</u></i></b>
        </p>
        <p> {{-- En caso de requerir otros servicios adicionales especializados, necesariamente deben ser reportados y
            autorizados por el Seguro Social Universitario La Paz, en base a diagnóstico médico. Los gastos que demanden
            su atención, favor remitir
            detalle a nombre del Seguro Social Universitario La Paz. --}}
            <b>
                <i>En caso de requerir atención médica especializada, internación, cirugías y estudios especializados,
                    se debe solicitar la autorización previa a este seguro, con remisión de informe
                    médico.
                </i>
            </b>
        </p>
        <p>
            <b><i>Todo asegurado y/o beneficiario, deberá presentar su carnet de asegurado vigente, al momento de
                    solicitar cualquier atención médica.
                </i></b>
        </p>
    </div>

    </div>
    <div style="font-size: 60%; position: absolute; bottom: 0; left: 0; right: 0; text-align: left;">
        <p style="margin: 0;">Derechos Reservados {{ $año }} SIGHOV2 SSU impreso con fecha
            {{ $hoy }} creado el
            {{ $solicitud->created_at }}</p>
        <p style="margin: 0;">Creado por el usuario
            {{ $solicitud->usuarioCreador->personaUser->nombres . ' ' . $solicitud->usuarioCreador->personaUser->p_apellido . ' ' . $solicitud->usuarioCreador->personaUser->s_apellido }}
        </p>
    </div>
</body>

</html>
