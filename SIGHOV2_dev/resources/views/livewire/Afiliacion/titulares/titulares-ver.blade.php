<div>
    <div class="row ">
        <div class="col-lg-2 col-md-12 col-sm-12 col-12">
            <!-- Verificar esto !!! porque sm? -->
            <div class="card-comun p-2">
                <p style="background-color:{{ $color }} ;text-align:center;color:white; border-radius:6px">
                    <b>TITULAR</b>
                </p>
                @can('descargar.fotografia_titular')
                    <div class="col-lg-12 p-2">
                        <x-descargar-button title="DESCARGAR FOTOGRAFIA" wire:click="descargarFotoTitular" style="padding:4px">
                            DESCARGAR FOTO</x-descargar-button>
                    </div>
                @endcan
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" style="width:200px; height:200px; object-fit:cover; object-position: top;"
                        alt="FTP.{{ $foto }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        @if ($estado_vigencia == 'VIGENTE')
                            <span class="h3" style="color: green;"><b>VIGENTE</b></span>
                        @elseif($estado_vigencia == 'CESANTE')
                            <span class="h3 parpadeo" style="color: #ecb41d;"><b>CESANTE</b></span>
                        @elseif($estado_vigencia == 'NO VIGENTE')
                            <span class="h3 parpadeo" style="color: red;"><b>NO
                                    VIGENTE</b></span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <span class="h5 font-weight-bold">{{ $edad }}
                            años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }}
                        </span>
                        {{-- <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoTitular }}
                            </span> --}}
                    </div>
                </div>

                @if ($tipoAporte == 'INSTITUCIONAL')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <strong style="font-size:90%">TIPO :</strong>
                            <span class=" font-weight-bold">{{ $tipoAporte }}
                                {{ $permanente }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <span class=" font-weight-bold h5">{{ $nombreInsPatronal }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <strong style="font-size:90%">TIPO :</strong>
                            <span class=" font-weight-bold">{{ $tipoAporte }} </span>
                        </div>
                    </div>
                @endif
                <hr class="p-0 m-0">
                <div class="row">
                    <div class="col-lg-12 col-md-4 col-sm-6 col-6">
                        <strong style="font-size:90%">FECHA DE INGRESO A SSU:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $fecha_afiliacion_literal }}</p>
                    </div>
                    @if ($fecha_reafiliacion)
                        <div class="col-lg-12 col-md-4 col-sm-6 col-6">
                            <strong style="font-size:90%"> FECHA DE AFILIACIÓN: </strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $fecha_reafiliacion_literal }}</p>
                        </div>
                    @else
                    @endif

                    <div class="col-lg-12 col-md-4 col-sm-6 col-6">
                        <strong style="font-size:90%">FECHA VENCIMIENTO CARNET:</strong>
                        @if ($es_carnet_indefinido === true)
                            <p class="p-1 p-comun-disabled">
                                <b>INDEFINIDO </b>
                            </p>
                        @else
                            <p class="p-1 p-comun-disabled">
                                {{ $fechaVencimientoCarnet }} </p>
                        @endif

                    </div>

                    <div class="col-lg-12 col-md-4 col-sm-6 col-6">
                        <strong style="font-size:90%">FECHA ULTIMA MODIFICACIÓN:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $fecha_ultima_edicion }} </p>
                    </div>
                </div>
                <hr class="p-0 m-0">
                @if (!$tieneFileDigital)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 pb-2">
                        <span
                            style="display: inline-block;
                                      padding: 8px 12px;
                                      border: 1px solid #f5c26b;
                                      border-radius: 10px;
                                      background-color: #fff3cd;
                                      color: #ff7c11;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                <path
                                    d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                            </svg>
                            El asegurado no cuenta con un File Digital registrado.
                        </span>
                    </div>
                @else
                    @can('file_digital.titulares')
                        <div class="div-tabla-responsive">
                            <table>
                                <tr>
                                    <td><b>FILE DIGITAL:</b></td>
                                    <td> <x-descargar-button title="DESCARGAR FILE DIGITAL" onclick="abrirAlertaMotivo()"
                                            style="padding:4px"></x-descargar-button>
                                    </td>
                                    <td> <x-ver-pdf-button title="VER FILE DIGITAL" wire:click="abrirModalVerFileDigital"
                                            style="padding:4px"></x-ver-pdf-button></td>
                                </tr>
                            </table>
                        </div>
                    @endcan
                @endif

                @can('aportes.titulares')
                    <!-- Eliminando ifs para probar -->
                    <!--modal para VER APORTES voluntarios-->

                    @if ($tipo_titular == true)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <x-aportes-button title="APORTES" wire:click="verAportesIns({{ $idPersona }})"><b>
                                    VER APORTES </b></x-aportes-button>
                        </div>
                    @endif
                    @if ($tipo_titular == false)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <x-aportes-button wire:click="verAportesVol({{ $idPersona }})"> VER
                                APORTES</x-aportes-button>
                        </div>
                    @endif
                @endcan
            </div>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right pr-3">
                        <strong> REQUISITOS:</strong>
                        @if ($estado_requisitos)
                            <strong style=" color:green; font-size:20px">COMPLETO</strong>
                        @else
                            <strong class="parpadeo" style=" color:orange; font-size:20px"><b>PENDIENTE</b>
                            </strong>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10 col-md-12 col-sm-12 col-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $nombre }}</p>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 col-3">
                        <strong> C.I.:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $carnet }}</p>
                    </div>

                    <div class="col-lg-1 col-md-3 col-sm-3 col-2">
                        <strong>EXP.:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $dept_exp }}</p>
                    </div>
                    <div class="col-lg-3 col-md-5 col-sm-5 col-7">
                        <strong>
                            FECHA DE NACIMIENTO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $fecha_nacimiento_literal }}</p>

                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                        <strong>SEXO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $sexo }}</p>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-6">
                        <strong>TIPO DOC.:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $tipoDoc }}</p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                        <strong>
                            ESTADO CIVIL:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $estado_civil }}</p>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <strong>DEPARTAMENTO DE NAC.:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $dep_nacimiento }}</p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <strong>PROVINCIA DE NAC.:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $prov_nacimiento }}</p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                        <strong>MUNICIPIO DE NAC.:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $mun_nacimiento }}</p>
                    </div>

                </div>
                {{-- DATOS REFERENCIALES --}}
                <p style="background-color:#1a5276 ; color:white;text-align:center; border-radius:6px">
                    DATOS REFERENCIALES</p>
                <div class="row">
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <strong> NOMBRE DE REFERENCIA:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $nombre_referencia }}</p>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-7">
                        <strong> TELÉFONO DE
                            REFERENCIA: </strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px;">
                            {{ $telefono_referencia }}</p>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 col-5">
                        <strong> TIPO DE SANGRE:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $sangre }}</p>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                        <strong> TELEFONO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $telefono }}</p>
                    </div>
                    <div class="col-lg-5 col-md-8 col-sm-8 col-8">
                        <strong> CORREO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $correo }}</p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                        <strong> NRO VIVIENDA:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $nro_vivienda }}
                        </p>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-8 ">
                        <strong> DIRECCION:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $direccion }}</p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 ">
                        <strong> ZONA: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $zona }}</p>
                    </div>

                    <!-- VER UBICACION DE TITULAR -->
                    {{-- <div class="col-lg-2 col-md-12 col-sm-12 col-12">
                        <br>
                        <x-ver-dos-button wire:click="AbrirModalUbicacionTitular" style="padding:3; "> UBICACIÓN
                        </x-ver-dos-button>
                        <!--  PARA VER LA UBICACION  -->
                        @include('livewire.Afiliacion.titulares.ModalUbicacionTitular')
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <label for="observacion"> OBSERVACIONES:
                        </label>
                        <textarea class="form-control" id="observacion" disabled style="border-radius:10px" wire:model=observacion></textarea>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <label for="alergias"> ALERGIAS:
                        </label>
                        <textarea class="form-control" id="alergias" disabled style="border-radius:10px" wire:model=alergias></textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-6 ">
                        <strong> ARCHIVO GRUPO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $archivo_grupo }}</p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-6 ">
                        <strong> FECHA ARCHIVO: </strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $fecha_archivo }}</p>
                    </div>
                </div>
            </div>

        </div>
        <!-- DATOS INSTITUCIONALES -->
        @if ($tipo_titular == true)
            <div class="col-lg-3 col-md-12 col-sm-12 col-12 ">
                @if ($titularInstitucion->count() === 1)
                    <div class="card p-1 pt-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        <p style="background-color:#1a5276; color:white;text-align:center; border-radius:6px">
                            DATOS INSTITUCIONALES
                        </p>
                        {{-- Diseño para una sola iteración --}}
                        @foreach ($titularInstitucion as $tInstitucion)
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                                @if ($tInstitucion->estado == true)
                                    <span class="h5" style="color: green;"><b>ACTIVO</b></span>
                                @elseif($tInstitucion->estado == false)
                                    <span class="h5 " style="color: red;"><b>INACTIVO</b></span>
                                @endif
                            </div>
                            <div class="row ">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>INSTITUCIÓN PRINCIPAL:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ $tInstitucion->titularInstitucionConInstitucion->nombre }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                    <strong>COD. TIT.</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->codigo
                                            ? $tInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                                optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->tipo_titular
                                            : '' }}
                                    </p>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                    <strong>COD. BEN.</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->codigo
                                            ? $tInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                                optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->tipo_beneficiario
                                            : '' }}
                                    </p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <strong>TIPO INSTITUCIÓN:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ $tInstitucion->titularInstitucionConInstitucion->claseAportanteInstitucion->nombre }}
                                    </p>
                                </div>

                                <div class="col-lg-12 col-md-8 col-sm-8 col-6">
                                    <strong>CARGO:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ $tInstitucion->cargo_actual }}
                                    </p>
                                </div>

                                <div class="col-lg-6 col-md-4 col-sm-4 col-6">
                                    <strong>FECHA INGRESO:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ \Carbon\Carbon::parse($tInstitucion->fecha_ingreso)->format('d /m/ Y') }}
                                    </p>
                                </div>
                                <div class="col-lg-6 col-md-3 col-sm-3 col-3">
                                    <strong>SALARIO:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8; padding-right:1; border-color:white; border-radius:8px">
                                        {{ number_format($tInstitucion->salario, 2, ',', '.') }}
                                    </p>
                                </div>

                                <div class="col-lg-7 col-md-4 col-sm-4 col-4">
                                    <strong>ITEM:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                        {{ $tInstitucion->item }}
                                    </p>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-5">
                                    <strong>CARGA HORARIA:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                        {{ $tInstitucion->carga_horaria }}
                                    </p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                    <strong>TITULARIDAD:</strong>
                                    <p class="p-1"
                                        style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                        {{ $tInstitucion->titularidad }}
                                    </p>
                                </div>
                                @if ($perteneceRentista)
                                    <div class="col-lg-12 col-md-6 col-sm-6 col-6">
                                        <strong>TIPO DE RENTA:</strong>
                                        <p class="p-1"
                                            style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                            {{ $tipo_rentista ?? 'SIN ESPECIFICAR' }}
                                        </p>
                                    </div>
                                @endif
                            </div>


                            {{--    <hr>
                                @if ($tInstitucion->estado == false)
                                    <div style="background-color:#fed8d8 ;border-radius:8px;padding:1px">
                                        <div class="row ">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                <p style="color:red"> <b>DATOS DE BAJA</b> </p>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                <strong>FECHA DE BAJA:</strong>
                                                <p class="p-1"
                                                    style="padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->carga_horaria }}
                                                </p>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                                                <strong>MOTIVO DE BAJA:</strong>
                                                <p class="p-1"
                                                    style="padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->carga_horaria }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                                                <strong>OBSERVACIONES DE BAJA:</strong>
                                                <p class="p-1"
                                                    style="padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->carga_horaria }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif --}}
                        @endforeach
                    </div>
                @else
                    {{-- Diseño para múltiples iteraciones --}}
                    <p style="background-color:#1a5276; color:white;text-align:center; border-radius:6px">
                        DATOS INSTITUCIONALES
                    </p>
                    @foreach ($titularInstitucion as $key => $tInstitucion)
                        <div class="accordion" id="accordionDatosVerInsti">
                            <div class="card " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                                <div class="card-header p-1" style="background-color:#f2f8fd"
                                    id="headingVerInsti{{ $key }}">
                                    <h2 class="mb-0">
                                        <button
                                            class="btn btn-outline btn-block text-left collapsed d-flex justify-content-between align-items-center"
                                            type="button" style="color: #1b5989" data-toggle="collapse"
                                            data-target="#collapseVerInsti{{ $key }}" aria-expanded="false"
                                            aria-controls="collapseVerInsti{{ $key }}">
                                            <strong>
                                                @if ($loop->first)
                                                    INSTITUCIÓN PRINCIPAL -

                                                    @if ($tInstitucion->estado == true)
                                                        <span style="color: green;"><b>ACTIVO</b></span>
                                                    @elseif($tInstitucion->estado == false)
                                                        <span style="color: red;"><b>INACTIVO</b></span>
                                                    @endif
                                                @else
                                                    INSTITUCIÓN {{ $loop->iteration }} -

                                                    @if ($tInstitucion->estado == true)
                                                        <span style="color: green;"><b>ACTIVO</b></span>
                                                    @elseif($tInstitucion->estado == false)
                                                        <span style="color: red;"><b>INACTIVO</b></span>
                                                    @endif
                                                @endif
                                            </strong>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-caret-down-fill "
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                            </svg>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseVerInsti{{ $key }}" class="collapse"
                                    aria-labelledby="headingVerInsti{{ $key }}"
                                    data-parent="#accordionDatosVerInsti">
                                    <div class="card-body p-1">
                                        <div class="row ">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                <strong> INSTITUCIÓN: </strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->titularInstitucionConInstitucion->nombre }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                                                <strong>COD. TIT.</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->codigo
                                                        ? $tInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                                            optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->tipo_titular
                                                        : '' }}
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                                                <strong>COD. BEN.</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->codigo
                                                        ? $tInstitucion->titularInstitucionConInstitucion->codigosInstitucion->codigo .
                                                            optional($tInstitucion->titularInstitucionConInstitucion->codigosInstitucion)->tipo_beneficiario
                                                        : '' }}
                                                </p>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                                <strong>TIPO INSTITUCIÓN:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->titularInstitucionConInstitucion->claseAportanteInstitucion->nombre }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                <strong>CARGO:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->cargo_actual }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                                <strong>FECHA INGRESO:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ \Carbon\Carbon::parse($tInstitucion->fecha_ingreso)->format('d /m/ Y') }}
                                                </p>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                                <strong>SALARIO:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ number_format($tInstitucion->salario, 2, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                                                <strong>ITEM:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->item }}
                                                </p>
                                            </div>
                                            <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                                                <strong>CARGA HORARIA:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->carga_horaria }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                <strong>TITULARIDAD:</strong>
                                                <p class="p-1"
                                                    style="background-color:#e5e8e8;padding-right:1; border-color:white; border-radius:8px">
                                                    {{ $tInstitucion->titularidad }}
                                                </p>
                                            </div>
                                        </div>
                                        {{-- - <hr>
                                            @if ($tInstitucion->estado == false)
                                                <div class="row">
                                                    <h1>sdsdf</h1>
                                                </div>
                                            @endif --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @elseif($tipo_titular == false)
            <div class="col-sm-3">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#1a5276; color:white;text-align:center;">
                        DATOS INSTITUCIONALES </p>
                    <p style="text-align:center"> <b> CONTRATO
                            VOLUNTARIO</b></p>
                    <div class="row ">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <strong> INICIO DE
                                CONTRATO: </strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $fecha_inicio_contrato_literal }}
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <strong> FIN DE CONTRATO:
                            </strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $fecha_fin_contrato_literal }}</p>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <strong> TELEFONO 1:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $telefono_1 }}
                            </p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <strong> TELEFONO 2: </strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $telefono_2 }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <strong> CORREO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $correoVoluntario }}</p>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
    @can('super-admin-only')
        <div style="height: 0.5rem;"></div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                <div class="p-2 card card-borde mb-2 h-100">
                    <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                        <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <strong>Nombre de usuario:</strong>
                            {{ $user_created }}
                        </li>
                        <li>
                            <strong>Fecha de registro:</strong>
                            {{ $fecha_created }}
                        </li>
                    </ul>
                </div>
            </div>
            @if ($tieneEdicion === true)
                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="p-2 card card-borde mb-2 h-100">
                        <h6 class="mb-3 font-weight-bold border-bottom pb-1" style="color: orange;">
                            <i class="fas fa-check-circle mr-1"></i> MODIFICADO POR
                        </h6>

                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Nombre de usuario:</strong>
                                {{ $user_edited }}
                            </li>
                            <li>
                                <strong>Fecha de registro:</strong>
                                {{ $fecha_edicion }}
                            </li>

                        </ul>
                    </div>
                </div>
            @endif

        </div>
    @endcan
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

    {{--  
    POR ALGUNA RAZON PONER AQUI NO DA
    @can('aportes.titulares')
        <!--modal para VER APORTES insitutcionalex-->
        @include('livewire.Afiliacion.titulares.modalVerAportesIns')
    @endcan
    @can('aportes.titulares')
        <!--modal para VER APORTES voluntarios-->
        @include('livewire.Afiliacion.titulares.modalVerAportesVol')
    @endcan --}}
    @can('file_digital.titulares')
        <!--Modal para ver PDF del titular -->
        @include('livewire.Afiliacion.titulares.modalVerPdfTitular')
    @endcan
</div>
<script>
    function abrirAlertaMotivo() {
        Swal.fire({
            title: '¿Desea continuar?',
            text: "Por favor, ingrese el motivo:",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Escriba el motivo aquí...',
            showCancelButton: true,
            confirmButtonText: 'Si, Descargar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) return '¡El motivo es obligatorio!'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviamos el motivo directo al componente de Livewire
                Livewire.dispatch('descargarArchivoConMotivo', {
                    motivo: result.value
                });
            }
        });
    }


    document.addEventListener('guardado', function() {
        Swal.fire({
            title: 'Aviso de Confidencialidad!',
            html: "<p class='justificado'>Usted ha descargado un documento digital del sistema. Este documento es confidencial y está destinado exclusivamente para uso interno. Por favor, maneje esta información con la máxima discreción y no la comparta con personas no autorizadas. Cualquier uso indebido o divulgación de este documento puede resultar en medidas disciplinarias y/o legales. Gracias por su comprensión y cooperación. <br><br> <b><em>Ley N° 164, Artículo 56. (Inviolabilidad y secreto de las comunicaciones).</em></b></p>",
            icon: 'warning',
            customClass: {
                htmlContainer: 'text-justify'
            }
        });

    });
</script>
