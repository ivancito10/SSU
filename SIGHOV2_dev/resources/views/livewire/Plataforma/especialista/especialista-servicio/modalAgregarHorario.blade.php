<div wire:ignore>
    <x-modal-sin-footer id="modalAgregarHorarios" labelledby="modalAgregarHorariosLabel" title="AGREGAR HORARIO "
        saveAction="agregarHorario()">

        <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p style="color:#2471a3" class="h5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2"><b>Introduzca y complete los siguientes campos:</b> </em>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <label for="permanente_horario">DURACIÓN DE
                        SERVICIO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="font-weight-bold text-red">TEMPORAL</span>
                        <div class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                            <input wire:model="permanente_horario" name="permanente_horario" type="checkbox"
                                class="custom-control-input" id="permanente_horario" onclick="funcioncitaHorario()">
                            <label class="custom-control-label" for="permanente_horario"
                                style="margin-left: 0.2rem;"></label>
                        </div>
                        <span class="font-weight-bold text-green">PERMANENTE</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <label for="fecha_inicio_horario"> FECHA INICIO: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <input class="form-control @error('fecha_inicio_horario') is-invalid @enderror" type='date'
                        style="border-radius:8px" min="{{ now()->toDateString() }}" id="fecha_inicio_horario"
                        wire:model=fecha_inicio_horario>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" id="fechaFinHorarioDiv">
                    <label for="fecha_fin_horario"> FECHA FIN:</label>
                    <input class="form-control @error('fecha_fin_horario') is-invalid @enderror" type='date'
                        style="border-radius:8px" min="{{ now()->toDateString() }}" id="fecha_fin_horario"
                        wire:model=fecha_fin_horario>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="id_dia_horario">DÍA DE LA SEMANA: <spam style="color:#cb4335;"><b>*</b></spam> </label>
                    <select wire:model="id_dia_horario" id="id_dia_horario" name="id_dia_horario"
                        class="form-control @error('id_dia_horario') is-invalid @enderror" style="border-radius:8px;"
                        tabindex="-1" aria-hidden="true" required>
                        <option selected="selected">Seleccione un día.</option>
                        @foreach ($dias as $dato)
                            <option value="{{ $dato->id }}">{{ $dato->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                    <label for="id_consultorio_horario"> CONSULTORIO: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <select wire:model="id_consultorio_horario" id="id_consultorio_horario"
                        name="id_consultorio_horario"
                        class="form-control select2 @error('id_consultorio_horario') is-invalid @enderror"
                        style="border-radius:8px" tabindex="-1" aria-hidden="true" required>
                        <option selected="selected">Seleccione el consultorio a registrar.</option>
                        @foreach ($consultorios as $dato)
                            <option value="{{ $dato->id }}">CONSULT. No:
                                {{ $dato->numero_consultorio }} - SEDE: {{ $dato->consultorioSedes->ubicacion }} -
                                {{ $dato->consultorioSedes->piso }}</option>
                        @endforeach

                    </select>
                </div>
            </div>

            <div class="row">
                <br>
                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                    <label for="id_horario_horario"> HORARIO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <select wire:model="id_horario_horario" id="id_horario_horario"
                        class="form-control select2 @error('id_horario_horario') is-invalid @enderror " tabindex="-1"
                        aria-hidden="true" required {{-- wire:change="ValidarHorario" --}}>
                        <option selected="selected">Seleccione el horario a registrar.</option>
                        @foreach ($horarios as $dato)
                            <option value="{{ $dato->id }}">( HORA:
                                {{ \Carbon\Carbon::parse($dato->hora_inicio)->format('H:i') }} a
                                {{ \Carbon\Carbon::parse($dato->hora_fin)->format('H:i') }} ) ( INTERVALO:
                                {{ $dato->intervalo }} min.) ( #PACIENTES: {{ $dato->numero_paciente }} )
                                (TURNO: {{ $dato->turno }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 text-right">
                    <br>
                    <div style="height: 0.5rem;"></div>

                    <x-agregar-button onclick="agregarHorario()">AGREGAR</x-agregar-button>
                </div>
            </div>

        </div>

        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="height: 400px; overflow-y: auto;">
                        @livewire('plataforma.especialista.especialista-servicio.vista-horarios-table')
                    </div>

                </div>
            </div>
        </div>



    </x-modal-sin-footer>
</div>

<script>
    window.addEventListener('agregarHorarioModal', event => {
        $('#modalAgregarHorarios').modal('show');
    });

    function funcioncitaHorario() {
        const fechaFinDiv = document.getElementById('fechaFinHorarioDiv');
        fechaFinHorarioDiv.hidden = !fechaFinHorarioDiv.hidden;
    }

    document.addEventListener('limpiarHorario', function() {
        const fechaFinDiv = document.getElementById('fechaFinHorarioDiv');
        fechaFinHorarioDiv.hidden = false;
    });

    function agregarHorario() {
        Swal.fire({
            title: '¿Desea guardar el horario?',
            text: "Se guardará el horario",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('validacionesNuevoHorario');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la asignación",
                    icon: "error"
                });
            }
        });
    }

    document.addEventListener('registroExitoso', function() {
        Swal.fire({
            title: "Registro de horario exitoso!",
            text: "Se guardó el registro",
            icon: "success"
        });
    });

    document.addEventListener('mensajeError', function() {
        Swal.fire({
            title: "Error",
            text: "Ocurrio un error",
            icon: "error"
        });
    });

    document.addEventListener('estadoActualizado', function() {
        Swal.fire({
            title: "Registro actualizado",
            text: "El estado del registro se actualizó correctamente",
            icon: "success"
        });
    });

    document.addEventListener('turnoExistente', function() {
        Swal.fire({
            title: 'El especialista ya tiene un horario asignado en este turno',
            text: "¿Desea registrar el nuevo horario de todas formas?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarNuevoHorarioConActualizaciones');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la asignación",
                    icon: "error"
                });
            }
        });
    });

    /*document.addEventListener('turnoDiaExistente', function() {
        Swal.fire({
            title: 'El especialista ya tiene un horario asignado en este turno y día',
            text: "¿Desea registrar el nuevo horario de todas formas?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarHorarios');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la asignación",
                    icon: "error"
                });
            }
        });
    });*/

    document.addEventListener('faltaPermanente', function() {
        Swal.fire({
            title: "Duración del horario no establecida",
            text: "No se seleccionó correctamente la duración del horario",
            icon: "warning"
        });
    });

    document.addEventListener('faltaFechaInicio', function() {
        Swal.fire({
            title: "Fecha de inicio no especificada",
            text: "No se detalló la fecha de inicio del horario",
            icon: "warning"
        });
    });

    document.addEventListener('faltaFechaFin', function() {
        Swal.fire({
            title: "Fecha de fin no especificada",
            text: "No se detalló la fecha de fin del horario",
            icon: "warning"
        });
    });

    document.addEventListener('faltaDia', function() {
        Swal.fire({
            title: "Día de la semana no especificado",
            text: "No se detalló el día de la semana del horario",
            icon: "warning"
        });
    });

    document.addEventListener('faltaConsultorio', function() {
        Swal.fire({
            title: "Consultorio no especificado",
            text: "No se detalló el consultorio del horario",
            icon: "warning"
        });
    });

    document.addEventListener('faltaHorario', function() {
        Swal.fire({
            title: "Horario no especificado",
            text: "No se detalló el horario del servicio",
            icon: "warning"
        });
    });

    /*document.addEventListener('DOMContentLoaded', function() {
        var selectHorarios = document.querySelector('#id_horario_horario');
        $(selectHorarios).select2({
            
        });

        $(selectHorarios).on('change', function(e) {
            var selectedValuesHorario = $(this).val();
            // Enviar los valores seleccionados al componente Livewire
            @this.set('id_horario_horario', selectedValuesHorario);
        });
    });*/
</script>
