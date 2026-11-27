<x-ver-modal id="verModalAfiliado" labelledby="verModalAfiliadoLabel" title=" HISTORIAL DEL ASEGURADO">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- row de 4 -->
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail"  style="width:200px; height:200px; object-fit:cover; object-position: top;" alt="ftp">
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <span><b>MATRICULA :</b> {{ $matricula }} </span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <span><b>EDAD :</b> {{ $edad }} años</span>
                        </div>
                    </div>
                </div>
                <!--  row de 8 -->
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="row ">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>TIPO :</strong>
                            <span style="color:#1a4c72;font-size:25px; font-weight: bold;">{{ $tipo_asegurado_m }}
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO: </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombre_completo }}
                            </p>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <strong> CARNET:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $ci }} @if ($complemento != null)
                                    - {{ $complemento }}
                                @endif
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <strong>EXP:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $sigla }}
                            </p>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <strong> FECHA NACIMIENTO: </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $fecha_nacimiento }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>SEXO:</strong>
                            @if ($sexo == 'F')
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; 
                                        border-color:white; border-radius:10px;">
                                    FEMENINO</p>
                            @else
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; 
                                    border-color:white; border-radius:10px;">
                                    MASCULINO</p>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> ESTADO CIVIL:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $estado_civil }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <br>

    <!--  Tabla -->
    <!--para el caso estudaintes-->

    @if ($varEstudiante)
        <div class="accordion" id="accordionDatosEstudiante">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="card-header" style="background-color:#f2f8fd" id="headingEstudiante">
                    <h2 class="mb-0">
                        <button
                            class="btn btn-outline btn-block text-left collapsed  d-flex justify-content-between align-items-center"
                            type="button" style="color: #1b5989" data-toggle="collapse"
                            data-target="#collapseEstudiante" aria-expanded="false" aria-controls="collapseFamiliar">
                            <b> HISTORIAL COMO ESTUDIANTE</b>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                <path
                                    d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                            </svg>
                        </button>
                    </h2>
                </div>
                <div id="collapseEstudiante" class="collapse" aria-labelledby="headingEstudiante"
                    data-parent="#accordionDatosEstudiante">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" style="max-height: 400; overflow-y: auto;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Tipo asegurado</th>
                                                <th>Fecha solicitud</th>
                                                <th>Fecha de afiliación </th>
                                                <th>Fecha de baja</th>
                                                <th>Carrera</th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align:center;">
                                            @foreach ($fue_estudiante as $dato)
                                                <tr>
                                                    <td>{{ $count = $count + 1 }}</td>
                                                    <td>ESTUDIANTE</td>
                                                    <td>{{ \Carbon\Carbon::parse($dato->fecha_solicitud_afiliacion)->Format('d /m/ Y') ?? 'S/D' }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($dato->fecha_afiliacion)->Format('d /m/ Y') ?? 'S/D' }}
                                                    </td>
                                                    <td>
                                                        @if ($dato['fecha_baja'])
                                                            {{ \Carbon\Carbon::parse($dato['fecha_baja'])->format('d/m/Y') }}
                                                        @else
                                                            S/R
                                                        @endif
                                                    </td>
                                                    <td>{{ $dato->carreraEstudiante->nombre ?? 'S/D' }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!--para el caso benefiicarios-->
    @if ($varBeneficiario)
        <div class="accordion" id="accordionDatosBeneficiario">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="card-header" style="background-color:#f2f8fd" id="headingBeneficiario">
                    <h2 class="mb-0">
                        <button
                            class="btn btn-outline btn-block text-left collapsed  d-flex justify-content-between align-items-center"
                            type="button" style="color: #1b5989" data-toggle="collapse"
                            data-target="#collapseBeneficiario" aria-expanded="false"
                            aria-controls="collapseBeneficiario">
                            <b> HISTORIAL COMO BENEFICIARIO</b>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                <path
                                    d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                            </svg>
                        </button>
                    </h2>
                </div>
                <div id="collapseBeneficiario" class="collapse" aria-labelledby="headingBeneficiario"
                    data-parent="#accordionDatosBeneficiario">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" style="max-height: 400; overflow-y: auto;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Tipo asegurado</th>
                                                <th>Fecha afiliacion</th>
                                                <th>Fecha baja </th>
                                                <th>Remuneracion</th>
                                                <th>Institucion</th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align:center;">
                                            @foreach ($fue_beneficiario as $beneficio)
                                                <tr>
                                                    <td>{{ $count = $count + 1 }}</td>
                                                    <td>{{ $beneficio['tipo_asegurado'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($beneficio['fecha_reafiliacion'])->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($beneficio['fecha_baja'])
                                                            {{ \Carbon\Carbon::parse($beneficio['fecha_baja'])->format('d/m/Y') }}
                                                        @else
                                                            S/R
                                                        @endif
                                                    </td>
                                                    <td>{{ $beneficio['remuneracion'] }}</td>

                                                    <td>{{ $beneficio['institucion'] }}</td>
                                                </tr>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endif

    <!--para el caso titular-->
    @if ($varTitular)
        <div class="accordion" id="accordionDatosTitular">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="card-header" style="background-color:#f2f8fd" id="headingTitular">
                    <h2 class="mb-0">
                        <button
                            class="btn btn-outline btn-block text-left collapsed  d-flex justify-content-between align-items-center"
                            type="button" style="color: #1b5989" data-toggle="collapse"
                            data-target="#collapseTitular" aria-expanded="false" aria-controls="collapseTitular">
                            <b> HISTORIAL COMO TITULAR</b>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                <path
                                    d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                            </svg>
                        </button>
                    </h2>
                </div>
                <div id="collapseTitular" class="collapse" aria-labelledby="headingTitular"
                    data-parent="#accordionDatosTitular">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive" style="max-height: 400; overflow-y: auto;">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Tipo de Titular</th>
                                                <th>Fecha de Afiliación</th>
                                                <th>Fecha de Baja</th>
                                                <th>Institución</th>
                                                <th>Contrato Voluntario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fue_titular as $titular)
                                                <tr>
                                                    <td>{{ $count = $count + 1 }}</td>
                                                    <td>{{ $titular['tipo_titular'] }} </td>
                                                    @if ($titular['fecha_reafiliacion'])
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($titular['fecha_reafiliacion'])->format('d/m/Y') }}
                                                        </td>
                                                    @else
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($titular['fecha_afiliacion'])->format('d/m/Y') }}
                                                        </td>
                                                    @endif

                                                    <td>
                                                        @if ($titular['fecha_baja'])
                                                            {{ \Carbon\Carbon::parse($titular['fecha_baja'])->format('d/m/Y') }}
                                                        @else
                                                            S/R
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($titular['tipo_titular'] === 'TITULAR INSTITUCIONAL')
                                                            <div>
                                                                {{ $titular['institucion'] }}
                                                                <br>
                                                                {{ $titular['sigla'] }}
                                                            </div>
                                                        @else
                                                            <div>
                                                                <p>No aplica</p>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($titular['tipo_titular'] === 'TITULAR VOLUNTARIO')
                                                            <div>
                                                                <b>Inicio:</b>
                                                                {{ $titular['fecha_inicio'] ? \Carbon\Carbon::parse($titular['fecha_inicio'])->format('d/m/Y') : 'SIN DATO' }}

                                                                <br>
                                                                <b>Fin:</b>
                                                                {{ $titular['fecha_inicio'] ? \Carbon\Carbon::parse($titular['fecha_inicio'])->format('d/m/Y') : 'SIN DATO' }}
                                                            </div>
                                                        @else
                                                            <div>
                                                                <p>No aplica</p>
                                                            </div>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    @endif

</x-ver-modal>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.addEventListener('modalVerAfiliado', event => {
        $('#verModalAfiliado').modal('show');
    });
</script>
