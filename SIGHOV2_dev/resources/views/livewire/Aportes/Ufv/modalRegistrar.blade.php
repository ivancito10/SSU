<div>
    <x-small-modal id="ufvModal" labelledby="ufvLabel" title=" AGREGAR NUEVO REGISTRO " 
    saveAction="guardarUfv()">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                
                <div class="card-body">
                <p  style="color:#1a5276 ;" class="h5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg><em><b> Ingrese el valor de la UFV.</b> </em> <spam style="color:#cb4335;"><b>*</b></spam></p>
                <form>
                    <div class="row mb-3 ">
                        <label for="valor"
                            class="col-sm-2 col-form-label">VALOR:</label>
                        <div class="col-sm-10">
                            <input wire:model="valor" type="number"
                                step="0.00001" class="form-control"
                                id="valor" placeholder="Ej. 0.00001" style="border-radius:10px;" min="0">
                            <input wire:model="valor" type="hidden" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="fecha"  class="col-sm-2 col-form-label">FECHA:</label>
                        <div class="col-sm-10">
                            <input wire:model="fecha" type="date"
                                class="form-control" id="fecha" style="border-radius:10px;">
                        </div>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>

        
    </x-small-modal>
</div>
<script>
    window.addEventListener('modalRegistrar', event => {
        $('#ufvModal').modal('show');
    });

    // Función para mostrar la alerta de confirmación
    function guardarUfv() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, llama a la función saveData()
                saveDataUfv();
            }
        });
    }
        // Función para guardar los datos
        function saveDataUfv() {
        Livewire.dispatch('create');
        $('#ufvModal').modal('hide');
    }
 
        document.addEventListener('ufvCreada', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La UFV se registró correctamente.",
                icon: "success"

            });
            $('#ufvModal').modal('hide');
        });
        document.addEventListener('ufvCancelado', function() {
            Swal.fire({
                title: "Registro Cancelado!",
                text: "La solicitud de registro fue anulada.",
                icon: "error"

            });
            $('#ufvModal').modal('hide');
        });
        document.addEventListener('ufvExiste', function() {
            Swal.fire({
                title: "UFV Duplicada! ",
                text: "La solicitud de registro fue anulada.",
                icon: "error"
            });
            $('#ufvModal').modal('hide');
        });
    
</script>
