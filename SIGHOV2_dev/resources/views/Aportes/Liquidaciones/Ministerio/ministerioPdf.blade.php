<!DOCTYPE html>
<style>
    html {
        margin: 0;
    }

    body {
        font-family: Arial, sans-serif;
        margin: 5mm 5mm 0mm 5mm;
    }

    footer {
        position: fixed;
        left: 5px;
        bottom: 0px;
        height: 30px;
        width: 100%;
        color: blue;
        text-align: center;
    }

    .define {
        width: 960px;
        margin: 0 auto;
    }

    i {
        text-transform: capitalize;
    }

    .footer {
        position: fixed;
        bottom: 0;
        right: 0;
        font-size: 60%;
        padding: 10px;
    }

    .logo {
        position: absolute;
        left: 10px;
    }

    .qr {
        position: absolute;
        right: 15px;
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

    .contenido {
        margin-bottom: 80px;
    }
</style>

<html>

<head>
    <title>LIQUIDACIÓN DE APORTES AL ASUSS</title>
</head>

<body>
    <div class="footer">

        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fechaActual }}<br>

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
    <img src="data:image/png;base64,{{ $qrCode }}" width="85" height="85" class="qr" />
    <div class="cabecera">
        <img class="logo" src="logo/ssu.png" width="112" height="80">
        <div>
            <h4 align="center" style="text-decoration:underline;">SEGURO SOCIAL UNIVERSITARIO - LA PAZ BOLIVIA</h4>
            <h3 style="margin: 0; padding: 0;" align="center"> LIQUIDACIÓN DE APORTES AL MINISTERIO DE SALUD </h3>
            <h5 style="margin: 0; padding: 0;" align="center"> CORRESPONDIENTE AL MES DE {{ $mes_literal }}
                {{ $gestion }} </h5>
            <h5 style="margin: 0; padding: 0;" align="center"> PERCIBIDO AL MES DE {{ $mes_literalHoy }}
                {{ $gestionActual }} </h5>
        </div>
    </div>
    <br><br>

    <div class="contenido">
        <div class="table-responsive">
            <table cellspacing="0" class="table table-striped table-bordered table-condensed table-hover" width="100%"
                border="2" align="center" style="font-size:10px">
                <tr class="bg-danger">
                    <td align="center"><b>FECHA <BR> LIQUI.</b></td>
                    <td align="center"><b>CPTE. <BR> APTE</b></td>
                    <td align="center"><b>INSTITUCIONES</b></td>
                    <td align="center"><b>MES</b></td>
                    <td align="center"><b>Nro <br> DE <br> COTIZ.</b></td>
                    <td align="center"><b>SALARIO <BR> COTIZABLE</b></td>
                    <td align="center"><b>APORTE <BR> PATRONAL</b></td>
                    <td align="center"><b>APORTE <BR> 5%</b></td>
                </tr>
                <tr>
                    <td colspan='8' align="center"><B><I><U>RENTISTAS</U></I></B></td>
                </tr>
                @foreach ($rentista as $ren)
                    <tr>
                        <td align="center"> {{ $ren->fecha_liquidacion }}</td>
                        <td align="center">{{ $ren->nro_registro_comprobante }}</td>

                        <td align="left">{{ $ren->nombre_institucion }} </td>
                        <td align="center"> {{ $ren->mes }}-{{ $ren->gestion }} </td>
                        <td align="center"> {{ $ren->cantidad_permanentes + $ren->cantidad_eventuales }}</td>
                        <td align="center">{{ $ren->total_salario_cotizable }}</td>
                        <td align="center">{{ $ren->total_comprobante }}</td>
                        <td align="center">{{ number_format($ren->total_comprobante * 0.05, 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan='8' align="center"><B><I><U>INSTITUCIONES AFILIADAS</U></I></B></td>
                </tr>
                @foreach ($patronal as $patro)
                    <tr>
                        <td align="center"> {{ $patro->fecha_liquidacion }}</td>
                        <td align="center">{{ $patro->nro_registro_comprobante }}</td>
                        <td align="left">{{ $patro->nombre_institucion }} </td>
                        <td align="center"> {{ $patro->mes }}-{{ $patro->gestion }} </td>
                        <td align="center"> {{ $patro->cantidad_permanentes + $patro->cantidad_eventuales }}</td>
                        <td align="center">{{ number_format($patro->total_salario_cotizable, 2) }}</td>
                        <td align="center">{{ number_format($patro->total_comprobante, 2) }}</td>
                        <td align="center">{{ number_format($patro->total_comprobante * 0.05, 2) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan='8' align="center"><B><I><U>TRASPASOS DE APORTES</U></I></B></td>
                </tr>
                @foreach ($traspaso as $tras)
                    <tr>
                        <td align="center"> {{ $tras->fecha_liquidacion }}</td>
                        <td align="center">{{ $tras->nro_registro_comprobante }}</td>

                        <td align="left">{{ $tras->nombre_institucion }} </td>
                        <td align="center"> {{ $tras->mes }} - {{ $tras->gestion }} </td>
                        <td align="center"> {{ $tras->cantidad_permanentes + $tras->cantidad_eventuales }}</td>
                        <td align="center">{{ number_format($tras->total_salario_cotizable, 2) }}</td>
                        <td align="center">{{ number_format($tras->total_comprobante, 2) }}</td>
                        <td align="center">{{ number_format($tras->total_comprobante * 0.05, 2) }}</td>
                    </tr>
                @endforeach
                <!--VOLUNTARIOS-->
                <tr>
                    <td colspan='8' align="center"><B><I><U>VOLUNTARIOS</U></I></B></td>
                </tr>
                @foreach ($voluntarios as $vol)
                    <tr>
                        <td align="center"> {{ $vol->fecha_liquidacion }}</td>
                        <td align="center">{{ $vol->nro_registro_comprobante }}</td>

                        <td align="left">{{ $vol->nombres }} {{ $vol->p_apellido }} {{ $vol->s_apellido }}</td>
                        <td align="center"> {{ $vol->mes }} - {{ $vol->gestion }} </td>
                        <td align="center"> {{ $vol->cantidad_permanentes + $vol->cantidad_eventuales }}</td>
                        <td align="center">{{ number_format($vol->total_salario_cotizable, 2) }}</td>
                        <td align="center">{{ number_format($vol->total_comprobante, 2) }}</td>
                        <td align="center">{{ number_format($vol->total_comprobante * 0.05, 2) }}</td>
                    </tr>
                @endforeach


                <tr>
                    <td colspan="4" align="center" style="color:#34495e">
                        <h3><b>RESUMEN LIQUIDACIÓN:</b></h3>
                    </td>
                    <td align="center" style="color:#34495e">
                        <h3><b>{{ $asuss->cotizacion }} </b></h3>
                    </td>
                    <td align="center" style="color:#34495e">
                        <h3><b>{{ number_format($asuss->total_general, 2) }} </b></h3>
                    </td>
                    <td align="center" style="color:#34495e">
                        <h3><b> {{ number_format($asuss->total_aporte, 2) }} </b></h3>
                    </td>
                    <td align="center" style="color:#34495e">
                        <h3><b>{{ number_format($asuss->tasa, 2) }}</b></h3>
                    </td>
                </tr>
            </table>
        </div>


        <div style="font-size:70% ; font-family: 'Times New Roman', Times, serif;">
            <p><b>NOTA: (a) Senasir, Seguros ProVida, La Vitalicia,
                    BB Prevision y AFP Futuro Cotizan el 3% a seguro a Corto Plazo </b></p>

        </div>

        <div style="font-size:70% ; font-family: 'Times New Roman', Times, serif;">
            <p style="text-decoration:underline;">RESUMEN</p>
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <td scope="col" style="text-align:left; padding-left:37px;">
                            APORTE DEL 5% AL MIN DE SALUD
                        </td>
                        <td scope="col" style="text-align:right; padding-right:15px;">
                            <b> {{ number_format($asuss->tasa, 2) }}</b>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <br><br>
        <div>
            <table style="font-size:70% ; font-family: 'Times New Roman', Times, serif;" width="100%">
                <tr>
                    <td width="50%" align="center"><u><b>DESCUENTOS REALIZADOS POR EL TGN</b></u></td>
                    <td width="20%" align="center"><b>MES</b></td>
                    <td width="22%" align="center"><b>MONTO</b></td>
                    <td width="8%" align="center"> </td>
                </tr>
                @foreach ($tgn as $tg)
                    <tr>
                        <td width="50%" align="left"> {{ $tg->nombre_institucion }}</td>
                        <td width="20%" align="center"> {{ $tg->mes }}-{{ $tg->gestion }} </td>
                        <td width="22%" align="center"> {{ $tg->ministerio }} </td>
                        <td width="8%" align="center"> </td>

                    </tr>
                @endforeach
            </table>


        </div>
        <br>



        <div style="font-size:12px ; margin-left:30px; font-family: 'Times New Roman', Times, serif;">
            <table width="100%">
                <thead>
                    <tr>
                        <td scope="col" style="text-align:right;"><b> </b> </td>
                        <td scope="col" style="text-align:right; padding-right:15px;"> - ( {{ $totalMinisterio }}
                            )
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <br>
        <div style="font-size:12px ; margin-left:30px; font-family: 'Times New Roman', Times, serif;">
            <table width="100%">
                <tr>
                    <td scope="col" style="text-align:center;  padding-left:50px;"> <b>TOTAL A CANCELAR AL
                            MINISTERIO
                            DE SALUD</b></td>
                    <td scope="col" style="text-align:right; padding-right:15px;"><b> Bs.
                            {{ number_format($asuss->tasa - $totalMinisterio, 2) }} </b></td>
                </tr>
            </table>
            <br>
            <p>La Paz, {{ $fechaTexto }} </p>
        </div>


    </div>
</body>


</html>
