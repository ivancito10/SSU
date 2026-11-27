<div>
    <x-ver-modal id="verEspecialidadModal" labelledby="verEspecialidadLabel" title="DETALLES DE ESPECIALIDAD">

        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 text-right">
                <strong>FECHA DE REGISTRO: </strong>
                <em> {{ $fecha_registro }}</em>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong>ESTADO :</strong>
                @if ($estado)
                    <span style="color: #1e8449 ; font-size:20px"> <b>ACTIVO</b></span>
                @else
                    <span style="font-size:20px" class="text-danger"> <b>INACTIVO</b></span>
                @endif
            </div>
        </div>
        <div class='row'>
            <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                <strong>ESPECIALIDAD:</strong>
                <p class="p-1  p-comun-disabled">
                    {{ $especialidad }}</p>

            </div>
            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                <strong>SIGLA:</strong>
                <p class="p-1 p-comun-disabled ">
                    {{ $sigla }}</p>
            </div>
        </div>
        <div class='row'>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 text-right "></div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong>ESPECIALIDAD DE IMAGENOLOGÍA :</strong>
                @if ($especialidadImagenologia)
                    <span style="color: #1e8449 ; font-size:20px"> <b>SI</b></span>
                @else
                    <span style="font-size:20px" class="text-danger"> <b>NO</b></span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="cabecera-tabla-celeste">
                    <strong class="fs-6">LISTA DE SERVICIOS ASIGNADOS</strong>
                </div>
            </div>
        </div>
        <div style="height: 0.5rem;"></div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table width="100%" class="table tabla-celeste">
                        <thead>
                            <tr>
                                <th width="20% ">SERVICIO</th>
                                <th width="20%">DURACIÓN</th>
                                <th width="20%">INICIO</th>
                                <th width="20%">FIN </th>
                                <th width="20%">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($serviciosLista) && $serviciosLista->isNotEmpty())
                                @foreach ($serviciosLista as $dato)
                                    <tr style="text-align:left;">
                                        <td>{{ $dato->servicioEspecialidadHabilitadoServicio->nombre_servicio }}
                                        </td>
                                        <td>
                                            @if ($dato->permanente == true)
                                                <p class="text-blue"><b> PERMANENTE</b> </p>
                                            @elseif($dato->permanente == false)
                                                <p style="color: #1e8449 "><b>TEMPORAL </b></p>
                                            @endif
                                        </td>
                                        @if ($dato->permanente === true)
                                            <td colspan="2" style="text-align: center;">PERMANENTE</td>
                                        @else
                                            <td class="text-center ">
                                                {{ mb_strtoupper(\Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d \\d\\e F \\d\\e Y')) }}
                                            </td>
                                            <td class="text-center ">
                                                {{ mb_strtoupper(\Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d \\d\\e F \\d\\e Y')) }}
                                            </td>
                                        @endif
                                        <td style="text-align:center;">
                                            @if ($dato->estado == true)
                                                <p style="color: #1e8449"> <b>ACTIVO</b> </p>
                                            @elseif($dato->estado == false)
                                                <p style="color: #c0392b "> <b>INACTIVO</b></p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" style="text-align:center; color: gray;">No hay servicios
                                        disponibles</td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>

            </div>

        </div>

    </x-ver-modal>
</div>


<script>
    window.addEventListener('modalVerEspecialidad', event => {
        $('#verEspecialidadModal').modal('show');
    });
</script>
