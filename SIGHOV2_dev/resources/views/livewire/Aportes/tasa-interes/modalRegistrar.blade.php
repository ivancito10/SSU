<div>
    <x-small-modal id="abrirModal" labelledby="editarLabel" title=" AGREGAR NUEVO REGISTRO " 
    saveAction="guardar()">
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#1a5276 ;" class="h5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg><em> <b>Ingrese los valores de la tasa de  interés.</b></em> 
                        <spam style="color:#cb4335;"><b>*</b></p>
                    </div>
                </div>
                <form>
                    <div class="row mb-3 ">
                        <label for="valor"
                            class="col-sm-2 col-form-label">VALOR:</label>
                        <div class="col-sm-10">
                            <input wire:model="valor" type="number"
                                step="0.000001" class="form-control" placeholder="Ej. 10.00"
                                id="valor" style="border-radius:10px;" min="0">
                            <input wire:model="valor" type="hidden">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="fecha"
                            class="col-sm-2 col-form-label">FECHA:</label>
                        <div class="col-sm-10">
                            <input wire:model="fecha" type="date"
                                class="form-control" id="fecha" style="border-radius:10px;" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </x-small-modal>
</div>
<script>
    window.addEventListener('modalRegistrar', event => {
        $('#abrirModal').modal('show');
    });

    // GUARDAR EDITAR ESPECIALIDAD
    function guardar() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se guardarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardar');
            } else {
                Swal.fire({
                    title: "Registro Cancelado!",
                    text: "La solicitud de registro fue anulada.",
                    icon: "error"
                });
            }
        });
    }
    document.addEventListener('guardarTasa', function() {
        Swal.fire({
            title: 'Guardado!',
            text: "La tasa de interes se registró correctamente.",
            icon: 'success'
        });
        $('#abrirModal').modal('hide');
    });
    document.addEventListener('canceladoTasa', function() {
        Swal.fire({
            title: 'Registro Cancelado!',
            text: "La solicitud de registro fue anulada.",
            icon: 'error'
        });
        $('#abrirModal').modal('hide');
    });
    document.addEventListener('tasaExiste', function() {
        Swal.fire({
            title: 'Tasa de Interés Duplicada!',
            text: "La solicitud de registro fue anulada.",
            icon: 'error'
        });
        $('#abrirModal').modal('hide');
    });
    
</script>
