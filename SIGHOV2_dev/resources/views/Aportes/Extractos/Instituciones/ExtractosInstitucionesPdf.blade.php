<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KARDEX INDIVIDUAL DE INSTITUCIONES</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("{{ asset('Images/SSU2019.png') }}") no-repeat center center fixed;
            background-size: cover;
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
            text-decoration: underline;
        }



        .highlight {
            font-weight: bold;
            text-decoration: underline;
        }



        .details-table td {
            text-align: left;
            padding-left: 20px;
        }

        .summary-table {
            margin-top: 20px;
            width: 100%;
            max-width: 500px;
            font-size: 12px;
            text-align: left;
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
            height: 85px;
            /* Ajusta el alto */
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin: 0 auto;
            /* Centra el div horizontalmente */
            margin-left: 20px;
            /* Margen izquierdo */
            margin-right: 20px;
            /* Margen derecho */
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }

        .contenido {
            margin-bottom: 70px;
        }
    </style>
</head>

<body>
    <div class="footer">

        <table width="100%">
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
    <img src="logo/ssu.png" width="112" height="80" alt="Logo" class="logo-left">
    <div class="header">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="85" height="85"
            class="qr" />

        <h2 style="text-align:center;"><ins>SEGURO SOCIAL UNIVERSITARIO - LA PAZ</ins>
            <br>
        </h2>
        <h3> INSTITUCIÓN: {{ $nombreInstitucion }} </h3>
    </div>

    <table class="table table-bordered" style=" width: 100%; font-size: 11px; text-align:left;">
        <tr>
            <td style="padding-left: 20px;"><b>ZONA:</b> {{ $zona }}</td>
            <td align="left" style="padding-left: 20px;"><b>NIT:</b> {{ $nit }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>DIRECCION:</b> {{ $direccion }} Nº {{ $nroVivienda }}</td>
            <td align="left" style="padding-left: 20px;"><b>Nº EMPLEADOR:</b> {{ $nroEmpleador }}</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;"><b>TELEFONO:</b> {{ $telefono }}</td>
            <td align="left" style="padding-left: 20px;"><b>ACTIVIDAD DEL EMPLEADOR:</b> {{ $actividadEmpleador }}
            </td>
        </tr>
    </table>

    <h3 style="text-align:center;"><ins>KARDEX DE APORTES DEL {{ $fechaInicioExtracto }} AL
            {{ $fechaFinExtracto }}</ins></h3>
    <br>
    <div class="contenido">
        <table class="table table-bordered" style=" width: 100%;text-align:center; font-size: 10px;">
            <thead style="background-color:#f2f2f2 ;">
                <tr>
                    <th>FECHA LIQ.</th>
                    <th>No. CPTE APORTE</th>
                    <th>Nº DE COTIZANTE E</th>
                    <th>Nº DE COTIZANTE P</th>
                    <th>MES</th>
                    <th>SALARIO COTIZABLE</th>
                    <th>APORTE PATRONAL</th>
                    <th>TASA DE INTERES</th>
                    <th>MORA</th>
                    <th>INTERESES</th>
                    <th>MULTAS S/I</th>
                    <th>MULTA NO PRES. PLA.</th>
                    <th>TOTAL INT - MUL</th>
                    <th>BAJAS MEDICAS</th>
                    <th>TOT. APO. CANCELADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($aportesInstitucion as $aporteInstitucion)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($aporteInstitucion->fecha_liquidacion)->format('d/m/Y') }}</td>
                        <td>{{ $aporteInstitucion->nro_registro_comprobante }}</td>
                        <td>{{ $aporteInstitucion->cantidad_eventuales }}</td>
                        <td>{{ $aporteInstitucion->cantidad_permanentes }}</td>
                        <td>{{ $aporteInstitucion->mesComprobante->mes }} - {{ $aporteInstitucion->gestion }}</td>
                        <td>{{ number_format($aporteInstitucion->total_salario_cotizable, 2, ',', '.') }}</td>
                        <td>{{ number_format($aporteInstitucion->importe_aporte_patronal, 2, ',', '.') }}</td>
                        <td>{{ $aporteInstitucion->tasa_interes }} %</td>
                        <td>{{ $aporteInstitucion->dias_mora }}</td>
                        <td>{{ number_format($aporteInstitucion->importe_intereses, 2, ',', '.') }}</td>
                        <td>{{ number_format($aporteInstitucion->importe_multa_interes, 2, ',', '.') }}</td>
                        <td>{{ number_format($aporteInstitucion->importe_multa_no_planilla, 2, ',', '.') }}</td>
                        <td>{{ number_format($aporteInstitucion->subtotal_recargos, 2, ',', '.') }}</td>
                        <td>{{ number_format($aporteInstitucion->monto_bajas_medicas, 2, ',', '.') }}</td>
                        <td><b><u>{{ number_format($aporteInstitucion->total_comprobante, 2, ',', '.') }}</u></b></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <table class="summary-table" align="center">
        <tr>
            <td><b>SALARIO COTIZABLE:</b></td>
            <td>{{ number_format($total_salario_cotizable, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>APORTES PATRONALES CANCELADOS:</b></td>
            <td>{{ number_format($total_aporte_patronal, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>ACTUALIZACION APORTES PATRONALES:</b></td>
            <td>{{ number_format($total_actualizacion_aporte, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b>RECARGOS DE LEY:</b></td>
            <td>{{ number_format($total_recargos_de_ley, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><b><u>TOTAL:</u></b></td>
            <td class="total"><b><u>{{ number_format($total, 2, ',', '.') }}</u></b></td>
        </tr>
    </table> --}}


    <div class="footer">

        <table width="100%">
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
</body>

</html>
