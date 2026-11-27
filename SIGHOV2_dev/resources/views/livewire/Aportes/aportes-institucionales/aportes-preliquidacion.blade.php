<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">EVALUAR CÁLCULO DE PRE- LIQUIDACIÓN </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>

    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" style="background-color:white;">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                     
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="font-size:18px;"><b><em> {{$nombre_institucion}}</em> </b></p> 
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <spam style="text-decoration:underline;"><em>Datos del Aporte Pendiente.</em>
                                    </spam>
                                </b>
                            </p>
                        </div>
                    </div>
                     
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <span><b>TIPO APORTANTE: </b>{{ $tipo_clase }} </span>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <span><b>ES TGN: </b>@if ($tgn == true)
                                SI
                                @else
                                NO
                                @endif 
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <p> <span><b>PLANILLA DE TIPO: </b> </span>

                                @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                                    <span ><b><em>MENSUAL </em> </b></span>
                                @elseif ($reintegro == true)
                                    <span ><b> <em>REINTEGRO </em></b></span>
                                @elseif($beneficio_social == true)
                                    <span ><b> <em> BENEFICIO SOCIAL</em></b></span>
                                @elseif($planilla_adicional == true)
                                    <span ><b><em> PLANILLA ADICIONAL</em> </b></span>
                                @elseif($retroactivo == true)
                                    <span ><b><em> RETROACTIVO</em> </b></span>
                            </p>
                            @endif

                            </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <span><b>MES:</b> {{$nombre_mes}} </span> 
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <span><b>GESTIÓN:</b> {{$gestion}} </span> 
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <label for= "total_salario_cotizable_m"> TOTAL SALARIO COTIZABLE:</label>
                            <input class="form-control" type="text" id="total_salario_cotizable_m"
                                wire:model='total_salario_cotizable_m' disabled
                                style=" height: 30px; border-radius: 10px; text-align:right;">
                        </div>

                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <label for="importe_aporte_patronal" style="color:#212f3d;"> IMPORTE: </label>
                            <input class="form-control" id="importe_aporte_patronal" type="text"
                                wire:model='importe_aporte_patronal_m' disabled style=" height: 30px; border-radius: 10px; text-align:right;">
                        </div>
                    </div>

 
                    <br> 
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="p-1">
                                <b>Link: </b><em>   Para registrar forma de pago y depósito.</em>  
                                 
                            </p>
                            <button class="btn btn-success" wire:click="link_forma_pago" title="IR A LA VISTA FORMA DE PAGO"  >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                                <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"/>
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"/>
                                </svg><b style=" font-size:16px;"> Ir a Forma de Pago </b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 mt-3 mt-lg-0" style="background-color:white;">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276 ;" class="h5"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-pencil-square"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em><b>Ingrese los siguientes datos para evaluar la pre-liquidación.</b></em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <label for= "fecha_pago">FECHA LIQUIDACIÓN:</label>
                            <input type="date" class="form-control" name="fecha_pago"
                                id="fecha_pago" wire:model='fecha_pago'
                                style="border-radius: 10px;">

                            @error('fecha_pago')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>

                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <label for="presenta_planilla">¿PRESENTARÁ PLANILLA?</label>
                            <div class="col-sm-10 d-flex align-items-center" style=" justify-content: center;">
                                <label class="mr-2" style="color: #c0392b ;">NO</label>
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="presenta_planilla" name="presenta_planilla"
                                        type="checkbox" class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2" style="color: #196f3d;">SI</label>
                            </div>
                        </div>

                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <br>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                                    style="background-color:white ;">
                                    <div class="row">
                                        <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="monto_demasia">MONTO EXCEDENTE:</label>
                                            <input wire:model="monto_demasia" name="monto_demasia"
                                                id="monto_demasia" type="number" step="0.01"
                                                class="form-control" placeholder="Ej.1,000.00"
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="demasia_descripcion">DESCRIPCIÓN:</label>
                                            <textarea wire:model="demasia_descripcion" name="demasia_descripcion" id="demasia_descripcion" type="text"
                                                class="form-control" placeholder=" Introduzca la descripción de monto excedente"
                                                oninput="this.value = this.value.toUpperCase()" required style="border-radius: 10px;" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                                    style="background-color:white;">
                                    <div class="row">
                                        <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="monto_deduccion">MONTO DEDUCCIÓN:</label>
                                            <input wire:model="monto_deduccion" name="monto_deduccion"
                                                id="monto_deduccion" type="number" step="0.01"
                                                class="form-control" placeholder="Ej.1,000.00"
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="deduccion_descripcion"> DESCRIPCIÓN:</label>
                                            <textarea wire:model="deduccion_descripcion" name="deduccion_descripcion" id="deduccion_descripcion" type="text"
                                                class="form-control" placeholder=" Introduzca la descripción de monto deducción"
                                                oninput="this.value = this.value.toUpperCase()" required style="border-radius: 10px;" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <x-boton-azul wire:click="pre_liquidacion"> <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                                <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                                </svg> EVALUAR </x-boton-azul>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div wire:loading wire:target="pre_liquidacion">
                                <div class="spinner-grow text-warning" role="status">

                                </div>
                                <b><i>Cargando datos espere porfavor...</i></b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                @if($abrir_comprobante == 1)
                    <div class="row">
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:16px">
                                <tr>
                                    <td  style="background-color:#E5E7E9; text-align:right; font-size:18px;"><b> FECHA LIQUIDACIÓN:</b> </td>
                                    <td align="center" style="text-align:center; font-size:18px;"><i> {{ $fecha_pago_m }}</i></td>
                                </tr>
                            </table>
                        </div>
                        @if($estado_alerta == 0)

                        @elseif($estado_alerta == 1)
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="alert alert-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                                </svg> Se ha calculado correctamente, verifique los datos!
                            </div>
                        </div>

                        @elseif($estado_alerta == 2)
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="alert alert-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                </svg> No se calculó, ingrese nuevamente los datos.
                            </div>
                        </div>
                        @endif


                    </div>
                            
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            
                            <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:16px">
                                <tr>
                                    <td colspan="3" align="center" style="background-color:black; color:white">
                                        <h5><B>COMPROBANTE DE PAGO MENSUAL DE APORTES</B></h5>
                                    </td>
                                    <td colspan="2" align="center" style="background-color:black; color:white">
                                        <h5>
                                            <b>Nº</b> 
                                        </h5>
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="background-color:#E5E7E9;"><b>CORRESPONDIENTE AL MES DE:</b> </td>
                                    <td align="center"><i> {{ $nombre_mes }}</i></td>
                                    <td colspan="2"><b>DE: </b> <i>{{ $gestion }}</i> </td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="background-color:#E5E7E9;"><b>NOMBRE O RAZÓN SOCIAL DEL EMPLEADOR: </b></td>
                                    <td align="center" style="background-color:#E5E7E9;"><b>NIT</b></td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="center" style="height: 30px;"><i>
                                    {{ $nombre_institucion }} </i></td>
                                    <td align="center"><i>{{ $nit }}</i></td>
                                </tr>
                                <tr>
                                    <td align="center" style="background-color:#E5E7E9;"><b>ZONA</b></td>
                                    <td colspan="2" align="center" style="background-color:#E5E7E9;"><b>CALLE O AVENIDA</b></td>
                                    <td align="center" style="background-color:#E5E7E9;"><b>NÚMERO</b></td>
                                    <td align="center" style="background-color:#E5E7E9;"><b>TELÉFONO</b></td>
                                </tr>
                                <tr>
                                    <td align="center" style="height: 30px;">
                                        <i>{{$zona}}
                                        </i>
                                    </td>
                                    <td colspan="2" align="center">
                                        <i>{{$direccion}}</i>
                                    </td>
                                    <td align="center">
                                        <i>{{$vivienda}}</i>
                                    </td>
                                    <td align="center"><i>{{$telefono}}</i></td>
                                </tr>
                                <tr></tr>
                                <td align="center" style="background-color:#E5E7E9;"><b>ACTIV. DEL EMPLEADOR</b></td>
                                <td align="center" style="background-color:#E5E7E9;"><b>Nº DEL EMPLEADOR</b></td>
                                <td colspan="2" align="center" style="background-color:#E5E7E9;"><b>Nº DE TRABAJADORES</b></td>
                                <td rowspan="2" align="center" style="background-color:#E5E7E9;"><b>TOTAL</b></td>
                                </tr>
                                <tr>
                                    <td rowspan="2" align="center">
                                        <i>{{$actividad}}</i>
                                    </td>
                                    <td rowspan="2" align="center">
                                        <i>{{$nro_empleador}}</i>
                                    </td>
                                    <td align="center" style="background-color:#E5E7E9;"><b>PERMANENTES</b></td>
                                    <td align="center" style="background-color:#E5E7E9;"><b>EVENTUALES</b></td>
                                </tr>
                                <tr>
                                    <td align="center"><i>{{$cant_permanentes}}</i></td>
                                    <td align="center"><i>{{$cant_eventuales}}</i></td>
                                    <td align="center">
                                        <i>{{$cant_permanentes + $cant_eventuales}}  </i>
                                    </td>
                                </tr>
                            </table>

                            <!--INCISO A-->
                            <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:17px">
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
                                        {{$tipo_clase}} </td>

                                    <td align="center"><i>
                                    {{ number_format($tasa_clase * 100, 2, ',', '.') }}</i>
                                        %</td>
                                    <td></td>
                                    <td align="right"> <i>
                                    {{ $total_salario_cotizable_m }}  </i>
                                    </td>
                                    <td align="right">
                                    <i> {{ $m_importe_aporte_patronal }}</i>
                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="background-color:#E5E7E9;"><b>EXCEDENTE</b></td>
                                    <td colspan="3" align="left" style="height: 30px;">
                                        {{$demasia_descripcion}}
                                    </td>

                                    <td align="right">
                                        <i>{{$m_monto_demasia}} </i>
                                    </td>
                                        
                                </tr>
                                <tr>
                                    <td align="left" style="background-color:#E5E7E9;"><b>COMPENSACIONES</b></td>
                                    <td colspan="3" align="left" style="height: 30px;">
                                        @if($total_compensacion != 0)
                                            <em> Por salarios menores al minímo nacional </em>
                                        @endif
                                    </td>

                                    <td align="right"><i>
                                    {{$total_compensacion}}  </i></td>
                                </tr>
                                <tr>
                                    <td align="left" style="background-color:#E5E7E9;"><b>ACTUALIZACIÓN APORTE</b></td>
                                    <td></td>
                                    <td align="center">
                                        <i>{{$factor_activo}} </i>
                                    </td>
                                    <td align="right"> 
                                        <i>{{$m_actualizacion_aporte}} </i> </td>
                                    <td align="right"> 
                                        <i>{{$m_importe_actualizacion_aporte}} </i>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" style="background-color:#85929E ; "> <i><b>SUB. TOTAL (a)</b></i></td>
                                    <td align="right">
                                        <b>Bs. </b><i>{{$m_subtotal_liquidacion}} </i>
                                    </td>
                                </tr>
                            </table>
                            <!--INCISO B-->
                            <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:17px">
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
                                    <td width="40%"><b>INTERÉS</b></td>
                                    <td align="center"><i>{{$m_tasa}} </i></td></td>
                                    <td align="center" width="15%"><i>{{$dias_mora}} </i> </td>
                                    <td align="right"> <i>{{$m_subtotal_liquidacion}} </i> </td>
                                    <td align="right"> <i>{{$m_intereses}} </i> </td>

                                </tr>
                                <tr>
                                    <td><b>MULTAS S/INTERÉS</b></td>
                                    <td align="center"><i>10.00 %</i></td>
                                    <td align="center"> </td>
                                    <td></td>
                                    <td align="right">  <i>{{$m_multas_sin_intereses}} </i></td>
                                </tr>
                                <tr>
                                    <td><b>MULTAS NO PRESENTACIÓN DE PLANILLA </b></td>
                                    <td align="center"><i>1.00 %</i></td>
                                    <td></td>
                                    <td></td>
                                    <td align="right">  <i>{{$m_multas_no_planilla}} </i>
                                    </td>

                                </tr>
                                <tr>
                                    <td colspan="4" align="right" style="background-color:#85929E;"> <i><b>SUB. TOTAL (b)</b></i></td>
                                    <td align="right"><b>Bs. </b> <i>{{$m_subtotal_recargos}} </i></td>
                                </tr>

                            </table>

                            <!--INCISO C-->
                            <table border="1" WIDTH="100%" align="center" cellspacing="0" cellpadding="0" style="font-size:17px">
                                <tr>
                                    <td colspan="5" align="left" style="background-color:black; color:white"><b>c) DEDUCCIONES</b></td>
                                </tr>
                                    @if($tgn == true)
                                    <tr>
                                        <td colspan="4" align="left">
                                            <b>OTROS :</b> <i> Menos el 5% para el Ministerio de Salud</i>
                                        </td>
                                        <td align="right">
                                            <i>{{$m_ministerio}} </i>
                                        </td>
                                    </tr>
                                    @endif
                                
                                    <tr>
                                        <td colspan="4" align="left">
                                            <b>OTROS : </b> {{$deduccion_descripcion}}
                                        </td>
                                        <td align="right">
                                            <i>{{$m_monto_deduccion}} </i> </td>
                                    </tr>
                                

                                <tr>
                                    <td colspan="4" align="right" style="background-color:#85929E;"> <i><b>SUB. TOTAL (c)</i></b></td>
                                    <td align="right"><b>Bs. </b> <i>{{$m_subtotal_deducciones}} </i></td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="right" style="background-color:#85929E;"><b><u>TOTAL (a+b-c)</u></b></td>
                                    <td align="right" style="background-color: #f9e79f ;font-size: 18px;">
                                        <b>Bs. </b><em>{{$m_total_a_b_c}}</em> 
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </div>

                    @endif

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('IntroduzcaFecha', function() {
        Swal.fire({
            title: "Inserte Fecha",
            text: "Se cancelo la evaluación.",
            icon: "error"
        });
    });

    document.addEventListener('AlertUFVExt', function() {
        Swal.fire({
            title: "UFV no Actualizada!",
            text: "No se realizó el cálculo.",
            icon: "warning"
        });
    });
</script>