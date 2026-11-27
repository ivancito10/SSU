<div>@can('registrar.aportes_voluntarios')
        <!--LISTA CON  MENU DE ACCIONES  PRINCIPAL-->

        <div class="input-group pr-4" style="justify-content: space-between; ">
            <H4 style="color:#212f3c ;">APORTES - <b> LISTA DE APORTES POR VOLUNTARIOS</b></H4>
            <!-- Aqui va el boto agregar-->
            @livewire('Aportes.aportes-voluntarios.aporte-voluntario-register')
        </div>
        <br>
    @endcan
    @can('lista.aportes_voluntarios')
        <div class="row">
            <div class="col-lg-12">
                @livewire('Aportes.AportesVoluntarios.aporte-voluntario-table')

                @can('ver.aportes_voluntarios')
                </div>
            </div>
            <!-- modal ver aportes voluntario-->
            @include('livewire.Aportes.aportes-voluntarios.modalAbrirAportesVoluntario')

            <!-- MODAL PARA VER LA PRE LIQUIDACION -->
            <x-ver-modal id="preliqModal" labelledby="preliqModalLabel" title=" EVALUAR PRE - LIQUIDACIÓN">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center; ">
                                        <p style="background-color:1a5276   ; color:white; border-radius:10px;">APORTANTE
                                            VOLUNTARIO</p>
                                    </div>
                                    <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <strong>NOMBRE COMPLETO: </strong>
                                        <p>{{ $nombresP }} {{ $pApellidoP }} {{ $sApellidoP }} </p>
                                    </div>

                                    <div class= "col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <span><b>CARNET: </b> {{ $ciP }} {{ $complementoP }} </span>
                                    </div>

                                    <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span><b>FECHA DE NACIMIENTO: </b>{{\Carbon\Carbon::parse($fechaNacimientoP)->format('d/m/Y')  }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0" style="background-color:white;">
                        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
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
                                            <em class="ml-2"><b>Ingrese los siguientes datos para evaluar la
                                                    pre-liquidación.</b> </em>
                                            <spam style="color:#cb4335;"><b>*</b></spam>
                                        </p>
                                    </div>

                                    <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                        <label for="fechaPagoP"> FECHA DE PAGO: </label>
                                        <input wire:model="fechaPagoP" name="fechaPagoP" id="fechaPagoP" type="date"
                                            class="form-control" placeholder="FECHA DE PAGO" style="border-radius: 10px;">
                                    </div>
                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="montoDemasiaP">MONTO EXCEDENTE: </label>
                                        <input wire:model="montoDemasiaP" name="montoDemasiaP" id="montoDemasiaP" type="number"
                                            step="0.01" class="form-control" placeholder="Ej.1,000.00"
                                            style="border-radius: 10px;">
                                    </div>

                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="montoDeduccionP">MONTO DEDUCCIÓN:</label>
                                        <input wire:model="montoDeduccionP" name="montoDeduccionP" id="montoDeduccionP"
                                            type="number" step="0.01" class="form-control" placeholder="Ej.1,000.00"
                                            style="border-radius: 10px;">

                                    </div>
                                    <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                        <br>
                                        <x-agregar-button wire:click="calcularPreliq">EVALUAR</x-agregar-button>
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                        <p style="background-color:white; color:#1f618d;" class="h5">
                                            <b>
                                                <spam style="text-decoration:underline;"><em>Datos Evaluados.</em> </spam>
                                            </b>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="totalGanadoP">TOTAL GANADO:</label>
                                        <input wire:model="totalGanadoP" name="totalGanadoP" id="totalGanadoP"
                                            class="form-control" placeholder="0,00" disabled style="border-radius: 10px;">
                                    </div>
                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="totalAporteP"> APORTE 10%:</label>
                                        <input wire:model="totalAporteP" name="totalAporteP" id="totalAporteP"
                                            class="form-control" placeholder="0,00" disabled style="border-radius: 10px;">
                                    </div>
                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="montoDemasiaP"> EXCEDENTE: </label>
                                        <input wire:model="montoDemasiaP" name="montoDemasiaP" id="montoDemasiaP" type="number"
                                            step="0.01" class="form-control" placeholder="0,00" disabled
                                            style="border-radius: 10px;">
                                    </div>

                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="montoDeduccionP"> DEDUCCIÓN:</label>
                                        <input wire:model="montoDeduccionP" name="montoDeduccionP" id="montoDeduccionP"
                                            type="number" step="0.01" class="form-control" placeholder="0,00" disabled
                                            style="border-radius: 10px;">
                                    </div>

                                    <div class='col-lg-9 col-md-12 col-sm-12 col-xs-12'>
                                    </div>


                                    <div class= "col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="totalAporteActualizadoP">TOTAL:</label>
                                        <input wire:model="totalAporteActualizadoP" name="totalAporteActualizadoP"
                                            id="totalAporteActualizadoP" class="form-control" placeholder="Ej.1,000.00"
                                            disabled style="  background-color: #fdfef2; border-radius: 10px;">
                                        <br>
                                    </div>



                                </div>
                            </div>
                        </div>

                    </div>



                </div>
            </x-ver-modal>


            {{-- Para el cargando --}}
            <div wire:loading>
                <x-cargando-dos />
            </div>
        @endcan
        
        <script>
            // ESTO ES PARA ESCUCHAR MODAL DE GRUPO DE APORTANTES
            window.addEventListener('openModalAportantesVoluntarios', event => {
                $('#aportantesModal').modal('show');
            });

            window.addEventListener('openModalPreliquidacion', event => {
                $('#preliqModal').modal('show');
                // Para scrooll del primer modal 
                $('#aportantesListosModal').css('overflow-y', 'auto');
            }); 

            function noExiste() {
                Swal.fire({
                    title: "Actualize los valores de la UFV",
                    icon: "error"
                });
            }

            function noFechaPago() {
                Swal.fire({
                    title: "Introduzca una fecha de pago",
                    icon: "error"
                });
            }

            document.addEventListener('noExisteUfv', function() {
                noExiste();
            });

            document.addEventListener('sinFechaPago', function() {
                noFechaPago();
            });
            document.addEventListener('error', function() {
                Swal.fire({
                    title: "Error!",
                    text: "Sucedió un error, verifique el error.",
                    icon: "warning"
                });
            });
            document.addEventListener('aporteVoluntarioCreado', function() {
                Swal.fire({
                    title: "Guardado!",
                    text: "La liquidación se registró correctamente.",
                    icon: "success"
                });
            });
        </script> 
    @endcan
</div>

