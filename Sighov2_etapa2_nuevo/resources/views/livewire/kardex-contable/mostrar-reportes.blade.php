<div>
    <div class="card-borde card-comun p-2">
        <div class="row">
            <div class="col-lg-12 col-md-6 col-sm-6 col-8">
                <div class="cabecera">
                    <b>REPORTE DE INGRESO DE MEDICAMENTOS</b>
                </div>
            </div>
        </div>

        <div style="height: 0.5rem;"></div>

        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="nombre_registrar">FECHA INICIO: <b style="color:#cb4335;">*</b></label>
                <input type="date" class="form-control @error('fechaInicio') is-invalid @enderror"
                    wire:model.live="fechaInicio">
                @error('fechaInicio')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <label for="nombre_registrar">FECHA FIN: <b style="color:#cb4335;">*</b></label>
                <input type="date" class="form-control @error('fechaFin') is-invalid @enderror"
                    wire:model.live="fechaFin">
                @error('fechaFin')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pt-4">
                <div class="form-check">
                    <input class="form-check-input @error('seleccionarAlmacen') is-invalid @enderror" type="radio"
                        value="almacenMedicamento" name="seleccionarAlmacen" wire:model="seleccionarAlmacen"
                        style="transform: scale(1.5); margin-right: 10px; border-color: #2471a3; accent-color: #2471a3; cursor: pointer;">
                    <label class="form-check-label" for="seleccionarAlmacen">
                        ALMACÉN DE MEDICAMENTO
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input @error('seleccionarAlmacen') is-invalid @enderror" type="radio"
                        value="almacenGeneral" name="seleccionarAlmacen" wire:model="seleccionarAlmacen"
                        style="transform: scale(1.5); margin-right: 10px; border-color: #2471a3; accent-color: #2471a3; cursor: pointer;">
                    <label class="form-check-label" for="seleccionarAlmacen">
                        ALMACÉN GENERAL
                    </label>
                </div>
                @error('seleccionarAlmacen')
                    <span class="text-danger d-block mt-1" style="font-size: 85%;">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pt-4">
                <x-buscar-button-tres style="width:15% !important; height: 46px;" wire:click="generarReporte"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="generarReporte">
                    </span>
                    <span wire:loading wire:target="generarReporte">
                        <i class="fas fa-spinner fa-spin me-3"></i> Cargando...
                    </span>
                </x-buscar-button-tres>
                <x-pdf2-button wire:click="reportePdf"></x-pdf2-button>
                <x-ver-excel-button wire:click="reporteExcel"></x-ver-excel-button>
            </div>
        </div>
    </div>
    <br>

    <div class="table-responsive">
        @if ($almacenConfirmado === 'almacenMedicamento')
            <livewire:kardex-contable.reporte-ingreso-table :fechaInicio="$fechaInicio" :fechaFin="$fechaFin" :buscarPresionado="true"
                :wire:key="'med-'.$fechaInicio.'-'.$fechaFin" />
        @elseif($almacenConfirmado === 'almacenGeneral')
            <livewire:kardex-contable.ingreso-materiales-table :fechaInicio="$fechaInicio" :fechaFin="$fechaFin" :buscarPresionado="true"
                :wire:key="'gen-'.$fechaInicio.'-'.$fechaFin" />
        @endif
    </div>
</div>


<script>
    document.addEventListener('definirFechas', function() {
        Swal.fire({
            title: "Datos incompletos!",
            text: "Debe completar todos los campos.",
            icon: "warning"
        });
    });
</script>
