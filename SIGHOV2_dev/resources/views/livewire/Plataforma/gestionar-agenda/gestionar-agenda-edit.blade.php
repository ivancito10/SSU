<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-card-list"
                viewBox="0 0 16 16">
                <path
                    d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                <path
                    d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">MODIFICACIÓN DE
                GESTIÓN
                DE AGENDA</h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
        <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos:</b> </em>
            <spam style="color:#cb4335;"><b>*</b></spam>
        </p>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <label for="id_servicio">SERVICIO: <b style="color:#cb4335;">*</b> </label>
            <select wire:model.live="id_servicio" id="id_servicio"
                class="form-control @error('id_servicio') is-invalid @enderror" style="width: 100%; border-radius:8px;"
                tabindex="-1" required>
                <option selected="selected" value="{{ $id_servicio }}"> {{ $servicioNombre }}
                </option>
                @foreach ($servicios as $servicio)
                    <option value="{{ $servicio['id'] }}">{{ $servicio['nombre_servicio'] }}</option>
                @endforeach
            </select>
            @error('id_servicio')
                <div class="text-danger">
                    <b>El campo servicio es obligatorio</b>
                </div>
            @enderror
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <label for="hoy_boolean"> INICIA:</label>
            <div class="col-sm-6 d-flex align-items-center">
                <strong style="color:#2980b9">MAÑANA </strong>
                <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-green">
                    <input wire:model="hoy_boolean" id="customSwitchHoy" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="customSwitchHoy"
                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                </div>
                <strong style="color:green">HOY</strong>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <label for="nro_dias">NRO DE DIAS: <b style="color:#cb4335;">*</b> </label>
            <input id="nro_dias" class="form-control @error('nro_dias') is-invalid @enderror" type="number"
                wire:model='nro_dias' style="height: 40px; border-radius:8px">
            @error('nro_dias')
                <div class="text-danger">
                    <b>{{ $message }}</b>
                </div>
            @enderror
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <label for="cantidad_turnos">CANTIDAD DE TURNOS: <b style="color:#cb4335;">*</b></label>
            <input id="cantidad_turnos" class="form-control @error('cantidad_turnos') is-invalid @enderror" disabled
                type="number" wire:model='cantidad_turnos' style="height: 40px; border-radius:8px" min="1">
            @error('cantidad_turno')
                <div class="text-danger">
                    <b>{{ $message }}</b>
                </div>
            @enderror
        </div>
    </div>
    <br>
    @foreach ($turnos as $index => $turno)
        <div class="card p-2"
            style="border-radius:6px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); background-color:#e9ecef">
            <strong style="color: #2471a3"> TURNO {{ $index + 1 }} : </strong>
            <div class="row mt-2">
                <div class="col-lg-3">
                    <label>HORA INICIO: <b style="color:#cb4335;">*</b></label>
                    <input class="form-control " type="time" wire:model="turnos.{{ $index }}.hora_inicio"
                        style="height: 40px; border-radius:8px">
                    @error('turnos.' . $index . '.hora_inicio')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <label>HORA FIN: <b style="color:#cb4335;">*</b></label>
                    <input class="form-control" type="time" wire:model="turnos.{{ $index }}.hora_fin"
                        style="height: 40px; border-radius:8px">
                    @error('turnos.' . $index . '.hora_fin')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <label>PORCENTAJE %: <b style="color:#cb4335;">*</b></label>
                    <input class="form-control" type="number" wire:model="turnos.{{ $index }}.porcentaje"
                        style="height: 40px; border-radius:8px" min="1" max="100">
                    @error('turnos.' . $index . '.porcentaje')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-3 pt-3">
                    <br class="pt-4">
                    <x-eliminar-button onclick="EliminandoGestionAgenda({{ $index }})"></x-eliminar-button>

                </div>
            </div>
        </div>
    @endforeach
    <br>
    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarGestionIngreso">
            CANCELAR</x-cancel-button>
        <x-save-button onclick="EdicionGestionAgenda()">GUARDAR</x-save-button>
    </div>

</div>

<script>
    function EdicionGestionAgenda() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('editandoGestionAgenda');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

            }
        });
    }

    function EliminandoGestionAgenda(index) {
        Swal.fire({
            title: '¿Eliminar turno?',
            text: "Se eliminará el turno",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('eliminarTurno', {
                    index
                });
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la acción",
                    icon: "error"
                });
            }
        });
    }
    // registro exitoso
    document.addEventListener('gestionAgendaEdicionExitoso', function() {
        Swal.fire({
            title: "Modificado satisfactoriamente",
            text: "La gestión de agenda fue modificada con exito",
            icon: "success"
        });
        $('#modalEditarGestionAgenda').modal('hide');
    });

    // ya exitse
    document.addEventListener('gestionAgendaYaRegistradoEdicion', function() {
        Swal.fire({
            title: "Ya esta registrado",
            text: "Los datos ya fueron registrados, intente con otros datos.",
            icon: "warning"
        });
        $('#modalEditarGestionAgenda').modal('hide');
    });
    // error por cathc
    document.addEventListener('gestionAgendaErrorEdicion', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "Intente nuevamente, si el error persiste comuniquese con la unidad de desarrollo.",
            icon: "error"
        });
        $('#modalEditarGestionAgenda').modal('hide');
    });
</script>
