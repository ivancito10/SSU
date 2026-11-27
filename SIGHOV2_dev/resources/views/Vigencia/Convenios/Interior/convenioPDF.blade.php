<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONVENIO INTERIOR</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {

            margin: 15px 25px;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
            display: flex;
            flex-direction: column;

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

        /* Pie de página */
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

        /* Estilo para impresión, asegurando que el pie de página esté en todas las páginas */
        @media print {
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                page-break-before: always;
                /* Asegura que el pie se repita al comienzo de cada nueva página */
            }
        }

        .titulos {
            background-color: #2271b1;
            color: white;
            padding: 5px;
            border: 2px;
            font-size: 11px;
            /* margin-left:5px; */
        }

        .contenido {
            font-size: 11px;
        }

        td {
            padding-left: 8px;
        }

        th {
            padding-left: 8px;
        }

        .unidad {
            color: #2271b1;
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        .footer_table {
            position: absolute;
            bottom: 60px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="document-container" style="text-align: justify; margin: 5px 5px;">
        <!-- Encabezado -->
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <img src="logo/ssu.png" class="logo-left" width="75" height="80" alt="Logo">
            <div style="text-align: center; flex-grow: 1;">
                <h5
                    style="text-decoration: underline; color:#641e16; margin: 0; font-family: Arial, sans-serif; letter-spacing: 1px; ">
                    CONVENIO
                </h5>
                <h5 style=" margin-top: 6px; font-family: Arial, sans-serif; letter-spacing: 1px;font-size:15px;">
                    SEGURO DE ORIGEN: <br>
                    {{ $datosConvenio->seguroInteriorAutorizacionInterior->nombre_seguro }} <br>
                    <div class="unidad">
                        UNIDAD DE VIGENCIA DE DERECHOS
                    </div>
                    C/IN/{{ $datosConvenio->cod_interior }}/{{ \Carbon\Carbon::parse($datosConvenio->created_at)->format('Y') }}
                </h5>
                {{-- @if ($tipo_certificado == false)
                <H3 style=" font-family: Arial, sans-serif; letter-spacing: 1px;"> 
                    NO AFILIACIÓN EMPRESA</H3>
                @elseif($tipo_certificado == true)
                <H3 style=" font-family: Arial, sans-serif; letter-spacing: 1px;">
                    AFILIACIÓN EMPRESA</H3>
                @endif --}}
            </div>
            <img src="logo/logo2.png" class="logo-right" width="130" height="70" alt="Logo">
        </div>

        <!-- Contenido del documento -->
        <div class="document-content">
            <table border="1" style="width: 100%; border-collapse: collapse;">

                <tr class="titulos">
                    <th colspan="2" style="text-align: left; ">DATOS DE VIGENCIA</th>
                </tr>
                <tr>
                    <td class="contenido"><strong>Fecha inicio:</strong></td>
                    <td class="contenido">{{ \Carbon\Carbon::parse($datosConvenio->fecha_inicio)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Fecha fin:</strong></td>
                    <td class="contenido">{{ \Carbon\Carbon::parse($datosConvenio->fecha_fin)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Días de vigencia:</strong></td>
                    <td class="contenido">{{ $diasVigencia }} días.
                    </td>
                </tr>
                <tr class="titulos">
                    {{-- <tr class="p-3 mb-2 bg-primary text-white"> --}}
                    <th colspan="2" style="text-align: left; ">DATOS PERSONALES</th>
                </tr>
                <tr>
                    <td class="contenido"><strong>Matrícula:</strong></td>
                    <td class="contenido">{{ $datosConvenio->personaInteriorAutorizacionInterior->matricula_seguro }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Sr(a):</strong></td>
                    <td class="contenido">
                        {{ $datosConvenio->personaInteriorAutorizacionInterior->p_apellido .
                            ' ' .
                            $datosConvenio->personaInteriorAutorizacionInterior->s_apellido .
                            ' ' .
                            $datosConvenio->personaInteriorAutorizacionInterior->nombres }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Número documento:</strong></td>
                    <td class="contenido">
                        {{ $datosConvenio->personaInteriorAutorizacionInterior->ci }}
                        @if ($datosConvenio->personaInteriorAutorizacionInterior->complemento != null)
                            - {{ $datosConvenio->personaInteriorAutorizacionInterior->complemento }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Fecha de nacimiento:</strong></td>
                    <td class="contenido">
                        {{ \Carbon\Carbon::parse($datosConvenio->personaInteriorAutorizacionInterior->fecha_nacimiento)->format('d/m/Y') }}
                    </td>
                </tr>
                <tr class="titulos">
                    <th colspan="2" style="text-align: left;">AUTORIZADO POR</th>
                </tr>
                <tr>
                    <td class="contenido"><strong>Nombre del seguro:</strong></td>
                    <td class="contenido">{{ $datosConvenio->seguroInteriorAutorizacionInterior->nombre_seguro }}</td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Dirección:</strong></td>
                    <td class="contenido">
                        {{ $datosConvenio->seguroInteriorAutorizacionInterior->residenciaSeguroInterior->direccion }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Nombre del responsable:</strong></td>
                    <td class="contenido">
                        {{ $datosConvenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->p_apellido .
                            ' ' .
                            $datosConvenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->s_apellido .
                            ' ' .
                            $datosConvenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->nombres }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Cédula de identidad:</strong></td>
                    <td class="contenido">
                        {{ $datosConvenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->ci }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Matrícula:</strong></td>
                    <td class="contenido">
                        {{ $datosConvenio->seguroInteriorAutorizacionInterior->personaSeguroInterior->matricula_seguro }}
                    </td>
                </tr>
                <tr class="titulos">
                    <th colspan="2" style="text-align: left;">AUTORIZADO POR EL SEGURO SOCIAL UNIVERSITARIO LA PAZ
                    </th>
                </tr>
                <tr>
                    <td class="contenido"><strong>Nombre completo:</strong></td>
                    <td class="contenido">DR.
                        {{ $datosGerente->personaPersonalSsu->p_apellido .
                            ' ' .
                            $datosGerente->personaPersonalSsu->s_apellido .
                            ' ' .
                            $datosGerente->personaPersonalSsu->nombres }}
                    </td>
                </tr>
                <tr>
                    <td class="contenido"><strong>Cargo:</strong></td>
                    <td class="contenido">{{ $datosGerente->cargo }}</td>
                </tr>
                <tr class="titulos">
                    <th colspan="2" style="text-align: left;">LIMITACIONES</th>
                </tr>
                <tr class="contenido">
                    <td colspan="2">
                        {{ $especialidades . $servicios . ' ' . $liname }}
                    </td>
                </tr>
                @if ($otrasLimitacionesTexto != null || $otrasLimitacionesTexto != '')
                    <tr class="titulos">
                        <th colspan="2" style="text-align: left;">OTRAS LIMITACIONES</th>
                    </tr>
                    <tr class="contenido">
                        <td colspan="2">
                            {!! $otrasLimitacionesTexto !!}
                        </td>
                    </tr>
                @endif
                @if ($observacionesTexto != null || $observacionesTexto != '')
                    <tr class="titulos">
                        <th colspan="2" style="text-align: left;">OBSERVACIONES</th>
                    </tr>
                    <tr class="contenido">
                        <td colspan="2">{!! $observacionesTexto !!}</td>
                    </tr>
                @endif

            </table>
            </br>
            <table>
                <tr class="contenido">
                    <td colspan="2" style="text-align: center; font-style:italic; border:none !important;">
                        LA ATENCIÓN SE EFECTÚA DE ACUERDO A CONVENIO DE <strong>SEGURO INTEGRADO Y
                            RECIPROCIDAD</strong>, ENTRE LOS
                        SEGUROS
                        SOCIALES UNIVERSITARIOS DE BOLIVIA.
                    </td>
                </tr>
            </table>
            <div class="row footer_table">
                <table border="0" style="width: 100%; border-collapse: collapse; text-align:center">
                    <tr>
                        <td width="50%" class="contenido">
                            Firma y Sello<br>Vigencia de Derechos
                        </td>
                        <td width="50%" class="contenido">Firma<br>Paciente</td>
                    </tr>
                </table>
            </div>
        </div>


        <div class="footer">

            <table width="100%">
                <tr>
                    <td style="font-size: 9px; vertical-align: top;">
                        <b>Fecha: </b>{{ $fecha_registro }}<br>
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
    </div>
</body>

</html>
