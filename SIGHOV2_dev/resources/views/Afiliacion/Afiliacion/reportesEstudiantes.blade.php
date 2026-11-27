<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title> Reporte de estudiante</title>

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
            top: 1px;
            right: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 1px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .texto {
            font-size: 13px;
        }

        .tabla-cabecera {
            background-color: #922b21;
            color: white;
            padding: 2px;
            margin: 2px;
            text-align: center;
            font-size: 13px;
            border-radius: 8px;
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
            <h2 style="margin: 0; padding: 0; font-size: 20px; color: #2c3e50;">Reporte de Afiliación</h2>
            <h4 style="margin: 0; font-weight: normal; color: #7f8c8d;">Registros Generales</h4>
        </div>
        <img src="logo/logo2.png" class="logo-right" width="150" height="80" alt="Logo" style="margin: 0;">
    </div>
    <br>
    <!-- DETALLES -->
    <table style="width: 100%; border-collapse: separate; border-spacing: 10px;">
        <tr>
            <td colspan="2"></td>
            <td style="width: 20%; text-align: right; font-size: 13px;">
                <b>FECHA INICIAL:</b>
            </td>
            <td
                style="width: 30%; text-align: center; font-size: 13px; background-color: #fdedec; border-radius: 8px; padding: 5px;">
                {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <!-- Celdas vacías para alinear a la derecha -->
            <td style="text-align: right; font-size: 13px;">
                <b> BUSQUEDA POR:</b>
            </td>
            <td
                style="text-align: left; font-size: 13px; background-color: #fdedec; border-radius: 8px; padding-left:15px">
                <b>{{ $estado_validar_mostrar }}</b>
            </td>

            <td style="width: 20%; text-align: right; font-size: 13px;">
                <b>FECHA FINAL:</b>
            </td>
            <td
                style="width: 30%; text-align: center; font-size: 13px; background-color: #fdedec; border-radius: 8px; padding: 5px;">
                {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <!-- RESULTADOS -->
    <table style="width: 100%; border-spacing: 5px; border-collapse: separate;">
        <tr>
            <th class="tabla-cabecera">
                NRO
            </th>
            <th class="tabla-cabecera">
                DATOS
                PERSONALES </th>
            <th class="tabla-cabecera">
                MATRICULA
            </th>
            <th class="tabla-cabecera">
                FECHA
            </th>
        </tr>
        @if ($tipo_reporte == 'registros_estudiantes')
            @if ($estudiantes_registrados->isNotEmpty())
                @foreach ($estudiantes_registrados as $registros)
                    <tr>
                        <td class="texto" style="text-align:center">{{ $count = $count + 1 }}</td>
                        <td class="texto" style="text-align:left">{{ $registros->personaEstudiante->nombres }}
                            {{ $registros->personaEstudiante->p_apellido }}
                            {{ $registros->personaEstudiante->s_apellido }}</td>
                        <td class="texto" style="text-align:center">
                            {{ $registros->personaEstudiante->matricula_seguro }}</td>
                        @if ($mostrar_fecha == 1)
                            <td class="texto" style="text-align:center">
                                {{ \Carbon\Carbon::parse($registros->fecha_solicitud_afiliacion)->format('d/m/Y') }}
                            </td>
                        @elseif($mostrar_fecha == 2)
                            <td class="texto" style="text-align:center">
                                {{ \Carbon\Carbon::parse($registros->fecha_afiliacion)->format('d/m/Y') }}
                            </td>
                        @elseif($mostrar_fecha == 3)
                            <td class="texto"style="text-align:center">
                                {{ \Carbon\Carbon::parse($registros->created_at)->format('d/m/Y') }}
                            </td>
                        @else
                            <td class="texto" style="text-align:center">
                                {{ \Carbon\Carbon::parse($registros->created_at)->format('d/m/Y') }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4"
                        style="text-align: center; padding: 1rem; border: 2px dashed #ccc; border-radius: 12px; background-color: #f9fafb; font-family: Arial, sans-serif;">
                        <p style="margin: 0; font-size: 1.2rem; color: #6b7280;">
                            No hay registros emitidos por esas fechas.
                        </p>
                    </td>
                </tr>
            @endif

        @endif
    </table>
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
