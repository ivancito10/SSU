<div>
    <x-small-modal id="agregarEspecialidadModal" labelledby="agregarEspecialidadModalLabel" title="AGREGAR ESPECIALIDAD"
        saveAction="guardarRegistro()">
        <div class='row'>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                <p style="color:#2471a3" class="h5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <em class="ml-2"><b>Ingresar los siguientes datos:</b> </em>

                </p>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>

        <div class="row">
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <label for="especialidad">ESPECIALIDAD: <b style="color:#cb4335;">*</b></label>
                <input class="form-control @error('especialidad') is-invalid @enderror" type="text" id="especialidad"
                    placeholder="Escriba la especialidad" wire:model="especialidad"
                    style="border-radius:8px; text-transform: uppercase;">
                @error('especialidad')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                <label for="sigla">SIGLA: <b style="color:#cb4335;">*</b></label>
                <input class="form-control @error('sigla') is-invalid @enderror" type="text" id="sigla"
                    placeholder="Ingrese la sigla" wire:model=sigla
                    style="border-radius:8px; text-transform: uppercase;">
                @error('sigla')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="especialidadImagenologia"> ESPECIALIDAD IMAGENOLOGÍA:</label>
                <div class="col-sm-6 d-flex align-items-center">
                    <label class="mr-2" style="color: red">NO</label>
                    <div class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                        <input wire:model="especialidadImagenologia" type="checkbox" class="custom-control-input"
                            id="customSwitchEspecialidadImagenologia">
                        <label class="custom-control-label" for="customSwitchEspecialidadImagenologia"
                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                    </div>
                    <label class="ml-2" style="color: green">SI</label>
                </div>
            </div>
        </div>

    </x-small-modal>
</div>

<script>
    function normalizeText(text) {
        return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    window.addEventListener('modalAgregarEspecialidad', event => {
        $('#agregarEspecialidadModal').modal('show');
    });

    // GUARDAR AGREGAR ESPECIALIDAD
    function guardarRegistro() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('save');
                $('#agregarEspecialidadModal').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }
</script>
