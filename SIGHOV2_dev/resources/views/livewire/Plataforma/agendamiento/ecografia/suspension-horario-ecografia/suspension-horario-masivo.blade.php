<div>

    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                AGREGAR SUSPENSIÓN DE HORARIO MASIVA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="volver" wire:loading.attr="disabled" wire:target="volver">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="volver">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3 mt-lg-0">
            <div class="card card-comun p-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <p style="color:#2471a3" class="h5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b>Ingresar y completar los siguientes campos:</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="fechaSuspension"> FECHA SUSPENSIÓN: </label>
                        <input class="form-control input-comun @error('fechaSuspension') is-invalid @enderror"
                            id="fechaSuspension" type="date" wire:model="fechaSuspension"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @error('fechaSuspension')
                            <div class="text-danger">
                                <b>El campo fecha de suspension es obligatorio.</b>
                            </div>
                        @enderror
                    </div>


                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="idMotivoSuspension"> MOTIVO SUSPENSIÓN:</label>
                        <select wire:model="idMotivoSuspension" id="idMotivoSuspension"
                            class="form-control input-comun @error('idMotivoSuspension') is-invalid @enderror">
                            <option value="0" selected>Seleccione el motivo...</option>
                            @foreach ($motivos as $motivo)
                                <option value={{ $motivo->id }}>{{ $motivo->nombre_motivo }}
                                </option>
                            @endforeach
                        </select>
                        @error('idMotivoSuspension')
                            <div class="text-danger">
                                <b>El campo motivo de suspension es obligatorio.</b>
                            </div>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="horaInicioSuspension"> HORA INICIO SUSPENSIÓN: </label>
                        <input class="form-control input-comun @error('horaInicioSuspension') is-invalid @enderror"
                            id="horaInicioSuspension" type="time" wire:model="horaInicioSuspension">
                        @error('horaInicioSuspension')
                            <div class="text-danger">
                                <b>El campo hora inicio suspension es obligatorio.</b>
                            </div>
                        @enderror
                    </div>

                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="horaFinSuspension"> HORA FIN SUSPENSIÓN: </label>
                        <input class="form-control input-comun @error('horaFinSuspension') is-invalid @enderror"
                            id="horaFinSuspension" type="time" wire:model="horaFinSuspension">
                        @error('horaFinSuspension')
                            <div class="text-danger">
                                <b>El campo hora fin suspension es obligatorio.</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="descripcion"> DESCRIPCIÓN: </label>
                        <textarea class="form-control input-comun @error('descripcion') is-invalid @enderror" id="descripcion"
                            placeholder="Ingrese la descripción..." wire:model="descripcion" oninput="this.value = this.value.toUpperCase()"></textarea>
                        @error('descripcion')
                            <div class="text-danger">
                                <b>El campo descripcion es obligatorio.</b>
                            </div>
                        @enderror

                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">

                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
                        <x-buscar-dos-button wire:click="validarSeleccion" class="p-1"><b>VALIDAR
                                SELECCIÓN</b></x-buscar-dos-button>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                        @if (!$switchValidado) hidden @endif>
                        <div class="card-body pt-2 pr-0 pl-0">
                            @livewire('plataforma.agendamiento.ecografia.suspension-horario-ecografia.suspension-horario-masivo-table')
                            <div wire:loading>
                                <x-cargando-dos/>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        document.addEventListener('agendaYaExiste', function() {
            Swal.fire({
                title: "Error",
                text: "Se cancelo el registro, ya existe un dato en la hora seleccionada!!!",
                icon: "error"
            });
        });

        document.addEventListener('AgendaRegistrada', function() {

            Swal.fire({
                title: "<b>Felicidades</b>",
                text: "Se registró correctamente la suspensión!!!",
                icon: "success"

            });
        });

        document.addEventListener('ExistenAgendados', function() {
            Swal.fire({
                title: "<b>Advertencia</b>",
                text: "No se pudo registrar, existen agendados en este intervalo!!!",
                icon: "warning"

            });
        });

        document.addEventListener('RegistroExistoso', function() {

            Swal.fire({
                title: "<b>Felicidades</b>",
                text: "Se registró correctamente la suspensión!!!",
                icon: "success"

            });
        });

        document.addEventListener('AbrilModal', function() {
            $('#verAgendaCompleta').modal('show');
        });

        // validacion para cuando no tenga horarios asignados
        document.addEventListener('sinHorario', function() {
            Swal.fire({
                title: "<b>No tiene Horarios</b>",
                text: "El especialista no tiene horarios en la fecha  seleccionada",
                icon: "warning"

            });
        });
        // validacion para cuando elija horario inicio meno  a fin
        document.addEventListener('horarioMenor', function() {
            Swal.fire({
                title: "<b>Error</b>",
                text: "Selecciona una hora fin mayor a la hora de inicio",
                icon: "warning"
            });
        });
        // validacion para el caso de que el asegurado no se encuentre:
        document.addEventListener('noEstaAsegurado', function() {
            Swal.fire({
                title: "<b>Error</b>",
                text: "La persona seleccionada no se encuentra afiliada",
                icon: "warning"
            });
        });

        //VALIDACIONES
        document.addEventListener('tieneVacacion', function() {
            Swal.fire({
                title: "<b>El especialista se encuentra con vacación</b>",
                text: "Seleccione otra fecha",
                icon: "warning"
            });
        });

        document.addEventListener('noTieneHorario', function() {

            Swal.fire({
                title: "<b>El especialista no cuenta con horarios disponibles</b>",
                text: "Seleccione otra fecha",
                icon: "warning"

            });
        });

        //PREGUNTA SI GUARDAR O NO
        document.addEventListener('preguntarGuardar', function() {
            Swal.fire({
                title: '¿Registrar suspensión de horario?',
                text: 'Se registrará la suspensión de horario del especialista',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.dispatch('guardarAnulacion');

                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el agendamiento",
                        icon: "error"
                    });
                }
            });
        });

        document.addEventListener('existeAgenda', function() {
            Swal.fire({
                title: "<b>El especialista tiene horarios o suspensiones registradas en el rango seleccionado</b>",
                text: "Seleccione otro rango o registre una nueva suspensión",
                icon: "warning"
            });
        });

        document.addEventListener('registroExistoso', function() {
            Swal.fire({
                title: "<b>Felicidades</b>",
                text: "La suspensión se registró correctamente",
                icon: "success",
                showConfirmButton: false,
            });
        });

        document.addEventListener('sinPersona', function() {
            Swal.fire({
                title: "<b>Seleccione un asegurado</b>",
                text: "Debe registrar un beneficiario para guardar la suspensión",
                icon: "warning"
            });
        });

        document.addEventListener('tieneAgendasYOSuspensiones', function() {
            Swal.fire({
                title: "<b>El especialista tiene horarios o suspensiones registradas en el rango seleccionado</b>",
                text: "Seleccione otro rango o registre una nueva suspensión",
                icon: "warning"
            });
        });

        document.addEventListener('errorCreacionAgendaAlSuspender', function() {
            Swal.fire({
                title: "<b>Error al registrar la suspension</b>",
                text: "Ocurrio un error al momento de registrar la suspension. Verifique e intente nuevamente",
                icon: "error"
            });
        });

        document.addEventListener('suspensionCreada', function() {
            Swal.fire({
                title: "<b>Suspensión registrada</b>",
                text: "La suspensión se registró correctamente",
                icon: "success",
                showConfirmButton: false,
                timer: 1000
            });
        });



        document.addEventListener('preguntarRegistroSuspension', function() {
            Swal.fire({
                title: '¿Registrar suspensión de horario?',
                text: 'Se registrará la suspensión de horario del especialista seleccionado',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.dispatch('registrarSuspension');

                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el agendamiento",
                        icon: "error"
                    });
                }
            });
        });
    </script>
