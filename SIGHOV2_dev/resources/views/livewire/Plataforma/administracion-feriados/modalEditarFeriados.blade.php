<div wire:ignore>
    <x-small-modal id="modalEditarFeriado" labelledby="modalEditarFeriadoLabel" title="AGREGAR FERIADO"
        saveAction="editFeriado()">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los siguientes datos:</b> </em>
                
            </p>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha">FECHA:</label>
                <input class="form-control" id="fecha" wire:model=fecha wire:change="obtenerNombreDia()"
                    type="date" style="border-radius:10px">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                <label for="dia">DÍA:</label>
                <input class="form-control" id="dia" wire:model=dia disabled type="text"
                    style="border-radius:10px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="descripcion">DESCRIPCIÓN:</label>
                <textarea class="form-control" id="descripcion" wire:model='descripcion' class="swal2-input" rows='4'
                    style="border-radius:8px; text-transform: uppercase;"></textarea>
            </div>
        </div>
    </x-small-modal>

</div>
<script>
    document.addEventListener('abrirmodalEditarFeriados', function() {
        $('#modalEditarFeriado').modal('show');
    });

    function editFeriado() {
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
                Livewire.dispatch('modificarFeriado');
                // $('#modalRegisterFeriado').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#modalEditarFeriado').modal('hide');
            }
        });
    }

    // para edicion exitosa
    document.addEventListener('feriadoEditado', function() {
        Swal.fire({
            title: "Modificación satisfactoria",
            text: "El feriado fue editado con exito",
            icon: "success"
        });
        $('#modalEditarFeriado').modal('hide');
    });
    // faltan datos
    document.addEventListener('faltaDatos', function() {
        Swal.fire({
            title: "Falta datos",
            text: "Por favor, complete todos los campos requeridos.",
            icon: "error"
        });
        $('#modalEditarFeriado').modal('hide');
    });
    // para error
    document.addEventListener('feriadooNoRegistrado', function() {
        Swal.fire({
            title: "Ocurrio un error",
            text: "No se pudo completar el registro. Intente nuevamente o comuníquese con la Unidad de Sistemas si el problema persiste.",
            icon: "error"
        });
        $('#modalEditarFeriado').modal('hide');
    });
</script>
