<div>
    <table class="tabla-ploma w-100">
        <tr>
            <td colspan="2"> <b>INSTITUCIÓN:</b> {{ $nombre }} </td>
            <td colspan="1"><b>ACRÓNIMO:</b> {{ $acronimo }} </td>

        </tr>
        <tr>
            <td><b>NIT:</b> {{ $nit }} </td>
            <td><b>CLASE APORTANTE:</b> {{ $tipoclaseaportante }} </td>
            <td><b>NUMERO DE EMPLEADOR:</b> {{ $num_empleador }} </td>
        </tr>
    </table>
    <div style="height: 0.5rem;"></div>
    <div class="col-lg-12">
        <fieldset class="field_estilos_sin_hover">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <em> Complete los siguientes datos para el generar el reporte en excel : </em>
                <span>*</span>
            </legend>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="mes_id"> MES: </label>
                    <select wire:model="mes_id" name="mes_id" id="mes_id"
                        class="form-control input-comun @error('mes_id') is-invalid @enderror" tabindex="-1" required>
                        <option selected=" ">Seleccione el mes ... </option>
                        @foreach ($meses as $mes)
                            <option value="{{ $mes->id }}">{{ $mes->mes }}
                            </option>
                        @endforeach
                    </select>

                    @error('mes_id')
                        <div class="text-danger">
                            <b>El campo mes es obligatorio</b>
                        </div>
                    @enderror
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label for="anio"> GESTIÓN: </label>
                    <select wire:model="anio" name="anio" id="anio"
                        class="form-control @error('anio') is-invalid @enderror input-comun" tabindex="-1" required>
                        <option selected=" ">Seleccione la gestión </option>
                        @foreach ($gestiones as $gestion)
                            <option value="{{ $gestion }}">{{ $gestion }}</option>
                        @endforeach
                    </select>
                    @error('anio')
                        <div class="text-danger">
                            <b>El campo gestion es obligatorio</b>
                        </div>
                    @enderror
                </div>
            </div>
            <div style="height: 0.5rem;"></div>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <x-reportes-button wire:click="generarReporteAfiliados">
                        GENERAR REPORTE
                    </x-reportes-button>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<script>
    document.addEventListener('excelExitoso', function() {
        $('#modalReporteAfiliados').modal('hide');
        Swal.fire({
            title: "Reporte generado",
            text: "El reporte se generó exitosamente",
            icon: "success"

        });
    });
</script>
