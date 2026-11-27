<div wire:ignore>
    <x-modal-sin-footer id="modalAgregarServicios" labelledby="modalAgregarServiciosLabel" title="AGREGAR SERVICIO "
        saveAction="agregarServicio()">
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
                    <label for="permanente_enter_servicio">DURACIÓN DE
                        SERVICIO: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <div class="d-flex align-items-center gap-2">
                        <span class="font-weight-bold text-red">TEMPORAL</span>
                        <div class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                            <input wire:model="permanente_enter_servicio" name="permanente_enter_servicio"
                                type="checkbox" class="custom-control-input" id="permanente_enter_servicio"
                                onclick="funcioncita()">
                            <label class="custom-control-label" for="permanente_enter_servicio"
                                style="margin-left: 0.2rem;"></label>
                        </div>
                        <span class="font-weight-bold text-green">PERMANENTE</span>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" id="fechaInicioDiv">
                    <label for="fecha_inicio_servicio"> FECHA INICIO: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <input class="form-control @error('fecha_inicio_servicio') is-invalid @enderror "
                        style="border-radius:8px;" id="fecha_inicio_servicio" wire:model="fecha_inicio_servicio"
                        type="date" min="{{ now()->toDateString() }}">
                    @error('fecha_inicio_servicio')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror

                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" id="fechaFinDiv">
                    <label for="fecha_fin_servicio"> FECHA FIN: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <input class="form-control @error('fecha_fin_servicio') is-invalid @enderror"
                        id="fecha_fin_servicio" wire:model=fecha_fin_servicio type="date" style="border-radius:8px;"
                        min="{{ now()->toDateString() }}">
                    @error('fecha_fin_servicio')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>

            </div>

            <div class="row">
                <div class='col-lg-4 col-md-6 col-sm-6 col-xs-12'>
                    <label for="id_dia_servicio">DIA DE LA SEMANA: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <select wire:model="id_dia_servicio" id="id_dia_servicio"
                        class="form-control select2 @error('id_dia_servicio') is-invalid @enderror"
                        style="width: 100%; border-radius:8px;" tabindex="-1" required>
                        <option selected="selected">Seleccione el día
                            del
                            servicio..
                        </option>
                        @foreach ($dias as $dia)
                            <option value="{{ $dia->id }}">{{ $dia->nombre }}</option>
                        @endforeach
                    </select>
                    @error('tipo_doc')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class='col-lg-5 col-md-6 col-sm-6 col-xs-12'>
                    <label for="id_servicio_servicio">TIPO DE SERVICIO: <spam style="color:#cb4335;"><b>*</b></spam>
                    </label>
                    <select wire:model="id_servicio_servicio" id="id_servicio_servicio"
                        class="form-control select2 @error('id_servicio_servicio') is-invalid @enderror"
                        style="width: 100%; border-radius:8px;" tabindex="-1" required>
                        <option selected="selected">Seleccione el
                            servicio..
                        </option>
                        @foreach ($servicios as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->nombre_servicio }}</option>
                        @endforeach
                    </select>
                    @error('id_servicio_servicio')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 text-right">
                    <br>
                    <div style="height: 0.5rem;"></div>
                    <x-agregar-button onclick="agregarServicio()">AGREGAR</x-agregar-button>
                </div>
            </div>
        </div>
        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); ">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @livewire('plataforma.especialista.especialista-servicio.vista-servicio-especialista-table')
                </div>
            </div>
        </div>

    </x-modal-sin-footer>
</div>

<script>
    window.addEventListener('agregarServicioModal', event => {
        $('#modalAgregarServicios').modal('show');
    });

    function funcioncita() {
        const fechaInicioDiv = document.getElementById('fechaInicioDiv');
        const fechaFinDiv = document.getElementById('fechaFinDiv');

        fechaInicioDiv.hidden = !fechaInicioDiv.hidden;
        fechaFinDiv.hidden = !fechaFinDiv.hidden;
    }

    document.addEventListener('registroExitoso', function() {
        const fechaInicioDiv = document.getElementById('fechaInicioDiv');
        const fechaFinDiv = document.getElementById('fechaFinDiv');

        fechaInicioDiv.hidden = false;
        fechaFinDiv.hidden = false;
    });

    function agregarServicio() {
        Swal.fire({
            title: '¿Desea guardar el servicio?',
            text: "Se guardará el servicio",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarNuevoServicio');
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
            title: "Registro de servicio exitoso!",
            text: "Se guardó el registro",
            icon: "success",
        });
    });

    document.addEventListener('mensajeError', function() {
        Swal.fire({
            title: "Error",
            text: "Ocurrio un error",
            icon: "error"
        });
    });
</script>
