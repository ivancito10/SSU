<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title> Afiliado Historial Pdf</title>

    <style>
        body {

            margin: 0px 0px;
            padding: 0px;
            background-color: #f4f4f4;
            color: #333;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
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

        .footer {
            background-color: #f8f9fa;
            padding-top: 3px;
            text-align: center;
            font-size: 14px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70px;
            /* Ajusta el alto */
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>

</head>

<body>
    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0;">
        <img src="logo/ssu.png" class="logo-left" width="80" height="80" alt="Logo" style="margin: 0;">
        <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">
            <h3 style=" margin: 3; padding-top:15px">REPORTE </h3>
            @if ($tipo_reporte == 'carnets' || $tipo_reporte == 'carnets_beneficiarios' || $tipo_reporte == 'carnets_estudiantes')
                <h4>CARNETS EMITIDOS </h4>
            @endif
            @if (
                $tipo_reporte == 'registros' ||
                    $tipo_reporte == 'registros_beneficiarios' ||
                    $tipo_reporte == 'registros_estudiantes')
                <h4>NUEVOS REGISTROS </h4>
            @endif
        </div>
        <img src="logo/logo2.png" class="logo-right" width="150" height="80" alt="Logo" style="margin: 0;">
    </div>
    <hr>
    <!-- DETALLES -->
    <table style="width: 100%; ">
        <tr>
            <td style="width: 70%; text-align:right"><b>FECHA INICIAL:</b></td>
            <td style="background-color:#fdedec ;text-align:center; border-radius:10px ">
                {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} </td>
        </tr>
        <br>
        <tr>
            <td style="width: 70%; text-align:right"><b>FECHA FINAL:</b></td>
            <td style="background-color:#fdedec;text-align:center ;border-radius:10px">
                {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }} </td>
        </tr>
    </table>
    <br>
    <!-- RESULTADOS -->
    <!--TABLA DE CARNETS EMITIDOS -->
    @if ($tipo_reporte == 'carnets')
        <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
            <tr>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">NRO
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px; text-align:center "> DATOS
                    PERSONALES </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">MATRICULA
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">FECHA
                </th>
            </tr>
            @foreach ($carnets_emitidos as $carnets)
                <tr>
                    <td style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td style="text-align:left">{{ $carnets->personaHCarnet->nombres }}
                        {{ $carnets->personaHCarnet->p_apellido }} {{ $carnets->personaHCarnet->s_apellido }}</td>
                    <td style="text-align:left">{{ $carnets->personaHCarnet->matricula_seguro }}</td>
                    <td style="text-align:left">{{ \Carbon\Carbon::parse($carnets->created_at)->format('d/m/Y') }}
                    </td>

                </tr>
            @endforeach
        </table>
    @endif
    <!--TABLA DE REGISTROS DE TITULARES GENERADOS -->
    @if ($tipo_reporte == 'registros')
        <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
            <tr>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">NRO
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px; text-align:center "> DATOS
                    PERSONALES </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">MATRICULA
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">FECHA
                </th>

            </tr>
            @foreach ($titulares_registrados as $titulares)
                <tr>
                    <td style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td style="text-align:left">{{ $titulares->titularPersona->nombres }}
                        {{ $titulares->titularPersona->p_apellido }} {{ $titulares->titularPersona->s_apellido }}</td>
                    <td style="text-align:left">{{ $titulares->titularPersona->matricula_seguro }}</td>
                    <td style="text-align:left">{{ \Carbon\Carbon::parse($titulares->created_at)->format('d/m/Y') }}
                    </td>

                </tr>
            @endforeach
        </table>
    @endif
    <!--TABLA DE CARNETS DE BENEFICIARIOS EMITIDOS -->
    @if ($tipo_reporte == 'carnets_beneficiarios')
        <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
            <tr>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">NRO
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px; text-align:center "> DATOS
                    PERSONALES </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">MATRICULA
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">FECHA
                </th>
            </tr>
            @foreach ($carnets_emitidos_beneficiarios as $carnets)
                <tr>
                    <td style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td style="text-align:left">{{ $carnets->personaHCarnet->nombres }}
                        {{ $carnets->personaHCarnet->p_apellido }} {{ $carnets->personaHCarnet->s_apellido }}</td>
                    <td style="text-align:left">{{ $carnets->personaHCarnet->matricula_seguro }}</td>
                    <td style="text-align:left">{{ \Carbon\Carbon::parse($carnets->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
    <!--TABLA DE REGISTROS DE BENEFICIARIOS GENERADOS -->
    @if ($tipo_reporte == 'registros_beneficiarios')
        <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
            <tr>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">NRO
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px; text-align:center "> DATOS
                    PERSONALES </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">MATRICULA
                </th>
                <!--   <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">TIPO
                </th> -->
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">FECHA
                </th>
            </tr>
            @foreach ($beneficiarios_registrados as $carnets)
                <tr>
                    <td style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td style="text-align:left">{{ $carnets->beneficiarioPersona->nombres }}
                        {{ $carnets->beneficiarioPersona->p_apellido }}
                        {{ $carnets->beneficiarioPersona->s_apellido }}</td>
                    <td style="text-align:left">{{ $carnets->beneficiarioPersona->matricula_seguro }}</td>
                    <<!-- td
                        style="text-align:left">{{ $carnets->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado }}
                        </td> -->
                        <td style="text-align:left">{{ \Carbon\Carbon::parse($carnets->created_at)->format('d/m/Y') }}
                        </td>
                </tr>
            @endforeach
        </table>
    @endif
    <!--TABLA DE CARNETS DE ESTUDIANTES EMITIDOS -->
    @if ($tipo_reporte == 'carnets_estudiantes')
        <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
            <tr>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">NRO
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px; text-align:center "> DATOS
                    PERSONALES </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">MATRICULA
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">FECHA
                </th>
            </tr>
            @foreach ($carnets_emitidos_estudiantes as $carnets)
                <tr>
                    <td style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td style="text-align:left">{{ $carnets->personaHCarnet->nombres }}
                        {{ $carnets->personaHCarnet->p_apellido }} {{ $carnets->personaHCarnet->s_apellido }}</td>
                    <td style="text-align:left">{{ $carnets->personaHCarnet->matricula_seguro }}</td>
                    <td style="text-align:left">{{ \Carbon\Carbon::parse($carnets->created_at)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </table>

    @endif

    <!--TABLA DE REGISTROS DE ESTUDIANTES GENERADOS -->
    @if ($tipo_reporte == 'registros_estudiantes')
        <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
            <tr>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">NRO
                </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px; text-align:center "> DATOS
                    PERSONALES </th>
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">MATRICULA
                </th>
                <!--   <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">TIPO
                </th> -->
                <th style="background-color: #922b21; color: white;padding:2px; margin:2px;text-align:center ">FECHA
                </th>
            </tr>
            @foreach ($estudiantes_registrados as $carnets)
                <tr>
                    <td style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td style="text-align:left">{{ $carnets->personaEstudiante->nombres }}
                        {{ $carnets->personaEstudiante->p_apellido }}
                        {{ $carnets->personaEstudiante->s_apellido }}</td>
                    <td style="text-align:left">{{ $carnets->personaEstudiante->matricula_seguro }}</td>

                    {{-- <td
                        style="text-align:left">{{ $carnets->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado }}
                        </td> --}}
                    <td style="text-align:left">{{ \Carbon\Carbon::parse($carnets->fecha_afiliacion)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach
        </table>

    @endif

    <div class="footer">
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>
                    {{ \Carbon\Carbon::parse($fecha_registro)->Format('d /m/ Y') }}<br>
                    <b>Usuario: </b>{{ ucwords(mb_strtolower($nombre_usuario)) }}<br>
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
                <td align="right">
                    <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="Logo"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
