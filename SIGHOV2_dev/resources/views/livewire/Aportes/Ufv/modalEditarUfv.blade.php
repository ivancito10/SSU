<div>
    <x-small-modal id="ufvEditarModal" labelledby="ufvEditarModalLabel" title="  MODIFICAR LA UFV " 
        saveAction="actualizarUfv()">

        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">   
            <div class="card-body">
                <p style="color:#1a5276 ;" class="h5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg><em><b> Declare los nuevos valores de la UFV.</b></em><spam style="color:#cb4335;"><b>*</b></spam> </p>
                <form>
                    <div class="row mb-3 ">
                        <label for="valor"
                            class="col-sm-2 col-form-label">VALOR:</label>
                        <div class="col-sm-10">
                            <input wire:model="valor" type="number"
                                step="0.00001" class="form-control"
                                id="valor" style="border-radius:10px;" min="0">
                            <input wire:model="valor" type="hidden">
                        </div>

                    </div>
                    <div class="row mb-3">
                        <label for="fecha"
                            class="col-sm-2 col-form-label">FECHA:</label>
                        <div class="col-sm-10">
                            <input wire:model="fecha" type="date"
                                class="form-control" id="fecha" disabled style="border-radius:10px;">
                        </div>
                    </div>
                </form>

            </div>
        </div>
        
    </x-small-modal>
</div>
<script>
    window.addEventListener('modalEditarUfv', event => {
        $('#ufvEditarModal').modal('show');
    });

    // Función para mostrar la alerta de confirmación
    function actualizarUfv() {
        Swal.fire({
            title: 'Actualizar registro?',
            text: "Se modificarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, modificar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, llama a la función saveData()
                Livewire.dispatch('actualizar');
                $('#ufvEditarModal').modal('hide');
            }
        });
    }
        
 
        document.addEventListener('ufvModificado', function() {
            Swal.fire({
                title: "Modificado!",
                text: "La UFV se modificó correctamente",
                icon: "success"

            });
            $('#ufvEditarModal').modal('hide');
        });
        document.addEventListener('ufvCanceladoMod', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Se canceló el registro",
                icon: "error"

            });
            $('#ufvEditarModal').modal('hide');
        });
        document.addEventListener('ufvExiste', function() {
            Swal.fire({
                title: "La UFV existe y se encuentra activa",
                text: "Se canceló el registro",
                icon: "error"
            });
            $('#ufvEditarModal').modal('hide');
        });
    
</script>
