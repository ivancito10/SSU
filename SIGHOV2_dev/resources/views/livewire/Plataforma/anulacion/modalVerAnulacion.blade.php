<div>
    <x-ver-modal id="verAnulacion" labelledby="verAnulacionLabel" title=" DETALLES DE ANULACIÓN ">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="card card-comun p-2">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <div class="cabecera text-center ">
                                        <b>ASEGURADO</b>
                                    </div>
                                </div>
                            </div>
                            <div style="height: 0.5rem;"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:center;">
                                    <!--  Mostramos la foto del asegurado en base 64 -->
                                    <img src="{{ !empty($fotoAsegurado) ? 'data:image/jpg;base64,' . $fotoAsegurado : asset('images/default_afiliacion.jpg') }}"
                                        class="img-thumbnail" alt="ASEGURADO"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                    <p style="color:#2c3e50; font-size: 25px"><b>{{ $tipoAsegurado }}</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>NOMBRE COMPLETO:</strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $nombres }}</p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong>MATRÍCULA:</strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $matricula }}</p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong>CARNET:</strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $carnetAsegurado }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                <div class="card card-comun p-2">
                    <div class=row>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                    <div class="cabecera text-center ">
                                        <b>ESPECIALISTA</b>
                                    </div>
                                </div>
                            </div>
                            <div style="height: 0.5rem;"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:center;">
                                    <!-- FOTOGRAFIA -->
                                    <img src="{{ !empty($fotoEspecialista) ? 'data:image/jpg;base64,' . $fotoEspecialista : asset('images/default_afiliacion.jpg') }}"
                                        class="img-thumbnail" alt="ESPECIALISTA"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                    <span style="color:#2c3e50;" class="h4">
                                        <b>{{ $nombreEspecialidad }} </b>
                                    </span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                    <span style="color: {{ $permanente ? '#28a745' : '#2874A6' }};"
                                        class="h5"><b>{{ !$permanente ? 'PERMANENTE' : 'EVENTUAL' }}</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>NOMBRE COMPLETO:</strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $nombreEspecialista }}</p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong>MATRICULA:</strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $matriculaEspecialista }}</p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong>CARNET:</strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $carnetEspecialista }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="p-2 card card-borde mb-2 h-100">
                    <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                        <i class="fas fa-check-circle mr-1"></i> AGENDA REGISTRADA POR
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <strong>Nombre de usuario:</strong>
                            {{ $user_created }}
                        </li>
                        <li>
                            <strong>En fecha:</strong>
                            {{ Carbon\Carbon::parse($fecha_creacion)->format('d/m/Y') }} <b> Hrs:</b>
                            {{ Carbon\Carbon::parse($fecha_creacion)->format('H:i A') }}

                        </li>
                        <li>
                            <strong>Fecha de agenda:</strong>
                            {{ \Carbon\Carbon::parse($fecha_agenda)->format('d/m/Y') }}
                        </li>
                        <li>
                            <strong>Hora de agenda:</strong>
                            {{ $hora_agenda }}
                        </li>
                        <li>
                            <strong>Modalidad de ficha:</strong>
                            {{ $servicio_agenda }}
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
            <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="p-2 card card-borde mb-2 h-100">
                    <h6 class="mb-3 text-danger font-weight-bold border-bottom pb-1">
                        <i class="fas fa-check-circle mr-1"></i> AGENDA ANULADA POR
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <strong>Nombre de usuario:</strong>
                            {{ $anuladoPor }}
                        </li>
                        <li>
                            <strong>En fecha:</strong>
                            {{ Carbon\Carbon::parse($fecha_anulacion)->format('d/m/Y') }} <b> Hrs:</b>
                            {{ Carbon\Carbon::parse($fecha_anulacion)->format('H:i A') }}

                        </li>
                        <li>
                            <strong>Motivo:</strong>
                            {{ $motivo_anulacion }}
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

        </div>

    </x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerAnulacion', event => {
        $('#verAnulacion').modal('show');
    });
</script>
