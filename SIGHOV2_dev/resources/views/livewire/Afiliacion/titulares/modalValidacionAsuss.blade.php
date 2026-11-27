<x-ver-modal id="verModalValidacionAsuss" labelledby="verModalValidacionAsussLabel" title=" VERIFICACION DE DATOS - ASUSS "
    saveAction="">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class=" d-flex flex-column align-items-center">
                            <img src="{{ $this->imagen_fotograficaAsuss }}" alt="Imagen Base64" class="img-thumbnail"
                                width="200" height="200">
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong> NOMBRE COMPLETO</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $nombreCompletoAsuss }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <strong> CARNET</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $carnet_completoAsuss }}</p>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <strong> FECHA DE NACIMIENTO</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $fecha_nacimientoAsuss }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <strong> SEXO</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $sexoAsuss }}</p>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <strong> ESTADO CIVIL</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $estado_civilAsuss }}</p>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <strong> PAIS</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $paisAsuss }}</p>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <strong> DEPARTAMENTO</strong>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $departamentoAsuss }}</p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--datos otros-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <!-- Muestra el acordeon -->
            <div class="accordion" id="accordionBdpa">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <div class="card-header p-0" id="headingBdpa" style="background:#5499c7;">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center" type="button" data-toggle="collapse"
                                data-target="#collapseBdpa" aria-expanded="false" aria-controls="collapseBdpa"
                                style="color:white">
                                BASE DE DATOS DE LA POBLACIÓN ASEGURADA (BDPA)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                </svg>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseBdpa" class="collapse" aria-labelledby="headingBdpa" data-parent="#accordionBdpa">
                        <div class="card-body">
                            <div class="accordion" id="accordionEstadoAsegurado">
                                @if ($this->estado_aseguradoAsuss == [])
                                    <b>NO EXISTEN REGISTROS EN LA BASE DE DATOS DE LA POBLACION ASEGURADA</b>
                                @else
                                    @foreach ($this->estado_aseguradoAsuss as $index => $estadoAsegurado)
                                        <div class="card">
                                            <div class="card-header p-0" id="heading{{ $index }}"
                                                style="background-color:#f2f4f4;">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-outline btn-block text-left" type="button"
                                                    style="color:black" data-toggle="collapse"
                                                        data-target="#collapse{{ $index }}" aria-expanded="true"
                                                        aria-controls="collapse{{ $index }}">
                                                        {{ $estadoAsegurado['ente_gestor'] }}
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor"
                                                            class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                                        </svg>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapse{{ $index }}"
                                                class="collapse @if ($index == 0)  @endif"
                                                aria-labelledby="heading{{ $index }}"
                                                data-parent="#accordionEstadoAsegurado">
                                                <div class="card-body p-2 ">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>ENTE GESTOR:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoAsegurado['ente_gestor'] }}</p>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>TIPO:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoAsegurado['categoria'] }}</p>

                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>FECHA DE AFILIACION:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoAsegurado['estado']['fecha_afiliacion'] }}
                                                            </p>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>NOMBRE ENTIDAD:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoAsegurado['nombre_entidad'] }}</p>

                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>ESTADO:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoAsegurado['estado']['descripcion'] }}</p>

                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fin del acordeon -->

            <!-- Inicio del acordeon OVT -->

            <div class="accordion" id="accordionOvt">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); ">
                    <div class="card-header p-0" id="headingBdpa" style="background:#cd6155;">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center" type="button" data-toggle="collapse"
                                data-target="#collapseOvt" aria-expanded="false" aria-controls="collapseOvt"
                                style="color:white">
                                OFICINA VIRTUAL DE TRÁMITES (OVT)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                </svg>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOvt" class="collapse" aria-labelledby="headingOvt" data-parent="#accordionOvt">
                        <div class="card-body">
                            <div class="accordion" id="accordionFuentePago">
                                @if ($this->estado_fuente_pagoAsuss == [])
                                    <b>NO EXISTEN REGISTROS EN LA OFICINA VIRTUAL DE TRÁMITES</b>
                                @else
                                    @foreach ($this->estado_fuente_pagoAsuss as $index => $estadoFuentePago)
                                        <div class="card">
                                            <div class="card-header p-0" id="headingFuentePago{{ $index }}"
                                                style="background-color:#f2f4f4;">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed d-flex justify-content-between align-items-center"
                                                    style="color:black"   type="button" data-toggle="collapse" 
                                                        data-target="#collapseFuentePago{{ $index }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapseFuentePago{{ $index }}">
                                                        {{ $estadoFuentePago['razon_social'] }}
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                            height="20" fill="currentColor"
                                                            class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                                        </svg>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseFuentePago{{ $index }}" class="collapse"
                                                aria-labelledby="headingFuentePago{{ $index }}"
                                                data-parent="#accordionFuentePago">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>RAZON SOCIAL:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoFuentePago['razon_social'] }}</p>

                                                        </div>
                                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>NIT:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoFuentePago['nit'] }}</p>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>ENTE GESTOR:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoFuentePago['caja'] }}</p>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>GESTIÓN:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoFuentePago['gestion'] }}</p>
                                                        </div>
                                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                            <strong>TIPO:</strong>
                                                            <p class="p-1"
                                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                                {{ $estadoFuentePago['tipo'] }}</p>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Fin del Acordeon OVT-->


        </div>

    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalValidacionAsuss', event => {

        $('#verModalValidacionAsuss').modal('show');
    });
</script>
