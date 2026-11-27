<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">

            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;"> AGREGAR REGISTRO
                COMPROBANTE </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <p style="background-color:#1a5276; color:white; border-radius:10px; ">APORTANTE VOLUNTARIO
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>NOMBRE COMPLETO: </strong>
                            <p>{{ $persona->nombres }} {{ $persona->p_apellido }} {{ $persona->s_apellido }} </p>

                        </div>

                        <div class= "col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <span><b>CARNET: </b> {{ $persona->ci }} {{ $persona->complemento }} </span>
                        </div>

                        <div class= "col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <span><b>FECHA DE NACIMIENTO: </b>
                                {{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }} </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <spam style="text-decoration:underline;"><em>Anterior Comprobante Liquidado.</em>
                                    </spam>
                                </b>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span><b>NRO. COMPROBANTE: </b> {{ $nroComproAnterior_m }} </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span><b> FECHA LIQUIDADA: </b> {{ $fechaLiquidacion_ant }} </span>
                        </div>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <span><b> APORTE LIQUIDADO: </b><em>{{ number_format($totalComproAnterior, 2, ',', '.') }}
                                </em> <b>Bs.</b> </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    @can('super-admin-only')
                        <div class="row">
                            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <x-agregar-button wire:click="agregar_recibo_caja">AGREGAR RECIBO </x-agregar-button>
                            </div>
                            {{-- resources/views/livewire/Aportes/aportes-voluntarios/modalListaRecibosCajaVoluntarios.blade.php --}}
                            @include('livewire.Aportes.aportes-voluntarios.modalListaRecibosCajaVoluntarios')
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
                                                        <b>{{ number_format($recibo['total_recibo'], 2, ',', '.') }}</b>
                                                    </td>
                                                    <td style="text-align:center;">
                                                        @if (count($recibo['recibo_oficial_caja_detalle_recibo']) > 0)
                                                            <div class="row">
                                                                @foreach ($recibo['recibo_oficial_caja_detalle_recibo'] as $detalle)
                                                                    {{ number_format($detalle['total'], 2, ',', '.') }}
                                                                    -
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
                                <em class="ml-2"><b>Ingresar y registrar la forma de pago.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fechaPago"> FECHA DE PAGO: </label>
                            <input wire:model="fechaPago" name="fechaPago" id="fechaPago" type="date"
                                class="form-control" placeholder="" wire:blur="calculoTotalAporte" required
                                style="border-radius: 10px;">
                            @error('fechaPago')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Introduzca la fecha.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>



                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="montoTotalPago"> TOTAL PAGADO: </label>
                            <input wire:model="montoTotalPago" name="montoTotalPago" id="montoTotalPago" type="number"
                                type="numeric" class="form-control" placeholder="Ej.1000.00" required
                                style="border-radius: 10px;" @if ($fechaPago) disabled @endif>
                            @error('montoTotalPago')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Introduzca el monto.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tipoPago">TIPO DEPÓSITO:</label>
                            <select wire:model="tipoPago" name="tipoPago" id="tipoPago" class="form-control"
                                required style="border-radius: 10px;">
                                <option selected>Seleccione tipo pago</option>
                                <option value="BANCO">BANCO</option>
                                <option value="CHEQUE">CHEQUE</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                            </select>
                            @error('tipoPago')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Introduzca el tipo depósito.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nroFormaPago"> NRO. DOCUMENTO: </label>
                            <input wire:model="nroFormaPago" name="nroFormaPago" id="nroFormaPago" type="text"
                                class="form-control" placeholder="Ej.123456789" required
                                style="border-radius: 10px;">
                            @error('nroFormaPago')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Introduzca el nro. de documento.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
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
                                <em class="ml-2"><b>Agregar el registro de liquidación.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fechaLiquidacion"> FECHA LIQUIDACIÓN: </label>
                            <input wire:model="fechaLiquidacion" name="fechaLiquidacion" id="fechaLiquidacion"
                                type="date" class="form-control" placeholder="FECHA DE LIQUIDACION" required
                                style="border-radius: 10px;">
                            @error('fechaLiquidacion')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Introduzca la fecha.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="mesId">MES:</label>
                            <select wire:model="mesId" id="mesId" name="mesId" class="form-control"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" aria-hidden="true" required>
                                <option selected="selected">Seleccione el mes.</option>
                                @foreach ($meses as $dato)
                                    <option value="{{ $dato->id }}">{{ $dato->mes }}</option>
                                @endforeach
                            </select>
                            @error('mesId')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Introduzca el mes.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>

                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="gestion">GESTIÓN:</label>
                            <input wire:model="gestion" name="gestion" id="gestion" class="form-control"
                                type="number" required style="border-radius: 10px;">
                            @error('gestion')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="row">
                                <div class="col-lg-6" style="background-color:white;">
                                    <div class="row">
                                        <div class= "col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <label for="montoDemasia">MONTO EXCEDENTE: </label>
                                            <input wire:model.live="montoDemasia" name="montoDemasia" id="montoDemasia"
                                                type="number" step="0.01" class="form-control"
                                                placeholder="Ej.1000.00" wire:change='calculoTotalAporte()'
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="demasiaDescripcion">DESCRIPCIÓN: </label>
                                            <textarea wire:model="demasiaDescripcion" name="demasiaDescripcion" id="demasiaDescripcion" type="text"
                                                class="form-control" placeholder="Introduzca la descripción" oninput="this.value = this.value.toUpperCase()"
                                                required style="border-radius: 10px;"></textarea>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="background-color:white;">
                                    <div class="row">
                                        <div class= "col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <label for="montoDeduccion">MONTO DEDUCCIÓN:</label>
                                            <input wire:model.live="montoDeduccion" name="montoDeduccion"
                                                id="montoDeduccion" type="number" step="0.01"
                                                class="form-control" placeholder="Ej.1000.00"
                                                wire:change='calculoTotalAporte()' style="border-radius: 10px;"
                                                min="0">
                                        </div>
                                        <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="deduccionDescripcion">DESCRIPCIÓN:</label>
                                            <textarea wire:model="deduccionDescripcion" name="deduccionDescripcion" id="deduccionDescripcion" type="text"
                                                class="form-control" placeholder=" Introduzca la descripcion" oninput="this.value = this.value.toUpperCase()"
                                                required style="border-radius: 10px;"></textarea>
                                        </div>
                                    </div>
                                </div>
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
                                <em class="ml-2"><b>Verificar los datos evaluados.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <div class="row">

                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style="">
                            <span><b>NRO. COMPROBANTE: </b> {{ $nroComprobanteGestion }} </span>
                            <br>
                        </div>

                        <div class= "col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="totalGanado">TOTAL GANADO (500 UFV):</label>
                            <input wire:model="totalGanado" name="totalGanado" id="totalGanado" type="number"
                                class="form-control" placeholder="Ej.1000.00"
                                @if ($fechaPago) disabled @endif
                                style=" border-radius: 10px; text-align:right;" min="0">
                        </div>
                        <div class= "col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="totalAporte"> APORTE POR EL 10%:</label>
                            <input wire:model="totalAporte" name="totalAporte" id="totalAporte" type="number"
                                class="form-control" placeholder="Ej.1000.00"
                                @if ($fechaPago) disabled @endif
                                style=" border-radius: 10px; text-align:right;" min="0">
                        </div>
                        <div class= "col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="totalAporteActualizado">TOTAL:</label>
                            <input wire:model="totalAporteActualizado" name="totalAporteActualizado"
                                id="totalAporteActualizado" type="number" class="form-control"
                                placeholder="Ej.1000.00" step="0.01"
                                @if ($fechaPago) disabled @endif
                                style=" background-color:#fdfef2; border-radius: 10px ;text-align:right;">
                        </div>

                    </div>
                    <br>
                    <div class="row">

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <x-cancel-button wire:click="cancelarAporte">CANCELAR</x-cancel-button>
                            <x-save-button onclick="registrarAporte()">GUARDAR</x-save-button>

                        </div>
                    </div>
                </div>
            </div>


        </div>



    </div>





    <script>
        function registrarAporte() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('crearNuevoAporte');
                } else {
                    Swal.fire({
                        title: "Registro cancelado!",
                        text: "Usted canceló el registro.",
                        icon: "error"
                    });
                }
            });
        }



        document.addEventListener('aporteVoluntarioCreado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La liquidación se registró correctamente.",
                icon: "success"

            });

        });
        document.addEventListener('aporteVoluntarioCancelado', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Usted canceló el registro.",
                icon: "error"
            });
        });

        document.addEventListener('reciboYaAgregado', function() {
            Swal.fire({
                title: "¡Recibo ya agregado!",
                text: "El recibo ya fue agregado.",
                icon: "warning"

            });
        });
        document.addEventListener('noExisteUfv', function() {
            Swal.fire({
                title: "Actualize los valores de la UFV!",
                text: "Se canceló el registro.",
                icon: "error"

            });
        });
        document.addEventListener('error', function() {
            Swal.fire({
                title: "Error!",
                text: "Sucedió un error, verifique el error.",
                icon: "warning"
            });
        });
        window.addEventListener('eliminarReciboVoluntario', event => {
            Swal.fire({
                title: "¡Eliminado!",
                text: "El recibo se quito de la lista",
                icon: "warning"

            });
        });
    </script>


</div>
