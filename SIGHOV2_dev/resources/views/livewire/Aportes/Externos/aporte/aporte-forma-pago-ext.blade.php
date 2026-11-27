<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;"> AGREGAR  REGISTRO FORMA DE PAGO </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    
                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span> <b>INSTITUCIÓN: </b> {{$nombre_institucion}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span> <b>NIT: </b> {{$nit}}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <spam style="text-decoration:underline;"><em> Aporte de Planilla Registrada.</em>
                                    </spam>
                                </b>
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <p> <span><b>PLANILLA DE TIPO: </b> </span>
                                @if($planilla_adicional == false && $beneficio_social == false && $reintegro == false && $retroactivo == false)
                                    <span ><b><em>MENSUAL </em> </b></span>
                                @elseif($reintegro == true)
                                    <span ><b> <em>REINTEGRO </em></b></span>
                                @elseif($beneficio_social == true)
                                    <span ><b> <em> BENEFICIO SOCIAL</em></b></span>
                                @elseif($planilla_adicional == true)
                                    <span ><b><em> PLANILLA ADICIONAL</em> </b></span>
                                @elseif($retroactivo == true)
                                    <span ><b><em> RETROACTIVO</em> </b></span>
                                @endif
                            </p>

                        </div>


                    </div>
                    <div class="row">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            
                            <label for="mes"> MES: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{$nombre_mes}} </p>

                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="gestion"> GESTIÓN: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{$gestion}} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <br>
                            <label for="salario_cotizable_m"> TOT. SALARIO COT.: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                                {{$salario_cotizable_m}} <b>    Bs.</b></p>
                            
                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <br>
                            <label for="aporte_a_liquidar"> IMPORTE: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                                {{$importe}} <b>    Bs.</b></p>
                            
                        </div>
                    </div>
                    @if($total_compensacion != 0)
                    <div class="row">
                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            
                        </div>
                        <div class='col-lg-7 col-md-12 col-sm-12 col-xs-12'>
                            <label for="total_compensacion"> COMP. DE SALARIO: </label>
                            <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                            {{$total_compensacion}} <b>    Bs.</b></p>
                            
                        </div>
                    </div>
                    @endif
                    <br>
                    <div class="row">
                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            
                        </div>
                        <div class='col-lg-7 col-md-12 col-sm-12 col-xs-12'>
                            <label for="aporte_a_liquidar" style="color:#34495e;"> APORTE A PAGAR: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                                {{$aporte_a_liquidar}} <b>    Bs.</b></p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="p-1"
                            style="background-color: transparent; padding-right: 1px; border: 2px solid #1a5276  ; border-radius: 8px;">
                            <b>Nota: </b> <em> Realice la Pre-liquidación para obtener cálculos más precisos del TOTAL a DEPOSITAR. </em></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" wire:click="link_preliquidacion">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                </svg> EVALUAR  PRE LIQUIDACIÓN</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <p 
                            style="border: 2px solid #d4ac0d; border-radius: 5px; background-color: #f7dc6f; padding-left: 15px; display: inline-block;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13.113 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                </svg> <em><b> Los campos marcados con (<span style="color:#cb4335;">*</span>) son obligatorios para continuar.</b></em> 
                            </p>
                        </div>

                    </div>
                    <br>
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
                                <em class="ml-2"><b>Registrar la forma de pago.</b> </em>
                                
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fecha_pago"> FECHA DE PAGO: <span style="color:#cb4335;">*</span></label>
                            <input wire:model="fecha_pago" name="fecha_pago" id="fecha_pago" type="date"
                                class="form-control  @error('fecha_pago') is-invalid @enderror" 
                                placeholder="FECHA DE PAGO" style="border-radius: 10px;">
                                @error('fecha_pago')
                                <div class="text-danger">
                                    <b>El campo  es obligatorio.</b>
                                </div>
                                @enderror
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tipo_pago">TIPO DEPÓSITO: <span style="color:#cb4335;">*</span></label>
                            <select wire:model="tipo_pago" name="tipo_pago" id="tipo_pago" 
                            class="form-control  @error('tipo_pago') is-invalid @enderror"
                                style="border-radius: 10px;">
                                <option value="">Seleccione tipo pago</option>
                                <option value="BANCO">BANCO</option>
                                <option value="CHEQUE">CHEQUE</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                            </select>
                            @error('tipo_pago')
                            <div class="text-danger">
                                <b>El campo  es obligatorio.</b>
                            </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nro_pago_forma"> NRO. DOCUMENTO: <span style="color:#cb4335;">*</span></label>
                            <input wire:model="nro_pago_forma" name="nro_pago_forma" id="nro_pago_forma"
                                type="text" class="form-control  @error('nro_pago_forma') is-invalid @enderror" placeholder="Ej. 123456789"
                                style="border-radius: 10px;">
                                @error('nro_pago_forma')
                                <div class="text-danger">
                                    <b>El campo  es obligatorio.</b>
                                </div>
                                @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="total_forma_pago"> TOTAL PAGADO: <span style="color:#cb4335;">*</span></label>
                            <input wire:model="total_forma_pago" name="total_forma_pago" id="total_forma_pago"
                                type="number" class="form-control  @error('total_forma_pago') is-invalid @enderror" placeholder="Ej. 1000.00"
                                style="border-radius: 10px;">
                                @error('total_forma_pago')
                                <div class="text-danger">
                                    <b>El campo  es obligatorio.</b>
                                </div>
                                @enderror
                        </div>
                        <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                            <label for="file_digital_pago" style="color:#1a5276">SUBIR EN PDF EL COMPROBANTE DE PAGO: <spam style="color:#cb4335;">
                                    <b>*</b>
                                </spam></label>
                            <input class="form-control  @error('file_digital_pago') is-invalid @enderror"
                                type="file" id="formFiledigital" name="file_digital_pago" required
                                wire:model="file_digital_pago" accept="application/pdf">
                            @error('file_digital_pago')
                                <div class="text-danger">
                                    <b>El campo  es obligatorio, de tipo PDF.</b>
                                </div>
                            @enderror
                            <div wire:loading wire:target="file_digital_pago">
                                <x-cargando-dos />
                            </div>

                        </div>
                    </div>
                    <br>
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
                                <em class="ml-2"><b>Ingrese y complete los siguientes campos si realizo monto en excedente y/o deducción.</b>
                                </em>
                                
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                                    style="background-color:white ;">
                                    <div class="row">
                                        <div class='col-lg-10 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="monto_excedente">MONTO EXCEDENTE: </label>
                                            <input wire:model="monto_excedente" name="monto_excedente"
                                                id="monto_excedente" type="number" step="0.01"
                                                class="form-control" placeholder="Ej. 1000.00"
                                                style="border-radius: 10px;">
                                        </div>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="excedente_descripcion"> DESCRIPCIÓN: </label>
                                            <textarea wire:model="excedente_descripcion" name="excedente_descripcion" id="excedente_descripcion" type="text"
                                                class="form-control" placeholder=" Introduzca la descripción del monto excedente"
                                                oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;" rows="3"> </textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                                    style="background-color:white;">
                                    <div class="row">
                                        <div class='col-lg-10 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="monto_deduccion_r">MONTO DEDUCCIÓN:</label>
                                            <input wire:model="monto_deduccion_r" name="monto_deduccion_r"
                                                id="monto_deduccion_r" type="number" step="0.01"
                                                class="form-control" placeholder="Ej. 1000.00"
                                                style="border-radius: 10px;">
                                        </div>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="deduccion_descripcion_r">DESCRIPCIÓN:</label>
                                            <textarea wire:model="deduccion_descripcion_r" name="deduccion_descripcion" id="deduccion_descripcion_r"
                                                type="text" class="form-control" placeholder=" Introduzca la descripción del monto deducción"
                                                oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;" rows="3"> </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br>
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <br><br>
                            <x-cancel-button wire:click="atras">CANCELAR</x-cancel-button>
                            <x-save-button onclick="RegistroFormaDePago()">GUARDAR</x-save-button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function RegistroFormaDePago() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán todos los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrar_forma_de_pago_ext');
            }
        });
    }
    document.addEventListener('guardado_ext_forma_pago', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se guardo correctamente el registro.",
            icon: "success"
        });
    });
    document.addEventListener('no_guardado_ext_forma_pago', function() {
        Swal.fire({
            title: "No se guardo!",
            text: "Ocurrio un error, no se guardo  el registro.",
            icon: "error"
        });
    });
</script>
