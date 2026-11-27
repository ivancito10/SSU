<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                ASIGNACIÓN DE SERVICIO - PLATAFORMA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="cancelarServicio" wire:loading.attr="disabled" wire:target="cancelarServicio">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="cancelarServicio">
                <x-cargando-dos />
            </div>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-comun p-2">
                <div class="cabecera text-center">
                    <b>DATOS DE ESPECIALIDAD</b>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <spam><b>ESPECIALIDAD:</b> </spam>
                        <p class="p-1 p-comun-disabled">
                            {{ $especialidad }}</p>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <spam><b>SIGLA:</b></spam>
                        <p class="p-1 p-comun-disabled">
                            {{ $sigla }}</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-comun p-2">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <p style="color:#2471a3" class="h5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2"><b>Agregar y completar los siguientes campos para el registro:</b>
                        </em>
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="d-flex flex-wrap align-items-center">
                            <label for="switchpermanent" class="me-3 mb-1 pr-3">
                                DURACIÓN DE SERVICIO: <b style="color:#cb4335;">*</b>
                            </label>
                            <label class="me-2 mb-1 pr-2 text-green">TEMPORAL</label>
                            <div
                                class="custom-control custom-switch custom-switch-off-success custom-switch-on-blue me-2 mb-1">
                                <input wire:model.live="permanente" name="permanente" type="checkbox"
                                    class="custom-control-input" id="switchpermanent">
                                <label class="custom-control-label" for="switchpermanent"></label>
                            </div>

                            <label class="mb-1 text-blue">PERMANENTE</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if (!$permanente)
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">

                            <label for="fecha_inicio"> FECHA INCIO: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control  @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio"
                                wire:model.live=fecha_inicio type="date" min="{{ now()->toDateString() }}"
                                style="border-radius:8px;">
                            @error('fecha_inicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror

                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> FECHA FIN: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control  @error('fecha_fin') is-invalid @enderror" id="fecha_fin"
                                wire:model.live=fecha_fin type="date" min="{{ now()->toDateString() }}"
                                style="border-radius:8px;">
                            @error('fecha_fin')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror

                        </div>
                    @endif
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="id_servicio">TIPO DE SERVICIO: <b style="color:#cb4335;">*</b></label>
                        <select wire:model="id_servicio" id="id_servicio"
                            class="form-control select2 @error('id_servicio') is-invalid @enderror"
                            style="width: 100%; border-radius:8px;" tabindex="-1" required>
                            <option selected="selected">Seleccione el
                                servicio
                            </option>
                            @foreach ($servicios as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nombre_servicio }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_servicio')
                            <div class="text-danger">
                                <b>El campo servicio es obligatorio.</b>
                            </div>
                        @enderror

                    </div>
                </div>

                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
                        <x-save-button wire:click="asignarServicioEspecialidad">GUARDAR</x-save-button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-comun p-1">
                <div class="table-responsive">
                    <table class="table tabla-celeste">
                        <thead>
                            <tr>
                                <th width="20% ">SERVICIO</th>
                                <th width="20%">DURACIÓN</th>
                                <th width="20%" class="text-center ">INICIO</th>
                                <th width="20%" class="text-center ">FIN </th>
                                <th width="20%">ESTADO</th>
                                <th width="20%">OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviciosSelected as $dato)
                                <tr style="text-align:left;">
                                    <td><b>{{ $dato->servicioEspecialidadHabilitadoServicio->nombre_servicio }}</b>
                                    </td>
                                    <td>
                                        @if ($dato->permanente == true)
                                            <p class="text-blue"> <b>PERMANENTE</b>
                                            </p>
                                        @elseif($dato->permanente == false)
                                            <p class="text-green"><b>TEMPORAL</b>
                                            </p>
                                        @endif
                                    </td>

                                    @if ($dato->permanente === true)
                                        <td colspan="2" style="text-align: center;">PERMANENTE</td>
                                    @else
                                        <td class="text-center">
                                            {{ mb_strtoupper(\Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d \\d\\e F \\d\\e Y')) }}
                                        </td>
                                        <td class="text-center">
                                            {{ mb_strtoupper(\Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d \\d\\e F \\d\\e Y')) }}
                                        </td>
                                    @endif

                                    <td>
                                        @if ($dato->estado)
                                            <p class=" font-weight-bold" style="color: #196f3d ;">ACTIVO</p>
                                        @else
                                            <p class=" font-weight-bold" style="color: #c0392b ;">INACTIVO</p>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($dato->estado == true)
                                            <x-deshabilitar-button
                                                wire:click="habilitarServicio({{ $dato->id }})" />
                                        @else
                                            <x-habilitar-button
                                                wire:click="deshabilitarServicio({{ $dato->id }}, {{ $dato->id_especialidad }}, {{ $dato->id_servicio }})" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    </div>
</div>
<script>
    //Sweet Alert para guardar servicio
    document.addEventListener('guardarAsignacionServicio', function() {
        Swal.fire({
            title: '¿Asignar Servicio?',
            text: "Se registrará el servicio asignado a la especialidad.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarServicioEspecialidad', [true]);
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en la especialidad.",
                    icon: "info"
                });
            }
        });
    });

    document.addEventListener('deshabilitarServicioSeleccionado', function() {
        Swal.fire({
            title: '¿deshabilitar servicio?',
            text: "Se deshabilitará el servicio seleccionado.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, deshabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('habilitarServicioActual', [true]);
                Swal.fire({
                    title: "Servicio Deshabilitado!",
                    text: " Se deshabilito el servicio.",
                    icon: "info",
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el servicio.",
                    icon: "warning"
                });
            }
        });
    });


    // SErvicio Existe
    document.addEventListener('servicioExiste', function() {
        let permanente = @this.get('permanenteServicio');
        let valor = permanente ? "PERMANENTE" : "TEMPORAL";
        let estado = @this.get('estadoServicio');
        Swal.fire({
            title: "¡Existe registro!",
            text: "Debe deshabilitar el servicio " + valor + " antes de continuar.",
            icon: "error",
        });
    });


    // SErvicio Existe
    document.addEventListener('servicioExistente', function() {
        Swal.fire({
            title: "¡Existe registro!",
            text: "Debe deshabilitar los servicios ACTIVOS antes de continuar.",
            icon: "error",
        });
    });

    // DESHABILITAR EL SERVICIO
    document.addEventListener('servicioDeshabilitado', function() {
        Swal.fire({
            title: "Servicio Deshabilitado!",
            text: " Se deshabilito el servicio",
            icon: "error",
        });
        $('#verAsignacionModal').modal('hide');
    });

    document.addEventListener('servicioHabilitadoError', function() {
        Swal.fire({
            title: "Existe un registro previo activo",
            text: "No se puede habilitar el servicio",
            icon: "error",
        });
        $('#verAsignacionModal').modal('hide');
    });

    document.addEventListener('registroExitoso', function() {
        Swal.fire({
            title: "¡Servicio registrado con éxito!",
            text: "El servicio ha sido guardado correctamente.",
            icon: "success",
        });
    });
</script>
