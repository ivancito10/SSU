<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRO
                DE ASEGURADO BENEFICIARIO </h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;border-radius:8px "> <b> BENEFICIARIO</b></p>
                <div class="row">
                    @if ($foto)
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#2471a3"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                <em class="ml-2" style="color:#2471a3"><b>Foto seleccionada</b> </em>
                            </p>
                        </div>
                    @else
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#5499c7 "
                                    class="bi bi-upload" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                </svg><em class="ml-2" style="color:#5499c7 "><b>Seleccionar
                                        Foto:</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if ($errors->has('error_message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error_message') }}
                            </div>
                        @endif
                        <input class="form-control form-control-sm @error('foto') is-invalid @enderror" type="file"
                            id="formFile" name="archivo" required wire:model="foto" accept=".jpg,.jpeg,.png">
                        @error('foto')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" text-align="center">
                        <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                        @if ($foto)
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <div class=" d-flex flex-column align-items-center">
                                    <br>
                                    <img src="{{ $foto->temporaryUrl() }}"
                                        style="  width: 200px; height: 200px; object-fit: cover;" class="img-thumbnail">
                                </div>
                            @endif

                        @endif
                    </div>
                </div>
                <br>
                @if ($file_digital)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#2471a3"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                <em class="ml-2" style="color:#2471a3"><b>File
                                        Digital Subido</b> </em>
                            </p>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#5499c7"
                                    class="bi bi-upload" viewBox="0 0 16 16">
                                    <path
                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                    <path
                                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                </svg><em class="ml-2" style="color:#5499c7"><b>Subir File
                                        Digital:</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                @endif

                <input class="form-control form-control-sm @error('file_digital') is-invalid @enderror" type="file"
                    id="formFiledigital" name="file_digital" required wire:model="file_digital" accept=".pdf">
                @error('file_digital')
                    <div class="text-danger">
                        <b>{{ $message }}</b>
                    </div>
                @enderror


                <div wire:loading wire:target="file_digital"> <x-cargando-dos /> </div>
                <hr>
                <div class="row" style="display: flex; justify-content:center;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_afiliacion"> FECHA AFILIACIÓN: </label>
                        <input class="form-control" id="fecha_afiliacion" wire:model=fecha_afiliacion disabled
                            style="background-color:#e5e8e8; border-color:white; border-radius:10px; text-align:center">
                    </div>
                </div>
            </div>
        </div>
        <!--DATOS DE BENEFICIARIO-->
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em class="ml-2 h5" style="color:#2471a3;"><b>Ingrese los datos
                                personales:</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>
                <div class="row ">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->
                            <button class="btn btn-success" type="submit" wire:click="buscarDatos">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>
                            <input class="form-control" type="search" id="buscarCarnet" wire:model='buscarCarnet'
                                style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px"
                                placeholder="Ingresa el Carnet de identidad">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <input class="form-control" type="search" id="buscarComplemento"
                            wire:model='buscarComplemento'
                            style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px"
                            placeholder="Ingrese el Complemento">
                    </div>
                    @if ($encontrado == '')
                    @else
                        <div class="col-lg-12 " role="alert">
                            <br>
                            @if ($encontrado == 1)
                                <div class="alert alert-success"> SE VALIDÓ CORRECTAMENTE</div>
                            @elseif($encontrado == 0)
                                <div class="alert alert-warning">NO SE VALIDARON LOS DATOS
                                    EN EL ASUSS,
                                    INGRESE MANUALMENTE LOS DATOS CON CUIDADO</div>
                            @elseif($encontrado == 2)
                                <div class="alert alert-danger">LA PERSONA YA SE ENCUENTRA AFILIADA COMO
                                    <strong>ESTUDIANTE</strong>
                                </div>
                            @elseif($encontrado == 3)
                                <div class="alert alert-danger">LA PERSONA YA SE ENCUENTRA AFILIADA COMO
                                    <strong>BENEFICIARIO</strong>
                                </div>
                            @elseif($encontrado == 4)
                                <div class="alert alert-danger">LA PERSONA YA SE ENCUENTRA AFILIADA COMO
                                    <strong>TITULAR</strong>
                                </div>
                            @elseif($encontrado == 5)
                                <div class="alert alert-danger">LA PERSONA YA SE ENCUENTRA AFILIADA COMO
                                    <strong>BENEFICIARIO NO VIGENTE</strong> SI QUIERE REGISTRAR DE NUEVO A ESTA PERSONA
                                    DEBE IR A
                                    LAS OPCIONES DE <strong> REAFILIAR</strong> EN EL LISTADO.
                                </div>
                            @endif
                        </div>
                    @endif
                    <div wire:loading wire:target="buscarDatos">
                        <div class="spinner-grow text-warning" role="status">
                        </div>
                        <b><i>Cargando datos espere porfavor...</i></b>
                    </div>
                </div>
                <!--  DATOS PERSONALES -->
                @if ($encontrado == '' || $encontrado == 2 || $encontrado == 3 || $encontrado == 4 || $encontrado == 5)
                @else
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="pApellidoBen"> PRIMER APELLIDO: </label>
                            <input class="form-control @error('pApellidoBen') is-invalid @enderror" id="pApellidoBen"
                                wire:model=pApellidoBen @if ($busqueda) disabled @endif
                                placeholder="Ingrese el primero apellido..." style="border-radius:10px"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="sApellidoBen"> SEGUNDO APELLIDO: </label>
                            <input class="form-control  @error('sApellidoBen') is-invalid @enderror" id="sApellidoBen"
                                wire:model=sApellidoBen @if ($busqueda) disabled @endif
                                placeholder="Ingrese el segundo apellido..." style="border-radius:10px"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombresBen"> NOMBRES: </label>
                            <input class="form-control  @error('nombresBen') is-invalid @enderror " id="nombresBen"
                                wire:model=nombresBen @if ($busqueda) disabled @endif
                                placeholder="Ingrese los nombres..." style="border-radius:10px"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="ciBen"> C.I: </label>
                            <input class="form-control" id="ciBen" wire:model=ciBen style="border-radius:10px"
                                @if ($busqueda) disabled @endif
                                placeholder="Ingrese  el nro de ci...">
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="complementoBen"> COMP: </label>
                            <input class="form-control" id="complementoBen" wire:model=complementoBen
                                oninput="this.value = this.value.toUpperCase()" style="border-radius:10px"
                                @if ($busqueda) disabled @endif
                                placeholder="Ingrese el complemento...">
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="expedido_ci">EXPEDIDO:</label>
                            <select wire:model.live="expedido_ci" id="expedido_ci"
                                @if ($expedido_ciSwitch) disabled @endif
                                class="form-control @error('expedido_ci') is-invalid @enderror"
                                style="width: 100%;border-radius:10px" tabindex="-1" required>
                                <option selected="selected">Seleccione.. </option>
                                @foreach ($this->departamento as $dep)
                                    <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="customSwitchSexo"> SEXO:</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <strong class="text-info">M</strong>
                                <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                                    <input wire:model="sexoBen" id="customSwitchSexo" type="checkbox"
                                        wire:change="GenerarMatricula" class="custom-control-input"
                                        @if ($matriculaBenSwitch) disabled @endif>
                                    <label class="custom-control-label" for="customSwitchSexo"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <strong class="text-pink">F</strong>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fechaNacimientoBen"> FECHA DE NACIMIENTO: </label>
                            <input type="date"
                                class="form-control  @error('fechaNacimientoBen') is-invalid @enderror "
                                id="fechaNacimientoBen" style="border-radius:10px" wire:model.live=fechaNacimientoBen
                                wire:change="validarFechaNacimiento" min="1900-01-01"
                                max="{{ now()->toDateString() }}" @if ($fechaNacimientoBenSwitch) disabled @endif>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <label for="matriculaBen"> MATRICULA: </label>
                            <input class="form-control @error('matriculaBen') is-invalid @enderror "
                                id="matriculaBen" wire:model="matriculaBen"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;"
                                disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="tipo_doc">TIPO DOC.:</label>
                            <select wire:model.live="tipo_doc" name="tipo_doc" id="tipo_doc"
                                @if ($tipo_docSwitch) disabled @endif
                                class="form-control @error('tipo_doc') is-invalid @enderror select2"
                                style="width: 100%;border-radius:10px" tabindex="-1" required>
                                <option selected="selected">Seleccione.. </option>
                                <option value="false">NACIONAL</option>
                                <option value="true">EXTRANJERO</option>
                            </select>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="estado_civil"> ESTADO CIVIL:</label>
                            <select wire:model="estado_civil" id="estado_civil"
                                @if ($estado_civilSwitch) disabled @endif
                                class="form-control @error('estado_civil') is-invalid @enderror"
                                style="width: 100%; border-radius:10px" tabindex="-1" required
                                wire:change="GenerarMatricula">
                                <option selected="selected">Selecciona estado civil... </option>
                                @foreach ($id_estado_civil as $estado_civil)
                                    <option value="{{ $estado_civil->id }}">{{ $estado_civil->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        @if ($datosLugarNacimiento === true)
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="dep_nacimiento_nombre">DEP. NAC.:</label>
                                <input class="form-control" id="dep_nacimiento_nombre"
                                    wire:model=dep_nacimiento_nombre disabled style="border-radius:10px">
                            </div>

                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="pro_nacimiento_nombre">PRO. NAC.:</label>
                                <input class="form-control" id="pro_nacimiento_nombre"
                                    wire:model=pro_nacimiento_nombre disabled style="border-radius:10px">
                            </div>

                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="mun_nacimiento_nombre">MUN. NAC.:</label>
                                <input class="form-control" id="mun_nacimiento_nombre"
                                    wire:model=mun_nacimiento_nombre disabled style="border-radius:10px">
                            </div>
                        @else
                            @if ($tipo_doc == 'false')
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="dep_nacimiento">DEP. NAC.:</label>
                                    <select wire:model.live="dep_nacimiento" id="dep_nacimiento"
                                        class="form-control @error('dep_nacimiento') is-invalid @enderror"
                                        style="width: 100% ; border-radius:10px" tabindex="-1" required
                                        wire:change='ObtenerProvincia()'>
                                        <option selected="selected">Seleccione el departamento de nacimiento...
                                        </option>
                                        @foreach ($this->departamento as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="pro_nacimiento">PRO. NAC.:</label>
                                    <select wire:model.live="pro_nacimiento" id="pro_nacimiento"
                                        class="form-control @error('pro_nacimiento') is-invalid @enderror select2"
                                        style="width: 100%;border-radius:10px" tabindex="-1" required
                                        wire:change='ObtenerMunicipio()'>
                                        <option selected="selected">Seleccione la provincia de nacimiento... </option>
                                        @foreach ($this->provincias as $pro)
                                            <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="mun_nacimiento">MUN. NAC.:</label>
                                    <select wire:model.live="mun_nacimiento" id="mun_nacimiento"
                                        class="form-control @error('mun_nacimiento') is-invalid @enderror  select2"
                                        style="width: 100%;border-radius:10px" tabindex="-1" required>
                                        <option selected="selected">Seleccione el municipio de nacimiento... </option>
                                        @foreach ($this->municipios as $mun)
                                            <option value="{{ $mun->id }}">{{ $mun->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                            @endif
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="id_tipo_asegurado">TIPO DE BENEFICIARIO:</label>
                            <select wire:model="id_tipo_asegurado" name="id_tipo_asegurado" id="id_tipo_asegurado"
                                class="form-control @error('id_tipo_asegurado') is-invalid @enderror"
                                style="width: 100%; border-radius:10px" tabindex="-1" required
                                wire:change="cambioTipoAsegurado">
                                <option selected="selected">Selecciona el tipo </option>
                                @foreach ($tipoBeneficiarioSelect as $tipoBeneficiario)
                                    <option value="{{ $tipoBeneficiario->id }}">
                                        {{ $tipoBeneficiario->tipo_asegurado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="customSwitch3" style="color:#2471a3; font-size:90%">¿BENEFICIARIO CON
                                TRASFERENCIA DE APORTES?</label>
                            <div class="col-sm-5 d-flex align-items-center">
                                <strong class="mr-2 ml-5 text-red">NO</strong>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" wire:model.live="con_remuneracion"
                                        class="custom-control-input" id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3"
                                        style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                </div>
                                <strong class="ml-2 text-green">SI</strong>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <!--datos referenciales-->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em class="ml-2" style="color:#2471a3; font-size:20px"><b>Ingrese los datos
                                referenciales:</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>

                <div class="row">
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono"> TELEFONO:</label>
                                <input class="form-control  @error('telefono') is-invalid @enderror" id="telefono"
                                    wire:model.live=telefono placeholder="Ingrese el teléfono..."
                                    style="border-radius:10px">
                                @error('telefono')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="correo"> CORREO:</label>
                                <input class="form-control" id="correo" wire:model.live=correo
                                    placeholder="Ingrese el correo electrónico..." style="border-radius:10px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre_referencia"> NOMBRE DE REFERENCIA: </label>
                                <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                    id="nombre_referencia" wire:model.live=nombre_referencia
                                    placeholder="Ingrese el nombre de referencia..."
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px">
                                @error('nombre_referencia')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono_referencia"> TELEFONO REFERENCIA:</label>
                                <input class="form-control @error('telefono_referencia') is-invalid @enderror"
                                    id="telefono_referencia" wire:model.live=telefono_referencia
                                    placeholder="Ingrese el teléfono de referencia..." style="border-radius:10px">
                                @error('telefono_referencia')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                                <label for="id_zona">ZONA:</label>
                                <select wire:model.live="id_zona" name="id_zona" id="id_zona"
                                    class="form-control @error('id_zona') is-invalid @enderror select2"
                                    style="width: 100%; border-radius:10px" tabindex="-1">
                                    <option selected="selected">Selecciona la zona </option>
                                    @foreach ($zonasSelect as $zona)
                                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nro_vivienda"> NRO VIVIENDA: </label>
                                <input class="form-control @error('nro_vivienda') is-invalid @enderror"
                                    id="nro_vivienda" wire:model.live=nro_vivienda
                                    placeholder="Ingrese el numero de vivienda..." style="border-radius:10px">
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
                                    wire:model.live=direccion placeholder="Ingrese la calle y/o avenida"
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px">
                                @error('direccion')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-primary" type="button" data-toggle="collapse"
                                    style="background-color:#1E6BA2; width:60%" data-target="#collapseExample"
                                    aria-expanded="false" aria-controls="collapseExample">
                                    <i class="fa fa-street-view" aria-hidden="true"></i> Agregar ubicación
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"
                                        style="color:white">
                                        <path
                                            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </button>
                                @if ($this->ubicacion_select == 0)
                                    <span style="font-size: 18px;" class=" text-danger">No Seleccionado <span
                                            class="fa-stack fa-lg">
                                            <i class="fa fa-street-view fa-stack-1x"></i>
                                            <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                        </span></span>
                                @else
                                    <span style="font-size: 18px;" class="text-success">Seleccionado
                                        Correctamente
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-street-view fa-stack-1x"></i>

                                        </span></span>
                                @endif
                                {{-- <div class="collapse" id="collapseExample" wire:ignore>
                                        <div class="card card-body">
                                            @include('livewire.Afiliacion.beneficiarios.ubicacion_beneficiario')
                                        </div>
                                    </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="grupo_sanguineo"> TIPO DE SANGRE:
                                </label>
                                <div class="col-sm-12">
                                    <select wire:model="grupo_sanguineo" style="border-radius:10px"
                                        id="grupo_sanguineo" class="form-control">
                                        <option value selected>Seleccione un
                                            tipo.. </option>
                                        <option value="RH A(-)">RH A(-)</option>
                                        <option value="RH A(+)">RH A(+)</option>
                                        <option value="RH AB(-)">RH AB(-)</option>
                                        <option value="RH AB(+)">RH AB(+)</option>
                                        <option value="RH B(-)">RH B(-)</option>
                                        <option value="RH B(+)">RH B(+)</option>
                                        <option value="RH O(-)">RH O(-)</option>
                                        <option value="RH O(+)">RH O(+)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="alergias"> ALERGIAS: </label>
                                <textarea class="form-control" id="alergias" wire:model=alergias placeholder="Ingrese las alergias..."
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px"></textarea>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="observacion"> OBSERVACIONES: </label>
                                <textarea class="form-control" id="observacion" wire:model=observacion placeholder="Ingrese las observaciones..."
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="collapse" id="collapseExample" wire:ignore>
                    <div class="card card-body">
                        @include('livewire.Afiliacion.beneficiarios.ubicacion_beneficiario')
                    </div>
                </div>
            </div>

            @if ($con_remuneracion)
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="#2471a3" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg> <em class="ml-2" style="color:#2471a3"><b>Ingrese los datos
                                            institucionales:</b> </em></p>
                            </div>
                            <p style="background-color:#c4dff3 ;text-align:center;"> <b> DATOS INSTITUCIÓN DE
                                    TRANSFERENCIA</b></p>
                            <div class="row">
                                <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="id_institucion">INSTITUCIÓN:</label>
                                    <select wire:model="id_institucion" name="id_institucion" id="id_institucion"
                                        class="form-control select2 @error('id_institucion') is-invalid @enderror"
                                        style=" border-radius:10px;" tabindex="-1" required>
                                        <option selected="selected">Selecciona la Institucion</option>
                                        @foreach ($institucionSelect as $institucion)
                                            <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="fecha_ingreso"> FECHA DE INGRESO:</label>
                                    <input class="form-control @error('fecha_ingreso') is-invalid @enderror"
                                        type="date" id="fecha_ingreso" style=" border-radius:10px;"
                                        wire:model=fecha_ingreso>
                                    @error('fecha_ingreso')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                    <label for="cargoRemu"> CARGO: </label>
                                    <input class="form-control  @error('cargoRemu') is-invalid @enderror"
                                        id="cargoRemu" style=" border-radius:10px;"
                                        placeholder="Ingrese el cargo del beneficiario.." wire:model=cargoRemu
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('cargoRemu')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="salarioRemu"> SALARIO: </label>
                                    <input class="form-control  @error('nombre_referencia') is-invalid @enderror"
                                        type='numeric' style=" border-radius:10px;"
                                        placeholder="Ingrese el salario del beneficiario.." id="salarioRemu"
                                        wire:model=salarioRemu>
                                    @error('salarioRemu')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="numero_item">ITEM: </label>
                                    <input class="form-control" id="numero_item" style=" border-radius:10px;"
                                        placeholder="Ingrese número item.." wire:model=numero_item
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="carga_horaria">CARGA HORARIA:</label>
                                    <select wire:model="carga_horaria" name="carga_horaria" id="carga_horaria"
                                        class="form-control select2 @error('carga_horaria') is-invalid @enderror"
                                        style=" border-radius:10px;" tabindex="-1" required>
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
                                    <label for="titularidad">TITULARIDAD:</label>
                                    <select wire:model="titularidad" name="titularidad" id="titularidad"
                                        class="form-control select2 @error('titularidad') is-invalid @enderror"
                                        style=" border-radius:10px;" tabindex="-1" required>
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
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!--  parte de la foto -->
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:{{ $color_titular }} ;text-align:center; border-radius:8px ;color:white;">
                    <b> DATOS
                        TITULAR</b>
                </p>
                <div class=" row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto_titular_mostrar) ? 'data:image/jpg;base64,' . $foto_titular_mostrar : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                        style="display: flex; justify-content: center; font-size: 25px">
                        @if ($vigencia == 'VIGENTE')
                            <span style="color: green;"><b>VIGENTE</b></span>
                        @elseif(vigencia == 'CESANTE')
                            <span class="parpadeo" style="color: yellow;"><b>CESANTE</b></span>
                        @elseif($vigencia == 'NO VIGENTE')
                            <span class="parpadeo" style="color: red;"><b>NO VIGENTE</b></span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula_titular }}
                        </span>
                        <span class=" font-weight-bold h5" style="color: #236cb6; ">
                            {{ $codigoTitular }} </span>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_titutlar }}
                            {{ $titularPermanente }} </span>

                    </div>
                </div>
                @if ($tipo_titutlar == 'INSTITUCIONAL')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <span class=" font-weight-bold h5" style="color:#a50601">{{ $nombreInstitucion }}
                            </span>
                        </div>
                    </div>
                @endif
                <hr class="m-1">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            {{ $nombreCompleto }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> CARNET:</strong>
                        <p class="p-1" style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            {{ $ci }}</p>
                    </div>
                </div>
                <div class="row">
                    @if ($tipo_titutlar == 'INSTITUCIONAL')
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="cargo">CARGO:</label>
                            <p class="p-1"
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                                {{ $cargo }}</p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="salario">SALARIO:</label>
                            <p class="p-1"
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                                Bs.
                                {{ $salario }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarBen">CANCELAR</x-cancel-button>
        <x-save-button onclick="registrarBen()">GUARDAR</x-save-button>
    </div>


    <script>
        function registrarBen() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarBeneficiario');
                    document.getElementById('cargando').style.display = 'block';
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"

                    });
                    document.getElementById('cargando').style.display = 'none';
                }
            });
        }


        document.addEventListener('beneficiarioCreado', function() {
            Swal.fire({
                title: "¡Registro exitoso!",
                text: "El registro fue realizado con éxito",
                icon: "success"

            });
        });

        // Validaciones de entrara para el buscador de asuss
        document.addEventListener('ciInvalido', function() {
            Swal.fire({
                title: "¡Invalido!",
                text: "Porfavor ingresa un numero de carnet válido para realizar la busqueda",
                icon: "warning",
            });
        });

        // VALIDACIONES CON FECHAS 
        document.addEventListener('FechaNacimientoInvalidaManana', function() {
            Swal.fire({
                title: "Fecha no válida",
                text: "La fecha de nacimiento no puede ser posterior a la fecha de hoy. Por favor, selecciona una fecha válida.",
                icon: "error",
                confirmButtonText: "Entendido"
            });
        });

        // es menor a 19 años
        document.addEventListener('beneficiarioMenor19Años', function() {
            Swal.fire({
                title: "¡Es menor de 19 años!",
                text: "El beneficiario tiene MENOS de 19 años, no se puede registrar como BENEFICIARIO MAYOR A 19 AÑOS",
                icon: "error"
            });
        });
        // es mayor a 25 años
        document.addEventListener('beneficiarioMayor25Años', function() {
            Swal.fire({
                title: "¡Es mayor de 25 años!",
                text: "El beneficiario tiene MÁS de 25 años, no se puede registrar como BENEFICIARIO MAYOR A 19 AÑOS",
                icon: "error"
            });
        });
        // es mayor a 19 años
        document.addEventListener('beneficiarioMayor19años', function() {
            Swal.fire({
                title: "¡Es mayor de 19 años!",
                text: "El beneficiario tiene MÁS de 19 años, no se puede registrar como BENEFICIARIO HIJO MENOR A 19 AÑOS",
                icon: "error"
            });
        });

        // es mayor a 1 año
        document.addEventListener('beneficiarioBebe', function() {
            Swal.fire({
                title: "¡Tiene mas de 1 año de edad!",
                text: "El beneficiario tiene MÁS de 1 año de edad, no se puede registrar como BENEFICIARIO BEBE RECIEN NACIDO ",
                icon: "error"
            });
        });
        // es menor a 35 años
        document.addEventListener('padreMadreMenor35años', function() {
            Swal.fire({
                title: "No puede ser Beneficiario Padre - Madre",
                text: "El beneficiario no tiene una edad posible para ser BENEFICIARIO PADRE/ MADRE, verifica bien la edad o el tipo de asegurado ",
                icon: "error"
            });
        });



        document.addEventListener('recibirSelectDos', function() {

            setTimeout(() => {
                var select = document.querySelector('#id_zona');
                $(select).select2({
                    width: '100%',
                    placeholder: 'Selecciona la zona',
                });
                $('.select2-selection').css('height', '46px');
                $('.select2-selection__rendered').css('line-height', '46px');
                $(select).on('select2:open', function() {
                    setTimeout(function() {
                        document.querySelector('.select2-search__field').focus();
                    }, 100);
                });

                $('#zonaId').on('change', function(e) {
                    var data = $(this).val();
                    @this.set('zonaId', data);
                });

            }, 500); // Espera un poco para asegurarse de que el DOM está listo
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
    </style>
</div>
