<div>
    <!--LIQUIDACION MODAL-->
    <x-big-component id="liquidacionModal" labelledby="liquidacionModalLabel"  title="AGREGAR REGISTRO LIQUIDACIÓN"
        saveAction="liquidarSweet()">
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276; color:white; border-radius:10px; ">ACTUAL INSTITUCIÓN </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="font-size:19px;"><b><em> {{$nombre_institucion}}</em> </b></p>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <span><b>TIPO APORTANTE: </b>{{ $tipo_clase }} </span>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <span><b>TGN: </b> @if($tgn)  SI @else NO @endif</span>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class=" form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">PLANILLA DE TIPO:
                                    </label>
                                    <div class="col-sm-7">
                                        @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                                            <input type="text" class="form-control-plaintext" id="staticEmail"
                                                value="MENSUAL" disabled style="font-style: italic; font-weight: bold;">
                                        @elseif ($reintegro == true)
                                            <input type="text" class="form-control-plaintext" id="staticEmail"
                                                value="REINTEGRO" style="" disabled style="font-style: italic; font-weight: bold;">
                                        @elseif($beneficio_social == true)
                                            <input type="text" class="form-control-plaintext" id="staticEmail"
                                                value="BENEFICIO SOCIAL" disabled style="font-style: italic; font-weight: bold;">
                                        @elseif($planilla_adicional == true)
                                            <input type="text" class="form-control-plaintext" id="staticEmail"
                                                value="PLANILLA ADICIONAL" disabled style="font-style: italic; font-weight: bold;">
                                        @elseif($retroactivo == true)
                                            <input type="text" class="form-control-plaintext" id="staticEmail"
                                                value="RETROACTIVO" disabled style="font-style: italic; font-weight: bold;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>NRO. COMPROBANTE: </b> {{$m_nro_registro}} </span>
                            </div>
                        </div>
                        
                        <div class="row"> 
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <span><b>MES: </b> {{$nombre_mes}} </span>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <span><b>GESTIÓN: </b> {{$gestion}} </span>
                            </div>

                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <br>
                                <label for="salario_cotizable_m"> TOT. SAL. COTIZABLE: </label>
                                <input class="form-control" type="text" wire:model='salario_cotizable_m' disabled
                                    style=" height: 30px; border-radius: 10px; text-align:right;">
                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <br>
                                <label for="importe"> IMPORTE: </label>
                                <input class="form-control" type="text" wire:model='importe' disabled
                                    style=" height: 30px; border-radius: 10px; text-align:right;">

                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <br> 
                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <br>
                                <label for="total_compensacion"> COMPENSACIÓN: </label>
                                <input class="form-control" type="text" wire:model='total_compensacion' disabled
                                    style=" height: 30px; border-radius: 10px; text-align:right;">

                            </div>

                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                
                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="total_a_pagar_m" style="color:#34495e;">   TOTAL: </label>
                                <input class="form-control" id="total_a_pagar_m" type="text"
                                    wire:model='total_a_pagar_m' disabled
                                    style=" height: 30px; border-radius: 10px; text-align:right;">
                            </div>
                            
                            
                            

                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p style="color:#1a5276 ; text-decoration:underline;" class="h5">
                                            <em><b>Actual depósito registrado.</b> </em>
                                        </p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b> FECHA DE PAGO:</b> {{ \Carbon\Carbon::parse($fecha_pago)->format('d/m/Y') }} </span>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b> TIPO DEPÓSITO: </b> {{$tipo_pago}} </span>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b> NRO. DOCUMENTO: </b> {{$nro_pago}} </span>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <span> <b> TOTAL DEPOSITADO: </b> <em>{{$forma_pago_c_m}}</em> <b>Bs.</b> </span>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                                <span> <b> MONTO EXCEDENTE:  </b> {{$monto_excedente}}  </span>

                                              
                                            </div>
                                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                                <span> <b> DESCRIPCIÓN:  </b> {{$demasia_descripcion}} </span>
                                                 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                                <span> <b> MONTO  DEDUCCIÓN:  </b> {{$monto_deduccion_r}}  </span>
                                            </div>
                                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                                <span> <b>   DESCRIPCIÓN: </b> {{$deduccion_descripcion}} </span>
                                                 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                        <button class="btn btn-warning" wire:click='abrir_file_pago' title=" ABRIR PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                                        <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"/>
                                        </svg>
                                            <b>ABRIR FORMA DE PAGO </b></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p style="color:#1a5276 ;" class="h5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                            <em class="ml-2"><b>Ingresar los siguientes campos para el registro.</b> </em>
                                            <spam style="color:#cb4335;"><b>*</b></spam>
                                        </p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for= "dato_fecha_liquidacion">FECHA LIQUIDACIÓN:</label>
                                        <input type="date" class="form-control" name="dato_fecha_liquidacion"
                                            id="dato_fecha_liquidacion" wire:model='dato_fecha_liquidacion'
                                            style="border-radius: 10px;">

                                        @error('dato_fecha_liquidacion')
                                            @if ($message == 'validation.required')
                                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                                            @else
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @endif
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                        <strong>¿PRESENTÓ PLANILLA?</strong>
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

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="observacion" class="form-label">OBSERVACIONES:</label>
                                        <textarea id="observacion" wire:model="observacion" type="text" class="form-control"
                                            placeholder="Describa la observación si corresponde" rows="2"
                                            oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        

    </x-big-component>
</div>

<script>
    window.addEventListener('modalLiquidacion', event => {
        $('#liquidacionModal').modal('show');
        
    });

    function liquidarSweet() {
        Livewire.dispatch('crear_liquidacion');
    }

    document.addEventListener('guardarComprobante', function() {
        Swal.fire({
            title: '¿Guardar liquidación?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('crear_liquidacion', [true]);
            } else {
                Livewire.dispatch('crear_liquidacion', [null]);
            }
        });
        $('#liquidacionModal').modal('hide');
    });

    document.addEventListener('liqCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La liquidación se registró correctamente.",
            icon: "success"
        });
        $('#liquidacionModal').modal('hide');
    });

    document.addEventListener('liqCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se canceló el registro",
            icon: "error"

        });
        $('#liquidacionModal').modal('hide');
    });

    document.addEventListener('IntroduzcaFecha', function() {
        Swal.fire({
            title: "Inserte la Fecha!",
            text: "Se canceló el registro.",
            icon: "warning"
        });
        $('#liquidacionModal').modal('hide');
    });

    document.addEventListener('noFormaPago', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se tiene que realizar el pago correspondiente!",
            icon: "error"
        });
        $('#liquidacionModal').modal('hide');
    });
</script>
