<div>
    <x-small-modal id="modalEditarGestionAgenda" labelledby="modalEditarGestionAgendaLabel" title="GESTIONAR AGENDA"
        saveAction="registroEdicionAgenda()">
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="id_servicio">SERVICIO: <b style="color:#cb4335;">*</b> </label>
                <select wire:model="id_servicio" id="id_servicio" class="form-control"
                    style="width: 100%; border-radius:8px;" tabindex="-1" required>
                    <option selected="selected" value="{{ $id_servicio }}"> {{ $servicioNombre }}
                    </option>
                    @foreach ($servicios as $servicio)
                        <option value="{{ $servicio['id'] }}">{{ $servicio['nombre_servicio'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                <label for="hoy_boolean_editar"> INICIA: <b style="color:#cb4335;">*</b></label>
                <div class="col-sm-6 d-flex align-items-center">
                    <strong style="color:#2980b9">MAÑANA </strong>
                    <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-green">
                        <input wire:model="hoy_boolean" id="customSwitchHoyEditar" type="checkbox"
                            class="custom-control-input">
                        <label class="custom-control-label" for="customSwitchHoyEditar"
                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                    </div>
                    <strong style="color:green">HOY</strong>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                <label for="nro_dias">NRO DE DIAS: <b style="color:#cb4335;">*</b> </label>
                <input id="nro_dias" class="form-control" type="number" wire:model='nro_dias'
                    style="height: 40px; border-radius:8px">
            </div>
        </div>
    </x-small-modal>

</div>
<script>
    document.addEventListener('abrirModalEditarsAgenda', function() {
        $('#modalEditarGestionAgenda').modal('show');
    });

    function registroEdicionAgenda() {
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
                // $('#modalEditarGestionAgenda').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalEditarGestionAgenda').modal('hide');
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
    // completa datos
    document.addEventListener('gestionAgendaCompletaDatosEdicion', function() {
        Swal.fire({
            title: "Completa los datos",
            text: "Porfavor ingresa todos los datos necesarios para completar el registro.",
            icon: "warning"
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
