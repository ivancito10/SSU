<div>
    <x-big-component id="anulacionVacacion" labelledby="anulacionVacacionLabel" title="ANULACIÓN DE VACACIÓN"
        saveAction="guardarAnulVacacion()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card card-comun p-2">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <div class="cabecera">
                                <b>ESPECIALISTA</b>
                            </div>
                            <div style="height: 0.5rem;"></div>

                            <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="ESPECIALISTA"
                                style="width: 200px; height: 200px; object-fit: cover;">
                            <br>
                            @if ($estadoEspecialista)
                                <span style='color:#46BD26 ;' class="h4"><b>ACTIVO</b></span>
                            @else
                                <span style='color:#FF350D ;' class="h4"><b>INACTIVO</b></span>
                            @endif
                            <br>
                            @if ($permanente)
                                <span style='color:#2874A6 ;' class="h4"><b>PERMANENTE</b></span>
                            @else
                                <span style='color:#DC7633 ;' class="h4"><b>EVENTUAL</b></span>
                            @endif

                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nombres"> NOMBRE
                                        COMPLETO:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $nombres }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <label for="matricula">
                                        MATRÍCULA:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $matricula }}</p>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <label for="ci"> CARNET:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $ci }}</p>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                    <label for="expedido">EXPEDIDO.:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $expedido }}</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                    <label for="es_extranjero"> TIPO DOC.:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $es_extranjero ? 'EXTRANJERO' : 'NACIONAL' }}</p>
                                </div>


                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ \Carbon\Carbon::parse($fecha_nacimiento)->translatedFormat('d /m/ Y') }}
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <strong>ESPECIALIDAD:</strong>
                                    <span style="color:#2c3e50;" class="h3">
                                        <b>{{ $doc_especialidad }} </b>
                                    </span>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="grado">
                                        GRADO ACADÉMICO:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $grado }}</p>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <label for="fecha_inicio">
                                        CONTRATO DESDE:</label>
                                    <p class="p-1 p-comun-disabled">
                                        {{ \Carbon\Carbon::parse($fecha_inicio)->translatedFormat('d /m/ Y') }}</p>
                                </div>
                                @if (!$permanente)
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                        <label for="fecha_fin">
                                            CONTRATO HASTA:</label>
                                        <p class="p-1 p-comun-disabled">
                                            {{ \Carbon\Carbon::parse($fecha_fin)->translatedFormat('d /m/ Y') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-2 card-comun">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <p style="color:#2471a3" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2"><b>Ingresar el motivo de anulación:</b>
                                </em>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicio_vacacion">NRO DE VACACIÓN: </label>
                            <input class="form-control input-comun" id="fecha_inicio_vacacion" wire:model="nroU"
                                disabled>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="inicioVacacionU">INICIO DE VACACIÓN: </label>
                            <input class="form-control input-comun" id="inicioVacacionU" wire:model="inicioVacacionU"
                                type="date" disabled>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="finVacacionU">FIN DE VACACIÓN: </label>
                            <input class="form-control input-comun" id="finVacacionU" wire:model="finVacacionU"
                                type="date" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="observacionU">INGRESE MOTIVO DE ANULACIÓN :</label>
                            <textarea class="form-control input-comun" id="observacionU" wire:model=observacionU style="resize:none;"></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </x-big-component>
</div>
<script>
    window.addEventListener('modalAnulacionVacacion', event => {

        $('#anulacionVacacion').modal('show');
    });

    function guardarAnulVacacion() {
        Swal.fire({
            title: '¿Registrar Vacación?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarAnulacionVacacion');
                $('#anulacionVacacion').modal('hide');

            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#anulacionVacacion').modal('hide');

            }
        });
    }
</script>
