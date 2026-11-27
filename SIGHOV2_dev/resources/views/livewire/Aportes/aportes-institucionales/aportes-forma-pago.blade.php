<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">AGREGAR REGISTRO DE
                FORMA DE PAGO </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <div class="row">
        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <spam style="text-decoration:underline;"><em>Anterior Comprobante
                                            Liquidado.</em> </spam>
                                </b>
                            </p>
                        </div>

                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span><b>NRO. COMPROBANTE: </b> {{ $nro_compro_anterior_m }} </span>
                        </div>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span><b> EN FECHA: </b> {{ $fecha_liquidado_ant }} </span>

                        </div>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span><b> TOTAL LIQUIDADO: </b> <em>{{ $total_compro_anterior }} </em> Bs. </span>
                        </div>


                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <p style="background-color:#1a5276; color:white; border-radius:10px; ">ACTUAL INSTITUCIÓN
                            </p>
                        </div>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <p style="font-size:19px;"><b><em> {{ $nombre_institucion }}</em> </b></p>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <span><b>TIPO APORTANTE: </b>{{ $tipo_clase }} </span>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <span><b>TGN: </b>
                                @if ($tgn)
                                    SI
                                @else
                                    NO
                                @endif
                            </span>

                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <p> <span><b>PLANILLA DE TIPO: </b> </span>

                                @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                                    <span><b><em>MENSUAL </em> </b></span>
                                @elseif ($reintegro == true)
                                    <span><b> <em>REINTEGRO </em></b></span>
                                @elseif($beneficio_social == true)
                                    <span><b> <em> BENEFICIO SOCIAL</em></b></span>
                                @elseif($planilla_adicional == true)
                                    <span><b><em> PLANILLA ADICIONAL</em> </b></span>
                                @elseif($retroactivo == true)
                                    <span><b><em> RETROACTIVO</em> </b></span>
                            </p>
                            @endif

                        </div>

                        <div class='col-lg-7 col-md-12 col-sm-12 col-xs-12'>
                            <span><b>MES: </b> {{ $nombre_mes }} </span>

                        </div>
                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <span><b>GESTIÓN: </b> {{ $gestion }} </span>
                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <br>
                            <label for="salario_cotizable_m"> TOTAL SALARIO COT.: </label>
                            <input class="form-control" type="text" wire:model='salario_cotizable_m' disabled
                                style=" height: 30px; border-radius: 10px; text-align:right;">

                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <br>
                            <label for="importe"> IMPORTE: </label>
                            <input class="form-control" type="text" wire:model='importe' disabled
                                style=" height: 30px; border-radius: 10px; text-align:right;">
                        </div>
                        @if ($total_compensacion != 0)
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="total_compensacion"> COMPEN. DE SALARIO: </label>
                                <input class="form-control" type="text" wire:model='total_compensacion' disabled
                                    style=" height: 30px; border-radius: 10px; text-align:right;">
                            </div>
                        @endif
                        @if ($valor_tgn != 0)
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="valor_tgn"> MENOS EL 5% AL TGN: </label>
                                <input class="form-control" type="text" wire:model='valor_tgn' disabled
                                    style=" height: 30px; border-radius: 10px; text-align:right;">
                            </div>
                        @endif

                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="aporte_a_liquidar" style="color:#34495e;"> TOTAL: </label>
                            <input class="form-control" id="aporte_a_liquidar" type="text"
                                wire:model='aporte_a_liquidar' disabled
                                style=" height: 30px; border-radius: 10px; text-align:right;">
                        </div>

                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p class="p-1">
                                <b>Nota: </b> <em> Realice la Pre-liquidación para obtener cálculos
                                    más precisos del TOTAL a DEPOSITAR. </em>


                            </p>
                            <button class="btn btn-warning" wire:click="link_preliquidacion"
                                title="IR A LA VISTA PRE - LIQUIDACIÓN">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    fill="currentColor" class="bi bi-calculator-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2zm2 .5v2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0-.5.5m0 4v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M4.5 9a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 12.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5M7.5 6a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM7 9.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM10 6.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5m.5 2.5a.5.5 0 0 0-.5.5v4a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 0-.5-.5z" />
                                </svg><b style="  font-size:16px;"> Ir a Pre-liquidación </b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 mt-3 mt-lg-0">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">

                    @can('super-admin-only')
                        <div class="row">

                            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <x-agregar-button wire:click="agregar_recibo_caja">AGREGAR RECIBO </x-agregar-button>
                            </div>
                            @include('livewire.Aportes.aportes-institucionales.modalListaRecibosCaja')
                        </div>

                        <div class="card card-comun p-2">
                            <fieldset class="">
                                <legend>
                                    <strong><svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                            fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
                                            <path
                                                d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z" />
                                        </svg> Lista de Recibos Seleccionados </strong>
                                </legend>

                                <div class="table-responsive">
                                    <table class="table tabla-celeste">
                                        <thead style="text-align:center;">
                                            <tr>
                                                <th rowspan="2"> NRO</th>
                                                <th rowspan="2"> NRO. RECIBO </th>
                                                <th rowspan="2"> FECHA </th>
                                                <th rowspan="2"> TOTAL RECIBO</th>
                                                <th rowspan="2"> DETALLE </th>
                                                <th rowspan="2"> CONCEPTO</th>
                                                <th rowspan="2"> OPCIONES </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recibos_seleccionados as $recibo)
                                                <tr>
                                                    <td style="text-align:center;"> {{ $loop->iteration }} </td>
                                                    <td style="text-align:center;"> <b>{{ $recibo['nro_recibo'] }} </b>
                                                    </td>
                                                    <td style="text-align:left;"> {{ $recibo['fecha_recibo'] }}
                                                    </td>
                                                    <td style="text-align:center;">
                                                        <b>{{ $recibo['total_recibo'] }}</b>
                                                    </td>
                                                    <td style="text-align:center;">
                                                        @if (count($recibo['recibo_oficial_caja_detalle_recibo']) > 0)
                                                            <div class="row">
                                                                @foreach ($recibo['recibo_oficial_caja_detalle_recibo'] as $detalle)
                                                                    {{ $detalle['total'] }} -
                                                                    {{ $detalle['tipo_deposito'] }}
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td style="text-align:center;"> {{ $recibo['concepto'] }} </td>

                                                    <td style="text-align:center;"> <x-eliminar-button
                                                            wire:click="eliminarRecibo('{{ $recibo['id'] }}')"></x-eliminar-button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </t>
                        </div>


                        <br>
                        <hr>
                    @endcan
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
                                <em class="ml-2"><b>Registrar la forma de pago (depósito).</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fecha_pago"> FECHA DE PAGO: </label>
                            <input wire:model="fecha_pago" name="fecha_pago" id="fecha_pago" type="date"
                                class="form-control @error('fecha_pago') is-invalid @enderror"
                                placeholder="FECHA DE PAGO" style="border-radius: 10px;">
                            @error('fecha_pago')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tipo_pago">TIPO PAGO:</label>
                            <select wire:model="tipo_pago" name="tipo_pago" id="tipo_pago"
                                class="form-control @error('tipo_pago') is-invalid @enderror"
                                style="border-radius: 10px;">
                                <option value="">Seleccione tipo pago</option>
                                <option value="BANCO">BANCO</option>
                                <option value="CHEQUE">CHEQUE</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                            </select>
                            @error('tipo_pago')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nro_pago_forma"> NRO. DOCUMENTO: </label>
                            <input wire:model="nro_pago_forma" name="nro_pago_forma" id="nro_pago_forma"
                                type="text" class="form-control @error('nro_pago_forma') is-invalid @enderror"
                                placeholder="Ej. 123456789" style="border-radius: 10px;">
                            @error('nro_pago_forma')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <label for="total_forma_pago"> TOTAL DEPOSITADO: </label>
                            <input wire:model="total_forma_pago" name="total_forma_pago" id="total_forma_pago"
                                type="number" class="form-control @error('total_forma_pago') is-invalid @enderror"
                                placeholder="Ej. 1000.00" style="border-radius: 10px;" min="0">
                            @error('total_forma_pago')
                                <div class="text-danger">
                                    <b>El campo es obligatorio.</b>
                                </div>
                            @enderror
                        </div>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <p style="color:#1a5276 ;" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2"><b>Ingrese los siguientes campos si es necesario.</b>
                                </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="background-color:white ;">
                                    <div class="row">
                                        <div class='col-lg-10 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="monto_excedente">MONTO EXCEDENTE: </label>
                                            <input wire:model="monto_excedente" name="monto_excedente"
                                                id="monto_excedente" type="number" step="0.01"
                                                class="form-control" placeholder="Ej. 1000.00"
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="excedente_descripcion"> DESCRIPCIÓN: </label>
                                            <textarea wire:model="excedente_descripcion" name="excedente_descripcion" id="excedente_descripcion" type="text"
                                                class="form-control" placeholder=" Introduzca la descripción del monto excedente"
                                                oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;" rows="3"> </textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="background-color:white;">
                                    <div class="row">
                                        <div class='col-lg-10 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="monto_deduccion_r">MONTO DEDUCCIÓN:</label>
                                            <input wire:model="monto_deduccion_r" name="monto_deduccion_r"
                                                id="monto_deduccion_r" type="number" step="0.01"
                                                class="form-control" placeholder="Ej. 1000.00"
                                                style="border-radius: 10px;" min="0">
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
                    <br><br><br><br><br><br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <x-cancel-button wire:click="atras">CANCELAR</x-cancel-button>
                            <x-save-button onclick="registrar()">GUARDAR</x-save-button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<script>
    function registrar() {
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
                Livewire.dispatch('RegistrarFromaDePago');
            }
        });
    }
    document.addEventListener('SeRegistroFormaDePago', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La forma de pago se registró correctamente!",
            icon: "success"

        });
    });
    window.addEventListener('eliminarRecibo', event => {
        Swal.fire({
            title: "¡Eliminado!",
            text: "El recibo se quito de la lista",
            icon: "warning"

        });
    });
    document.addEventListener('NoSeRegistroFormaDePago', function() {
        Swal.fire({
            title: "Registro cancelado!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });
</script>
