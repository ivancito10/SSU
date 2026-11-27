<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE DE POBLACIÓN ASEGURADA</title>
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
            padding-top: 100px;
            padding-bottom: 50px;
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
                    <h4 style="margin: 0; padding: 0;">POBLACIÓN ASEGURADA</h4>
                </td>

                <!-- Columna Derecha -->
                <td style="text-align: center; vertical-align: middle;">
                    <img src="logo/logo2.png" width="95" height="60" alt="Logo">
                </td>
            </tr>
        </table>
    </header>
    <br>

    <table
        style=" width: 80%; font-size: 12px; text-align:left; margin: auto; border:2px solid {{ $color_asegurado }}; border-collapse:collapse;">
        <tr>
            <td colspan="2"
                style="text-align:center; font-size: 14px; background-color: {{ $color_asegurado }}; color: white;">
                <b>DATOS PERSONALES DEL
                    ASEGURADO</b>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : public_path('images/default_afiliacion.jpg') }}"
                    class="img-thumbnail" alt="FTP.{{ $foto }}"
                    style="width:200px; height:200px; object-fit:cover; object-position: top;">
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>CÉDULA DE IDENTIDAD:</b></td>
            <td>{{ $ci }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>NOMBRE COMPLETO:</b></td>
            <td>{{ $nombreCompleto }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>EDAD:</b></td>
            <td>{{ $edad }}</td>
        </tr>
        <tr>
            <td class="text-left" style="padding-left: 20px;"><b>SEXO:</b></td>
            <td>{{ $sexo }}</td>
        </tr>

        <tr>
            <td style="padding-left: 20px;"><b>FECHA DE NACIMIENTO:</b> </td>
            <td>{{ $fecha_nacimiento }}</td>
        </tr>
        <tr>
            <td class="text-left" style="padding-left: 20px;"><b>DIRECCIÓN:</b></td>
            <td>{{ $direccion }}</td>
        </tr>
        <tr>
            <td class="text-left" style="padding-left: 20px;"><b>ZONA:</b></td>
            <td>{{ $zona }}</td>
        </tr>
        <tr>
            <td colspan="2"
                style="text-align:center; font-size: 14px; background-color: {{ $color_asegurado }}; color: white;">
                <b>DATOS REFERENCIALES DEL
                    ASEGURADO</b>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>NOMBRE DE REFERENCIA:</b></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>TELÉFONO DE REFERENCIA:</b></td>
            <td>{{ $telefono_referencia }}</td>
        </tr>
    </table>
    <br>
    <table
        style=" width: 80%; font-size: 12px; text-align:left; margin: auto; border:none; border:2px solid {{ $color_asegurado }}; border-collapse:collapse;">
        <tr>
            <td colspan="2"
                style="text-align:center; font-size: 14px; background-color: {{ $color_asegurado }}; color: white;">
                <b>INFORMACIÓN DEL SEGURO Y CARNET</b>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>MATRÍCULA:</b></td>
            <td>{{ $matricula_seguro }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>TIPO ASEGURADO:</b></td>
            <td>{{ $tipo_asegurado }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>ESTADO VIGENCIA:</b></td>
            <td>{{ $estado_vigencia }}</td>
        </tr>
        <tr>
            @if (
                $tipo_asegurado == 'TITULAR DEL INTERIOR' ||
                    $tipo_asegurado == 'BENEFICIARIO DEL INTERIOR' ||
                    $tipo_asegurado == 'TITULAR CONVENIO')
                <td style="padding-left: 20px;"><b>ESTADO CONVENIO:</b></td>
            @else
                <td style="padding-left: 20px;"><b>ESTADO CARNET:</b></td>
            @endif

            <td>{{ $estado_carnet }}</td>
        </tr>
        <tr>
            @if (
                $tipo_asegurado == 'TITULAR DEL INTERIOR' ||
                    $tipo_asegurado == 'BENEFICIARIO DEL INTERIOR' ||
                    $tipo_asegurado == 'TITULAR CONVENIO')
                <td style="padding-left: 20px;"><b>FECHA INICIO DE CONVENIO:</b> </td>
            @else
                <td style="padding-left: 20px;"><b>FECHA DE EMISIÓN DE CARNET:</b> </td>
            @endif

            <td>{{ $fecha_emision }}</td>
        </tr>
        <tr>
            @if (
                $tipo_asegurado == 'TITULAR DEL INTERIOR' ||
                    $tipo_asegurado == 'BENEFICIARIO DEL INTERIOR' ||
                    $tipo_asegurado == 'TITULAR CONVENIO')
                <td class="text-left" style="padding-left: 20px;"><b>FECHA FIN DE CONVENIO:</b></td>
            @else
                <td class="text-left" style="padding-left: 20px;"><b>FECHA DE VENCIMIENTO DE CARNET:</b></td>
            @endif

            <td>{{ $fecha_vencimiento }}</td>
        </tr>
        <tr>
            <td colspan="2"
                style="text-align:center; font-size: 14px; background-color: {{ $color_asegurado }}; color: white;">
                <b>DATOS INSTITUCIONALES</b>
            </td>
        </tr>
        <tr>


            @if ($tipo_asegurado == 'TITULAR')
                @foreach ($institucionesTitular as $index => $institucionTitular)
        <tr>
            <td class="text-left" style="padding-left: 20px;"><b>INSTITUCIÓN {{ $index + 1 }}:</b></td>
            <td>{{ $institucionTitular }}</td>
        </tr>
        @endforeach
    @else
        <tr>
            @if ($tipo_asegurado == 'TITULAR DEL INTERIOR' || $tipo_asegurado == 'BENEFICIARIO DEL INTERIOR')
                <td class="text-left" style="padding-left: 20px;"><b>SEGURO DE ORIGEN:</b></td>
            @elseif ($tipo_asegurado == 'TITULAR CONVENIO')
                <td class="text-left" style="padding-left: 20px;"><b>GARANTE O TITULAR RESPONSABLE:</b></td>
            @else
                <td class="text-left" style="padding-left: 20px;"><b>INSTITUCIÓN:</b></td>
            @endif
            <td>{{ $institucion }}</td>
        </tr>
        @endif

        @if ($limitaciones)
            <tr>
                <td colspan="2"
                    style="text-align:center; font-size: 14px; background-color: {{ $color_asegurado }}; color: white;">
                    <b>LIMITACIONES</b>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 20px;"><b>LIMITACIONES:</b></td>
                <td>{{ $limitaciones }}</td>
            </tr>
        @endif
    </table>
    <br>

    @if (strpos($tipo_asegurado, 'BENEFICIARIO') !== false)
        @if ($tipo_asegurado != 'BENEFICIARIO DEL INTERIOR')
            <table
                style=" width: 80%; font-size: 12px; text-align:left; margin: auto; border:none; border:2px solid {{ $color_asegurado }}; border-collapse:collapse;">
                <tr>
                    <td colspan="2"
                        style="text-align:center; font-size: 14px; background-color: {{ $color_asegurado }}; color: white;">
                        <b>DATOS DEL TITULAR</b>
                    </td>
                </tr>
                <tr>
                    <td class="text-left" style="padding-left: 20px;"><b>MATRÍCULA:</b></td>
                    <td>{{ $matriculaTitular }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="padding-left: 20px;"><b>CARNET:</b></td>
                    <td>{{ $ciTitular }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="padding-left: 20px;"><b>NOMBRE COMPLETO:</b></td>
                    <td>{{ $nombreTitular }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="padding-left: 20px;"><b>INSTITUCIÓN:</b></td>
                    <td>{{ $institucionTitular }}</td>
                </tr>
                <tr>
                    <td class="text-left" style="padding-left: 20px;"><b>TELÉFONO:</b></td>
                    <td>{{ $telefonoTitular }}</td>
                </tr>
            </table>
        @endif
    @endif

    <div style="text-align: justify; font-size: 12px;">
        <p><i><b>
                    El presente documento tendrá una validez de 24 horas a partir de su emisión.
                    Transcurrido este período, deberá ser renovado o emitido nuevamente en caso de ser requerido para
                    cualquier
                    trámite o gestión.</b></i></p>
    </div>


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
                    {{-- <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em> --}}
                </td>
                <td style="vertical-align: top;">
                    <img src="images/logo_fondo_blanco.png" width="110" height="90" alt="Logo"
                        style="display: block;">
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
