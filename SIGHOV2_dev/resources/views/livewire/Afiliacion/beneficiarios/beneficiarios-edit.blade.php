<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white"
                class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                <path fill-rule="evenodd"
                    d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
            </svg>
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">EDITAR
                DATOS DE
                ASEGURADO BENEFICIARIO</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:{{ $color }}; color: white; text-align:center; border-radius:8px"> <b>
                        BENEFICIARIO</b>
                </p>

                <div class="row">

                    @if ($foto)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#2471a3"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                <em class="ml-2" style="color:#2471a3"><b>Foto cambiada</b> </em>
                            </p>
                        </div>
                    @else
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                    class="bi bi-upload" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                </svg><em class="ml-2" style="color:#2471a3"><b>Seleccionar
                                        Foto:</b> </em>
                            </p>
                        </div>
                    @endif

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if ($errors->has('error_message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error_message') }}
                            </div>
                        @endif
                        <input class="form-control" type="file" id="formFile" name="archivo" required
                            wire:model.live="foto" accept=".jpg,.jpeg,.png">

                    </div>
                    <div class="col-lg-12">
                        <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                        @if ($foto)
                            <div class=" d-flex flex-column align-items-center">
                                <img src="{{ $foto->temporaryUrl() }}"
                                    style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                    class="img-thumbnail">
                            </div>
                        @elseif ($fotoAntigua)
                            <div class=" d-flex flex-column align-items-center">
                                <img src="{{ !empty($fotoAntigua) ? 'data:image/jpg;base64,' . $fotoAntigua : asset('images/default_afiliacion.jpg') }}"
                                    style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                    class="img-thumbnail">
                            </div>
                        @endif
                    </div>
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
                        {{--   <span class=" font-weight-bold h5" style="color: #236cb6; ">
                            {{ $codigoBeneficiario }} </span> --}}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong> FECHA INGRESO AL SSU:
                        </strong>
                        <p class="form-control"
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px; padding-right:10px; ">
                            {{ \Carbon\Carbon::parse($fecha_afiliacion)->Format('d /m/ Y') }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong> FECHA DE VENCIMIENTO CARNET:
                        </strong>
                        <p class="form-control"
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px; padding-right:10px; ">
                            {{ $fechaVencimientoCarnet }}
                        </p>
                    </div>
                </div>

                @if ($fecha_reafiliacion != null)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong> FECHA AFILIACIÓN:
                            </strong>
                            <p class="form-control"
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px; padding-right:10px; ">
                                {{ \Carbon\Carbon::parse($fecha_reafiliacion)->Format('d /m/ Y') }}
                            </p>
                        </div>
                    </div>
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <strong>¿BENEFICIARIO CON
                        TRASPASO DE APORTES ?: </strong>
                    @if ($con_remuneracion)
                        <span class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;text-align:center">
                            SI
                        </span>
                    @else
                        <span class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;text-align:center">
                            NO
                        </span>
                    @endif
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <strong>FECHA ÚLTIMA EDICIÓN:</strong>
                    <p class="p-1"
                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                        {{ $fechaUltimaEdicion }} </p>
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
                    <div class="div-tabla-responsive ">
                        <table>
                            <tr>
                                <td><b>FILE DIGITAL:</b></td>
                                <td>
                                    <div>
                                        <x-descargar-button title="DESCARGAR FILE DIGITAL"
                                            onclick="alertaGuardarFileBeneficiario()"
                                            style="padding:4px"></x-descargar-button>
                                        <div id="cargando-file" style="display: none;">
                                            <x-cargando-dos />
                                        </div>
                                    </div>
                                </td>
                                <td> <x-ver-pdf-button title="VER FILE DIGITAL" wire:click="abrirModalFileEditar"
                                        style="padding:4px"></x-ver-pdf-button></td>
                            </tr>
                        </table>
                    </div>

                    @include('livewire.Afiliacion.titulares.modalVerPdfTitular')
                @endif

                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    @if ($file_digital)
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#5499c7"
                                class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                            </svg>
                            <em class="ml-2" style="color:#5499c7"><b>File
                                    Digital cargado:</b> </em>

                        </p>
                    @else
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#5499c7"
                                class="bi bi-upload" viewBox="0 0 16 16">
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                <path
                                    d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                            </svg><em class="ml-2" style="color:#5499c7"><b>Subir File
                                    Digital:</b> </em>

                        </p>
                    @endif
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div wire:loading wire:target="file_digital">
                        <x-cargando-dos />
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <input class="form-control form-control-sm @error('file_digital') is-invalid @enderror"
                        type="file" id="formFiledigital" name="file_digital" required wire:model="file_digital"
                        onchange="confirmarFile(this)" accept=".pdf">
                    @error('file_digital')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <!--DATOS REFERENCIALES-->
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <label style="color:#34495e; "> TIPO
                            DE
                            ASEGURADO : </label>
                        <span class=" h5 font-weight-bold" style="color:#1f618d">{{ $tipo_aseguradoBen }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="nombre"> NOMBRE COMPLETO: </label>
                        <input class="form-control" id="nombre" wire:model="nombre" disabled
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="ci"> C.I.: </label>
                        <input class="form-control" id="ci" wire:model=ci disabled
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="expedido_ci">EXPEDIDO:</label>
                        <input class="form-control" id="expedido_ci" wire:model="expedido_ci" disabled
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-6 col-xs-12">
                        <label for="es_extranjero">TIPO DOC.:</label>
                        <input class="form-control" wire:model="es_extranjero" id="es_extranjero" disabled
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="sexo"> SEXO:</label>
                        <input class="form-control" wire:model="sexo" id="sexo" disabled
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:
                        </label>
                        <input class="form-control" id="fecha_nacimiento" wire:model="fecha_nacimiento_vista"
                            disabled style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                        <label for="id_estado_civil">ESTADO CIVIL:</label>
                        <select wire:model="id_estado_civil" id="id_estado_civil"
                            class="form-control @error('id_estado_civil') is-invalid @enderror select2 "
                            style="padding-rigth:1;  border-radius:10px;" tabindex="-1">
                            <option selected="selected " value="{{ $id_estado_civil }}"> {{ $estado_civil }}
                            </option>
                            @foreach ($estadoCivilSelect as $civilSelected)
                                <option value="{{ $civilSelected->id }}">{{ $civilSelected->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <label for="dep_nacimiento">DEPARTAMENTO DE
                            NACIMIENTO:</label>
                        <select wire:model.live="id_dep_nacimiento" id="dep_nacimiento"
                            class="form-control select2 @error('dep_nacimiento') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required
                            wire:change='ObtenerProvincia()'>
                            <option selected=" ">Departamento de nacimiento </option>
                            @foreach ($this->departamento as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <label for="pro_nacimiento">PROVINCIA DE
                            NACIMIENTO:</label>
                        <select wire:model.live="id_pro_nacimiento" id="pro_nacimiento"
                            class="form-control select2 @error('pro_nacimiento') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required
                            wire:change='ObtenerMunicipio()'>
                            <option selected=" ">Provincia de nacimiento </option>
                            @if ($this->provincias)
                                @foreach ($this->provincias as $pro)
                                    <option value="{{ $pro->id }}">
                                        {{ $pro->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="mun_nacimiento">MUNICIPIO DE
                            NACIMIENTO:</label>
                        <select wire:model.live="id_mun_nacimiento" name="mun_nacimiento" id="mun_nacimiento"
                            class="form-control select2 @error('mun_nacimiento') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required>
                            <option selected=" ">Municipio de nacimiento </option>
                            @if ($this->municipios)
                                @foreach ($this->municipios as $mun)
                                    <option value="{{ $mun->id }}">{{ $mun->nombre }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em class="ml-2 h5" style="color:#2471a3 ; "><b>Ingrese los datos referenciales a
                                modificar :</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 text-right">
                        <strong>ESTADO DE REQUISITOS:</strong>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-sm-6 d-flex align-items-center">
                            <label class="text-warning">PENDIENTE</label>
                            <div
                                class="custom-control custom-switch custom-switch-off-warning custom-switch-on-success">
                                <input wire:model="estado_requisitos" type="checkbox" class="custom-control-input"
                                    id="customSwitch2">
                                <label class="custom-control-label" for="customSwitch2"
                                    style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                            </div>
                            <label class="text-green">COMPLETO</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono"> TELEFONO:</label>
                                <input class="form-control @error('telefono') is-invalid @enderror" id="telefono"
                                    wire:model.live=telefono style="border-radius:10px">
                                @error('telefono')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="correo"> CORREO:</label>
                                <input class="form-control @error('correo') is-invalid @enderror" id="correo"
                                    wire:model.live=correo style="border-radius:10px">
                                @error('correo')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre_referencia"> NOMBRE DE
                                    REFERENCIA: </label>
                                <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                    id="nombre_referencia" wire:model.live=nombre_referencia
                                    style="border-radius:10px" oninput="this.value = this.value.toUpperCase()">
                                @error('nombre_referencia')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror

                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono_referencia"> TEL.
                                    REFERENCIA:</label>
                                <input class="form-control @error('telefono_referencia') is-invalid @enderror"
                                    id="telefono_referencia" wire:model.live=telefono_referencia
                                    style="border-radius:10px">
                                @error('telefono_referencia')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                                <label for="zonaId">ZONA:</label>
                                <select wire:model="zonaId" name="zonaId" id="zonaId"
                                    class="form-control @error('zonaId') is-invalid @enderror select2"
                                    style="width: 100% ;border-radius:10px" tabindex="-1">
                                    <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                    </option>
                                    @foreach ($zonasSelect as $zonaSelected)
                                        <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nro_vivienda"> NRO VIVIENDA: </label>
                                <input class="form-control @error('nro_vivienda') is-invalid @enderror"
                                    id="nro_vivienda" wire:model.live=nro_vivienda style="border-radius:10px">
                                @error('nro_vivienda')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="direccion"> DIRECCIÓN: </label>
                                <input class="form-control  @error('direccion') is-invalid @enderror" id="direccion"
                                    oninput="this.value = this.value.toUpperCase()" wire:model.live=direccion
                                    style="border-radius:10px">
                                @error('direccion')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="grupo_sanguineo"> GRUPO SANGUINEO:
                                </label>
                                <input class="form-control" id="grupo_sanguineo" wire:model=grupo_sanguineo disabled
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;text-align:center">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="alergias"> ALERGIAS: </label>
                                <textarea class="form-control" id="alergias" wire:model=alergias rows="3 "
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px">
                                </textarea>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="observacion"> OBSERVACIONES: </label>
                                <textarea class="form-control" id="observacion" wire:model=observacion rows="3"
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px">
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($con_remuneracion)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg> <em class="ml-2 h5" style="color:#2471a3 ; "><b>Ingrese los datos de la institución
                                    de
                                    transferencia a
                                    modificar:</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="idBenIns">INSTITUCIÓN:</label>
                            <select wire:model="idBenIns" name="idBenIns" id="idBenIns"
                                class="form-control @error('idBenIns') is-invalid @enderror select2"
                                style="width: 100% ;border-radius:10px" tabindex="-1" required>
                                <option selected="selected">Selecciona la Institucion</option>
                                @foreach ($institucionSelect as $institucion)
                                    <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fechaIngresoBenIns">FECHA DE INGRESO: </label>
                            <input class="form-control @error('fechaIngresoBenIns') is-invalid @enderror"
                                id="fechaIngresoBenIns" wire:model.live=fechaIngresoBenIns style="border-radius:10px"
                                type="date">
                            @error('fechaIngresoBenIns')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="cargoBenIns">CARGO:</label>
                            <input class="form-control @error('cargoBenIns') is-invalid @enderror" id="cargoBenIns"
                                wire:model.live=cargoBenIns style="border-radius:10px">
                            @error('salario')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="salarioBenIns"> SALARIO: </label>
                            <input class="form-control @error('salarioBenIns') is-invalid @enderror"
                                id="salarioBenIns" wire:model.live=salarioBenIns style="border-radius:10px">
                            @error('salarioBenIns')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="itemBenIns">ITEM: </label>
                            <input class="form-control @error('itemBenIns') is-invalid @enderror" id="itemBenIns"
                                wire:model.live=itemBenIns style="border-radius:10px">
                            @error('itemBenIns')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="cargaHorariaIns">CARGA HORARIA:</label>
                            <select wire:model="cargaHorariaIns" name="cargaHorariaIns" id="cargaHorariaIns"
                                class="form-control @error('cargaHorariaIns') is-invalid @enderror select2"
                                style="width: 100% ;border-radius:10px" tabindex="-1">
                                <option value="" selected disabled>Seleccione carga horaria</option>
                                <option value="160 Hrs.">160 Hrs.</option>
                                <option value="128 Hrs.">128 Hrs.</option>
                                <option value="114 Hrs.">114 Hrs.</option>
                                <option value="112 Hrs.">112 Hrs.</option>
                                <option value="96 Hrs.">96 Hrs.</option>
                                <option value="80 Hrs.">80 Hrs.</option>
                                <option value="64 Hrs.">64 Hrs.</option>
                                <option value="48 Hrs.">48 Hrs.</option>
                                <option value="32 Hrs.">32 Hrs.</option>
                                <option value="16 Hrs.">16 Hrs.</option>
                                <option value="POR ACTUALIZAR">POR ACTUALIZAR</option>
                                <option value="TC">TC</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="titularidadIns">TITULARIDAD:</label>
                            <select wire:model="titularidadIns" name="titularidadIns" id="titularidadIns"
                                class="form-control @error('titularidadIns') is-invalid @enderror"
                                style="width: 100% ;border-radius:10px" tabindex="-1">
                                <option value="" selected disabled>Seleccione titularidad</option>
                                <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                <option value="CONTRATADO">CONTRATADO</option>
                                <option value="EVENTUAL">EVENTUAL</option>
                                <option value="INTERINO">INTERINO</option>
                                 <option value="INVITADO">INVITADO</option>
                                <option value="NINGUNO">NINGUNO</option>
                                <option value="TITULAR A">TITULAR A</option>
                                <option value="TITULAR B">TITULAR B</option>
                                <option value="TITULAR C">TITULAR C</option>
                                <option value="TITULAR EMERITO">TITULAR EMERITO</option>
                            </select>
                        </div>
                    </div>
                @endif
            </div>
            <!--ubicacion -->
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="row">
                            <div class="col-lg-12 ">
                                <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                    data-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample" style="background-color:#1E6BA2; width:50%">
                                    <i class="fa fa-street-view" aria-hidden="true"></i> Agregar
                                    ubicación
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"
                                        style="color:white">
                                        <path
                                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </button>
                                @if ($this->ubicacion_select == 0)
                                    <span style="font-size: 18px;" class=" text-danger">No Seleccionado
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-street-view fa-stack-1x"></i>
                                            <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                        </span>
                                    </span>
                                @else
                                    <span style="font-size: 18px;" class="text-success">Seleccionado
                                        Correctamente
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-street-view fa-stack-1x"></i>
                                        </span>
                                    </span>
                                @endif
                                <div class="collapse" id="collapseExample" wire:ignore>
                                    <div class="card card-body">
                                        @include('livewire.Afiliacion.beneficiarios.ubicacion_beneficiarios_edit')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" wire:ignore.render>
                        <div class="col-lg-12">
                            <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                data-target="#collapseExample2" aria-expanded="false"
                                aria-controls="collapseExample2" style="background-color:#2161a2 ; width:80%">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                                </svg> Ubicación Actual
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"
                                    style="color:white">
                                    <path
                                        d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                </svg>
                            </button>
                            <div class="collapse" id="collapseExample2" wire:ignore>
                                <div class="card card-body">
                                    @include('livewire.Afiliacion.beneficiarios.ubicacionActualBeneficiario')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br>
    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarBen"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarEdicionBen()">GUARDAR</x-save-button>
    </div>
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

        .select2-container .select2-selection--single {
            height: 40px !important;
            padding: 6px 12px;
            border-radius: 10px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 25px;
        }
    </style>

</div>
<script>
    // esto es lo de las zonas
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#zonaId');
        $(select).select2();
        $(select).on('select2:open', function() {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#zonaId').on('change', function(e) {
            var data = $(this).val();
            @this.set('zonaId', data);
        });
        initMap(); // Mapa de ubicación actual
        initMap2(); // Mapa de ubicación actual
    });

    // EDITAR BENEFICIARIO
    function guardarEdicionBen() {
        Swal.fire({
            title: '¿Guardar modificación?',
            text: "Se registrarán los datos modificados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarBeneficiario');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

            }
        });
    }

    document.addEventListener('beneficiarioModificado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente",
            icon: "success"

        });
    });
    document.addEventListener('beneficiarioCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });

    function alertaGuardarFileBeneficiario() {

        //$('#verModalTitular').modal('hide');
        Swal.fire({
            title: `¿Descargar file digital?`,
            text: "Se descargará el file digital seleccionado del asegurado.",
            icon: 'question',
            iconColor: '#2980b9',
            input: "text",
            inputLabel: "INTRODUZCA EL MOTIVO : ",
            inputAttributes: {
                autocapitalize: "off",
                style: "text-transform: uppercase;"
            },
            showCancelButton: true,
            confirmButtonText: "Si, Descargar",
            confirmButtonColor: "#2471a3",
            showLoaderOnConfirm: true,
            cancelButtonText: "No, Cancelar",
            cancelButtonColor: '#cb4335',

            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`¡Porfavor introduzca el motivo de descarga!`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()

        }).then((result) => {
            if (result.isConfirmed) {
                // document.getElementById('cargando-file').style.display = 'block';
                Livewire.dispatch('descargarArchivoMotivoBeneficiario', [result.value]);
            }
        });
    }
</script>
