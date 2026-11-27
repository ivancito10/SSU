<div>
    <x-small-modal id="ModalEditarFormularioRegistro" labelledby="ModalEditarFormularioRegistroLabel"
        title="MODIFICAR CONSULTORIO" saveAction="saveEditarConsultorio()">
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
                    <em class="ml-2"><b>Ingresar los nuevos datos a registrar:</b> </em>

                </p>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="ubicacion">UBICACIÓN: </label>
                <input wire:model="ubicacion" disabled class="form-control"
                    style="background-color:#f8f9f9; border-radius:8px;">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="id_sedes"> PISO: <b style="color:#cb4335;">*</b></label>
                <select wire:model="id_sedes" id="id_sedes" class="form-control" style="border-radius:8px;"
                    placeholder="Seleccione el piso">
                    @foreach ($this->pisos as $pisos)
                        <option value="{{ $pisos->id }}">{{ $pisos->piso }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="nro_consultorio"> NÚMERO DE CONSULTORIO: <b style="color:#cb4335;">*</b></label>
                <input class="form-control" wire:model=nro_consultorio oninput="this.value = this.value.toUpperCase()"
                    style="border-radius:8px;" placeholder="Ingrese el nro. consultorio">
            </div>
        </div>
    </x-small-modal>
</div>


<script>
    window.addEventListener('ModalEditarConsultorio', event => {
        $('#ModalEditarFormularioRegistro').modal('show');
    });

    function saveEditarConsultorio() {
        Swal.fire({
            title: '¿Modificar Consultorio?',
            text: "Se modificarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarEditarConsultorio');
                $('#ModalEditarFormularioRegistro').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#ModalEditarFormularioRegistro').modal('hide');
            }
        });
    }
</script>
