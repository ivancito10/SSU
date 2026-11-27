<x-ver-modal id="VerAgendaAseguradoBeneficiario" labelledby="VerAgendaAseguradoBeneficiarioLabel" title="DETALLE AGENDA"
    saveAction="">

    @if ($suspensionAgendaBeneficiario)
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="alert alert-warning text-center shadow-sm">
                    LA AGENDA MOSTRADA A CONTINUACIÓN CORRESPONDE A UNA SUSPENSIÓN DE HORARIO POR: <br>
                    <strong>{{ $motivoSuspensionBeneficiario }}</strong> <br>
                    REGISTRADA EN FECHA <strong>{{ $fechaSuspensionCreadaBeneficiario }}</strong> POR LA/EL USUARIO
                    <strong>{{ $usuarioCreaSuspensionBeneficiario }}</strong>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card-comun p-2 mt-1" style="height: 100%;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cabecera-borde-azul justify-content-center">
                            <b>DATOS ASEGURADO</b>
                        </div>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-6 col-6  text-center">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
                                <img src="{{ !empty($fotoAgendaExistenteBeneficiario) ? 'data:image/jpg;base64,' . $fotoAgendaExistenteBeneficiario : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail" alt="img"
                                    style="width: 140px; height: 140px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-6 col-6  text-center">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span><b>{{ $nombreCompletoAgendaExistenteBeneficiario }}</b></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span><b style="color: #1a5276 ;">{{ $matriculaAgendaExistenteBeneficiario }}</b></span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <p><b>EDAD</b>:
                                    {{ $edadAgendaExistenteBeneficiario }}
                                    años
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <span><b
                                        style="color: #1a5276 ;font-size:30px">{{ $tipoAseguradoAgendaExistenteBeneficiario }}</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div class="card-comun p-2 mt-1" style="height: 100%;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cabecera-borde-azul justify-content-center">
                            <b>DATOS AGENDA</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span>
                            <b style="color: #1E8449; "> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill=" #1E8449" class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                                    <path d=" M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0
                                5.29-11.584l.013-.012.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1
                                0-.707.707l.354.354-.354.354-.012.012A6.97 6.97 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2
                                5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                                </svg> HORA</b>:
                            <span style="font-size: 19px;">
                                {{ $horaAgendaExistenteBeneficiario }}
                            </span>
                            <br>

                            <b style="color: #1E8449; "> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill=" #1E8449" class="bi bi-calendar2-check-fill"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                </svg> FECHA CITA</b>: <span
                                style="font-size: 19px;">{{ $fechaAgendaExistenteBeneficiario }} - <b
                                    style="color: #1a5276 ;"> {{ $dia_semana }}</b>
                            </span>
                            <br>

                            <b> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                    <path
                                        d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                </svg> SERVICIO AGENDADO</b>: <span
                                style="font-size: 19px;">{{ $servicioPlataformaAgendaExistenteBeneficiario }}
                            </span>
                            <br>

                            <b> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                </svg> FECHA DE REGISTRO</b>: <span
                                style="font-size: 19px;">{{ $fechaRegistroAgendaExistenteBeneficiario }}
                            </span>
                        </span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cabecera-borde-azul justify-content-center">
                            <b>UBICACIÓN</b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span>
                            <b> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                    <path
                                        d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg> UBICACIÓN:</b> <span
                                style="font-size: 19px;">{{ $ubicacionAgendaExistenteBeneficiario }}</span>
                            <br>

                            <b> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-buildings-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M15 .5a.5.5 0 0 0-.724-.447l-8 4A.5.5 0 0 0 6 4.5v3.14L.342 9.526A.5.5 0 0 0 0 10v5.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V14h1v1.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zM2 11h1v1H2zm2 0h1v1H4zm-1 2v1H2v-1zm1 0h1v1H4zm9-10v1h-1V3zM8 5h1v1H8zm1 2v1H8V7zM8 9h1v1H8zm2 0h1v1h-1zm-1 2v1H8v-1zm1 0h1v1h-1zm3-2v1h-1V9zm-1 2h1v1h-1zm-2-4h1v1h-1zm3 0v1h-1V7zm-2-2v1h-1V5zm1 0h1v1h-1z" />
                                </svg> PISO:</b>
                            <span style="font-size: 19px;">
                                {{ $pisoAgendaExistenteBeneficiario }}</span>
                            <br>

                            <b> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                    <path
                                        d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                                    <path
                                        d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                                </svg> CONSULTORIO:</b> <span style="font-size: 19px;">
                                {{ $nroConsultorioAgendaExistenteBeneficiario }}
                            </span>
                        </span>

                        {{-- <p style="text-align: right;"><button type="button" class="btn btn-outline-info"
                                            data-toggle="modal" data-target="#UbicacionSeguroInterior">Ubicación</button>
                                    </p> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card-comun p-2 mt-1" style="height: 100%;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cabecera-borde-azul justify-content-center">
                            <b>DATOS ESPECIALISTA</b>
                        </div>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-4 col-4 text-center">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <img src="data:image/jpg;base64,{{ $fotoEspecialistaAgendaExistenteBeneficiario }}"
                                    class="img-thumbnail" alt="img"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-8 col-8 ">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>ESPECIALIDAD</b>: {{ $especialidadAgendaExistenteBeneficiario }} <br>
                                    <b>ESPECIALISTA</b>: {{ $especialistaAgendaExistenteBeneficiario }} <br>
                                    <b>HORARIO</b>: {{ $horaInicioAgendaExistenteBeneficiario }} -
                                    {{ $horaFinAgendaExistenteBeneficiario }} <br>
                                    <b>TURNO</b>: {{ $turnoAgendaExistenteBeneficiario }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if (!$suspensionAgendaBeneficiario)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <p style="text-align: right;"><button type="button" class="btn btn-warning"
                                    wire:click="anularAgendaAnterior"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor" class="bi bi-trash-fill"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                                    </svg>
                                    <b>ANULAR AGENDA</b></button>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-ver-modal>
