<x-ver-modal-sin-titulo id="agenda" labelledby="agendaLabel">
    <div class="row">
        <!-- Aqui las tablas de las agendas -->
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 text-left">
                    <strong class="h3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-calendar-check-fill" viewBox="0 0 16 16">
                            <path
                                d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1h3zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                        </svg>
                        <em><b>{{ $diaLiteral }} : </b></em></strong>
                    <span class="h3" style="color:#21618c;"><b> {{ $texto }}</b><span>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 text-right">
                    <strong>ESPECIALIDAD:</strong>
                    <span style="color:#2c3e50;" class="h3">
                        <b>{{ $doc_especialidad }} </b>
                    </span>
                </div>
            </div>
            <div style="height: 0.5rem;"></div>

            <!-- Tabla para el turno mañana -->
            @if ($turno_m !== null)
                <div class="card card-borde p-2 ">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>TURNO:</b></span>
                            <p class="h5 p-1" style="background-color:#E6E6E6; border-radius:8px;"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#FCC314"
                                    class="bi bi-sun-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8M8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0m0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13m8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5M3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8m10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0m-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0m9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707M4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708" />
                                </svg> <em>MAÑANA</em> </p>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>HORA:</b></span>
                            <p class="h5"> <em>{{ $hinicio_m }} -
                                    {{ $hfin_m }}</em></p>

                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>UBICACIÓN:</b></span>
                            <p class="h5"> <em>{{ $ubicacion_m }} </em></p>

                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>CONSULTORIO:</b></span>
                            <p class="h5"><em> {{ $piso_m }} - {{ $nro_consultorio_m }}</em></p>

                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="d-flex justify-content-end gap-2 ">
                                <div class="pr-2">
                                    <button class="btn btn-warning" type="button" wire:click="imprimir_boucher_mañana">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> AGENDADOS </button>
                                </div>
                                <div>
                                    <button class="btn btn-success" type="button" wire:click="imprimir_agenda_mañana">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> REPORTE AGENDA</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div style="height: 0.5rem;"></div>
                    <div style="max-height: 550px; overflow-y: auto;">
                        <table class="tabla-celeste" style="width: 100%;">
                            <thead style="text-align:center;">
                                <tr>
                                    <th scope="col">HORA </th>
                                    <th scope="col">ESTADO</th>
                                    <th scope="col">MATRICULA</th>
                                    <th scope="col">ASEGURADO</th>
                                    <th scope="col">TIPO DE ASEGURADO</th>
                                    <th scope="col">AGENDA EN</th>
                                    <th scope="col">OBSERVACIÓN</th>
                                    <th scope="col">AGENDADO POR</th>
                                    <th scope="col">FECHA REGISTRO</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($consulta->isEmpty() || $consulta === [])
                                    <tr>
                                        <td colspan="6" style="text-align: center;">SIN AGENDAS</td>
                                    </tr>
                                @else
                                    @foreach ($consulta as $dato)
                                        <tr>
                                            <td style="text-align:center;">
                                                {{ $dato->hora_agenda ?? 'N/A' }}</td>

                                            @if ($dato->id_suspension_horario != null && $dato->anulacion_ficha == false)
                                                <td style="color:#dc7633;">SUSPENDIDO</td>
                                            @elseif($dato->id_suspension_horario != null && $dato->anulacion_ficha == true)
                                                <td style="color:red;">ANULADO</td>
                                            @elseif($dato->id_suspension_horario == null && $dato->anulacion_ficha == false)
                                                <td style="color:#196f3d;">OCUPADO</td>
                                            @else
                                                <td style="color:#cb4335;">ANULADO</td>
                                            @endif

                                            <td style="text-align:center;">
                                                {{ $dato->matricula_seguro ?? 'N/A' }}</td>

                                            <td style="text-align:left;">{{ $dato->nombres }}
                                                @if ($dato->p_apellido)
                                                    {{ $dato->p_apellido }}
                                                @endif
                                                @if ($dato->s_apellido)
                                                    {{ $dato->s_apellido }}
                                                @endif
                                            </td>
                                            <td>{{ $dato->tipo_asegurado }} </td>
                                            <td style="text-align:left;">
                                                {{ $dato->nombre_servicio ?? 'N/A' }}</td>



                                            <td style="text-align:left;">
                                                {{ $dato->motivo_anulacion ?? ' ' }}</td>
                                            <td> {{ $dato->name }} </td>
                                            <td> {{ $dato->created_at }} </td>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            @endif

            <!-- Tabla para el turno tarde -->
            @if ($turno_t !== null)
                <div class="card card-borde p-2 ">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>TURNO:</b></span>
                            <p class="h5 p-1" style="background-color:#E6E6E6; border-radius:8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#9E6659"
                                    class="bi bi-sunset-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M7.646 4.854a.5.5 0 0 0 .708 0l1.5-1.5a.5.5 0 0 0-.708-.708l-.646.647V1.5a.5.5 0 0 0-1 0v1.793l-.646-.647a.5.5 0 1 0-.708.708zm-5.303-.51a.5.5 0 0 1 .707 0l1.414 1.413a.5.5 0 0 1-.707.707L2.343 5.05a.5.5 0 0 1 0-.707zm11.314 0a.5.5 0 0 1 0 .706l-1.414 1.414a.5.5 0 1 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zM11.709 11.5a4 4 0 1 0-7.418 0H.5a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1h-3.79zM0 10a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2A.5.5 0 0 1 0 10m13 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
                                </svg>
                                <em>TARDE</em>
                            </p>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>HORA:</b></span>
                            <p class="h5"> <em> {{ $hinicio_t }} - {{ $hfin_t }}</em></p>

                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>UBICACIÓN:</b></span>
                            <p class="h5"> <em>{{ $ubicacion_t }} </em></p>

                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>CONSULTORIO:</b></span>
                            <p class="h5"><em> {{ $piso_t }} - {{ $nro_consultorio_t }}</em></p>

                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="d-flex justify-content-end gap-2 ">
                                <div class="pr-2">
                                    <button class="btn btn-warning" type="button"
                                        wire:click="imprimir_boucher_tarde">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> AGENDADOS </button>
                                </div>
                                <div>
                                    <button class="btn btn-success" type="button"
                                        wire:click="imprimir_agenda_tarde">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> REPORTE AGENDA</button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div style="height: 0.5rem;"></div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="tabla-celeste">
                            <thead>
                                <tr>
                                    <th scope="col">HORA </th>
                                    <th scope="col">ESTADO</th>
                                    <th scope="col">MATRICULA</th>
                                    <th scope="col">ASEGURADO</th>
                                    <th scope="col"> TIPO ASEGURADO</th>
                                    <th scope="col">AGENDA</th>
                                    <th scope="col">OBSERVACIÓN</th>
                                    <th scope="col"> AGENDADO POR</th>
                                    <th scope="col"> FECHA REGISTRO</th>

                                </tr>
                            </thead>
                            <tbody>

                                @if ($consulta_tarde->isEmpty() || $consulta_tarde === [])
                                    <tr>
                                        <td colspan="6" style="text-align: center;">SIN REGISTROS</td>
                                    </tr>
                                @else
                                    @foreach ($consulta_tarde as $dato)
                                        <tr>
                                            <td>{{ $dato->hora_agenda ?? 'N/A' }}</td>

                                            @if ($dato->id_suspension_horario != null && $dato->anulacion_ficha == false)
                                                <td style="color:#dc7633;">SUSPENDIDO</td>
                                            @elseif($dato->id_suspension_horario != null && $dato->anulacion_ficha == true)
                                                <td style="color:red;">ANULADO</td>
                                            @elseif($dato->id_suspension_horario == null && $dato->anulacion_ficha == false)
                                                <td style="color:#196f3d;">OCUPADO</td>
                                            @else
                                                <td style="color:#cb4335;">ANULADO</td>
                                            @endif

                                            <td>{{ $dato->matricula_seguro ?? 'N/A' }}</td>
                                            <td>{{ $dato->nombres }}
                                                @if ($dato->p_apellido)
                                                    {{ $dato->p_apellido }}
                                                @endif
                                                @if ($dato->s_apellido)
                                                    {{ $dato->s_apellido }}
                                                @endif
                                            </td>
                                            <td>{{ $dato->tipo_asegurado }} </td>
                                            <td>{{ $dato->nombre_servicio ?? 'N/A' }}</td>
                                            <td>{{ $dato->motivo_anulacion ?? ' ' }}</td>
                                            <td> {{ $dato->name }} </td>
                                            <td> {{ $dato->created_at }} </td>

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

            <!-- Tabla para el turno noche -->
            @if ($turno_n !== null)
                <div class="card card-borde p-2 ">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>TURNO:</b></span>
                            <p class="h5 p-1" style="background-color:#E6E6E6; border-radius:8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    fill="currentColor" class="bi bi-moon-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M6 .278a.77.77 0 0 1 .08.858 7.2 7.2 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277q.792-.001 1.533-.16a.79.79 0 0 1 .81.316.73.73 0 0 1-.031.893A8.35 8.35 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.75.75 0 0 1 6 .278" />
                                </svg> <em>NOCHE</em>
                            </p>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>HORA:</b></span>
                            <p class="h5"> <em>{{ $hinicio_n }} - {{ $hfin_n }}</em></p>

                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>UBICACIÓN:</b></span>
                            <p class="h5"> <em>{{ $ubicacion_n }} </em></p>

                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                            <span class="h6"><b>CONSULTORIO:</b></span>
                            <p class="h5"><em> {{ $piso_n }} - {{ $nro_consultorio_n }}</em></p>

                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="d-flex justify-content-end gap-2 ">
                                <div class="pr-2">
                                    <button class="btn btn-warning" type="button"
                                        wire:click="imprimir_boucher_noche">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> AGENDADOS </button>
                                </div>
                                <div>
                                    <button class="btn btn-success" type="button"
                                        wire:click="imprimir_agenda_noche">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> REPORTE AGENDA</button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div style="height: 0.5rem;"></div>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <table class="tabla-celeste">
                            <thead>
                                <tr>
                                    <th scope="col">HORA </th>
                                    <th scope="col">ESTADO</th>
                                    <th scope="col">MATRICULA</th>
                                    <th scope="col">ASEGURADO</th>
                                    <th scope="col">TIPO ASEGURADO</th>
                                    <th scope="col">AGENDA</th>
                                    <th scope="col">OBSERVACIÓN</th>
                                    <th scope="col">AGENDADO POR</th>
                                    <th scope="col"> FECHA REGISTRO</th>

                                </tr>
                            </thead>
                            <tbody>

                                @if ($consulta_noche->isEmpty() || $consulta_noche === [])
                                    <tr>
                                        <td colspan="6" style="text-align: center;">SIN REGISTROS</td>
                                    </tr>
                                @else
                                    @foreach ($consulta_noche as $dato)
                                        <tr>
                                            <td>{{ $dato->hora_agenda ?? 'N/A' }}</td>

                                            @if ($dato->id_suspension_horario != null && $dato->anulacion_ficha == false)
                                                <td style="color:#dc7633;">SUSPENDIDO</td>
                                            @elseif($dato->id_suspension_horario != null && $dato->anulacion_ficha == true)
                                                <td style="color:red;">ANULADO</td>
                                            @elseif($dato->id_suspension_horario == null && $dato->anulacion_ficha == false)
                                                <td style="color:#196f3d;">OCUPADO</td>
                                            @else
                                                <td style="color:#cb4335;">ANULADO</td>
                                            @endif

                                            <td>{{ $dato->matricula_seguro ?? 'N/A' }}</td>
                                            <td>{{ $dato->nombres }}
                                                @if ($dato->p_apellido)
                                                    {{ $dato->p_apellido }}
                                                @endif
                                                @if ($dato->s_apellido)
                                                    {{ $dato->s_apellido }}
                                                @endif
                                            </td>
                                            <td>{{ $dato->tipo_asegurado }} </td>
                                            <td>{{ $dato->nombre_servicio ?? 'N/A' }}</td>

                                            <td>{{ $dato->motivo_anulacion ?? ' ' }}</td>
                                            <td> {{ $dato->name }} </td>
                                            <td> {{ $dato->created_at }} </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif


        </div>
    </div>

</x-ver-modal-sin-titulo>

<script>
    document.addEventListener('BoucherGenerado', function() {
        Swal.fire({
            title: "<b>Boucher Generado </b>",
            text: "Se generó correctamente el boucher del la fecha seleccionada",
            icon: 'success',
            showConfirmButton: true
        });
    });

    document.addEventListener('ReporteGenerado', function() {
        Swal.fire({
            title: "<b>Reporte Generado </b>",
            text: "Se generó correctamente el reportede agenda correspondiente a la fecha seleccionada",
            icon: 'success',
            showConfirmButton: true
        });
    });
</script>
