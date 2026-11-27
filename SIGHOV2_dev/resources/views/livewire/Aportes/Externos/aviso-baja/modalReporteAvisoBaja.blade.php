<div>
    <x-modal-small-ignore-sin-footer id="reporteModalAvisoBAja" labelledby="reporteModalAvisoBAjaLabel" title="GENERAR REPORTE ">

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="fecha_inicio">FECHA INICIO:</label>
                <input class="form-control input-comun @error('fecha_inicio') is-invalid @enderror " id="fecha_inicio"
                    type="date" wire:model="fecha_inicio">
                @error('fecha_inicio')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="fecha_fin">FECHA FIN:</label>
                <input class="form-control input-comun @error('fecha_fin') is-invalid @enderror " id="fecha_fin"
                    type="date" wire:model="fecha_fin">
                @error('fecha_fin')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        </div>
        <div class="modal-footer" style="border: none; background-color: white;">
            <x-descargar-button onclick="funcionGuardarReporte()"
                wire:loading.attr="disabled">GENERAR</x-descargar-button>
        </div>
    </x-modal-small-ignore-sin-footer>
</div>
<script>
    window.addEventListener('abrirModalReporteRecursos', event => {
        $('#reporteModalAvisoBAja').modal('show');
    });
    window.addEventListener('modalCerrarmodalReportesSeguimientoPdf', event => {
        $('#reporteModalAvisoBAja').modal('hide');
    });

    function funcionGuardarReporte() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#modalReportes').modal('hide');
                Livewire.dispatch('generarReporte');
            }
        });
    }
</script>
