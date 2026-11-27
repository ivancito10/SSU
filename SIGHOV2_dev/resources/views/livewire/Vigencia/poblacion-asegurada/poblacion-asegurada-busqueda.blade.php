<div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class=" card-header cabecera">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
                <b>POBLACIÓN ASEGURADA BÚSQUEDA </b>
            </div>
            <div style="height: 0.5rem;"></div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">

            @if ($busquedaExistosa)
                <div class="row d-flex justify-content-end">

                    {{-- Envolvemos los botones en un div para que Flexbox los trate como un solo grupo --}}
                    <div class="d-flex align-items-center">

                        @if ($mostrarImprimir)
                            <x-agregar-button wire:click="verReportes"> <b>IMPRIMIR</b></x-agregar-button>



                            @can('imprimir.historia_clinica')
                                <button class="btn btn-warning mx-2" wire:click="imprimir_tapa_hc">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                        <path
                                            d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                                        <path
                                            d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                                    </svg> <b>IMPRIMIR TAPA HISTORIA CLÍNICA</b>
                                </button>
                            @endcan
                        @endif
                    </div>

                </div>
            @endif

        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <p style="color:#2471a3">

                            <em class="ml-2 h6">
                                <b>Para realizar la búsqueda ingrese alguno de los siguientes datos: </b>
                            </em>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1 col-md-2 col-sm-2 col-2">
                        <br>
                        <button class="btn btn-primary w-100" type="submit  " wire:click="buscarAsegurado">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-5 col-5">
                        <strong style="color:#2471a3">CARNET:</strong>
                        <input class="form-control input-comun" type="search" id="carnet" style="margin-left: 5px;"
                            placeholder="Ingrese el C.I." wire:model="carnetBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-5 col-4">
                        <strong style="color:#2471a3">COMPL:</strong>
                        <input class="form-control input-comun" type="search" id="complemento"
                            style="   margin-left: 5px;" placeholder=" Ingrese complemento"
                            wire:model="complementoBusqueda" oninput="this.value = this.value.toUpperCase();">
                    </div>

                    <div class="col-lg-5 col-md-6 col-sm-6 col-4">
                        <strong style="color:#2471a3">MATRÍCULA:</strong>
                        <input class="form-control input-comun" type="search" id="matricula"
                            style="  margin-left: 5px;" placeholder=" Ingrese la matricula"
                            wire:model="matriculaBusqueda" oninput="this.value = this.value.toUpperCase();">
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-8">
                        <strong style="color:#2471a3">NOMBRES:</strong>
                        <input class="form-control input-comun" type="search" id="nombresBusqueda"
                            placeholder=" Ingrese los nombres" wire:model="nombresBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <strong style="color:#2471a3">PRIMER APELLIDO:</strong>
                        <input class="form-control input-comun" type="search" id="pApellidoBusqueda"
                            placeholder=" Ingrese el primer apellido" wire:model="pApellidoBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <strong style="color:#2471a3">SEGUNDO APELLIDO:</strong>
                        <input class="form-control input-comun" type="search" id="sApellidoBusqueda"
                            placeholder=" Ingrese el segundo apellido" wire:model="sApellidoBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>


    @if ($busquedaExistosa)

        <!-- DATOS PERSONALES CARD -->
        <fieldset class="field_estilos">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    <path fill-rule="evenodd"
                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                </svg>
                <strong>DATOS PERSONALES</strong>

            </legend>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-4">
                    <p
                        style="background-color:{{ $color_asegurado }} ;text-align:center;color:{{ $color_texto }} ; border-radius:6px">
                        <b> {{ $poblacion_actual }} </b>
                    </p>
                    <div class="row d-flex flex-column align-items-center">

                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail"
                            style="width:200px; height:200px; object-fit:cover; object-position: top;"
                            alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            @if ($estado_vigencia == 'VIGENTE')
                                <span class="h3" style="color: green;"><b>VIGENTE</b></span>
                            @elseif($estado_vigencia == 'CESANTE')
                                <span class="h3 parpadeo" style="color: #ecb41d;"><b>CESANTE</b></span>
                            @elseif($estado_vigencia == 'NO VIGENTE')
                                <span class="h3 parpadeo" style="color: red;"><b>NO
                                        VIGENTE</b></span>
                            @endif
                        </div>
                        <div class="align-items-center mr-4 ml-4">
                            @if ($poblacion_actual == 'ESTUDIANTE')
                                <p style="font-size: small; font-weight: 800;color:#00b9ef">
                                    {{ $mensaje_estado_estudiante }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12  text-center">
                            <span class="h5 font-weight-bold">{{ $edad }}
                                años</span>
                        </div>
                        @if ($estado_vigencia == 'NO VIGENTE')
                            @if ($tieneBajaInstitucional === true)
                                <div class="col-lg-12 col-md-12 col-sm-12  text-center">
                                    <span class="h5 " style="color: red;"><b>CON BAJA INSTITUCIONAL</b></span>
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            <strong style="font-size:90%; color:#1A5276;"> MATRÍCULA:</strong>
                            <span class=" font-weight-bold"
                                style="color: #212121; font-size:25px; ">{{ $matricula_seguro }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12  ">
                            <strong style="font-size:90%; color:#1A5276;">TIPO ASEGURADO:</strong>
                            <span class=" font-weight-bold">{{ $poblacion_actual }} </span>
                        </div>
                    </div>

                    @if ($tipo_asegurado == 'TITULAR')
                        @if ($es_institucional == true)
                            @if ($tipo_rentista != null)
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12  ">
                                        <strong style="font-size:90%; color:#1A5276;">TIPO RENTISTA:</strong>
                                        <span class=" font-weight-bold"> {{ $tipo_rentista }} </span>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <strong style="color:#1A5276;">INSTITUCIÓN:</strong>
                                    @foreach ($institucionesTitular as $index => $institucionTitular)
                                        @if ($institucionTitular['estado'] === true)
                                            <span class="font-weight-bold h7">
                                                {{ $institucionTitular['nombre'] }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12  ">
                                    <strong style="font-size:90%; color:#1A5276;">TIPO:</strong>
                                    <span class=" font-weight-bold"> VOLUNTARIO </span>
                                </div>


                            </div>
                        @endif
                    @endif
                    @if ($tipo_asegurado == 'PREAFILIADO TITULAR')

                        @if ($institucion_preafiliado === 'APORTANTE VOLUNTARIO')
                            <div class="col-lg-12 col-md-12 col-sm-12 ">
                                <span style="color:#1A5276;"><b>TIPO:</b></span> <span class=" font-weight-bold h7">
                                    {{ $institucion_preafiliado }} </span>
                            </div>
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 ">
                                <strong> <span style="color:#1A5276;"> INSTITUCIÓN:</span> <span
                                        class=" font-weight-bold h7">
                                        {{ $institucion_preafiliado }}
                                    </span></strong>
                            </div>
                        @endif
                    @endif


                    <hr class="p-0 m-0">
                    <br>

                    <div class="row">
                        @if ($buscador_poblacion == 'TITULAR' || $buscador_poblacion == 'ESTUDIANTE' || $buscador_poblacion == 'BENEFICIARIOS')
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <strong style="font-size:90%; color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="18" height="18" fill="currentColor"
                                        class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                    </svg> FECHA INGRESO A SSU:</strong>
                                <p class="p-1"
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;  ">
                                    @if ($fecha_ingreso != null)
                                        {{ \Carbon\Carbon::parse($fecha_ingreso)->format('d/m/Y') }}
                                    @else
                                        SIN DATO
                                    @endif
                                </p>
                            </div>
                        @endif
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <strong style="font-size:90%; color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                    width="18" height="18" fill="currentColor"
                                    class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0" />
                                </svg> FECHA AFILIACIÓN:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;  ">
                                @if ($fecha_afiliacion != null)
                                    {{ \Carbon\Carbon::parse($fecha_afiliacion)->format('d/m/Y') }}
                                @else
                                    SIN DATO
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <p style="margin:0;">
                                @if (
                                    $poblacion_actual == 'TITULAR DEL INTERIOR' ||
                                        $poblacion_actual == 'BENEFICIARIO DEL INTERIOR' ||
                                        $poblacion_actual == 'TITULAR CONVENIO')
                                    <span style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor"
                                            class="bi bi-check2-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                            <path
                                                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                        </svg><b> ESTADO CONVENIO: </b></span>
                                @else
                                    <span style="color:#1A5276;"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="16" height="16" fill="currentColor"
                                            class="bi bi-check2-circle" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                            <path
                                                d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                        </svg><b> ESTADO CARNET: </b></span>
                                @endif
                                @if ($estado_carnet == 'CADUCO')
                                    <span style="color: #C0392B;"><b>CADUCO</b> </span>
                                @elseif($estado_carnet == 'VIGENTE')
                                    <span style="color:#196F3D;"><b>VIGENTE </b> </span>
                                @else
                                    <span style="color: #283747;"><b> {{ $estado_carnet }}</b> </span>
                                @endif
                            </p>

                        </div>

                    </div>
                    <div class="row g-0">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">

                            @if (
                                $tipo_asegurado == 'TITULAR DEL INTERIOR' ||
                                    $tipo_asegurado == 'BENEFICIARIO DEL INTERIOR' ||
                                    $tipo_asegurado == 'TITULAR CONVENIO')
                                <strong style="font-size:90%; color:#1A5276;"> FECHA INICIO CONVENIO:</strong>
                            @else
                                <strong style="font-size:90%; color:#1A5276;"> FECHA EMISIÓN DE CARNET:</strong>
                            @endif

                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; margin:0;">
                                {{ $fecha_emision }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            @if (
                                $tipo_asegurado == 'TITULAR DEL INTERIOR' ||
                                    $tipo_asegurado == 'BENEFICIARIO DEL INTERIOR' ||
                                    $tipo_asegurado == 'TITULAR CONVENIO')
                                <strong style="font-size:90%; color:#1A5276;"> FECHA FIN CONVENIO:</strong>
                            @else
                                <strong style="font-size:90%; color:#1A5276;"> FECHA VENCIMIENTO DE
                                    CARNET:</strong>
                            @endif
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                {{ $fecha_vencimiento }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" fill="#1A5276" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg> NOMBRE COMPLETO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                {{ $nombreCompleto }}</p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" fill="#1A5276" class="bi bi-person-video" viewBox="0 0 16 16">
                                    <path d="M8 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                    <path
                                        d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm10.798 11c-.453-1.27-1.76-3-4.798-3-3.037 0-4.345 1.73-4.798 3H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1z" />
                                </svg> C.I.:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                {{ $ci }}</p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6   ">
                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" fill="#1A5276" class="bi bi-gender-ambiguous"
                                    viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z" />
                                </svg> SEXO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                                {{ $sexo }}</p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <strong style="color:#1A5276;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#1A5276"
                                    class="bi bi-cake2" viewBox="0 0 16 16">
                                    <path
                                        d="m3.494.013-.595.79A.747.747 0 0 0 3 1.814v2.683q-.224.051-.432.107c-.702.187-1.305.418-1.745.696C.408 5.56 0 5.954 0 6.5v7c0 .546.408.94.823 1.201.44.278 1.043.51 1.745.696C3.978 15.773 5.898 16 8 16s4.022-.227 5.432-.603c.701-.187 1.305-.418 1.745-.696.415-.261.823-.655.823-1.201v-7c0-.546-.408-.94-.823-1.201-.44-.278-1.043-.51-1.745-.696A12 12 0 0 0 13 4.496v-2.69a.747.747 0 0 0 .092-1.004l-.598-.79-.595.792A.747.747 0 0 0 12 1.813V4.3a22 22 0 0 0-2-.23V1.806a.747.747 0 0 0 .092-1.004l-.598-.79-.595.792A.747.747 0 0 0 9 1.813v2.204a29 29 0 0 0-2 0V1.806A.747.747 0 0 0 7.092.802l-.598-.79-.595.792A.747.747 0 0 0 6 1.813V4.07c-.71.05-1.383.129-2 .23V1.806A.747.747 0 0 0 4.092.802zm-.668 5.556L3 5.524v.967q.468.111 1 .201V5.315a21 21 0 0 1 2-.242v1.855q.488.036 1 .054V5.018a28 28 0 0 1 2 0v1.964q.512-.018 1-.054V5.073c.72.054 1.393.137 2 .242v1.377q.532-.09 1-.201v-.967l.175.045c.655.175 1.15.374 1.469.575.344.217.356.35.356.356s-.012.139-.356.356c-.319.2-.814.4-1.47.575C11.87 7.78 10.041 8 8 8c-2.04 0-3.87-.221-5.174-.569-.656-.175-1.151-.374-1.47-.575C1.012 6.639 1 6.506 1 6.5s.012-.139.356-.356c.319-.2.814-.4 1.47-.575M15 7.806v1.027l-.68.907a.94.94 0 0 1-1.17.276 1.94 1.94 0 0 0-2.236.363l-.348.348a1 1 0 0 1-1.307.092l-.06-.044a2 2 0 0 0-2.399 0l-.06.044a1 1 0 0 1-1.306-.092l-.35-.35a1.935 1.935 0 0 0-2.233-.362.935.935 0 0 1-1.168-.277L1 8.82V7.806c.42.232.956.428 1.568.591C3.978 8.773 5.898 9 8 9s4.022-.227 5.432-.603c.612-.163 1.149-.36 1.568-.591m0 2.679V13.5c0 .006-.012.139-.356.355-.319.202-.814.401-1.47.576C11.87 14.78 10.041 15 8 15c-2.04 0-3.87-.221-5.174-.569-.656-.175-1.151-.374-1.47-.575-.344-.217-.356-.35-.356-.356v-3.02a1.935 1.935 0 0 0 2.298.43.935.935 0 0 1 1.08.175l.348.349a2 2 0 0 0 2.615.185l.059-.044a1 1 0 0 1 1.2 0l.06.044a2 2 0 0 0 2.613-.185l.348-.348a.94.94 0 0 1 1.082-.175c.781.39 1.718.208 2.297-.426" />
                                </svg> FECHA DE NACIMIENTO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                                {{ $fecha_nacimiento }}</p>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6   ">
                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-droplet-fill"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M8 16a6 6 0 0 0 6-6c0-1.655-1.122-2.904-2.432-4.362C10.254 4.176 8.75 2.503 8 0c0 0-6 5.686-6 10a6 6 0 0 0 6 6M6.646 4.646l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448c.82-1.641 1.717-2.753 2.093-3.13" />
                                </svg> GRUPO SANGUINEO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                                {{ $grupo_sanguineo }}</p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-6 col-6   ">
                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="19"
                                    height="19" fill="currentColor" class="bi bi-heart-pulse"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053.918 3.995.78 5.323 1.508 7H.43c-2.128-5.697 4.165-8.83 7.394-5.857q.09.083.176.171a3 3 0 0 1 .176-.17c3.23-2.974 9.522.159 7.394 5.856h-1.078c.728-1.677.59-3.005.108-3.947C13.486.878 10.4.28 8.717 2.01zM2.212 10h1.315C4.593 11.183 6.05 12.458 8 13.795c1.949-1.337 3.407-2.612 4.473-3.795h1.315c-1.265 1.566-3.14 3.25-5.788 5-2.648-1.75-4.523-3.434-5.788-5" />
                                    <path
                                        d="M10.464 3.314a.5.5 0 0 0-.945.049L7.921 8.956 6.464 5.314a.5.5 0 0 0-.88-.091L3.732 8H.5a.5.5 0 0 0 0 1H4a.5.5 0 0 0 .416-.223l1.473-2.209 1.647 4.118a.5.5 0 0 0 .945-.049l1.598-5.593 1.457 3.642A.5.5 0 0 0 12 9h3.5a.5.5 0 0 0 0-1h-3.162z" />
                                </svg> ALERGIAS:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                                {{ $alergias }}</p>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12  ">
                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" fill="#1A5276" class="bi bi-exclamation-triangle-fill"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                </svg> OBSERVACIÓN DE AFILIACIONES:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                                {{ strip_tags($observaciones) }} </p>
                        </div>

                    </div>
                </div>
                @if ($poblacion_actual == 'ESTUDIANTE')
                    <div class="col-lg-6 col-md-6 col-sm-6 ">
                        <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="bi bi-backpack2-fill" viewBox="0 0 16 16">
                                <path d="M5 13h6v-3h-1v.5a.5.5 0 0 1-1 0V10H5z" />
                                <path
                                    d="M6 2v.341C3.67 3.165 2 5.388 2 8v1.191l-1.17.585A1.5 1.5 0 0 0 0 11.118V13.5A1.5 1.5 0 0 0 1.5 15h1c.456.607 1.182 1 2 1h7c.818 0 1.544-.393 2-1h1a1.5 1.5 0 0 0 1.5-1.5v-2.382a1.5 1.5 0 0 0-.83-1.342L14 9.191V8a6 6 0 0 0-4-5.659V2a2 2 0 1 0-4 0m2-1a1 1 0 0 1 1 1v.083a6 6 0 0 0-2 0V2a1 1 0 0 1 1-1m0 3a4 4 0 0 1 3.96 3.43.5.5 0 1 1-.99.14 3 3 0 0 0-5.94 0 .5.5 0 1 1-.99-.14A4 4 0 0 1 8 4M4.5 9h7a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-4a.5.5 0 0 1 .5-.5" />
                            </svg>CARRERA:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $carrera }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 ">
                        <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="bi bi-building-fill" viewBox="0 0 16 16">
                                <path
                                    d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                            </svg>FACULTAD:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $facultad }}</p>
                    </div>
                @endif
            </div>
        </fieldset>

        <!-- DATOS REFERENCIALES CARD -->

        @if ($tieneDatosRerenciales == true)
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <strong>DATOS REFERENCIALES</strong>

                </legend>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12   ">
                        <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" fill="#1A5276" class="bi bi-house-check-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
                                <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293z" />
                                <path
                                    d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.707l.547.547 1.17-1.951a.5.5 0 1 1 .858.514" />
                            </svg> DIRECCIÓN:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; margin:0;">
                            {{ $direccion }}</p>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4  ">
                        <strong style="color:#1A5276;"><svg xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" fill="#1A5276" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                            </svg> ZONA: </strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; margin:0;">
                            {{ $zona }}</p>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4  ">
                        <strong style="color:#1A5276;"><svg xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" fill="#1A5276" class="bi bi-highlighter" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M11.096.644a2 2 0 0 1 2.791.036l1.433 1.433a2 2 0 0 1 .035 2.791l-.413.435-8.07 8.995a.5.5 0 0 1-.372.166h-3a.5.5 0 0 1-.234-.058l-.412.412A.5.5 0 0 1 2.5 15h-2a.5.5 0 0 1-.354-.854l1.412-1.412A.5.5 0 0 1 1.5 12.5v-3a.5.5 0 0 1 .166-.372l8.995-8.07zm-.115 1.47L2.727 9.52l3.753 3.753 7.406-8.254zm3.585 2.17.064-.068a1 1 0 0 0-.017-1.396L13.18 1.387a1 1 0 0 0-1.396-.018l-.068.065zM5.293 13.5 2.5 10.707v1.586L3.707 13.5z" />
                            </svg> NRO VIVIENDA:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; margin:0;">
                            {{ $nro_vivienda }}
                        </p>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-4  ">
                        <strong style="color:#1A5276;"><svg xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" fill="#1A5276" class="bi bi-telephone-forward-fill"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877zm10.761.135a.5.5 0 0 1 .708 0l2.5 2.5a.5.5 0 0 1 0 .708l-2.5 2.5a.5.5 0 0 1-.708-.708L14.293 4H9.5a.5.5 0 0 1 0-1h4.793l-1.647-1.646a.5.5 0 0 1 0-.708" />
                            </svg> TELÉFONO: </strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px; margin:0;">
                            {{ $telefono }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12 ">
                        <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" fill="#1A5276" class="bi bi-person-circle" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                            </svg> NOMBRE DE REFERENCIA:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                            {{ $nombre_referencia }}</p>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12">
                        <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" fill="#1A5276" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                            </svg> TELÉFONO DE REFERENCIA: </strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                            {{ $telefono_referencia }}</p>
                    </div>

                </div>


            </fieldset>
        @endif


        <!-- DATOS INSTITUCIONALES CARD -->

        @if ($poblacion_actual == 'TITULAR')
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-building-fill" viewBox="0 0 16 16">
                        <path
                            d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                    </svg>
                    <strong>DATOS INSTITUCIONALES</strong>

                </legend>
                <!-- DATOS EXTRAS DEL TITULAR  -->
                <div class="row">
                    @if ($poblacion_actual == 'TITULAR')
                        @if ($es_institucional === true)
                            @foreach ($institucionesTitular as $index => $institucionTitular)
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 50%;">
                                            @if ($loop->first)
                                                <strong style="color:#1A5276;"> INSTITUCIÓN PRINCIPAL:</strong>
                                            @else
                                                <strong style="color:#1A5276;"> INSTITUCIÓN
                                                    {{ $loop->iteration }}</strong>
                                            @endif
                                            <p class=" fw-bold text-dark mb-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-dot" viewBox="0 0 16 16">
                                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                </svg> {{ $institucionTitular['nombre'] }}
                                            </p>
                                        </td>
                                        <td style="width: 25%;">
                                            @if ($institucionTitular['titularidad'] != '')
                                                <p class="mb-0">
                                                    <b class="text-secondary">Titularidad:</b>
                                                    <span class="text-dark">
                                                        {{ $institucionTitular['titularidad'] }}
                                                    </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td style="width: 25%;">
                                            @if ($institucionTitular['estado'] === true)
                                                <span class="badge bg-success px-3 py-2 mb-2">
                                                    <i class="bi bi-check-circle"></i> ACTIVO
                                                </span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2">
                                                    <i class="bi bi-x-circle"></i> INACTIVO
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @endforeach
                        @else
                            <div class="col-lg-12">
                                <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor"
                                        class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
                                    </svg> CONTRATO VOLUNTARIO:</strong>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 ">
                                <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor"
                                        class="bi bi-calendar2-event" viewBox="0 0 16 16">
                                        <path
                                            d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                                        <path
                                            d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z" />
                                    </svg> INICIO DE CONTRATO:</strong>
                                <p class=" p-1 p-comun-disabled">
                                    {{ $fecha_inicio_contrato ? \Carbon\Carbon::parse($fecha_inicio_contrato)->format('d/m/Y') : 'SIN DATO' }}
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 ">
                                <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor"
                                        class="bi bi-calendar-week" viewBox="0 0 16 16">
                                        <path
                                            d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                                    </svg> FIN DE CONTRATO:</strong>
                                <p class=" p-1 p-comun-disabled">
                                    {{ $fecha_fin_contrato ? \Carbon\Carbon::parse($fecha_fin_contrato)->format('d/m/Y') : 'SIN DATO' }}
                                </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 ">
                                <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor"
                                        class="bi bi-clock-history" viewBox="0 0 16 16">
                                        <path
                                            d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                                        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                        <path
                                            d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                    </svg> DIAS RESTANTES:</strong>
                                <p class=" p-1 p-comun-disabled">
                                    {{ $dias_vigencia_contrato ?: '0' }}</p>
                            </div>



                        @endif
                    @else
                    @endif
                </div>

            </fieldset>
        @endif

        <!-- DATOS DEL TITULAR  CARD -->

        @if ($poblacion_actual != 'PREAFILIADO BENEFICIARIO')
            @if (strpos($poblacion_actual, 'BENEFICIARIO') !== false)
                @if ($poblacion_actual != 'BENEFICIARIO DEL INTERIOR')
                    <fieldset class="field_estilos">
                        <legend>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
                                <path
                                    d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            </svg>
                            <strong>DATOS DEL TITULAR</strong>

                        </legend>
                        <!-- DATOS EXTRAS  DEL BENEFICIARIO  -->
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4">
                                <div class="row d-flex flex-column align-items-center">
                                    <img src="{{ !empty($foto_titular) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                        class="img-thumbnail"
                                        style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                        alt="FTP.{{ $foto }}">
                                </div>

                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 ">
                                        <strong style="font-size:90%; color:#1A5276;"> MATRÍCULA:</strong>
                                        <span class=" font-weight-bold"
                                            style="color: #212121; font-size:25px; ">{{ $matriculaTitular }}
                                        </span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 ">
                                        @if ($vigencia_titular == 'VIGENTE')
                                            <span class="h3" style="color: green;"><b>VIGENTE</b></span>
                                        @elseif($vigencia_titular == 'CESANTE')
                                            <span class="h3 parpadeo" style="color: #ecb41d;"><b>CESANTE</b></span>
                                        @elseif($vigencia_titular == 'NO VIGENTE')
                                            <span class="h3 parpadeo" style="color: red;"><b>NO
                                                    VIGENTE</b></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                                        <strong style="color:#1A5276;"> NOMBRE COMPLETO:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $nombreTitular }}</p>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12   ">

                                        <strong style="color:#1A5276;"> CARNET:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $ciTitular }}</p>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12  ">
                                        <strong style="color:#1A5276;"> TELÉFONO:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $telefonoTitular }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    @if ($es_institucional === true)
                                        @foreach ($institucionesTitular as $index => $institucionTitular)
                                            <table style="width: 100%;">
                                                <tr>
                                                    <td style="width: 50%;">
                                                        @if ($loop->first)
                                                            <strong style="color:#1A5276;"> INSTITUCIÓN
                                                                PRINCIPAL:</strong>
                                                        @else
                                                            <strong style="color:#1A5276;"> INSTITUCIÓN
                                                                {{ $loop->iteration }}</strong>
                                                        @endif
                                                        <p class=" fw-bold text-dark mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-dot"
                                                                viewBox="0 0 16 16">
                                                                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                            </svg> {{ $institucionTitular['nombre'] }}
                                                        </p>
                                                    </td>
                                                    <td style="width: 35%;">
                                                        @if ($institucionTitular['titularidad'] != '')
                                                            <p class="mb-0">
                                                                <b class="text-secondary">Titularidad:</b>
                                                                <span class="text-dark">
                                                                    {{ $institucionTitular['titularidad'] }}
                                                                </span>
                                                            </p>
                                                        @endif
                                                    </td>
                                                    <td style="width: 15%;">
                                                        @if ($institucionTitular['estado'] == true)
                                                            <span class="badge bg-success px-3 py-2 mb-2">
                                                                <i class="bi bi-check-circle"></i> ACTIVO
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger px-3 py-2">
                                                                <i class="bi bi-x-circle"></i> INACTIVO
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        @endforeach
                                    @else
                                        <div class="col-lg-12">
                                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16" fill="currentColor"
                                                    class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z" />
                                                </svg> CONTRATO VOLUNTARIO:</strong>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 ">
                                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16" fill="currentColor"
                                                    class="bi bi-calendar2-event" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 7.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                                    <path
                                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                                                    <path
                                                        d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5z" />
                                                </svg> INICIO DE CONTRATO:</strong>
                                            <p class=" p-1 p-comun-disabled">
                                                {{ $fecha_inicio_contrato ? \Carbon\Carbon::parse($fecha_inicio_contrato)->format('d/m/Y') : 'SIN DATO' }}
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 ">
                                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16" fill="currentColor"
                                                    class="bi bi-calendar-week" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                                                    <path
                                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                                                </svg> FIN DE CONTRATO:</strong>
                                            <p class=" p-1 p-comun-disabled">
                                                {{ $fecha_fin_contrato ? \Carbon\Carbon::parse($fecha_fin_contrato)->format('d/m/Y') : 'SIN DATO' }}
                                            </p>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 ">
                                            <strong style="color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                                    width="16" height="16" fill="currentColor"
                                                    class="bi bi-clock-history" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                                                    <path
                                                        d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                    <path
                                                        d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                </svg> DIAS RESTANTES:</strong>
                                            <p class=" p-1 p-comun-disabled">
                                                {{ $dias_vigencia_contrato ?: '0' }}</p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </fieldset>
                @endif
            @endif
        @endif

        <!-- DATOS DEL TITULAR PREAFIL  CARD -->

        @if ($poblacion_actual == 'NO ASEGURADO')
            @if (strpos($datos['tipo_asegurado'], 'BENEFICIARIO') !== false)
                @if ($datos['tipo_asegurado'] != 'BENEFICIARIO DEL INTERIOR' && $datos['tipo_asegurado'] != 'BENEFICIARIO CONVIVIENTE')
                    <fieldset class="field_estilos">
                        <legend>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
                                <path
                                    d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            </svg>
                            <strong>DATOS DEL TITULAR</strong>

                        </legend>
                        <!-- DATOS EXTRAS  DEL BENEFICIARIO  -->

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-4">
                                <div class="row d-flex flex-column align-items-center">
                                    <img src="{{ !empty($foto_titular) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                        class="img-thumbnail"
                                        style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                        alt="FTP.{{ $foto }}">
                                </div>

                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-8">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7 col-sm-7 ">
                                        <strong style="font-size:90%; color:#1A5276;"> MATRÍCULA:</strong>
                                        <span class=" font-weight-bold"
                                            style="color: #212121; font-size:25px; ">{{ $matriculaTitular }}
                                        </span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 ">
                                        @if ($vigencia_titular == 'VIGENTE')
                                            <span class="h3" style="color: green;"><b>VIGENTE</b></span>
                                        @elseif($vigencia_titular == 'CESANTE')
                                            <span class="h3 parpadeo" style="color: #ecb41d;"><b>CESANTE</b></span>
                                        @elseif($vigencia_titular == 'NO VIGENTE')
                                            <span class="h3 parpadeo" style="color: red;"><b>NO
                                                    VIGENTE</b></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 ">
                                        <strong style="color:#1A5276;"> INSTITUCIÓN:</strong>
                                        <span class=" font-weight-bold h5"> {{ $institucionTitular }} </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                                        <strong style="color:#1A5276;"> NOMBRE COMPLETO:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $nombreTitular }}</p>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12   ">

                                        <strong style="color:#1A5276;"> CARNET:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $ciTitular }}</p>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12  ">
                                        <strong style="color:#1A5276;"> TELÉFONO:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{ $telefonoTitular }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
            @endif
        @endif


        <!-- PREAFILIACIÓN DATOS DEL TITULAR CARD -->
        @if ($poblacion_actual == 'PREAFILIADO BENEFICIARIO')
            @if ($matriculaDependiente != null)
                <fieldset class="field_estilos">
                    <legend>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-bounding-box" viewBox="0 0 16 16">
                            <path
                                d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        </svg>
                        <strong> DATOS TITULAR</strong>

                    </legend>
                    <!-- DATOS DE PREAFILIADO BENEFICIARIO -->
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <div class="row d-flex flex-column align-items-center">
                                <img src="{{ !empty($foto_titular) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail"
                                    style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                    alt="FTP.{{ $foto }}">
                            </div>

                        </div>
                        <div class="col-lg-9 col-md-9 col-sm-8">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 ">
                                    <strong style="font-size:90%; color:#1A5276;"> MATRÍCULA:</strong>
                                    <span class=" font-weight-bold"
                                        style="color: #212121; font-size:25px; ">{{ $matriculaDependiente }}
                                    </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 ">
                                    <div class="col-lg-12 col-md-12 col-sm-12  ">
                                        <strong style="color:#1A5276;"> INSTITUCIÓN:</strong>
                                    </div>
                                    @foreach ($institucionesTitular as $index => $institucionTitular)
                                        <div class="col-12 mb-3">
                                            <h6 class=" fw-bold text-dark mb-2">
                                                {{ $index + 1 }}. {{ $institucionTitular['nombre'] }}
                                            </h6>


                                            <hr>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 ">
                                    <strong style="color:#1A5276;"> NOMBRE COMPLETO:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $nombreDependiente }}</p>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-12   ">

                                    <strong style="color:#1A5276;"> CARNET:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{ $ciDependiente }}</p>
                                </div>
                            </div>

                        </div>
                    </div>




                </fieldset>
            @endif
        @endif



        <!-- CONVENIOS INSTERIOR Y EMERGENCIA  CARD -->
        @if ($poblacion_actual == 'TITULAR DEL INTERIOR' || $poblacion_actual == 'BENEFICIARIO DEL INTERIOR')
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-person-bounding-box" viewBox="0 0 16 16">
                        <path
                            d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                    </svg>
                    <strong>DATOS INSTITUCIONALES</strong>

                </legend>
                <!-- DATOS DE   TITULAR INTERIOR Y BENEFICIARIO INTERIOR -->

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12   ">
                        <strong style="font-size:90% ;color:#1A5276;"> <svg xmlns="http://www.w3.org/2000/svg"
                                width="16" height="16" fill="currentColor" class="bi bi-building-fill"
                                viewBox="0 0 16 16">
                                <path
                                    d="M3 0a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h3v-3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V16h3a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm1 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                            </svg> SEGURO DE ORIGEN:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $institucion }} </p>
                    </div>
                </div>
                @if ($limitaciones)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12  ">
                            <strong style="font-size:90%; color:#1A5276;">LIMITACIONES:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $limitaciones }} </p>
                        </div>
                    </div>
                @endif


            </fieldset>
        @endif

        <!-- HISTORIAL CONVENIO INTERIOR CARD -->
        @if (!empty($datosInterior))
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-journal" viewBox="0 0 16 16">
                        <path
                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                        <path
                            d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                    </svg>
                    <strong> HISTORIAL - CONVENIO INTERIOR </strong>

                </legend>
                <div style="overflow-y: auto; max-height: 600px; border: 1px solid #ddd;">
                    <table class="table table-bordered table-hover" style="width: 100%; border-collapse: collapse;">
                        <thead
                            style="background-color: #2471a3; color: white; text-align: center; position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <td scope="col">COD. CITE</td>
                                <td scope="col">FECHA INICIO</td>
                                <td scope="col">FECHA FIN</td>
                                <td scope="col">DEPT. SEGURO</td>
                                <td scope="col">MEDICAMENTOS LINAME</td>
                                <td scope="col">MEDICAMENTOS EXTRALINAME</td>
                                <td scope="col">OBSERVACIONES</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->datosInterior as $index => $dato)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td style="text-align: left;"> {{ $dato['cod_cite'] }} </td>
                                    <td style="text-align: left;">
                                        {{ \Carbon\Carbon::parse($dato['fecha_inicio'])->format('d/m/Y') }} </td>
                                    <td style="text-align: left;">
                                        {{ \Carbon\Carbon::parse($dato['fecha_fin'])->format('d/m/Y') }} </td>

                                    <td style="text-align: left;"> {{ $dato['departamento_seguro'] }} </td>
                                    <td style="text-align: center;">
                                        @if ($dato['medicamentos_liname'] == true)
                                            <span style="color: #1E8449; font-weight:bold;"> SI </span>
                                        @else
                                            <span style="color: #C0392B; font-weight:bold;"> NO </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($dato['medicamentos_extraliname'] == true)
                                            <span style="color: #1E8449; font-weight:bold;"> SI </span>
                                        @else
                                            <span style="color: #C0392B; font-weight:bold;"> NO </span>
                                        @endif
                                    </td>
                                    <td>{{ strip_tags($dato['observaciones']) }}</td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </fieldset>
        @endif

        <!-- HISTORIAL CONVENIO EMERGENCIA CARD -->
        @if (!empty($datosEmergencia))
            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-journal" viewBox="0 0 16 16">
                        <path
                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                        <path
                            d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                    </svg>
                    <strong> HISTORIAL - CONVENIO EMERGENCIA </strong>

                </legend>
                <div style="overflow-y: auto; max-height: 600px; border: 1px solid #ddd;">
                    <table class="table table-bordered table-hover" style="width: 100%; border-collapse: collapse;">
                        <thead
                            style="background-color: #2471a3; color: white; text-align: center; position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <td scope="col">GARANTE O TITULAR RESPONSABLE</td>
                                <td scope="col">CARNET</td>
                                <td scope="col">MATRÍCULA</td>
                                <td scope="col">FECHA INICIO</td>
                                <td scope="col">FECHA FIN</td>
                                <td scope="col">LIMITACIONES</td>
                                <td scope="col">MEDICAMENTOS LINAME</td>
                                <td scope="col">MEDICAMENTOS EXTRALINAME</td>
                                <td scope="col">OBSERVACIONES</td>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->datosEmergencia as $index => $dato)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td style="text-align: left;"> {{ $dato['nombreAutoriza'] }} </td>
                                    <td style="text-align: left;"> {{ $dato['ciAutoriza'] }} </td>
                                    <td style="text-align: left;"> {{ $dato['matriculaAutoriza'] }} </td>
                                    <td style="text-align: left;">
                                        {{ \Carbon\Carbon::parse($dato['fecha_inicio'])->format('d/m/Y') }} </td>
                                    <td style="text-align: left;">
                                        {{ \Carbon\Carbon::parse($dato['fecha_fin'])->format('d/m/Y') }} </td>
                                    <td style="text-align: left;"> {{ strip_tags($dato['limitaciones']) }}</td>
                                    <td style="text-align: center;">
                                        @if ($dato['medicamentos_liname'] == true)
                                            <span style="color: #1E8449; font-weight:bold;"> SI </span>
                                        @else
                                            <span style="color: #C0392B; font-weight:bold;"> NO </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($dato['medicamentos_extraliname'] == true)
                                            <span style="color: #1E8449; font-weight:bold;"> SI </span>
                                        @else
                                            <span style="color: #C0392B; font-weight:bold;"> NO </span>
                                        @endif
                                    </td>
                                    <td style="text-align: left;"> {{ strip_tags($dato['observaciones']) }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </fieldset>
        @endif

        <!-- HISTORIAL  BAJAS INSTITUCIONALES  CARD -->
        @if ($tieneBajaInstitucional != null)

            <fieldset class="field_estilos">
                <legend>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-journal" viewBox="0 0 16 16">
                        <path
                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                        <path
                            d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                    </svg>
                    <strong> HISTORIAL - BAJAS INSTITUCIONALES </strong>

                </legend>
                <div style="overflow-y: auto; max-height: 600px; border: 1px solid #ddd;">
                    <table class="table table-bordered table-hover" style="width: 100%; border-collapse: collapse;">
                        <thead
                            style="background-color: #2471a3; color: white; text-align: center; position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>#</th>
                                <td scope="col">NRO. DOCUMENTO</td>
                                <td scope="col">INSTITUCIÓN</td>
                                <td scope="col">FECHA BAJA INSTITUCIÓN</td>
                                <td scope="col">FECHA BAJA SSU</td>
                                <td scope="col">MOTIVO</td>
                                <td scope="col">DESCRIPCIÓN</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->datosBaja as $index => $dato)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td style="text-align: left;"> {{ $dato['nro_documento'] }} </td>
                                    <td style="text-align: left;"> {{ $dato['institucion_baja'] }} </td>
                                    <td style="text-align: left;">
                                        {{ \Carbon\Carbon::parse($dato['fecha_baja_inst'])->format('d/m/Y') }}</td>
                                    <td style="text-align: left;">
                                        {{ \Carbon\Carbon::parse($dato['fecha_baja_ssu'])->format('d/m/Y') }}
                                    <td style="text-align: left;"> {{ $dato['motivo_baja'] }} </td>
                                    <td style="text-align: left;"> {{ $dato['descripcion_motivo'] }} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </fieldset>
        @endif
    @else
        <div class="row">
            @if ($multiplesResultados)
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        <div class="card-header cabecera">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                            <b>RESULTADOS OBTENIDOS</b>
                        </div>
                        <div style="height: 0.5rem;"></div>
                        <div style="max-height: 400px; overflow-y: auto;">

                            <table class="table tabla-azul-oscuro">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th style="text-align: center;">CARNET</th>
                                        <th style="text-align: center;">MATRICULA</th>
                                        <th style="text-align: center;">NOMBRE COMPLETO</th>
                                        <th style="text-align: center;">TIPO DE ASEGURADO</th>
                                        <th style="text-align: center;">OPCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($existePersona as $persona)
                                        <tr>
                                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                                            <td>{{ $persona->ci }} @if ($persona->complemento)
                                                    - {{ $persona->complemento }}
                                                @endif
                                            </td>
                                            <td>{{ $persona->matricula_seguro }}</td>
                                            <td>
                                                @if ($persona->p_apellido == null)
                                                    {{ $persona->s_apellido . ' ' . $persona->nombres }}
                                                @elseif ($persona->s_apellido == null)
                                                    {{ $persona->p_apellido . ' ' . $persona->nombres }}
                                                @else
                                                    {{ $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres }}
                                                @endif
                                            </td>
                                            <td>{{ $persona->tipoAseguradoPersona->tipo_asegurado }}</td>
                                            <td style="text-align: center;">
                                                <button class="btn btn-success" type="submit"
                                                    wire:click="search_qr('{{ $persona->clave_unica }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-person-fill-check" viewBox="0 0 16 16">
                                                        <path
                                                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                        <path
                                                            d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif




    <script>
        document.addEventListener('noAfiliadoNoVigente', function() {
            Swal.fire({
                icon: "error",
                title: "No existen registros asociados",
                text: "El código escaneado no pertenece a un afiliado vigente",
            });
        });

        document.addEventListener('noEncontrado', function() {
            Swal.fire({
                title: "Asegurado no encontrado!",
                text: "No se encuentran los datos de la persona ingresada.",
                icon: "warning",
            });
        });

        document.addEventListener('noAseguradoRegular', function() {
            Swal.fire({
                title: "Los datos no corresponden a ningún asegurado",
                text: "Los datos no corresponden a ningún asegurado Titular, Beneficiario o Estudiante",
                icon: "warning"
            });
        });
    </script>
    <style>
        @keyframes parpadeo {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .parpadeo {
            animation: parpadeo 1s infinite;
        }

        .div-tabla-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }
    </style>

</div>
