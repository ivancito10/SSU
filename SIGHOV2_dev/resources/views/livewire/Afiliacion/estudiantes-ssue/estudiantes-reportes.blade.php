<div>
    <div class="row">
        <div class="col-lg-7 col-md-7 col-sm-7 col-7">
            <label for="tipo_reporte">TIPO DE REPORTE:</label>
            <select wire:model.live="tipo_reporte" id="tipo_reporte"
                class="form-control select2 @error('tipo_reporte') is-invalid @enderror "
                style="width: 100%;border-radius:10px" tabindex="-1" required>
                <option selected="selected">Seleccione el tipo de reporte.. </option>
                <!-- <option value="carnets">CARNETS EMITIDOS</option> -->
                <option value="registros">REGISTROS NUEVOS</option>
                <option value="reporte_umsa">REPORTE MENSUAL UMSA</option>
            </select>
            @error('tipo_reporte')
            <div class="text-danger">
                <b>{{ $message }}</b>
            </div>
            @enderror
        </div>
        @if ($tipo_reporte != 'reporte_umsa')
        <div class="col-lg-5 col-md-5 col-sm-5 col-5">
            <label for="estado_validar">VALIDACIÓN:</label>
            <select wire:model.live="estado_validar" id="estado_validar"
                class="form-control select2 @error('estado_validar') is-invalid @enderror "
                style="width: 100%;border-radius:10px" tabindex="-1" required>
                <option selected="selected">Seleccione.. </option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="OBSERVADO">OBSERVADO</option>
                <option value="VALIDADO">VALIDADO</option>
                <option value="AFILIADO">AFILIADO</option>
                <option value="TODOS">TODOS</option>
            </select>
            @error('estado_validar')
            <div class="text-danger">
                <b>El campo validación es obligatorio.</b>
            </div>
            @enderror
        </div>
        @endif


        @if ($tipo_reporte == 'reporte_umsa')
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <label for="switchFormatoReporte"> FORMATO DE REPORTE:</label>
            <div class="col-sm-6 d-flex align-items-center">
                <strong style="color:#2980b9">PDF</strong>
                <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-green">
                    <input wire:model.live="formato_reporte" id="switchFormatoReporte" type="checkbox"
                        class="custom-control-input">
                    <label class="custom-control-label" for="switchFormatoReporte"
                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                </div>
                <strong style="color:#2E8016">EXCEL</strong>
            </div>
        </div>
        @endif

    </div>
    @if ($tipo_reporte != 'reporte_umsa' || $formato_reporte == true)
    <div class="row">
        <div class="col-lg-6 col-md-4 col-sm-6 col-12" id="div_fecha_inicio">
            <label for="fecha_inicio">FECHA INICIO:</label>
            <input class="form-control @error('fecha_inicio') is-invalid @enderror  " id="fecha_inicio" type="date"
                style="border-radius:10px" wire:model="fecha_inicio">
            @error('fecha_inicio')
            <div class="text-danger">
                <b>{{ $message }}</b>
            </div>
            @enderror
        </div>
        <div class="col-lg-6 col-md-4 col-sm-6 col-12" id="div_fecha_fin">
            <label for="fecha_fin">FECHA FIN:</label>
            <input class="form-control @error('fecha_fin') is-invalid @enderror " id="fecha_fin" type="date"
                style="border-radius:10px" wire:model="fecha_fin">
            @error('fecha_fin')
            <div class="text-danger">
                <b>{{ $message }}</b>
            </div>
            @enderror
        </div>
    </div>
    @endif
    <div class="modal-footer" style="border: none; background-color: white;">
        <x-descargar-button onclick="funcionGuardarReporte()" wire:loading.attr="disabled">GENERAR</x-descargar-button>
    </div>
    <div class="col-lg-12" wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    function funcionGuardarReporte() {
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
                Livewire.dispatch('generarReporteEstudiantes');

            }
        });
    }

    document.addEventListener('ErrorGeneralEst', function() {
        Swal.fire({
            title: "Se canceló la acción",
            text: "Intenta nuevamente, si el problame persiste comunicate con la unidad de sistemas ",
            icon: "error"
            //showConfirmButton: false,
            //timer: 2000,
        });
    });


    document.addEventListener('reporteGeneradoExitosoEst', function() {
        Swal.fire({
            title: "Reporte descargado",
            text: "Se descargo correctamente",
            icon: "success",
        });
    });
</script>