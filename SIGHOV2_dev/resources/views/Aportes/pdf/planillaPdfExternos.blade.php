<!DOCTYPE html>
<link rel="stylesheet" href="{{ public_path('css/estilos.css') }}">
<style>
    html,
    body {
        height: 100%;
        margin-top: 3px;
        margin-bottom: 3px;
        margin-left: 0;
        margin-right: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .header {
        align-items: center;
        font-family: Times New Roman, serif;
        font-size: 11px;
        padding: 15px;
    }

    .cuerpo {
        flex: 1;
        /* Esto hace que el conteenido crezca y empuje el footer hacia abajo */
        padding: 5px;
        padding-top: 5px;
    }

    .header-title {
        font-size: 16px;
        height: 0px;
        font-weight: bold;
        text-align: center;
    }

    .logo-left {
        width: 60px;
        height: auto;
        margin-right: 10px;
    }

    .header-container {
        width: 100%;
    }

    .secondary-header {
        background-color: #a1b9c3;
        color: #333;
    }

    .tabla-central {
        width: 100%;
        border-collapse: collapse;

    }

    .tr_general {
        font-size: 10px;
    }

    .data-row {
        font-size: 10px;
    }

    .columnas-generales {
        border: 1px solid black;
    }

</style>


<html>

<head>
    <meta charset="UTF-8">
    <title>PLANILLA </title>
    <style>
        cuerpo {
            position: relative;
            background: url('/public/images/logoSSUHDCarnet.png') no-repeat center center;
            background-size: contain;
        }
    </style>
</head>

<body> 
    <div class="header">
        <div class="header-title">
            DETALLE DE APORTES
        </div>
        <div class="header-container">
            <img src="{{ public_path('logo/ssu.png') }}" class="logo-left" alt="Logo">
            <div class="header-left">
                <table width="100%">
                    <tr>
                        <td><b>INSTITUCIÓN:</b> {{ $comprobante->institucionComprobante->nombre }}</td>
                        <td><b>TIPO DE APORTE:</b> 
                            @if($tipoAporte == "PATRONAL")
                                MENSUAL
                            @else
                                {{ $tipoAporte }}
                            @endif    
                         </td>
                    </tr>
                    <tr>
                        <td><b>MONTO SALARIO COTIZABLE</b> 
                            {{ number_format($comprobante->total_salario_cotizable, 2, '.', ',') }} <b><em>Bs.</em></b> </td>
                        <td><b>FECHA SUBIDA DE PLANILLA:</b>
                            {{ \Carbon\Carbon::parse($comprobante->created_at)->Format('d /m/ Y') }}</td>
                    </tr>
                    @if ($comprobante->total_compensacion != 0)
                        <tr>
                            <td><b>TOTAL COMPENSACIÓN AL SALARIO MÍNIMO</b> Bs. {{ $comprobante->total_compensacion }} <b><em>Bs.</em></b></td>
                            <!-- <td><b>FECHA DE LIQUIDACIÓN:</b>
                                {{ \Carbon\Carbon::parse($comprobante->fecha_liquidacion)->Format('d /m/ Y') }}</td> -->
                        </tr>
                    @endif
                    
                    <tr>
                        
                        <td><b>TOTAL APORTE</b>  {{ number_format($comprobante->importe_aporte_patronal ?? 0, 2, '.', ',') }} <b><em>Bs.</em></b></td>
                        <td><b>GESTIÓN: </b> {{ $comprobante->mesComprobante->mes }} ,
                            {{ $comprobante->gestion }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="body" style="position: relative;">
        <table class="tabla-central" style="position: relative; z-index: 1;">
            <tr class="tr_general columnas-generales">
                <th colspan="3" class="main-header columnas-generales ">
                    DATOS APORTANTE
                </th>
                <th colspan="10" class="main-header columnas-generales ">
                    DATOS APORTE
                </th>
            </tr>
            <tr class="tr_general columnas-generales">
                <th class="secondary-header columnas-generales">
                    #
                </th>
                <th class="secondary-header columnas-generales">
                    CI
                </th>
                <th class="secondary-header columnas-generales ">
                    NOMBRE COMPLETO
                </th>
                <th class="secondary-header columnas-generales">
                    ITEM
                </th>
                <th class="secondary-header columnas-generales">
                    ÁREA
                </th>
                <th class="secondary-header columnas-generales">
                    CARGO
                </th>
                <th class="secondary-header columnas-generales">
                    CARGA HORARIA
                </th>
                <th class="secondary-header columnas-generales">
                    DIAS TRABAJADOS
                </th>
                <th class="secondary-header columnas-generales">
                    FECHA DE INGRESO
                </th>
                <th class="secondary-header columnas-generales">
                    FECHA DE NOVEDAD
                </th>
                <th class="secondary-header columnas-generales">
                    MOTIVO DE NOVEDAD
                </th>
                <th class="secondary-header columnas-generales">
                    TOTAL GANADO
                </th>
                <th class="secondary-header columnas-generales">
                    TOTAL APORTE
                </th>
            </tr>
            @foreach ($datos as $dato)
                <tr class="tr_general columnas-generales">
                    <td class="columnas-generales" style="text-align: center;"> {{ $count = $count + 1 }}</td>
                    <td class="columnas-generales" style="text-align: left;">
                        {{ $dato->personaAporteInstitucional->ci }}</td>
                    <td class="columnas-generales" style="text-align: left;">
                        {{ $dato->personaAporteInstitucional->p_apellido . ' ' . $dato->personaAporteInstitucional->s_apellido . ' ' . $dato->personaAporteInstitucional->nombres }}
                    </td>
                    <td class="columnas-generales" style="text-align: left;"> {{ $dato->item }}</td>
                    <td class="columnas-generales" style="text-align: left;"> {{ $dato->area }}</td>
                    <td class="columnas-generales" style="text-align: left;"> {{ $dato->cargo }}</td>
                    <td class="columnas-generales" style="text-align:center;"> {{ $dato->carga_horaria }}</td>
                    <td class="columnas-generales" style="text-align:center;"> {{ $dato->dias_trabajados }}</td>
                    <td class="columnas-generales" style="text-align:center;">
                        {{ \Carbon\Carbon::parse($dato->fecha_ingreso)->Format('d /m/ Y') }}</td>
                    @if ($dato->fecha_novedad_retiro_suspension)
                        <td class="columnas-generales" style="text-align:center;">
                            {{ \Carbon\Carbon::parse($dato->fecha_novedad_retiro_suspension)->Format('d /m/ Y') }}
                        </td>
                    @else
                        <td style="text-align:center;">  </td>
                    @endif
                    <td class="columnas-generales" style="text-align: left;">
                        {{ $dato->motivo_novedad_retiro_suspension ?? ' ' }}</td>
                    <td class="columnas-generales " style="text-align: right;"> 
                            {{ number_format($dato->total_ganado, 2, '.', ',') }}
                    </td>
                    <td class="columnas-generales" style="text-align: right;"> 
                            {{ number_format($dato->total_aporte, 2, '.', ',') }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>


</html>
