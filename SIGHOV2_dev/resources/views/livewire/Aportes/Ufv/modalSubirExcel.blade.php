<div wire:ignore>
    <x-small-modal id="excelModal" labelledby="excelLabel" title=" SUBIR  EXCEL " 
    saveAction="savePlanillaUfv()">
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        
                        <p style="color:#1a5276 ;" class="h5"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg><em><b> Suba el archivo en formato Excel.</b>
                                     </em><spam style="color:#cb4335;"><b>*</b></spam></p>
                                        <br>
                        @if ($errors->has('error_message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error_message') }}
                            </div>
                        @endif
                        <label for="formFile" class="form-label">SELECCIONAR ARCHIVO: </label>
                        <input wire:model="planilla" class="form-control" type="file" id="formFile"
                            name="archivo" required accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" >
                            <br>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target.except="render">
                        <x-cargando-dos/> 
                        </div>

                    </div>
                </div>

            </div>

        </div>
        
        
    </x-small-modal>
</div>
<script>
    window.addEventListener('modalSubirExcel', event => {
        $('#excelModal').modal('show');
    });

    // GUARDAR EDITAR ESPECIALIDAD
    function savePlanillaUfv() {
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
                Livewire.dispatch('SubirPlanillaUfv');
                $('#excelModal').modal('hide');
            }
        });
    }

    document.addEventListener('ErrorSubirPlanilla', function() {
        $('#excelModal').modal('hide');
        Swal.fire({
            title: " Formato Incorrecto!",
            text: "Se canceló el registro.",
            icon: "error"

        });
    });

    document.addEventListener('SubirPlanillaExitoso', function() {
        $('#excelModal').modal('hide');
        Swal.fire({
            title: "Guardado!",
            text: "Se cargo correctamente la planilla.",
            icon: "success"

        });
    });
    
</script>
