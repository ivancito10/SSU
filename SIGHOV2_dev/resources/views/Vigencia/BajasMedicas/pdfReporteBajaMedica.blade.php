<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE BAJAS MÉDICAS</title>
    <style>
        header {
            margin: 20px 50px 0 50px;
            /* Márgenes específicos para el encabezado */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
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
            height: 80px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
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
                    <h4 style="margin: 0; padding: 0;">REPORTE DE BAJAS MÉDICAS</h4>
                </td>

                <!-- Columna Derecha -->
                <td style="text-align: center; vertical-align: middle;">
                    <img src="logo/logo2.png" width="95" height="60" alt="Logo">
                </td>
            </tr>
        </table>
    </header>
    
    <p style="text-align:center; font-size: 14px;">
        DEL {{ $fecha_inicio }} AL {{ $fecha_fin }}
    </p>

    <table style="border: 1px solid black; width: 100%; border-collapse: collapse; font-size: 10px;">
        <thead style="background-color: #E0E0E0; color: black;">
            <tr>
                <th style="border: 1px solid black;">N°</th>
                <th style="border: 1px solid black;">CÓDIGO DE BAJA</th>
                <th style="border: 1px solid black;">FECHA RECEPCIÓN</th>
                <th style="border: 1px solid black;">NOMBRE COMPLETO</th>
                <th style="border: 1px solid black;">MATRÍCULA</th>
                <th style="border: 1px solid black;">INSTITUCIÓN</th>
                <th style="border: 1px solid black;">DÍAS DE BAJA</th>
                <th style="border: 1px solid black;">FECHA INICIO DE BAJA</th>
                <th style="border: 1px solid black;">FECHA FIN DE BAJA</th>
                <th style="border: 1px solid black;">MÉDICO TRATANTE</th>
                <th style="border: 1px solid black;">DIAGNÓSTICO</th>
                <th style="border: 1px solid black;">ENTREGADO A</th>
                <th style="border: 1px solid black;">OBSERVACIONES</th>
            </tr>
        </thead>
        <tbody>
            @if ($bajasMedicas == [])
                <tr>
                    <td colspan="13" style="border: 1px solid black; text-align:center;">Sin resultados</td>
                </tr>
            @else
                @foreach ($bajasMedicas as $index => $bajaMedica)
                    <tr>
                        <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['codigoBaja'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ \Carbon\Carbon::parse($bajaMedica['fecha_recepcion'])->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['nombre_completo'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['matricula'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['institucion'] }}
                        </td>
                        <td style="border: 1px solid black; text-align:center;">
                            {{ $bajaMedica['dias_baja'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ \Carbon\Carbon::parse($bajaMedica['fecha_inicio_baja'])->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ \Carbon\Carbon::parse($bajaMedica['fecha_fin_baja'])->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['medico_tratante'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['diagnostico'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['entregado_a'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaMedica['observaciones'] }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fecha_registro }} <br>
                    <b>Usuario: </b> {{ $nombre_usuario }}<br>
                </td>

                <td class="text-left" style="font-size: 9px; vertical-align: top;">

                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630, La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    
                </td>
                <td>
                    <img src="images/logo_fondo_blanco.png" width="110" height="70" alt="Logo">
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
