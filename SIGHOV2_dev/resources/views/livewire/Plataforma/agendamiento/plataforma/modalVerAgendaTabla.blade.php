<div>
    <x-ver-modal id="verModalAgendita" labelledby="verModalAgenditaLabel" title="VER AGENDA DEL ASEGURADO">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="card card-comun p-2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="cabecera text-center">
                                <b>ASEGURADO</b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nombreCompleto }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> CARNET:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $ci }}</p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> MATRICULA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $matricula }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> INSTITUCION:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $institucion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <div class="card dard-comun p-2">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="cabecera text-center">
                                <b>DATOS DE AGENDA REGISTRADA</b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>FECHA DE AGENDA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ \Carbon\Carbon::parse($fecha_agenda)->translatedFormat('d /m/ Y ') }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>HORA DE AGENDA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $hora_agenda }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>DIA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nombreDia }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>SERVICIO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $servicio }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>NRO CONSULTORIO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $nro_consultorio }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>PISO :</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $piso }}</p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>UBICACIÓN:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $ubicacion }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <strong>ESPECIALISTA:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $especialista }}</p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <strong>ESPECIALIDAD:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $especialidadNom }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @can('super-admin-only')
            <div class="row">
                @if ($usuarioCreadorAgenda !== null)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> AGENDA CREADA POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuarioCreadorAgenda }}
                                </li>
                                <li>
                                    <strong>En fecha:</strong>
                                    {{ Carbon\Carbon::parse($fechaCreacionAgenda)->format('d/m/Y') }} <b> Hrs:</b>
                                    {{ Carbon\Carbon::parse($fechaCreacionAgenda)->format('H:i A') }}
                                </li>
                                <li>
                                    <strong>Nombre de equipo:</strong>
                                    {{ $nombre_equipo }}
                                </li>
                                <li>
                                    <strong>Direccion IP de equipo:</strong>
                                    {{ $ip }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif
                @if ($usuarioSuspendeAgenda !== null)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-danger font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> AGENDA SUSPENDIDA POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuarioSuspendeAgenda }}
                                </li>
                                <li>
                                    <strong>En fecha:</strong>
                                    {{ $fechaSuspensionAgenda }}
                                </li>
                                <li>
                                    <strong>Motivo:</strong>
                                    {{ $motivoSuspensionAgenda }}
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

                @if ($usuarioAnulaAgenda !== null)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-danger font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> AGENDA ANULADA POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuarioAnulaAgenda }}
                                </li>
                                <li>
                                    <strong>En fecha:</strong>
                                    {{ Carbon\Carbon::parse($fechaAnulacionAgenda)->format('d/m/Y') }} <b> Hrs:</b>
                                    {{ Carbon\Carbon::parse($fechaAnulacionAgenda)->format('H:i A') }}
                                </li>

                                <li>
                                    <strong>Motivo:</strong>
                                    {{ $motivoAnulacionAgenda }}
                                </li>
                                <b>De tabla anulación agenda --</b>
                                <li>
                                    <strong>Direccion IP de equipo:</strong>
                                    {{ $ip_anulado }}
                                </li>
                                <li>
                                    <strong>Nombre de equipo:</strong>
                                    {{ $nombre_equipo_anulado }}
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        @endcan
    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalVerAgendaTabla', event => {
        $('#verModalAgendita').modal('show');
    });
</script>
