<!DOCTYPE html>
<style>
    html {
        margin: 0;
    }

    body {
        font-family:  Arial, sans-serif;
        margin: 5mm 5mm 0mm 5mm;
        background-image: url('images/copia_mas_clara.jpeg');
        background-repeat: no-repeat; 
        background-position: center; 
        background-opacity: 0.9; 

        
        /* Ajusta la opacidad de la imagen */
        z-index: -1;
    }



    .define {
        width: 960px;
        margin: 0 auto;
    }

    i {
        text-transform: capitalize;
    }
    .qr {
        position: absolute;
        right: 20px;
    }
    .header {
        height: 100px;
        margin-bottom: -10px;
    }
    .titulo{
        margin-top: 15px;
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
        height: 80px; /* Ajusta el alto */
        box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        margin: 0 auto; /* Centra el div horizontalmente */
        margin-left: 20px; /* Margen izquierdo */
        margin-right: 30px; /* Margen derecho */
    }
    .logo-left {
        position: absolute;
        /* Posiciona el logo absolutamente respecto al contenedor */
        top: 10px;
        left: 20px;
        /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
    }
</style>


<html>

<head>
    <title>COMPROBANTE DE PAGO</title>
</head>

<body>
    <!-- <div class="header"> -->
        <img src="logo/ssu.png" class="logo-left" width="115" height="82" alt="Logo">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="80" height="80" class="qr" />
        
    <!-- </div> -->
    <br>
    <h2 align="center" class="titulo" style="text-decoration:underline;  font-family: Arial, sans-serif; letter-spacing: 1px;"> 
    SEGURO SOCIAL UNIVERSITARIO LA PAZ</h2>
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:13px">
        <tr>
            <td colspan="3" align="center" style="background-color:black; color:white">
                <h4><B>COMPROBANTE DE PAGO MENSUAL DE APORTES VOLUNTARIOS</B></h4>
            </td>
            <td colspan="2" align="center" style="background-color:black; color:white"><b>Nº</b>
                {{ $comprobanteVoluntario->comprobanteFormaPago->nro_registro_comprobante }}</td>

        </tr>
        <tr>
            <td colspan="2" style="background-color:#E5E7E9;"><b>CORRESPONDIENTE AL MES DE:</b> </td>
            <td align="center"><i>{{ $comprobanteVoluntario->comprobanteFormaPago->mesComprobante->mes }}</i></td>
            <td colspan="2"><b>DE:</b> <i>{{ $comprobanteVoluntario->comprobanteFormaPago->gestion }}</i> </td>
        </tr>

        <tr>
            <td colspan="4" style="background-color:#E5E7E9;"><b>NOMBRE COMPLETO: </b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>CI</b></td>
        </tr>
        <tr>
            <td colspan="4" align="center" style="height: 30px;"><i>
                    {{ 
                        
                        $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->p_apellido .' ' .
                        $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->s_apellido . ' ' .
                        $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->nombres 
                     }}</i>
            </td>
            <td align="center">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->personaContratoVoluntario->ci }}</i>
            </td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>ZONA</b></td>
            <td colspan="2" align="center" style="background-color:#E5E7E9;"><b>CALLE O AVENIDA</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>NÚMERO</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>TELÉFONO</b></td>
        </tr>
        <tr>
            <td align="center" style="height: 30px;">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->residenciaContratoVoluntario->zonaResidencia->nombre }}
                </i></td>
            <td colspan="2" align="center">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->residenciaContratoVoluntario->direccion }}</i>
            </td>
            <td align="center">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->residenciaContratoVoluntario->nro_vivienda }}</i>
            </td>
            <td align="center">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->telefono_1 }}</i>
            </td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>ACTIV. DEL EMPLEADO</b>R</td>
            <td align="center" style="background-color:#E5E7E9;"><b>Nº DEL EMPLEADOR</b></td>
            <td colspan="2" align="center" style="background-color:#E5E7E9;"><b>Nº DE TRABAJADORES</b></td>
            <td rowspan="2" align="center" style="background-color:#E5E7E9;"><b>TOTAL</b></td>
        </tr>
        <tr>
            <td rowspan="2" align="center">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->claseAportanteContratoVoluntario->tipo }}</i>
            </td>
            <td rowspan="2" align="center"><i>0</i></td>
            <td align="center" style="background-color:#E5E7E9;"><b>PERMANENTES</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>EVENTUALES</b></td>
        </tr>
        <tr>
            <td align="center"><i>{{ $comprobanteVoluntario->comprobanteFormaPago->cantidad_permanentes }}</i></td>
            <td align="center"><i>{{ $comprobanteVoluntario->comprobanteFormaPago->cantidad_eventuales }}</i></td>
            <td align="center">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->cantidad_permanentes + $comprobanteVoluntario->comprobanteFormaPago->cantidad_eventuales }}</i>
            </td>
        </tr>
    </table>

    <!--INCISO A-->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td colspan="5" style="background-color:black; color:white"><b>a) LIQUIDACIÓN DE APORTES</b></td>
        </tr>
        <tr>
            <td align="center" width="20%" style="background-color:#E5E7E9;"> <b>APORTES</b></td>
            <td align="center" width="10%" style="background-color:#E5E7E9;"><b>TASA</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>FACTOR ACT.</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>TOTAL SALARIO COTIZABLE</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>IMPORTE</b></td>
        </tr>
        <!--aqui poner el if-->
        <tr>
            <td align="left">
                {{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->claseAportanteContratoVoluntario->tipo }}
            </td>

            <td align="center"><i>
                    {{ $comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->claseAportanteContratoVoluntario->porcentaje *100}}</i>
                %</td>
            <td></td>
            <td align="right"> <i><b>Bs. </b> {{ number_format($comprobanteVoluntario->comprobanteFormaPago->total_salario_cotizable, 2, ',', '.') }}
                </i></td>
            <td align="right"><b>Bs. </b>{{ number_format($comprobanteVoluntario->comprobanteFormaPago->importe_aporte_patronal, 2, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>EXCEDENTE</b></td>
            <td colspan="3" align="left" style="height: 30px;">
                <i>{{ $comprobanteVoluntario->comprobanteFormaPago->demasia_descripcion }} </i>
            </td>

            <td align="right"><b>Bs. </b><i>{{ number_format($comprobanteVoluntario->comprobanteFormaPago->monto_demasia, 2, ',', '.') }}
                </i></td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>ACTUALIZACIÓN APORTE</b></td>
            <td></td>
            <td align="center"><i> {{ $comprobanteVoluntario->comprobanteFormaPago->factor_actual }} </i></td>
            <td align="right"> <b>Bs. </b>
                {{ number_format($comprobanteVoluntario->comprobanteFormaPago->actualizacion_aporte, 2, ',', '.') }}
            </td>
            <td align="right"> <b>Bs. </b>
                <i>{{ number_format($comprobanteVoluntario->comprobanteFormaPago->actualizacion_aporte, 2, ',', '.') }}
                </i></td>

        </tr>
        <tr>
            <td colspan="4" align="right" style="background-color:#85929E ; "> <i><b>SUB. TOTAL (a)</b></i></td>
            <td align="right"><i><b>Bs. </b>
                    {{ number_format($comprobanteVoluntario->comprobanteFormaPago->subtotal_liquidacion, 2, ',', '.') }}
                </i></td>
        </tr>
    </table>
    <!--INCISO B-->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td colspan="5" style="background-color:black; color:white"><b>b) RECARGOS DE LEY</b></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" width="10%" style="background-color:#E5E7E9;"><b>TASA</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>DIAS DE MORA</b></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td width="40%"><b>INTÉRES</b></td>
            <td align="center"><i>{{ $comprobanteVoluntario->comprobanteFormaPago->tasa_interes }}</i></td>
            <td align="center">{{ $comprobanteVoluntario->comprobanteFormaPago->dias_mora }} </td>
            <td align="right"><b>Bs. </b>
                {{ number_format($comprobanteVoluntario->comprobanteFormaPago->subtotal_liquidacion, 2, ',', '.') }}
            </td>
            <td align="center"><i>{{ $comprobanteVoluntario->comprobanteFormaPago->importe_intereses }} </i></td>

        </tr>
        <tr>
            <td><b>MULTAS S/INTÉRES</b></td>
            <td align="center"><i>10.00 %</i></td>
            <td align="center"> </td>
            <td></td>
            <td align="right"><i><b>Bs. </b>{{ $comprobanteVoluntario->comprobanteFormaPago->importe_multa_interes }}</i></td>
        </tr>
        <tr>
            <td><b>MULTAS NO PRESENTACIÓN DE PLANILLA </b></td>
            <td align="center"><i>1.00 %</i></td>
            <td></td>
            <td></td>
            <td align="right"><i><b>Bs. </b> {{ $comprobanteVoluntario->comprobanteFormaPago->importe_multa_no_planilla }}</i>
            </td>

        </tr>
        <tr>
            <td colspan="4" align="right" style="background-color:#85929E;"> <i><b>SUB. TOTAL (b)</b></i></td>
            <td align="right"><i><b>Bs. </b>{{ $comprobanteVoluntario->comprobanteFormaPago->subtotal_recargos }}</i>
            </td>
        </tr>

    </table>

    <!--INCISO C-->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td colspan="5" align="left" style="background-color:black; color:white"><b>c) DEDUCCIONES</b></td>
        </tr>
        @if ($comprobanteVoluntario->comprobanteFormaPago->aporteVComprobante->contratoVoluntarioAporteVoluntario->claseAportanteContratoVoluntario->nombre === 'TGN')
            <tr>
                <td colspan="4" align="left">
                    <b>OTROS :</b> <i> Menos el 5% para el Ministerio de Salud</i>
                </td>
                <td align="right">
                    <b>Bs. </b>{{ number_format($comprobanteVoluntario->comprobanteFormaPago->ministerio, 2, ',', '.') }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="4" align="left">
                    <b>OTROS : </b> {{ $comprobanteVoluntario->comprobanteFormaPago->deduccion_descripcion }}
                </td>
                <td align="right"><b>Bs. </b>
                    {{ number_format($comprobanteVoluntario->comprobanteFormaPago->monto_deduccion, 2, ',', '.') }}
                </td>
            </tr>
        @endif


        <tr>
            <td colspan="4" align="right" style="background-color:#85929E;"> <i><b>SUB. TOTAL (c)</i></b></td>
            <td align="right"><b>Bs. </b> <i>
                    {{ number_format($comprobanteVoluntario->comprobanteFormaPago->monto_deduccion, 2, ',', '.') }} </i>
            </td>
        </tr>
        <tr>
            <td colspan="4" align="right" style="background-color:#85929E;"><b><u>TOTAL (a+b-c)</u></b></td>
            <td align="right"><b>Bs. </b>
                <i>{{ number_format($comprobanteVoluntario->comprobanteFormaPago->monto_total_comprobante, 2, ',', '.') }}
                </i></td>

        </tr>

    </table>


    <table border="1" WIDTH="100%" HEIGHT="55%" align="center" cellspacing="0" cellpadding="0"
        style="font-size:14px">
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>LIQUIDADOR</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>JEFATURA</b></td>
        </tr>
        <tr>
            <td> . <br> . </td>
            <td> . <br> . </td>
        </tr>
    </table>
    <!--FORMA DE PAGO -->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:16px">
        <tr>
            <td align="center" style="font-size: 14px; background-color:black; color:white"><b>FORMA DE PAGO</b></td>
        </tr>
    </table>

    <table border="1" align="center" WIDTH="100%" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b> LUGAR DE PAGO </b></td>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b>Nro Comprobante Anterior</b></td>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b>Total Anterior</b> </td>
            <td width="25%"></td>
        </tr>
        <tr>
            <td align="center">LA PAZ</td>
            <td align="center">{{ $comprobanteVoluntario->nro_compro_anterior }} </td>
            <td align="right"> <b>Bs. </b>
                {{ number_format($comprobanteVoluntario->total_compro_anterior, 2, ',', '.') }} </td>
            <td width="25%"></td>
        </tr>

    </table>
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b> FECHA DE PAGO </b></td>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b>TIPO DE PAGO</b></td>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b>NRO DEPOSITO</b> </td>
            <td width="25%" align="center" style="background-color:#E5E7E9;"><b>MONTO TOTAL</b> </td>

        </tr>
        <tr>
            <td align="center">{{ \Carbon\Carbon::parse( $comprobanteVoluntario->forma_pago )->format('d/m/Y') }} </td>
            <td align="center">{{ $comprobanteVoluntario->tipo_pago }} </td>
            <td align="center">{{ $comprobanteVoluntario->nro_pago }} </td>
            <td align="right"><b>Bs. </b>{{ number_format($comprobanteVoluntario->total_forma_pago, 2, ',', '.') }} </td>

        </tr>
    </table>
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td style="height: 30px;"><b>SON:</b><em>{{ $numeroEnteroEnLetras }} {{ $parte_decimal }}/100
                    BOLIVIANOS.</em>
            </td>
        </tr>
    </table>
    <!--OBSERVACIONES -->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td align="center" colspan="2" style="font-size:14px; background-color:black; color:white">
                <b>OBSERVACIONES</b>
                <!--<br>El Contrato comienza el-->
                <!--<b>fecha_inicio</b> hasta-->
                <!--<b>fecha_fin_</b> Tiene-->
                <!--<b>dias_restantes_contrato</b> Restantes. <br><br><br> -->
            </td>

        </tr>
        <tr>
            <td align="LEFT" colspan="2" style="font-size: 11px; height: 20px; "><b>
                    {{ $comprobanteVoluntario->comprobanteFormaPago->observacion }} </b> </td>

        </tr>
        <tr>
            <td><b>JURO LA EXACTITUD DE LOS DATOS CONSIGNADOS EN ESTE DOCUMENTO.</b> <BR><BR> FIRMA:
                _____________________________________________
                <BR><BR>NOMBRE:___________________________________________<BR><BR>
            </td>
            <td style align="center"><br><br><br><br>
                REGISTRO CAJA (O BANCO)</td>
        </tr>
        <tr>
            <td colspan="2" align="right" style="background-color:#E5E7E9;"><b>Fecha de Liquidación: </b>
            {{ \Carbon\Carbon::parse( $comprobanteVoluntario->comprobanteFormaPago->fecha_liquidacion )->format('d/m/Y') }}
            </td>
        </tr>


    </table>
    
    <div class="footer">
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;" >
                    <b>Fecha: </b>{{ \Carbon\Carbon::Now()->toDateTimeString() }}<br>
                    <b>Usuario: </b>
                    {{ $comprobanteVoluntario->comprobanteFormaPago->usuarioCreador->personaUser->p_apellido .
                ' ' .
                $comprobanteVoluntario->comprobanteFormaPago->usuarioCreador->personaUser->s_apellido .
                ' ' .
                $comprobanteVoluntario->comprobanteFormaPago->usuarioCreador->personaUser->nombres }}<br>
                    
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
                    <td align="left" >
                    
                    <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="Logo" style="opacity: 0.7;">

                    </td>
            </tr>
        </table>
    </div>

</body>

</html>
