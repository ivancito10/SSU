<div>
    <div class="card-borde card-comun p-2">
        <div class="row">
            <div class="col-lg-12 col-md-6 col-sm-6 col-8">
                <div class="cabecera">
                    <b>REPORTE DE SALDOS FINALES</b>
                </div>
            </div>
        </div>

        <div style="height: 0.5rem;"></div>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="rangoSeleccionado">SELECCIONAR PERIODO: <b style="color:#cb4335;">*</b></label>
                <select class="form-control @error('rangoSeleccionado') is-invalid @enderror"
                    wire:model.live="rangoSeleccionado">
                    <option value="">-- Seleccione un periodo --</option>
                    <option value="opcion1">PERIODO 12/2025</option>
                    <option value="opcion2">PERIODO 03/2026</option>
                    <option value="opcion3">PERIODO 08/2026</option>
                    <option value="opcion4">PERIODO 09/2026</option>
                    <option value="opcion5">PERIODO 10/2026</option>
                </select>
                @error('rangoSeleccionado')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- 
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
            </div> --}}

            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 pt-4">
                {{-- 
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
                        value="farmacia" name="seleccionarAlmacen" wire:model="seleccionarAlmacen"
                        style="transform: scale(1.5); margin-right: 10px; border-color: #2471a3; accent-color: #2471a3; cursor: pointer;">
                    <label class="form-check-label" for="seleccionarAlmacen">
                        FARMACIA
                    </label>
                </div>--}}
                <div class="form-check">
                    <input class="form-check-input @error('seleccionarAlmacen') is-invalid @enderror" type="radio"
                        value="almacenYfarmacia" name="seleccionarAlmacen" wire:model="seleccionarAlmacen"
                        style="transform: scale(1.5); margin-right: 10px; border-color: #2471a3; accent-color: #2471a3; cursor: pointer;">
                    <label class="form-check-label" for="seleccionarAlmacen">
                        ALMACÉN DE MEDICAMENTO Y FARMACIA
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
                        <i class="fas fa-spinner fa-spin mr-1"></i> Cargando...
                    </span>
                </x-buscar-button-tres>
                {{-- <x-pdf2-button wire:click="reportePdf"></x-pdf2-button> --}}
                <x-ver-excel-button wire:click="reporteExcel"></x-ver-excel-button>
            </div>
        </div>
    </div>
    <br>

    <div class="table-responsive">
        @if ($almacenConfirmado === 'almacenMedicamento')
            <livewire:kardex-contable.medicamento-saldos-table :fechaInicio="$fechaInicio" :fechaFin="$fechaFin" :buscarPresionado="true"
                :idUnidad="[89]" :wire:key="'med-89-'.$fechaInicio.'-'.$fechaFin" />
        @elseif ($almacenConfirmado === 'farmacia')
            <livewire:kardex-contable.medicamento-saldos-table :fechaInicio="$fechaInicio" :fechaFin="$fechaFin" :buscarPresionado="true"
                :idUnidad="[76]" :wire:key="'med-76-'.$fechaInicio.'-'.$fechaFin" />
        @elseif ($almacenConfirmado === 'almacenYfarmacia')
            <livewire:kardex-contable.medicamento-saldos-table :fechaInicio="$fechaInicio" :fechaFin="$fechaFin" :buscarPresionado="true"
                :idUnidad="[76, 89]" :wire:key="'med-'.$fechaInicio.'-'.$fechaFin" />
        @elseif($almacenConfirmado === 'almacenGeneral')
            <livewire:kardex-contable.materiales-saldos-table :fechaInicio="$fechaInicio" :fechaFin="$fechaFin" :buscarPresionado="true"
                :wire:key="'gen-'.$fechaInicio.'-'.$fechaFin" />
        @endif
    </div>

</div>
