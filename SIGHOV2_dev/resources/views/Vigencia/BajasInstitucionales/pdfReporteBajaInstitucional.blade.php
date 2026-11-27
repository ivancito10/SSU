<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE - REGISTRO DE BAJAS INSTITUCIONALES</title>
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
            /* margin-bottom: 80px; */
            padding-left: 50px;
            padding-right: 50px;
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
            height: 75px;
            padding-left: 10px;
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
                    <h4 style="margin: 0; padding: 0;">REPORTE DE REGISTRO DE BAJAS INSTITUCIONALES</h4>
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
                <th style="border: 1px solid black;">NOMBRE COMPLETO</th>
                <th style="border: 1px solid black;">MATRÍCULA</th>
                <th style="border: 1px solid black;">INSTITUCIÓN</th>
                <th style="border: 1px solid black;">FECHA DE BAJA DE LA INSTITUCIÓN</th>
                <th style="border: 1px solid black;">FECHA DE BAJA EN EL SSU</th>
                <th style="border: 1px solid black;">FECHA DE REGISTRO DE BAJA</th>
                <th style="border: 1px solid black;">MOTIVO DE LA BAJA</th>
                <th style="border: 1px solid black;">DESCRIPCIÓN MOTIVO</th>
                <th style="border: 1px solid black;">FECHA DE BAJA ASUSS</th>
            </tr>
        </thead>
        <tbody>
            @if ($bajasInstitucionales == [])
                <tr>
                    <td colspan="8" style="border: 1px solid black; text-align:center;">Sin resultados</td>
                </tr>
            @else
                @foreach ($bajasInstitucionales as $index => $bajaInstitucional)
                    <tr>
                        <td style="border: 1px solid black;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid black;">
                            {{ $bajaInstitucional['nombreCompleto'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaInstitucional['matricula'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaInstitucional['institucion'] }}
                        </td>
                        <td style="border: 1px solid black; text-align:center;">
                            {{ \Carbon\Carbon::parse($bajaInstitucional['fechaBajaInstitucion'])->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black; text-align:center;">
                            {{ \Carbon\Carbon::parse($bajaInstitucional['fechaBajaSsu'])->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black; text-align:center;">
                            {{ \Carbon\Carbon::parse($bajaInstitucional['fechaRegistro'])->format('d/m/Y') }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaInstitucional['motivoBaja'] }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $bajaInstitucional['observacion'] }}
                        </td>
                        <td style="border: 1px solid black; text-align:center;">
                            {{ $bajaInstitucional['fechaBajaAsuss']
                                ? Carbon\Carbon::parse($bajaInstitucional['fechaBajaAsuss'])->format('d/m/Y')
                                : '' }}
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
