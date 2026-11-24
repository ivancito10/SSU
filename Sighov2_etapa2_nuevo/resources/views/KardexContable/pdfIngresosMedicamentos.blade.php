<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTE KARDEX CONTABLE INGRESOS</title>
    <style>
        header {
            margin: 0 30px 0 30px;
            /* Márgenes específicos para el encabezado */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        html,
        body {
            margin: 0;
            padding-top: 10mm;
            padding-bottom: 30mm;
            padding-left: 10mm;
            padding-right: 10mm;
            font-family: 'Helvetica', 'Arial', sans-serif font-size: 9px;
            background-size: cover;
        }

        .bold-text {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }

        .items th {
            background: #ebf1f5;
            color: #2a3b45;
            font-weight: bold;
            padding: 2px;
            border: 1px solid #2a3b45;
            text-align: center;
        }

        .items td {
            padding: 2px;
            border: 1px solid #2a3b45;
            /*font-family: 'Courier New', Courier, monospace;*/
            font-size: 9px;
        }

        .courier {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black !important;
            border-collapse: collapse;
        }

        .logo {
            display: block;
            /* Asegura que las imágenes se comporten como bloques */
            margin: 0 auto;
            /* Centra las imágenes horizontalmente */
        }

        /* utilidades */
        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .vertical-top {
            vertical-align: top;
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
            height: 85px;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
</head>

<body>
    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fechaActual }} <br>
                    <b>Usuario: </b> {{ $nombreUsuario }}<br>
                </td>

                <td class="text-left" style="font-size: 9px; vertical-align: top;">
                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630 , La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em>
                </td>
                <td>
                    <img src="images/logo_fondo_blanco.png" width="140" height="90" alt="1Logo"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </footer>

    <h3 class="text-center" style="color:#3a5260;"><u>KARDEX CONTABLE - INGRESO DE MEDICAMENTOS</u></h3>
    <p class="text-center">DEL <b>{{ $fechaInicio }}</b> HASTA
        <b>{{ $fechaFin }}</b>
    </p>

    <table class="items" style="width: 100%; font-size: 11px;">
        <thead style="background-color:#eef3f6;">
            <tr>
                <th style="width: 3%;">N°</th>
                <th style="width: 6%;">CÓDIGO</th>
                <th style="width: 15%;">DESCRIPCIÓN</th>
                <th style="width: 12%;">FORMA FARMACÉUTICA</th>
                <th style="width: 6%;">FECHA INGRESO</th>
                <th style="width: 15%;">TIPO DE PROCESO</th>
                <th style="width: 15%;">PROVEEDOR</th>
                <th style="width: 6%;">CANTIDAD</th>
                <th style="width: 6%;">PRECIO UNITARIO</th>
                <th style="width: 6%;">PRECIO TOTAL</th>
                <th style="width: 10%;">CUENTA CONTABLE</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($detalles as $index => $detalle)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">
                        {{ $detalle->codigo ?? '-' }}
                    </td>
                    <td class="text-left">
                        {{ $detalle->descripcion ?? '-' }}
                    </td>
                    <td class="text-left">
                        {{ $detalle->detalle_extra ?? '-' }}
                    </td>
                    <td class="text-center">
                        {{ !empty($detalle->fecha_ingreso) ? date('d/m/Y', strtotime($detalle->fecha_ingreso)) : '-' }}
                    </td>
                    <td class="text-left">
                        {{ $detalle->modalidad_compra ?? '' }}
                        @if (!empty($detalle->numero_ingreso))
                            - N° {{ $detalle->numero_ingreso }}
                        @endif
                    </td>
                    <td class="text-left">
                        {{ $detalle->proveedor ?? '-' }}
                    </td>
                    <td class="text-right">
                        {{ number_format($detalle->cantidad ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($detalle->precio_unitario ?? 0, 2, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($detalle->precio_total ?? 0, 2, ',', '.') }}
                    </td>
                    <td class="text-center">
                        {{ $detalle->cuenta_contable ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No hay registros para este rango de fechas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
