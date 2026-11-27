<div wire:ignore>
    <x-small-modal id="editarModalHorario" labelledby="editarModalHorarioLabel" title="MODIFICAR HORARIO"
        saveAction="edicionHorario()">
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
                    <em class="ml-2"><b>Ingresar y completar los siguientes datos:</b> </em>

                </p>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="hora_inicio"> HORA INICIO: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" id="hora_inicio" type="time" wire:model=hora_inicio
                    wire:change="asignarNuevoTurnoycalcularNumeroPacientes" style="border-radius:8px;">
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="hora_fin"> HORA FIN: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" id="hora_fin" type="time" wire:model=hora_fin
                    wire:change="calcularNumeroPacientes" style="border-radius:8px;">
            </div>

            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <label for="intervalo">INTERVALO: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" id="intervalo" type="number" placeholder="Ej. 15" wire:model=intervalo
                    wire:change="calcularNumeroPacientes" style="border-radius:8px;">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="turno">TURNO:</label>
                <input class="form-control" id="turno" type="text" wire:model=turno disabled
                    style="border-radius:8px;">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="numero_paciente">N° DE PACIENTES:</label>
                <input class="form-control" id="numero_paciente" type="numeric" disabled wire:model=numero_paciente
                    style="border-radius:8px;">
            </div>

        </div>




    </x-small-modal>
</div>

<script>
    window.addEventListener('modalEditarHorario', event => {

        $('#editarModalHorario').modal('show');
    });
    // EDITAR HORARIO
    function edicionHorario() {
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
                Livewire.dispatch('editHorario');
                $('#editarModalHorario').modal('hide');
            } else {
                //Livewire.dispatch('resetearDatos');
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

                $('#editarModalHorario').modal('hide');
            }
        });
    }

    document.addEventListener('horarioRegistrado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente",
            icon: "success"

        });
    });

    document.addEventListener('horarioNoRegistrado', function() {
        Swal.fire({
            title: "Completa todos los datos!",
            text: "Se cancelo el registro",
            icon: "error"

        });
    });
</script>
