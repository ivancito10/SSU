<!DOCTYPE html>
<style>
    html {
        margin: 2;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 5mm 5mm 5mm 5mm;
        padding-bottom: 90px;
        background: url("{{ asset('Images/SSU2019.png') }}") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    .define {
        width: 960px;
        margin: 0 auto;
    }

    i {
        text-transform: capitalize;
    }

    .header {
        text-align: center;
        margin-bottom: 10px;
        height: 100px;
    }

    .logo-left {
        position: absolute;
        /* Posiciona el logo absolutamente respecto al contenedor */
        top: 10px;
        left: 20px;
        /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
    }

    .qr {
        position: absolute;
        /* Posiciona el logo absolutamente respecto al contenedor */
        top: 10px;
        right: 20px;
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
</style>
<html>

<head>
    <title>REPORTE REEMBOLSO DE BAJAS MÉDICAS</title>

</head>

<body>
    <div class="footer">
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ \Carbon\Carbon::Now()->toDateTimeString() }}<br>
                    <b>Usuario: </b> {{ $usuario }}<br>

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
    <img src="logo/ssu.png" width="115" height="80" alt="Logo" class="logo-left">
    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="85" height="85" class="qr" />
    <div class="header">
        <h2 align="center"> <ins>SEGURO SOCIAL UNIVERSITARIO - LA PAZ</ins></h2>
        <p align="center">
            <b>REEMBOLSO POR SUBSIDIO DE INCAPACIDAD TEMPORAL</b><br>
            <b>DE LA INSTITUCIÓN {{ $datos_institucion->nombre }}</b><br>
            <b>{{ $datos_mes->mes }} DE {{ $datos_comprobante->gestion }}</b><br>
        </p>


    </div>

    <table border="1" style="font-size:10px; border: 1px solid black" align="center" width="100%">
        <thead>
            <tr>
                <th>NOMBRE</th>
                <th>ITEM</th>
                <th>MATRICULA</th>
                <th>Baja Desde</th>
                <th>Baja Hasta</th>
                <th>Días Baja</th>
                <th>Tipo de Baja</th>
                <th>Total Ganado</th>
                <th>Salario Día</th>
                <th>Monto Baja</th>
                <th>IMPORTE POR PAGAR</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datos_bajas as $baja)
                <tr>
                    <td>{{ $baja->p_apellido }} {{ $baja->s_apellido }} {{ $baja->nombres }}</td>
                    <td align="center">{{ $baja->item }}</td>
                    <td align="right">{{ $baja->matricula_seguro }}</td>
                    <td align="right">{{ \Carbon\Carbon::parse($baja->fecha_inicio)->format('d/m/Y') }}</td>
                    <td align="right">{{ \Carbon\Carbon::parse($baja->fecha_fin)->format('d/m/Y') }}</td>
                    <td align="center">{{ $baja->dias_baja }}</td>
                    <td align="right">{{ $baja->tipo }}</td>
                    <td align="right">{{ number_format($baja->total_ganado, 2, ',', '.') }}</td>
                    <td align="right">{{ number_format($baja->total_ganado_dia, 2, ',', '.') }}</td>
                    <td align="right">{{ number_format($baja->monto_baja_medica, 2, ',', '.') }}</td>
                    <td align="right"><b>{{ number_format($baja->monto_baja_medica, 2, ',', '.') }} Bs.</b></td>
                </tr>
            @endforeach

        </tbody>
        <tr>
            <td colspan="10" style="font-size:12px"><b>TOTAL GENERAL BAJAS MÉDICAS {{ $datos_institucion->nombre }}
                    {{ $datos_mes->mes }} DE {{ $datos_comprobante->gestion }}</b></td>
            <td style="font-size:13px" align="right">
                <b>{{ number_format($datos_comprobante->monto_bajas_medicas, 2, ',', '.') }} Bs.</b>
            </td>
        </tr>
    </table>


</body>

</html>
