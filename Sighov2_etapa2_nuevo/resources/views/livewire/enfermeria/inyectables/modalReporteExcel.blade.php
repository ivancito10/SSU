<div>
    <x-modal-small-sin-footer id="modalReporteInyecciones" labelledby="modalReporteInyeccionesLabel"
        title=" REPORTE EXCEL DE INYECCIONES" wire:ignore.self>

        <div class="row">
            <div class="col-12" style="text-align:left;">
                <div class="card cabecera">
                    <b> FILTROS DEL REPORTE</b>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mt-2">
                <label for="reporteFechaInicio">FECHA DESDE:</label>
                <input type="date" id="reporteFechaInicio"
                    class="form-control input-comun @error('reporteFechaInicio') is-invalid @enderror"
                    wire:model="reporteFechaInicio" max="{{ now()->format('Y-m-d') }}">
                @error('reporteFechaInicio') <div class="text-danger small"><b>{{ $message }}</b></div> @enderror
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mt-2">
                <label for="reporteFechaFin">FECHA HASTA:</label>
                <input type="date" id="reporteFechaFin"
                    class="form-control input-comun @error('reporteFechaFin') is-invalid @enderror"
                    wire:model="reporteFechaFin" max="{{ now()->format('Y-m-d') }}">
                @error('reporteFechaFin') <div class="text-danger small"><b>{{ $message }}</b></div> @enderror
            </div>

            <div class="col-12 mt-2">
                <label for="reporteEstado">ESTADO:</label>
                <select id="reporteEstado" class="form-control input-comun" wire:model="reporteEstado">
                    <option value="TODOS">TODOS</option>
                    <option value="ACTIVOS">SOLO ACTIVOS</option>
                    <option value="ANULADOS">SOLO ANULADOS</option>
                </select>
            </div>

            <div class="col-12 mt-2">
                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>
                    Si deja las fechas vacías se exportarán todos los registros.</small>
            </div>
        </div>

        <div class="modal-footer pt-3 pb-0 px-0" style="border-top: none !important;">
            <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
            <x-reportes-button wire:click="exportarExcel" wire:loading.attr="disabled" wire:target="exportarExcel">
                <span wire:loading.remove wire:target="exportarExcel">DESCARGAR EXCEL</span>
                <span wire:loading wire:target="exportarExcel"><i class="fas fa-spinner fa-spin"></i> GENERANDO...</span>
            </x-reportes-button>
        </div>
    </x-modal-small-sin-footer>
</div>
<script>
    window.addEventListener('abrirModalReporteInyecciones', () => {
        $('#modalReporteInyecciones').modal('show');
    });
    window.addEventListener('cerrarModalReporteInyecciones', () => {
        $('#modalReporteInyecciones').modal('hide');
    });
</script>
