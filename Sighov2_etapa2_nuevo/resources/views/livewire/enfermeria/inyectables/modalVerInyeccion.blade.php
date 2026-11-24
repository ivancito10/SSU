<div>
    <x-ver-modal id="modalVerInyeccion" labelledby="modalVerInyeccionLabel"
        title="DETALLE DE ATENCIÓN DE INYECCIÓN {{ $detalleAtencion ? '#' . $detalleAtencion['id'] : '' }}">
        @if ($detalleAtencion)
            <div class="card-borde p-2">
                {{-- DATOS DEL PACIENTE + ESTADO --}}
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>DATOS DEL PACIENTE</b>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 col-12 text-right">
                        <strong>ESTADO: </strong>
                        @if ($detalleAtencion['estado'])
                            <span style="background-color:#1B950E;color:white; padding:5px;border-radius:10px"><b>ACTIVO</b></span>
                        @else
                            <span style="background-color:#EB4123;color:white; padding:5px;border-radius:10px">INACTIVO</span>
                        @endif
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong><i class="fas fa-user mr-1"></i> NOMBRE COMPLETO:</strong>
                        <p class="p-comun-disabled p-1">{{ $detalleAtencion['paciente']['nombre'] }}</p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <strong><i class="fas fa-id-card mr-1"></i> CARNET:</strong>
                        <p class="p-comun-disabled p-1">{{ $detalleAtencion['paciente']['ci'] }}</p>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <strong><i class="fas fa-barcode mr-1"></i> MATRÍCULA:</strong>
                        <p class="p-comun-disabled p-1">{{ $detalleAtencion['paciente']['matricula'] }}</p>
                    </div>
                </div>

                <div style="height: 0.5rem;"></div>

                {{-- DATOS DE LA ATENCIÓN --}}
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>DATOS DE LA ATENCIÓN</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <strong><i class="fas fa-file-medical mr-1"></i> # RECETA:</strong>
                        <p class="p-comun-disabled p-1">{{ $detalleAtencion['nro_receta'] }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                        <strong><i class="fas fa-calendar-alt mr-1"></i> FECHA REGISTRO:</strong>
                        <p class="p-comun-disabled p-1">{{ $detalleAtencion['fecha_registro'] }}</p>
                    </div>
                </div>

                <div style="height: 0.5rem;"></div>

                {{-- INYECCIONES / MEDICAMENTOS REGISTRADOS --}}
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <div class="card cabecera">
                            <b>INYECCIONES / MEDICAMENTOS REGISTRADOS</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table tabla-ploma">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 45%;">MEDICAMENTO</th>
                                        <th>OBSERVACIÓN / VÍA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detalleAtencion['medicamentos'] as $index => $med)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><b>{{ $med['nombre'] }}</b></td>
                                            <td>{{ $med['observacion'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" style="text-align: center;">No se registraron medicamentos</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- PROCEDIMIENTOS DE ENFERMERÍA REALIZADOS --}}
                @if ($detalleAtencion['procedimiento'])
                    <div style="height: 0.5rem;"></div>
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                            <div class="card cabecera">
                                <b>PROCEDIMIENTOS DE ENFERMERÍA REALIZADOS</b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="d-flex flex-wrap justify-content-around py-2">
                                @php
                                    $procedimientosLista = [
                                        'curacion_plana'      => 'Curación Plana',
                                        'curacion_infectada'  => 'Curación Infectada',
                                        'oxigenoterapia'      => 'Oxigenoterapia',
                                        'retiro_puntos'       => 'Retiro de Puntos',
                                        'prueba_sensibilidad' => 'Prueba Sensibilidad',
                                        'sangria'             => 'Sangría',
                                    ];
                                @endphp
                                @foreach ($procedimientosLista as $campo => $etiqueta)
                                    @if ($detalleAtencion['procedimiento'][$campo])
                                        <span class="m-1"
                                            style="background-color:#1B950E; color:white; padding:5px 12px; border-radius:10px; font-size: 13px;">
                                            <i class="fas fa-check-circle mr-1"></i><b>{{ $etiqueta }}</b>
                                        </span>
                                    @else
                                        <span class="m-1"
                                            style="background-color:#e9ecef; color:#6c757d; padding:5px 12px; border-radius:10px; font-size: 13px;">
                                            <i class="fas fa-circle mr-1" style="font-size: 9px;"></i>{{ $etiqueta }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-1">
                            <strong><i class="fas fa-sticky-note mr-1"></i> NOTAS ADICIONALES:</strong>
                            <p class="p-comun-disabled p-1">{{ $detalleAtencion['procedimiento']['observaciones'] }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div style="height: 0.5rem;"></div>

            {{-- AUDITORÍA: REGISTRADO POR / MODIFICADO POR --}}
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                            <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                {{ $detalleAtencion['user_crea'] }}
                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                @if ($detalleAtencion['fecha_creado'])
                                    {{ \Carbon\Carbon::parse($detalleAtencion['fecha_creado'])->format('d/m/Y') }}
                                    <b> Hrs:</b>
                                    {{ \Carbon\Carbon::parse($detalleAtencion['fecha_creado'])->format('H:i A') }}
                                @else
                                    SIN DATO
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                @if ($detalleAtencion['fecha_editado'] != null)
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-orange font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $detalleAtencion['user_edit'] }}
                                </li>
                                <li>
                                    <strong>Fecha de registro:</strong>
                                    {{ \Carbon\Carbon::parse($detalleAtencion['fecha_editado'])->format('d/m/Y') }}
                                    <b> Hrs:</b>
                                    {{ \Carbon\Carbon::parse($detalleAtencion['fecha_editado'])->format('H:i A') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </x-ver-modal>
</div>
<script>
    document.addEventListener('verInyeccionModal', function() {
        $('#modalVerInyeccion').modal('show');
    });
</script>
