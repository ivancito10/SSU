<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="tipo_reporte">TIPO DE REPORTE:</label>
            <select wire:model.live="tipo_reporte" id="tipo_reporte"
                class="form-control input-comun  @error('tipo_reporte') is-invalid @enderror" tabindex="-1" required>
                <option selected="selected">Seleccione el tipo de reporte.. </option>
                <option value="carnets">CARNETS EMITIDOS</option>
                <option value="registros">REGISTROS NUEVOS</option>
                <option value="institucion">REGISTROS POR INSTITUCIÓN</option>
                <option value="tipoBeneficiarios"> TIPO DE BENEFICIARIO</option>
            </select>
            @error('tipo_reporte')
                <div class="text-danger">
                    <b>{{ $message }}</b>
                </div>
            @enderror
        </div>

        @if ($tipo_reporte == 'institucion')
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label for="id_institucion">INSTITUCIÓN
                    <span style="color:#cb4335;"><b>*</b></span>
                </label>
                <select wire:model="id_institucion" name="id_institucion" id="id_institucion"
                    class="form-control @error('id_institucion') is-invalid @enderror"
                    style="width: 100%; border-radius:10px;" tabindex="-1" required>
                    <option selected="selected">Selecciona la nueva Institución </option>
                    @foreach ($instituciones as $dato)
                        <option value="{{ $dato->id }}">{{ $dato->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_institucion')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        @endif


        @if ($tipo_reporte == 'tipoBeneficiarios')
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label for="id_tipo_asegurado">TIPO DE BENEFICIARIO
                    <span style="color:#cb4335;"><b>*</b></span>
                </label>
                <select wire:model="id_tipo_asegurado" name="id_tipo_asegurado" id="id_tipo_asegurado"
                    class="form-control @error('id_tipo_asegurado') is-invalid @enderror"
                    style="width: 100%; border-radius:10px;" tabindex="-1" required>
                    <option selected="selected">Seleccione el tipo de asegurado ... </option>
                    @foreach ($tipos_asegurados as $dato)
                        <option value="{{ $dato->id }}">{{ $dato->tipo_asegurado }}
                        </option>
                    @endforeach
                </select>
                @error('id_tipo_asegurado')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror
            </div>
        @endif


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
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#modalReportes').modal('hide');
                Livewire.dispatch('generarReporteBeneficiarios');
            }
        });
    }


    document.addEventListener('ErrorGeneralBen', function() {
        Swal.fire({
            title: "Se canceló la acción",
            text: "Intenta nuevamente, si el problame persiste comunicate con la unidad de sistemas ",
            icon: "error"
            //showConfirmButton: false,
            //timer: 2000,
        });
    });


    document.addEventListener('reporteGeneradoExitosoBen', function() {
        Swal.fire({
            title: "Reporte descargado",
            text: "Se descargo correctamente",
            icon: "success",
        });
    });
</script>
