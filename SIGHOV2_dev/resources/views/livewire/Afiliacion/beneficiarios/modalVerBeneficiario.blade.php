<div>
    <x-ver-dos-modal id="verModalBeneficiario" labelledby="verModalBeneficiarioLabel" title="VER DATOS DEL BENEFICIARIO">
        <div class="row">
            <!--columna 2, donde va la FOTO -->
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                <div class="card-comun p-2">
                    <p style="background-color:{{ $color }}; color:white;text-align:center; border-radius:8px">
                        BENEFICIARIO</p>

                    <div class="row d-flex flex-column align-items-center">
                        @can('descargar.fotografia_beneficiario')
                            <div class="col-lg-12 p-2">
                                <x-descargar-button title="DESCARGAR FOTOGRAFIA" wire:click="descargarFotoBeneficiario"
                                    style="padding:4px">
                                    DESCARGAR FOTO</x-descargar-button>
                            </div>
                        @endcan
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail"
                            style="width:200px; height:200px; object-fit:cover; object-position: top;"
                            alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            @if ($vigencia == 'VIGENTE')
                                <span class="h3" style="color: green;"><b>VIGENTE</b></span>
                            @elseif($vigencia == 'CESANTE')
                                <span class="h3 parpadeo" style="color: #ecb41d;"><b>CESANTE</b></span>
                            @elseif($vigencia == 'NO VIGENTE')
                                <span class="h3 parpadeo" style="color: red;"><b>NO
                                        VIGENTE</b></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <span class="h5 font-weight-bold">{{ $edad }}
                                años</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                            <strong style="font-size:90%">MATRICULA :</strong>
                            <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }}
                            </span>
                            {{-- <span class=" font-weight-bold h5" style="color: #236cb6; ">
                                {{ $codigoBeneficiario }} </span> --}}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong> FECHA INGRESO AL SSU:
                            </strong>
                            <p class="form-control"
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ \Carbon\Carbon::parse($fecha_afiliacion)->Format('d /m/ Y') }} </p>
                        </div>
                    </div>
                    @if ($fecha_reafiliacion == null)
                    @else
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <strong> FECHA AFILIACIÓN:
                                </strong>
                                <p class="form-control"
                                    style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                    {{ \Carbon\Carbon::parse($fecha_reafiliacion)->Format('d /m/ Y') }} </p>
                            </div>
                        </div>
                    @endif
                    {{--
                    @if ($this->con_remuneracion)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong>BENEFICIARIO CON
                                REMUNERACIÓN: </strong>
                            @if ($con_remuneracion)
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;text-align:center">
                                SI
                            </p>
                            @else
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;text-align:center">
                                NO
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif --}}

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>FECHA VENCIMIENTO CARNET:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $fechaVencimientoCarnet }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>FECHA ÚLTIMA MODIFICACIÓN:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $fechaUltimaEdicion }} </p>
                        </div>
                    </div>
                    <hr>

                    @if (!$tieneFileDigital)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 pb-2">
                            <span
                                style="display: inline-block;
                                      padding: 8px 12px;
                                      border: 1px solid #f5c26b;
                                      border-radius: 10px;
                                      background-color: #fff3cd;
                                      color: #ff7c11;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
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
                                        <td> <x-descargar-button title="DESCARGAR FILE DIGITAL" onclick="alertaGuardar()"
                                                style="padding:4px"></x-descargar-button>
                                        </td>

                                    </tr>
                                </table>
                            </div>
                        @endcan
                    @endif

                </div>
            </div>

            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); ">
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-center">
                            <label style="color:#34495e; "> TIPO
                                DE
                                ASEGURADO : </label>
                            <span class=" h5 font-weight-bold" style="color:#1f618d">{{ $tipo_aseguradoBen }}
                            </span>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 text-center">
                            <strong style="color:#34495e">
                                REQUISITOS:</strong>
                            @if ($estado_requisitos)
                                <span class=" h4 text-success font-weight-bold">COMPLETO</span>
                            @else
                                <span class=" h4 text-warning font-weight-bold parpadeo">PENDIENTE</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombre }}
                            </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong> CI: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $ci }}
                            </p>
                        </div>
                        <div class='col-lg-1 col-md-4 col-sm-6 col-xs-12'>
                            <strong>EXP.:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $expedido_ci }} </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                            <strong>TIPO DOC.:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $es_extranjero }} </p>

                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong> FECHA DE NACIMIENTO:
                            </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <strong> SEXO:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $sexo }} </p>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <strong> ESTADO CIVIL:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $estado_civil }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <strong> DEPARTAMENTO DE NACIMIENTO:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $dep_nacimiento }} </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <strong> PROVINCIA DE NACIMIENTO:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $pro_nacimiento }} </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <strong> MUNICIPIO DE NACIMIENTO:</strong>
                            <p class="p-1" disabled
                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ $mun_nacimiento }} </p>
                        </div>
                    </div>
                    <!--DATOS REFERENCIALES-->
                    <p style="background-color:#1a5276; color:white;text-align:center;border-radius:8px">
                        DATOS REFERENCIALES</p>
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong>TELEFONO:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $telefono }} </p>
                                </div>
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <strong> CORREO:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $correo }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <strong> NOMBRE DE REFERENCIA:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $nombre_referencia }} </p>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong> TELF. REFERENCIA:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $telefono_referencia }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <strong> ZONA:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $zona }} </p>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong> NRO VIVIENDA:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $nro_vivienda }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <strong> DIRECCION:</strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $direccion }} </p>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-6 col-12 ">
                                    <strong> ARCHIVO GRUPO: </strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $archivo_grupo }}</p>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12 ">
                                    <strong> FECHA ARCHIVO: </strong>
                                    <p class="p-1 p-comun-disabled">
                                        {{ $fecha_archivo }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <strong> GRUPO SANGUINEO: </strong>
                                    <p class="p-1" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                        {{ $grupo_sanguineo }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="alergias"> ALERGIAS: </label>
                                    <textarea class="form-control" id="alergias" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; " wire:model=alergias></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="observacion"> OBSERVACIONES: </label>
                                    <textarea class="form-control" name="observacion" id="observacion" disabled
                                        style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; "
                                        wire:model=observacion></textarea>
                                </div>
                            </div>
                            <br>
                            <!--    MODAL PARA VER LA UBICACION -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <x-ver-dos-button wire:click="AbrirModalUbicacionBeneficiario">UBICACIÓN
                                </x-ver-dos-button>
                                @include('livewire.Afiliacion.beneficiarios.modalUbicacionBeneficiario')
                            </div>
                        </div>
                    </div>
                </div>

                <!--DATOS INSITUCIONALES EN CASO DE REMUNERACION-->
                @if ($this->con_remuneracion)
                    <div class="accordion" id="accordionDatosFamiliar">
                        <div class="card" style=" box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            <div class="card-header p-1" style="background-color:#1a5276" id="headingFamiliar">
                                <h2 class="mb-0">
                                    <button
                                        class="btn btn-outline btn-block text-left collapsed  d-flex justify-content-between align-items-center"
                                        type="button" style="color: white" data-toggle="collapse"
                                        data-target="#collapseFamiliar" aria-expanded="false"
                                        aria-controls="collapseFamiliar"> DATOS INSTITUCIONALES DE BENEFICIARIO CON
                                        TRASPASO DE APORTES
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFamiliar" class="collapse" aria-labelledby="headingFamiliar"
                                data-parent="#accordionDatosFamiliar">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <strong> INSTITUCION:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ $nombreBenIns }} </p>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <strong> FECHA DE INGRESO:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ \Carbon\Carbon::parse($fechaIngresoBenIns)->Format('d /m/ Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <strong> CARGO:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ $cargoBenIns }} </p>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <strong> SALARIO:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ $salarioBenIns }} </p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <strong>ITEM:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ $itemBenIns }} </p>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <strong>CARGA HORARIA:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ $cargaHorariaBenIns }} </p>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <strong>TITULARIDAD:</strong>
                                            <p class="p-1" disabled
                                                style="background-color:#e0e0e0; border-color:white; border-radius:10px; padding-right:10px; ">
                                                {{ $titularidadBenIns }} </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
                <div class="card p-2"
                    style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); height: 100%;">
                    <p
                        style="background-color:{{ $color_titular }}; color:white;text-align:center; border-radius:8px">
                        DATOS DE TITULAR</p>

                    <div class=" row d-flex flex-column align-items-center">
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($this->foto_titular_mostrar) ? 'data:image/jpg;base64,' . $this->foto_titular_mostrar : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail"
                            style="width:200px; height:200px; object-fit:cover; object-position: top;"
                            alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 25px">
                            @if ($vigenciaTitular == 'VIGENTE')
                                <span style="color: green;"><b>VIGENTE</b></span>
                            @elseif($vigenciaTitular == 'CESANTE')
                                <span style="color: #ecb41d;"><b>CESANTE</b></span>
                            @elseif($vigenciaTitular == 'NO VIGENTE')
                                <span class="parpadeo" style="color: red;"><b>NO
                                        VIGENTE</b></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                            <strong style="font-weight">MATRICULA :</strong>
                            <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matriculaTit }} </span>
                            {{--    <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoTitular }} </span> --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong style="font-weight">TIPO :</strong>
                            <span class=" font-weight-bold h5">{{ $tipoAporteTitular }}
                                {{ $titularPermanente }} </span>
                        </div>
                    </div>

                    @if ($vigenciaTitular == 'NO VIGENTE')
                        @if ($institucionesTitular)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <strong>INSTITUCIONES :</strong>
                                    @foreach ($institucionesTitular as $instis)
                                        <div class="mb-2">
                                            <p class="font-weight-bold h5 mb-0">
                                                - {{ $instis->titularInstitucionConInstitucion->nombre }}
                                            </p>
                                            <p class="font-weight-bold text-red pt-1 mb-0">
                                                FECHA BAJA :
                                                {{ $instis->fecha_baja ? \Carbon\Carbon::parse($instis->fecha_baja)->format('d/m/Y') : 'SIN DATO' }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        @if ($tipoAporteTitular == 'INSTITUCIONAL')
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <span class=" font-weight-bold h5">{{ $nombreInstitucionTit }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                    <strong style="font-size:90%">TIPO :</strong>
                                    <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipoAporteTitular }}
                                    </span>

                                </div>
                            </div>
                        @endif
                    @endif


                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO:</strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombreCompletoTitular }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> CARNET: </strong>
                            <p class="p-1"
                                style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $ciTitular }}
                            </p>
                        </div>
                    </div>
                    @if ($tipoAporteTitular == 'INSTITUCIONAL' && $vigenciaTitular == 'VIGENTE')
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>CARGO :</strong>
                                <p class="p-1"
                                    style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $cargoTit }} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>SALARIO :</strong>
                                <p class="p-1"
                                    style="background-color:#e0e0e0;padding-rigth:1; border-color:white; border-radius:10px;">
                                    {{ $salarioTit }} </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
    </x-ver-dos-modal>

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
    </style>
</div>
<script>
    window.addEventListener('modalVerBeneficiario', event => {

        $('#verModalBeneficiario').modal('show');
    });

    // ALERTA DESCARGAR PDF 
    function alertaDescargar() {
        Swal.fire({
            title: '¿Descargar Archivo?',
            text: "Se descargará el archivo seleccionado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, descargar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('descargarArchivo');
                $('#verModalBeneficiario').modal('hide');
            }
        });
    }
</script>
