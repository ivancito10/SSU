<div>
    <x-big-component id="agregarVacacion" labelledby="agregarVacacionLabel" title="REGISTRO DE VACACIÓN"
        saveAction="guardarVacacion()">
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
                                style="width: 150px; height: 150px; object-fit: cover;">
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
                                    <strong for="nombres"> NOMBRE
                                        COMPLETO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $nombres }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <strong for="matricula">
                                        MATRÍCULA:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $matricula }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <strong for="ci"> CARNET:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $ci }}</p>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                    <strong for="expedido">EXPEDIDO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $expedido }}</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                    <strong for="es_extranjero"> TIPO DOC.:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $es_extranjero ? 'EXTRANJERO' : 'NACIONAL' }}</p>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <strong for="fecha_nacimiento"> FECHA DE NACIMIENTO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
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
                                    <strong for="grado">
                                        GRADO ACADÉMICO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $grado }}</p>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <strong for="fecha_inicio">
                                        INICIO CONTRATO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ \Carbon\Carbon::parse($fecha_inicio)->translatedFormat('d/m/Y') }}</p>
                                </div>
                                @if (!$permanente)
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                        <strong for="fecha_fin">
                                            FIN CONTRATO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ \Carbon\Carbon::parse($fecha_fin)->translatedFormat('d/m/Y') }}
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
                <div class="card card-comun p-2">
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
                                <em class="ml-2"><b>Ingresar y completar los datos para el registro vacación:</b>
                                </em>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_vacacion">NRO. BOLETA RR.HH: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control" id="nro_vacacion" wire:model="nro_vacacion" type="text"
                                placeholder="Ingrese el número de boleta de RRHH"
                                oninput="this.value = this.value.toUpperCase()" style="border-radius:8px;">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                            <label for="fecha_inicio_vacacion">FECHA INICIO: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control" id="fecha_inicio_vacacion" wire:model="fecha_inicio_vacacion"
                                type="date" min="{{ now()->toDateString() }}" style="border-radius:8px;">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                            <label for="fecha_fin_vacacion">FECHA FIN: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control" id="fecha_fin_vacacion" wire:model="fecha_fin_vacacion"
                                type="date" min="{{ now()->toDateString() }}" style="border-radius:8px;">
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </x-big-component>
</div>
<script>
    window.addEventListener('modalAgregarHorario', event => {

        $('#agregarVacacion').modal('show');
    });

    // REgistro exitoso de vacacion 
    document.addEventListener('registroExitosoVacacion', function() {
        Swal.fire({
            title: "Registro de vacacion exitoso!",
            text: "Se guardó el registro",
            icon: "success",
            timer: 10000,


        });
    });

    function guardarVacacion() {
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
                Livewire.dispatch('registrarVacacion');
                $('#agregarVacacion').modal('hide');

            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#agregarVacacion').modal('hide');

            }
        });
    }
    // Error enn el registro de la especalidad
    document.addEventListener('errorRegistroVacacion', function() {
        Swal.fire({
            title: "¡Error en registro !",
            text: " Porfavor completa todos los campos",
            icon: "error",
            timer: 10000,
        });
    });
</script>
