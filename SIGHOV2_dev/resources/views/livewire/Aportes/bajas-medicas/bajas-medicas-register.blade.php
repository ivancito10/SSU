<div>

    <div class="card-header" style="background-color:#154360">

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;"> REGISTRAR REEMBOLSOS</h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered">
            </thead>
            <tr>
                <th colspan="5"
                    style="font-size: 22px;   
                                      padding-top: 0; padding-bottom: 0;">

                    {{ $institucion }}
                </th>
            </tr>

            <tr
                style="font-size: 16px; background-color: #1a5276 ; color:white; text-align:center; 
                                    height: 20px; line-height: 20px; padding-top: 0; padding-bottom: 0;">
                <th colspan="1"> Gestión </th>
                <th colspan="1"> Planilla</th>
                <th colspan="1">Total Salario Cotizable</th>
                <th colspan="1"> Importe</th>
                @if ($total_compensacion != 0)
                    <th colspan="1">Comp. Salario Mínimo Nacional</th>
                @endif

            </tr>
            </thead>
            <tbody
                style="font-size:19px; text-align:center;height: 20px; line-height: 20px; padding-top: 0; padding-bottom: 0;">
                <tr>
                    <td> {{ $mes }}, {{ $gestion }} </td>
                    <td>
                        @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                            <span style="font-size:16px;">De tipo: <b><em>MENSUAL</em> </b> </span>
                        @elseif ($reintegro == true)
                            <span style="font-size:16px;">De tipo: <b><em>REINTEGRO</em> </b>
                            </span>
                        @elseif($beneficio_social == true)
                            <span style="font-size:16px;">De tipo: <b><em>BENEFICIO SOCIAL</em>
                                </b> </span>
                        @elseif($planilla_adicional == true)
                            <span style="font-size:16px;">De tipo: <b><em>PLANILLA ADICIONAL</em>
                                </b> </span>
                        @elseif($retroactivo == true)
                            <span style="font-size:16px;">De tipo: <b><em>RETROACTIVO</em> </b>
                            </span>
                        @endif
                    </td>
                    <td> {{ $salario_cotizable_m }} <b>Bs.</b> </td>
                    <td> {{ $importe }} <b>Bs.</b> </td>
                    @if ($total_compensacion != 0)
                        <td> {{ $total_compensacion }} <b>Bs.</b> </td>
                    @endif
                </tr>

            </tbody>

        </table>

    </div>
    <br>
    <div>
        <span style="margin-left:15px;">
            <span style="background-color:#D6EAF8; color:#2471A3; border-radius:9px; padding:2px 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#2471A3"
                    class="bi bi-person-check-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
            </span>

        </span>
        <span><b style="margin-left:5px; font-size:18px;"> DETALLE DE APORTANTES</b></span>

    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @livewire('Aportes.BajasMedicas.aportantes-table', ['compId' => $compId])

        </div>

    </div>


    @include('livewire.Aportes.bajas-medicas.modalAbrirBajaPlanilla')
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>

    <!--modal para BAJAS MEDICAS -->
    {{--   <x-big-component id="modalBajaMedica" labelledby="modalBajaMedicaLabel" title="AGREGAR  BAJA MÉDICA"
        saveAction="saveBajaMedica()">

        <div class="row">

            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" style=" background-color:white;">
                <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276 ; color:white; border-radius:10px; "> APORTANTE
                                    INSTITUCIONAL</p>
                            </div>
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>NOMBRE COMPLETO: </strong>
                                <p>{{ $nombres }} {{ $p_apellido }} {{ $s_apellido }} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>CARGO: </b>{{ $cargo }} </span>

                            </div>
                            <div class= "col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <span><b>ITEM: </b>
                                    @if ($item == null)
                                        No tiene
                                    @elseif ($item != null)
                                        {{ $item }}
                                    @endif
                                </span>

                            </div>
                            <div class= "col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <span> <b>MATRICULA: </b>
                                    @if ($matricula == null)
                                        No tiene
                                    @elseif ($matricula != null)
                                        {{ $matricula }}
                                    @endif
                                </span>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em>Aporte Registrado.</em> </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="total_ganado"> TOTAL GANADO: </label>
                                <input class="form-control" type="text" id="total_ganado" wire:model='total_ganado'
                                    disabled style=" height: 30px; border-radius: 10px;">
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="total_ganado_dia"> GANADO POR DÍA: </label>
                                <input class="form-control" type="text"
                                    id="total_ganado_dia"wire:model='total_ganado_dia' disabled
                                    style=" height: 30px; border-radius: 10px;">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="total_aporte" style="color:#154360 ;"> APORTE PAGADO:</label>
                                <input class="form-control" type="text" id="total_aporte "wire:model='total_aporte'
                                    disabled style=" height: 30px; border-radius: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
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
                                    <em class="ml-2"><b>Agregar el registro de baja médica.</b> </em>
                                </p>
                            </div>
                        </div>

                        <!-- <div class="row">
                                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                        <label for="tgn">ES BAJA CONTINUA: <b style="color:#cb4335;">*</b> </label>
                                        <div class="d-flex flex-column flex-md-row align-items-center">
                                            <label class="mr-md-2 mb-2 mb-md-0">NO</label>
                                            <div
                                                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input wire:model="es_baja_continua" name="es_baja_continua" type="checkbox"
                                                    class="custom-control-input" id="customSwitch2">
                                                <label class="custom-control-label" for="customSwitch2"
                                                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                            </div>
                                            <label class="mr-md-2 mb-2 mb-md-0">SI</label>
                                        </div>
                                    </div>
                                </div> -->



                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_inicio"> FECHA DE INICIO:<b style="color:#cb4335;">*</b> </label>
                                <input wire:model="fecha_inicio" type="date" name="fecha_inicio"
                                    id="fecha_inicio" class="form-control" wire:change='CalcularMonto()'
                                    style="border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="dias_baja"> DÍAS DE BAJA:<b style="color:#cb4335;">*</b> </label>
                                <Input value="" wire:model="dias_baja" type="number" name="dias_baja"
                                    id="dias_baja" class="form-control" placeholder="Ingrese los dias de baja"
                                    wire:change='CalcularMonto()' style="border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                <label for="fecha_fin"> FECHA FINAL:<b style="color:#cb4335;">*</b> </label>
                                <Input value="" wire:model="fecha_final" type="date" name="fecha_fin"
                                    id="fecha_fin" class="form-control " disabled
                                    style="  background-color: #f8fcff;border-radius: 10px;">

                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <label for="tipo">TIPO DE BAJA MÉDICA:<b style="color:#cb4335;">*</b> </label>
                                <select wire:model="tipo_baja" name="tipo" id="tipo" class="form-control"
                                    wire:change='CalcularMonto()' style="border-radius: 10px;">
                                    <option value="0" selected>Seleccione el tipo de baja médica</option>
                                    <option value="ENFERMEDAD">ENFERMEDAD</option>
                                    <option value="MATERNIDAD">MATERNIDAD</option>
                                    <option value="ACCIDENTE">ACCIDENTE LABORAL</option>
                                </select>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="customSwitchSexo"> ¿ CON CONTINUIDAD LABORAL?:</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <strong style="color:#F54927">NO</strong>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                                        <input wire:model.live="continuidad" id="customSwitchSexo" type="checkbox"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="customSwitchSexo"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <strong style="color:#38BA47">SI</strong>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <label for="monto_baja_medica"> TOTAL:</label>
                                <Input value="" wire:model="monto_baja_des" type="text"
                                    name="monto_baja_medica" id="monto_baja_medica" class="form-control " disabled
                                    style=" height: 30px; background-color: #fdfef2; border-radius: 10px; text-align:right;">
                            </div>


                        </div>
                    </div>
                </div>

            </div>
        </div>

    </x-big-component> --}}

</div>




<script>
    // ESTO ES PARA ESCUCHAR MODAL BAJA MEDICA
    window.addEventListener('openModalBajasMedicas', event => {
        $('#modalBajaMedica').modal('show');
    });

    /*     function saveBajaMedica() {
            Swal.fire({
                title: '¿Guardar Baja Médica?',
                text: "Se registrarán los datos ingresados.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('GuardarBaja');
                    $('#modalBajaMedica').modal('hide');
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                    $('#modalBajaMedica').modal('hide');
                }
            });
        } */



    /*    document.addEventListener('BajaMedicaRegistrado', function() {
           $('#modalBajaMedica').modal('hide');
           Swal.fire({
               title: "Guardado!",
               text: "La baja médica se registró correctamente",
               icon: "success"

           });
       }); */



    document.addEventListener('eliminarBajaMedicaPlanilla', function() {

        Swal.fire({
            title: '¿Eliminar baja médica?',
            text: "Se eliminará la baja médica registrada.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('eliminarBajaPlanilla');
            } else {
                Swal.fire({
                    title: "Cancelado",
                    text: "Se canceló la acción",
                    icon: "info"
                });
            }
        });
    });

    document.addEventListener('bajaEliminada', function() {
        Swal.fire({
            title: "Baja médica eliminada",
            text: "La baja médica se eliminó satisfactoriamente",
            icon: "success"

        });
    });
    document.addEventListener('bajaNoEliminada', function() {
        Swal.fire({
            title: "Error",
            text: "No se pudo borrar la baja médica, verifique e inténtelo nuevamente",
            icon: "warning"
        });
    });
</script>
