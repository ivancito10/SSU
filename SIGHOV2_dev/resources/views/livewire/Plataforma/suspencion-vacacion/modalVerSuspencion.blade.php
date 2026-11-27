<div>
    <x-ver-modal id="verVacacion" labelledby="verVacacionLabel" title="DETALLES DE VACACIONES">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card card-comun p-2">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <div class="cabecera">
                                <b>ESPECIALISTA</b>
                            </div>
                            <div style="height: 0.5rem;"></div>

                            <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="ESPECIALISTA"
                                style="width: 150px; height: 150px; object-fit: cover;">

                            <br>
                            @if ($estadoEspecialista)
                                <span style='color:#46BD26 ;' class="h4"><b>ACTIVO</b></span>
                            @else
                                <span style='color:#FF350D ;' class="h4"><b>INACTIVO</b></span>
                            @endif
                            <br>
                            @if ($permanente)
                                <span style='color:#2874A6 ;' class="h4"><b>PERMANENTE</b></span>
                            @else
                                <span style='color:#DC7633 ;' class="h4"><b>EVENTUAL</b></span>
                            @endif

                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">

                            <div class="row">
                                <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                    <strong for="nombres"> NOMBRE
                                        COMPLETO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $nombres }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <strong for="matricula">
                                        MATRÍCULA:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $matricula }}</p>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                                    <strong for="ci"> CARNET:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $ci }}</p>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                    <strong for="expedido">EXPEDIDO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $expedido }}</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                    <strong for="es_extranjero"> TIPO DOC.:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $es_extranjero ? 'EXTRANJERO' : 'NACIONAL' }}</p>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <strong for="fecha_nacimiento"> FECHA DE NACIMIENTO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ \Carbon\Carbon::parse($fecha_nacimiento)->translatedFormat('d /m/ Y') }}
                                    </p>
                                </div>
                            </div>


                            <hr>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <strong>ESPECIALIDAD:</strong>
                                    <span style="color:#2c3e50;" class="h3">
                                        <b>{{ $doc_especialidad }} </b>
                                    </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong for="grado">
                                        GRADO ACADÉMICO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $grado }}</p>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <strong for="fecha_inicio">
                                        INICIO CONTRATO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ \Carbon\Carbon::parse($fecha_inicio)->translatedFormat('d/m/Y') }}</p>
                                </div>
                                @if (!$permanente)
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                        <strong for="fecha_fin">
                                            FIN CONTRATO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ \Carbon\Carbon::parse($fecha_fin)->translatedFormat('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card card-comun p-2">
                    <div class="cabecera-tabla-azul-oscuro justify-content-center">
                        <b>LISTADO DE VACACIONES REGISTRADAS</b>
                    </div>
                    <div style="height: 0.2rem;"></div>
                    <div class="scroll-container-vacaciones">
                        <table class="tabla-azul-oscuro" width="100%">
                            <thead>
                                <tr>
                                    <th>NRO. BOLETA RR.HH</th>
                                    <th>FECHA INICIO </th>
                                    <th>FECHA FIN</th>
                                    <th>ESTADO</th>
                                    <th>OBSERVACIONES</th>
                                    <th>OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($vacaciones_esp && $vacaciones_esp->count() > 0)
                                    @foreach ($vacaciones_esp as $dato)
                                        <tr>
                                            <td wire:model="dato->nro_registro_vacacion_rrhh">
                                                {{ $dato->nro_registro_vacacion_rrhh }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}
                                            </td>
                                            <td>
                                                @if ($dato->estado == 'VACACION ACTIVA')
                                                    <p style="color:#229954 "><b>CON VACACIÓN</b></p>
                                                @elseif($dato->estado == 'VACACION ANULADA')
                                                    <p style="color:#e74c3c"><b> VACACION ANULADA</b></p>
                                                @elseif($dato->estado == 'VACACION VENCIDA')
                                                    <p style="color:#e67e22 "><b>VACACIÓN VENCIDA</b></p>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dato->observacion)
                                                    {{ $dato->observacion }}
                                                @else
                                                    <em>Sin observaciones</em>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dato->estado == 'VACACION ACTIVA')
                                                    <x-ver-button style="padding:5px;"
                                                        wire:click="verAgendados(['{{ $idEsp }}', '{{ $dato->fecha_inicio }}',  '{{ $dato->fecha_fin }}'])">
                                                        AGENDADOS</x-ver-button>
                                                @else
                                                    <em>Sin opciones.</em>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="6">No hay registros de vacaciones.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-ver-modal>
    <style>
        .scroll-container-vacaciones {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</div>


<script>
    window.addEventListener('modalVerVacacion', event => {

        $('#verVacacion').modal('show');
    });
</script>
