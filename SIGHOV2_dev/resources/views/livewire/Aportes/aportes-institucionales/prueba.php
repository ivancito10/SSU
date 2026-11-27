<!DOCTYPE html>

<style>
    html {
        margin: 0;
    }

    body {
        font-family: "Times New Roman", serif;
        margin: 5mm 5mm 0mm 5mm;
        background: url("https://as2.ftcdn.net/v2/jpg/00/11/00/59/1000_F_11005943_uPkhPlYAY1ezkSFec33lxQfBJZPuJW74.jpg") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: 120% auto;

        opacity: 0.5;
        /* Ajusta la opacidad de la imagen */
        z-index: -1;
    }

    footer {
        position: fixed;
        left: 5px;
        bottom: 0px;
        height: 40px;
        width: 100%;
        color: blue;
        text-align: center;
        size: 15px
    }

    .define {
        width: 960px;
        margin: 0 auto;
    }

    i {
        text-transform: capitalize;
    }
</style>


<html>

<head>
    <title>COMPROBANTE DE PAGO</title>
</head>

<body>
    <h2 align="center"><a href="{{ url('aportes/aportestraspasosvoluntarios')}}"> <ins>SEGURO SOCIAL UNIVERSITARIO LA
                PAZ</ins></a></h2>
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:13px">
        <tr>
            <td colspan="3" align="center" style="background-color:black; color:white">
                <h4><B>COMPROBANTE DE PAGO MENSUAL DE APORTES</B></h4>
            </td>
            <td colspan="2" align="center" style="background-color:black; color:white"><b>Nº</b>
                {{$comprobante->nro_registro_comprobante}}</td>

        </tr>
        <tr>
            <td colspan="2" style="background-color:#E5E7E9;"><b>CORRESPONDIENTE AL MES DE:</b> </td>
            <td align="center"><i>{{$comprobante->nombre_mes}}</i></td>
            <td colspan="2"><b>DE:</b> <i>{{$comprobante->gestion}}</i> </td>
        </tr>

        <tr>
            <td colspan="4" style="background-color:#E5E7E9;"><b>NOMBRE O RAZON SOCIAL DEL EMPLEADOR: </b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>NIT</b></td>
        </tr>
        <tr>
            <td colspan="4" align="center" style="height: 30px;"><i>
                    {{$comprobante->nombre_institucion}}</i></td>
            <td align="center"><i>{{$comprobante->nit}}</i></td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>ZONA</b></td>
            <td colspan="2" align="center" style="background-color:#E5E7E9;"><b>CALLE O AVENIDA</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>NÚMERO</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>TELEFONO</b></td>
        </tr>
        <tr>
            <td align="center" style="height: 30px;"><i>{{$comprobante->nombre_zona}} </i></td>
            <td colspan="2" align="center"><i>{{$comprobante->direccion}}</i></td>
            <td align="center"><i>{{$comprobante->numero_direccion}}</i></td>
            <td align="center"><i>{{$comprobante->telefono}}</i></td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>ACTIV. DEL EMPLEADO</b>R</td>
            <td align="center" style="background-color:#E5E7E9;"><b>Nº DEL EMPLEADOR</b></td>
            <td colspan="2" align="center" style="background-color:#E5E7E9;"><b>Nº DE TRABAJADORES</b></td>
            <td rowspan="2" align="center" style="background-color:#E5E7E9;"><b>TOTAL</b></td>
        </tr>
        <tr>
            <td rowspan="2" align="center"><i>{{$comprobante->actividad_empleador}}</i></td>
            <td rowspan="2" align="center"><i>{{$comprobante->nro_empleador}}</i></td>
            <td align="center" style="background-color:#E5E7E9;"><b>PERMANENTES</b></td>
            <td align="center" style="background-color:#E5E7E9;"><b>EVENTUALES</b></td>
        </tr>
        <tr>
            <td align="center"><i>{{$comprobante->cantidad_permanentes}}</i></td>
            <td align="center"><i>{{$comprobante->cantidad_eventuales}}</i></td>
            <td align="center"><i>{{ $comprobante->cantidad_permanentes + $comprobante->cantidad_eventuales }}</i></td>
        </tr>
    </table>

    <!--INCISO A-->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td colspan="5" style="background-color:black; color:white"><b>a) LIQUIDACION DE APORTES</b></td>
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
            <td align="left">{{$comprobante->tipo_clase_aportante}} </td>

            <td align="center"><i> {{$comprobante->porcentaje_clase_aportante}}</i> %</td>
            <td></td>
            <td align="center"> <i> {{ $comprobante->total_salario_cotizable}} </i></td>
            <td align="center"> <b>Bs.</b> {{ $comprobante->importe_aporte_patronal}} </td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>EXCEDENTE</b></td>
            <td colspan="3" align="left" style="height: 30px;">
                <i>{{$comprobante->demasia_descripcion}} </i>
            </td>

            <td align="center"><b>Bs. </b><i> {{$comprobante->monto_demasia}} </i></td>
        </tr>
        <tr>
            <td align="center" style="background-color:#E5E7E9;"><b>ACTUALIZACION APORTE</b></td>
            <td></td>
            <td align="center"><i> {{ $comprobante->factor_actual}} </i></td>
            <td align="center"> <b>Bs. </b> {{ $comprobante->actualizacion_aporte}} </td>
            <td align="center"> <b>Bs. </b> <i>{{ $comprobante->importe_actualizacion_aporte}}</i></td>

        </tr>
        <tr>
            <td colspan="4" align="right" style="background-color:#85929E ; "> <i><b>SUB. TOTAL (a)</b></i></td>
            <td align="center"><i><b>Bs. </b> {{ $comprobante->subtotal_liquidacion}} </i></td>
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
            <td width="40%"><b>INTERES</b></td>
            <td align="center"><i>{{$comprobante->tasa_interes}}</i></td>
            <td align="center">{{$comprobante->dias_mora}} </td>
            <td align="center"><b>Bs. </b> {{ $comprobante->subtotal_liquidacion}} </td>
            <td align="center"><i>{{$comprobante->importe_intereses}} </i></td>

        </tr>
        <tr>
            <td><b>MULTAS S/INTERES</b></td>
            <td align="center"><i>10.00 %</i></td>
            <td align="center"> </td>
            <td></td>
            <td align="center"><i>{{$comprobante->importe_multa_interes}}</i></td>
        </tr>
        <tr>
            <td><b>MULTAS NO PRESENTACION DE PLANILLA </b></td>
            <td align="right"><i>1.00 %</i></td>
            <td></td>
            <td></td>
            <td align="center"><i> {{$comprobante->importe_multa_no_planilla}}</i></td>

        </tr>
        <tr>
            <td colspan="4" align="right" style="background-color:#85929E;"> <i><b>SUB. TOTAL (b)</b></i></td>
            <td align="center"><b><i>{{ $comprobante-> subtotal_recargos}}</i></b></td>
        </tr>

    </table>

    <!--INCISO C-->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td colspan="5" align="left" style="background-color:black; color:white"><b>c) DEDUCCIONES</b></td>
        </tr>
        @if ($comprobante->nombre_clase_aportante === 'TGN')
        <tr>
            <td colspan="4" align="left">
                <b>OTROS :</b> <i> Menos el 5% para el Ministerio de Salud</i>
            </td>
            <td align="center"><b>Bs.</b> {{$comprobante->ministerio}}</td>
        </tr>
        @else
        <tr>
            <td colspan="4" align="left">
                <b>OTROS : </b> {{$comprobante->deduccion_descripcion}}
            </td>
            <td align="center"><b>Bs.</b> {{$comprobante->monto_deduccion}}</td>
        </tr>
        @endif


        <tr>
            <td colspan="4" align="right" style="background-color:#85929E;"> <i><b>SUB. TOTAL (c)</i></b></td>
            <td align="center"><b>Bs.</b> <i> {{$comprobante->subtotal_deducciones}}</i></td>
        </tr>
        <tr>
            <td colspan="4" align="right" style="background-color:#85929E;"><b><u>TOTAL (a+b-c)</u></b></td>
            <td align="center"><b>Bs.</b> <i>{{$comprobante->monto_total_comprobante}}</i></td>

        </tr>

    </table>


    <table border="1" WIDTH="100%" HEIGHT="55%" align="center" cellspacing="0" cellpadding="0" style="font-size:14px">
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
            <td align="center" style="height: 20px; background-color:black; color:white"><b>FORMA DE PAGO</b></td>
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
            <td align="center"> 4652654</td>
            <td align="center"> <b>Bs .</b> 9500 </td>
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
            <td align="center">{{$comprobante->fecha_liquidacion}} </td>
            <td align="center">{{$comprobante->tipo_pago}} </td>
            <td align="center">{{$comprobante->nro_pago}} </td>
            <td align="center">{{$comprobante->total_forma_pago}} </td>

        </tr>
    </table>
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td style="height: 30px;"><b>SON:</b><em>{{$numeroEnteroEnLetras}} {{$parte_decimal}}/100 BOLIVIANOS.</em>
            </td>
        </tr>
    </table>
    <!--OBSERVACIONES -->
    <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:12px">
        <tr>
            <td align="center" colspan="2" style="font-size: 15px" style="background-color:black; color:white">
                <b>OBSERVACIONES</b>
                <!--<br>El Contrato comienza el-->
                <!--<b>fecha_inicio</b> hasta-->
                <!--<b>fecha_fin_</b> Tiene-->
                <!--<b>dias_restantes_contrato</b> Restantes. <br><br><br> -->
            </td>

        </tr>
        <tr>
            <td align="LEFT" colspan="2" style="font-size: 11px; height: 20px; "><b> {{$comprobante->observacion}} </b>
                <!--<br>El Contrato comienza el-->
                <!--<b>fecha_inicio</b> hasta-->
                <!--<b>fecha_fin_</b> Tiene-->
                <!--<b>dias_restantes_contrato</b> Restantes. <br><br><br> -->
            </td>

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
                {{ $comprobante->fecha_liquidacion}}
            </td>
        </tr>


    </table>
    <div style="font-size:80%">
        <p>Av. 6 de Agosto Nº 2630
            Telf: 22434267 Correo: contactos@ssulapaz.org
            - SIGHO v.2 <br>Fecha Impresión: <b>{{\Carbon\Carbon::Now()->toDateTimeString()}}</b> - Usuario:
            <b>{{$comprobante->id_user_created}} </b>
        </p>
    </div>

</body>


</html>