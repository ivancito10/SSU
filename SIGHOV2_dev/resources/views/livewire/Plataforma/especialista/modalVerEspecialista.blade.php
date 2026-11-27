<div>
    <x-ver-modal id="verModal" labelledby="verLabel" title="VER DATOS DEL ESPECIALISTA">
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
                                style="width: 200px; height: 200px; object-fit: cover;">
                            <div style="height: 0.5rem;"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                                    @if ($estadoEspecialista)
                                        <span style='color:#46BD26 ;' class="h4"><b>ACTIVO</b></span>
                                    @else
                                        <span style='color:#FF350D ;' class="h4"><b>INACTIVO</b></span>
                                    @endif
                                    <br>
                                    @if ($permanente)
                                        <span style='color:#2874A6 ;' class="h4 "><b>PERMANENTE</b></span>
                                    @else
                                        <span style='color:#DC7633 ;' class="h4"><b>EVENTUAL</b></span>
                                    @endif
                                </div>


                            </div>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <strong>NOMBRE COMPLETO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $nombres }} </p>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong>MATRÍCULA:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                        {{ $matricula }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <strong>CARNET:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                        {{ $carnet }} </p>
                                </div>



                                <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                    <strong>EXPEDIDO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                        {{ $expedido }} </p>
                                </div>
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <strong>TIPO DOC.:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                        {{ $tipoDoc }} </p>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong>FECHA DE NACIMIENTO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $fecha_nacimiento }} </p>
                                </div>

                            </div>
                            <hr style="color:#0562B0">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <strong>ESPECIALIDAD:</strong>
                                    <span style="color:#2c3e50;" class="h3">
                                        <b>{{ $doc_especialidad }} </b>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <strong>GRADO ACADÉMICO:</strong>
                                    <p class="p-1"
                                        style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                        {{ $grado }} </p>
                                </div>

                                @if (!$permanente)
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <strong>INICIO CONTRATO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ $fecha_inicio_m }} </p>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <strong>FIN CONTRATO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ $fecha_fin_m }} </p>
                                    </div>
                                @endif
                            </div>
                            <div style="height: 0.5rem;"></div>

                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <strong>TIPO DE ATENCIÓN A POBLACIÓN ASEGURADA: </strong>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                    @if ($checkAfiliado)
                                        <p><b>AFILIADO:</b> <br> <b style="color: #196f3d;">SI</b> </p>
                                    @else
                                        <p><b>AFILIADO:</b> <br><b style="color: #c0392b;">NO</b> </p>
                                    @endif

                                </div>

                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">

                                    @if ($checkConvenio)
                                        <p><b>CONVENIO:</b> <br> <b style="color: #196f3d;">SI</b> </p>
                                    @else
                                        <p><b>CONVENIO:</b> <br> <b style="color: #c0392b;">NO</b> </p>
                                    @endif
                                </div>

                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">

                                    @if ($checkSsue)
                                        <p><b>SSUE:</b> <br> <b style="color: #196f3d;">SI</b> </p>
                                    @else
                                        <p><b>SSUE:</b> <br> <b style="color: #c0392b;">NO</b> </p>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <strong>ESPECIALISTA DE IMAGENOLOGÍA: </strong>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4">
                                    @if ($especialistaImagenologia)
                                        <b style="color: #196f3d;">SI</b> </p>
                                    @else
                                        <b style="color: #c0392b;">NO</b> </p>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cabecera-tabla-celeste justify-content-center">
            <b>LISTA DE SERVICIOS Y HORARIOS
                HABILITADOS</b>
        </div>
        <div style="height: 0.5rem;"></div>


        <div class="scroll-container">
            <table class="table tabla-azul-oscuro" width="100%">
                <thead>
                    <tr>
                        <th>LUNES</th>
                        <th>MARTES</th>
                        <th>MIÉRCOLES</th>
                        <th>JUEVES</th>
                        <th>VIERNES</th>
                        <th>SÁBADO</th>
                        <th>DOMINGO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach (['1' => 'LUNES', '2' => 'MARTES', '3' => 'MIÉRCOLES', '4' => 'JUEVES', '5' => 'VIERNES', '6' => 'SÁBADO', '0' => 'DOMINGO'] as $diaId => $diaNombre)
                            <td style="border: 1px solid #d1d5d8; padding: 6px; vertical-align: top;">
                                @php
                                    $serviciosDia = collect($serviciosSelected)->filter(
                                        fn($servicio) => $servicio->id_dia == $diaId,
                                    );
                                @endphp

                                @if ($serviciosDia->isNotEmpty())
                                    <div class="cabecera text-center">
                                        <b>SERVICIOS:</b>
                                    </div>
                                    @foreach ($serviciosDia as $servicio)
                                        <div class="card-borde p-1 mt-1">
                                            <li style=" margin-left: 16px; margin: 2px 0;">
                                                <b>
                                                    {{ $servicio->servicioEspecialistaHabilitadoServicio?->nombre_servicio }}</b>
                                            </li>
                                            @if ($servicio->permanente == true)
                                                <span class="d-flex justify-content-center " style="font-size:90%"><b
                                                        style="background-color: #2874A6;border-radius:6px;color:white;padding:2px">PERMANENTE</b></span>
                                            @else
                                                <span class="d-flex justify-content-center " style="font-size:90%"><b
                                                        style="background-color: #c95148ff;border-radius:6px;color:white;padding:2px">EVENTUAL</b></span>

                                                <span style="font-size:90%"><b>INICIO:
                                                    </b>
                                                    {{ \Carbon\Carbon::parse($servicio->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                </span>
                                                <br>
                                                <span style="font-size:90%"> <b>FIN:
                                                    </b>
                                                    {{ \Carbon\Carbon::parse($servicio->fecha_fin)->translatedFormat('d /m/ Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <p style="color: #e74c3c; font-weight: bold; text-align: center; margin: 2px 0;">
                                        SIN SERVICIO SELECCIONADO</p>
                                @endif

                                @php
                                    $horariosDia = collect($asignacionHorarioSelected)->filter(
                                        fn($horario) => $horario->id_dia == $diaId,
                                    );
                                @endphp
                                @if ($horariosDia->isNotEmpty())
                                    @foreach ($horariosDia as $horario)
                                        <div style="height: 0.5rem;"></div>
                                        <div class="cabecera text-center">
                                            <b>
                                                {{ $horariosDia->count() === 1 ? 'HORARIO' : 'HORARIO ' . $loop->iteration }}
                                            </b>
                                        </div>
                                        <div style="height: 0.5rem;"></div>

                                        <div class="card-borde p-1">
                                            <p style="margin: 2px 0;">De:
                                                <b>{{ \Carbon\Carbon::parse($horario->horarioAsignacionHorario->hora_inicio)->format('H:i') }}</b>
                                                a
                                                <b>{{ \Carbon\Carbon::parse($horario->horarioAsignacionHorario->hora_fin)->format('H:i') }}</b>
                                            </p>
                                            <p style="margin: 2px 0;">Pacientes:
                                                <b>{{ $horario->horarioAsignacionHorario->numero_paciente }}</b>
                                            </p>
                                            <p style="margin: 2px 0;">Turno:
                                                <b>{{ $horario->horarioAsignacionHorario->turno }}</b>
                                            </p>
                                            <p style="margin: 2px 0;">Consultorio:
                                                <b>{{ $horario->consultorioAsignacionHorario->numero_consultorio }}</b>
                                            </p>
                                            <p style="margin: 2px 0;">Ubicación:
                                                <b>{{ $horario->consultorioAsignacionHorario->consultorioSedes->piso }}</b>,
                                                <b>{{ $horario->consultorioAsignacionHorario->consultorioSedes->ubicacion }}</b>
                                            </p>
                                        </div>
                                    @endforeach
                                @else
                                    <p style="color: #1167c9ff; font-weight: bold; text-align: center; margin: 2px 0;">
                                        SIN HORARIO ASIGNADO</p>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                </tbody>
            </table>
        </div>


    </x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerEspecialista', event => {
        $('#verModal').modal('show');
    });

    // HABILITAR EL SERVICIO
    document.addEventListener('servicioEspHabilitado', function() {
        Swal.fire({
            title: "Servicio Habilitado!",
            text: " Se habilito el servicio",
            icon: "success",
            timer: 10000,
        });
        $('#verModal').modal('hide');
    });
    // DESHABILITAR EL SERVICIO
    document.addEventListener('servicioEspDeshabilitado', function() {
        Swal.fire({
            title: "Servicio Deshabilitado!",
            text: " Se deshabilito el servicio",
            icon: "error",
            timer: 10000,
        });
        $('#verModal').modal('hide');
    });
</script>
