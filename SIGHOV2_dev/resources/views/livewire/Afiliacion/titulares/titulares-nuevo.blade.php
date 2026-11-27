<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRO
                DE ASEGURADO</h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color: #c4dff3 ;text-align:center;"> <b>
                        TITULAR</b></p>
                <!--parte de la foto -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="#5499c7 " class="bi bi-upload" viewBox="0 0 16 16">
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
                        @if ($errors->has('error_message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error_message') }}
                            </div>
                        @endif
                        <input class="form-control form-control-sm @error('foto') is-invalid @enderror" type="file"
                            id="formFile" name="archivo" required wire:model="foto" accept=".jpg,.jpeg,.png">
                        @error('foto')
                            <span class="form-text text-danger">Introduzca la foto en formato jpg.</span>
                        @enderror
                    </div>
                    <br>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12" align="center">
                        <div wire:loading wire:target="foto">
                            <x-cargando-dos />
                        </div>

                        @if ($foto)
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <img src="{{ $foto->temporaryUrl() }}"
                                    style=" width: 200px; height: 200px; object-fit: cover;" class="img-thumbnail">
                            @endif
                        @endif

                    </div>
                </div>
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
                                        Digital Subido:</b> </em>
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
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div wire:loading wire:target="file_digital">
                            <x-cargando-dos />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input class="form-control form-control-sm @error('file_digital') is-invalid @enderror"
                            type="file" id="formFiledigital" name="file_digital" required wire:model="file_digital"
                            accept=".pdf">
                        @error('file_digital')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_afiliacion"> FECHA DE
                            INGRESO SSU:
                        </label>
                        <input class="form-control" id="fecha_afiliacion" wire:model=fecha_afiliacion
                            style="padding-rigth:1; border-color:white; border-radius:10px;" disabled>
                    </div>
                </div>
                <hr>
                <!--  Boton para los aportes institucionales -->

                <div>
                    <x-aportes-button wire:click="verAportesIns">VER
                        APORTES INSTITUCIONALES</x-aportes-button>
                </div>

                <br>
                <div>
                    <x-aportes-button wire:click="verAportesVol"> VER
                        APORTES VOLUNTARIOS</x-aportes-button>
                </div>

                <!--modal para VER APORTES insitutcionalex-->
                @include('livewire.Afiliacion.titulares.modalVerAportesIns')
                <!--modal para VER APORTES voluntarios-->
                @include('livewire.Afiliacion.titulares.modalVerAportesVol')
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2 " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los datos
                                personales:</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>

                <!--DATOS DE LA PERSONA-->
                @if ($idPersona == 0)
                    <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->

                            <button class="btn btn-primary" type="submit" wire:click="search_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input class="form-control " type="search" id="search"
                                        style="width: calc(100% - 20px); margin-left: 5px;"
                                        placeholder="Ingrese el C.I" wire:model="carnetBusqueda">
                                </div>
                                <div class="col-lg-6">
                                    <input class="form-control" type="search" id="complemento"
                                        style="width: calc(100% - 20px); margin-left: 5px;"
                                        oninput="this.value = this.value.toUpperCase()"
                                        placeholder=" Ingrese complemento" wire:model="complementoBusqueda">
                                </div>
                            </div>
                        </div>

                        <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                        @if ($estado_api == '')
                        @else
                            <div class="col-lg-12 " role="alert">
                                <br>
                                @if ($estado_api == 1)
                                    <div class="alert alert-success"> SE VALIDÓ
                                        CORRECTAMENTE A TRAVÉS DEL ASUSS
                                    </div>
                                @elseif ($estado_api == 2)
                                    <div class="alert alert-success"> SE VALIDÓ
                                        CORRECTAMENTE A TRAVÉS DE LA BASE
                                        DE DATOS</div>
                                @elseif ($estado_api == 3)
                                    <div class="alert alert-danger">LA PERSONA YA SE
                                        ENCUENTRA AFILIADA
                                        COMO<strong> ESTUDIANTE</strong></div>
                                @elseif ($estado_api == 4)
                                    <div class="alert alert-danger">LA PERSONA YA SE
                                        ENCUENTRA AFILIADA
                                        COMO<strong> BENEFICIARIO</strong></div>
                                @elseif ($estado_api == 5)
                                    <div class="alert alert-danger">LA PERSONA YA SE
                                        ENCUENTRA AFILIADA
                                        COMO<strong> TITULAR</strong></div>
                                @elseif ($estado_api == 0)
                                    <div class="alert alert-warning">NO SE VALIDARON LOS DATOS
                                        EN EL ASUSS,
                                        INGRESE MANUALMENTE LOS DATOS CON CUIDADO</div>
                                @elseif($estado_api == 6)
                                    <div class="alert alert-danger">LA PERSONA YA SE ENCUENTRA AFILIADA COMO
                                        <strong>TITULAR NO VIGENTE</strong> SI QUIERE REGISTRAR DE NUEVO A ESTA
                                        PERSONA DEBE IR A
                                        LAS OPCIONES DE <strong> REAFILIAR</strong> EN EL LISTADO.
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div wire:loading wire:target="search_persona">
                        <div class="spinner-grow text-warning" role="status">
                        </div>
                        <b><i>Cargando datos espere porfavor...</i></b>
                    </div>

                    @if ($estado_api == '')
                    @elseif ($estado_api == 1 || $estado_api == 2 || $estado_api == 0)
                        <!--DATOS DE LA NUEVA PERSONA-->
                        <!--REGISTRAR MANUALMENTE-->
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="p_apellido"> PRIMER APELLIDO:</label>
                                <input class="form-control" id="p_apellido" placeholder=" Apellido paterno"
                                    type="text" oninput="this.value = this.value.toUpperCase()"
                                    wire:model=ap_paterno style=" border-radius:10px;"
                                    @if ($encontrado) disabled @endif>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="s_apellido"> SEGUNDO
                                    APELLIDO:</label>
                                <input class="form-control" id="s_apellido" placeholder=" Apellido materno"
                                    type="text" oninput="this.value = this.value.toUpperCase()"
                                    wire:model=ap_materno style="border-radius:10px;"
                                    @if ($encontrado) disabled @endif>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre"> NOMBRES:</label>
                                <input class="form-control" id="nombre" placeholder=" Introduzca el nombre"
                                    type="text" oninput="this.value = this.value.toUpperCase()" wire:model=nombre
                                    style=" border-radius:10px;" @if ($encontrado) disabled @endif>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <label for="ci">C.I.:</label>
                                <input type="number" class="form-control @error('ci') is-invalid @enderror"
                                    placeholder=" Introduzca el carnet" wire:model=ci name="ci" id="ci"
                                    style=" border-radius:10px;" @if ($encontrado) disabled @endif>

                                @error('ci')
                                    @if ($message == 'validation.numeric')
                                        <span class="form-text text-danger">El valor
                                            ingresado debe ser
                                            numérico.</span>
                                    @elseif ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo
                                            es obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <label for="complemento_m">COMPL.: </label>
                                <input type="text" class="form-control" placeholder=" Complemento"
                                    wire:model.live="complemento" name="complemento_m" id="complemento_m"
                                    oninput="this.value = this.value.toUpperCase()" style=" border-radius:10px;"
                                    @if ($encontrado) disabled @endif>
                            </div>

                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="tipo_doc">TIPO C.I.:</label>
                                <select wire:model.live="tipo_doc" name="tipo_doc" id="tipo_doc"
                                    class="form-control @error('tipo_doc') is-invalid @enderror"
                                    wire:change='esExtranjeroFunction()'
                                    @if ($tipo_docSwitch) disabled @endif
                                    style="width: 100%; border-radius:10px;" tabindex="-1" required>

                                    <option value="false">NACIONAL</option>
                                    <option value="true">EXTRANJERO</option>
                                </select>
                            </div>
                            <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                                <label for="expedido_ci">EXPEDIDO:</label>
                                <select wire:model.live="expedido_ci" id="expedido_ci"
                                    @if ($expedido_ciSwitch) disabled @endif
                                    class="form-control select2 @error('expedido_ci') is-invalid @enderror"
                                    style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                    <option selected="selected" disabled>Seleccione..
                                    </option>
                                    @foreach ($this->departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="customSwitchSexo"> SEXO:</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <strong style="color:#2980b9">M</strong>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                                        <input wire:model="sexo" id="customSwitchSexo" type="checkbox"
                                            class="custom-control-input" wire:change='GenerarMatricula()'
                                            @if ($matriculaSwitch) disabled @endif>
                                        <label class="custom-control-label" for="customSwitchSexo"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <strong style="color:#e452b9">F</strong>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="fecha_nacimiento">FECHA NACIMIENTO:
                                </label>
                                <input type="date"
                                    class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                    placeholder=" Fecha Nacimiento" wire:model="fecha_nacimiento"
                                    @if ($fecha_nacimientoSwitch) disabled @endif name="fecha_nacimiento"
                                    id="fecha_nacimiento" style=" border-radius:10px;"
                                    wire:change="validarFechaNacimiento" min="1900-01-01"
                                    max="{{ now()->toDateString() }}">
                            </div>

                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="matricula"> MATRICULA:</label>
                                <input class="form-control" name="matricula" id="matricula" disabled
                                    wire:model.live="matricula"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;">
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="estado_civil"> ESTADO CIVIL:</label>
                                <select wire:model="estado_civil" id="estado_civil"
                                    class="form-control select2 @error('estado_civil') is-invalid @enderror"
                                    style="width: 100%; border-radius:10px;" tabindex="-1" required
                                    wire:change='GenerarMatricula()'>
                                    <option selected="selected">Selecciona
                                        estado civil... </option>
                                    @foreach ($id_estado_civil as $estado_civil)
                                        <option value="{{ $estado_civil->id }}">
                                            {{ $estado_civil->nombre }}
                                        </option>
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
                                @if ($tipo_doc === 'false')
                                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="dep_nacimiento">DEPARTAMENTO DE
                                            NACIMIENTO:</label>
                                        <select wire:model.live="dep_nacimiento" id="dep_nacimiento"
                                            class="form-control select2 @error('dep_nacimiento') is-invalid @enderror"
                                            style="width: 100%; border-radius:10px;" tabindex="-1" required
                                            wire:change='ObtenerProvincia()'>
                                            <option selected="selected">Seleccione el
                                                departamento de nacimiento..
                                            </option>
                                            @foreach ($this->departamento as $dep)
                                                <option value="{{ $dep->id }}">{{ $dep->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="pro_nacimiento">PROVINCIA DE
                                            NACIMIENTO:</label>
                                        <select wire:model.live="pro_nacimiento" id="pro_nacimiento"
                                            class="form-control select2 @error('pro_nacimiento') is-invalid @enderror"
                                            style="width: 100%; border-radius:10px;" tabindex="-1" required
                                            wire:change='ObtenerMunicipio()'>
                                            <option selected="selected">Seleccione la provincia
                                                de nacimiento...
                                            </option>
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
                                        <select wire:model.live="mun_nacimiento" name="mun_nacimiento"
                                            id="mun_nacimiento"
                                            class="form-control select2 @error('mun_nacimiento') is-invalid @enderror"
                                            style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                            <option selected="selected">Seleccione el municipio
                                                de nacimiento...
                                            </option>
                                            @if ($this->municipios)
                                                @foreach ($this->municipios as $mun)
                                                    <option value="{{ $mun->id }}">{{ $mun->nombre }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                @else
                                @endif
                            @endif
                        </div>
                    @endif

                    <!--  Jalando datos de la lista  -->
                @else
                    <!--JALANDO MANUALMENTE-->
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="p_apellido"> PRIMER APELLIDO:</label>
                            <input class="form-control" id="p_apellido" disabled wire:model=ap_paterno
                                style="padding-rigth:1; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="s_apellido"> SEGUNDO
                                APELLIDO:</label>
                            <input class="form-control" id="s_apellido" disabled wire:model=ap_materno
                                style="padding-rigth:1; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombres"> NOMBRES:</label>
                            <input class="form-control" id="nombres" disabled wire:model=nombre
                                style="padding-rigth:1; border-color:white; border-radius:10px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> C.I.:</label>
                            <input class="form-control" id="ci"
                                style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                wire:model=ci>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                            <label for="complemento_m">COMPL.: </label>
                            <input type="text" class="form-control" disabled placeholder=" Complemento"
                                style="padding-rigth:1; border-color:white; border-radius:10px;"
                                wire:model="complemento" name="complemento_m" id="complemento_m"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="tipo_doc">TIPO C.I.:</label>
                            <select wire:model.live="tipo_doc" name="tipo_doc" id="tipo_doc"
                                class="form-control @error('tipo_doc') is-invalid @enderror"
                                wire:change='esExtranjeroFunction()'
                                @if ($tipo_docSwitch) disabled @endif
                                style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                <option selected="selected">Seleccione el tipo de
                                    carnet...</option>
                                <option value="false">NACIONAL</option>
                                <option value="true">EXTRANJERO</option>
                            </select>
                            @error('tipo_doc')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                        <div class='col-lg-2 col-md-4 col-sm-6 col-xs-12'>
                            <label for="expedido_ci">EXPEDIDO:</label>
                            <select wire:model="expedido_ci" id="expedido_ci"
                                class="form-control select2 @error('expedido_ci') is-invalid @enderror"
                                style="width: 100%; border-radius:10px" tabindex="-1" required>
                                <option selected="selected">Seleccione..
                                </option>
                                @foreach ($this->departamento as $dep)
                                    <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
                                @endforeach
                            </select>
                            @error('expedido_ci')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="customSwitchSexo"> SEXO:</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <strong style="color:#2980b9">M</strong>
                                <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                                    <input wire:model="sexo" id="customSwitchSexo" type="checkbox"
                                        class="custom-control-input" wire:change='GenerarMatricula()'
                                        @if ($matriculaSwitch) disabled @endif>
                                    <label class="custom-control-label" for="customSwitchSexo"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <strong style="color:#e452b9">F</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_nacimiento"> FECHA NACIMIENTO:
                            </label>
                            <input class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                id="fecha_nacimiento" type="date" style="border-radius:10px"
                                wire:model="fecha_nacimiento" wire:change="validarFechaNacimiento" min="1900-01-01"
                                max="{{ now()->toDateString() }}">
                            @error('fecha_nacimiento')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula"> MATRICULA:</label>
                            <input class="form-control" name="matricula" id="matricula" disabled
                                style="padding-rigth:1; border-color:white; border-radius:10px;"
                                wire:model.live="matricula">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="estado_civil"> ESTADO CIVIL:</label>
                            <select wire:model.blur="estado_civil" id="estado_civil"
                                class="form-control select2 @error('estado_civil') is-invalid @enderror"
                                style="width: 100%; border-radius:10px" tabindex="-1" required
                                wire:change='GenerarMatricula()'>
                                <option selected="selected">Selecciona
                                    estado civil... </option>
                                @foreach ($id_estado_civil as $estado_civil)
                                    <option value="{{ $estado_civil->id }}">
                                        {{ $estado_civil->nombre }}</option>
                                @endforeach
                            </select>
                            @error('estado_civil')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    @if ($tipo_doc === 'false')
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="dep_nacimiento">DEPARTAMENTO DE NACIMIENTO:</label>
                                <select wire:model.live="dep_nacimiento" name="dep_nacimiento" id="dep_nacimiento"
                                    class="form-control select2 @error('dep_nacimiento') is-invalid @enderror"
                                    style="width: 100%; border-radius:10px" tabindex="-1" required
                                    wire:change='ObtenerProvincia()'>
                                    <option selected="selected">Seleccione el departamento de nacimiento..</option>
                                    @foreach ($this->departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('dep_nacimiento')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="pro_nacimiento">PROVINCIA DE NACIMIENTO:</label>
                                <select wire:model.live="pro_nacimiento" name="pro_nacimiento" id="pro_nacimiento"
                                    class="form-control select2 @error('pro_nacimiento') is-invalid @enderror"
                                    style="width: 100%; border-radius:10px" tabindex="-1" required
                                    wire:change='ObtenerMunicipio()'>
                                    <option selected="selected">Seleccione la provincia de nacimiento..</option>
                                    @if ($this->provincias)
                                        @foreach ($this->provincias as $pro)
                                            <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('pro_nacimiento')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="mun_nacimiento">MUNICIPIO DE NACIMIENTO:</label>
                                <select wire:model.live="mun_nacimiento" name="mun_nacimiento" id="mun_nacimiento"
                                    class="form-control select2 @error('mun_nacimiento') is-invalid @enderror"
                                    style="width: 100%;border-radius:10px " tabindex="-1" required>
                                    <option selected="selected">Seleccione el municipio de nacimiento..</option>
                                    @if ($this->municipios)
                                        @foreach ($this->municipios as $mun)
                                            <option value="{{ $mun->id }}">{{ $mun->nombre }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('mun_nacimiento')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @else
                    @endif
                @endif
            </div>
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <br>
                <!-- DATOS REFERENCIALES -->
                {{-- @if ($titularExiste !== '' && $estado_api !== '')
                @if ($titularExiste === 0) --}}
                @if ($estado_api === null)
                @else
                    @if ($estado_api === 0 || $estado_api === 1 || $estado_api === 2)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5" style="color:#2471a3 ">
                                    <strong>Ingrese los datos referenciales:</strong>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </em>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="telefono"> TELEFONO: </label>
                                        <input class="form-control @error('telefono') is-invalid @enderror"
                                            name="telefono" placeholder="Ingrese el numero de telefono"
                                            id="telefono" style="border-radius:10px" wire:model.live="telefono">
                                        @error('telefono')
                                            <div class="text-danger">
                                                <b>{{ $message }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="correo"> CORREO: </label>
                                        <input class="form-control" name="correo" id="correo"
                                            placeholder="Ingrese el correo " wire:model.live="correo"
                                            style="border-radius:10px">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombre_referencia"> NOMBRE DE
                                            REFERENCIA:</label>
                                        <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                            name="nombre_referencia" id="nombre_referencia"
                                            style="border-radius:10px" placeholder="Ingrese el nombre de referencia"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.live="nombre_referencia">
                                        @error('nombre_referencia')
                                            <div class="text-danger">
                                                <b>{{ $message }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="telefono_referencia"> TELEFONO
                                            DE
                                            REFERENCIA: </label>
                                        <input class="form-control @error('telefono_referencia') is-invalid @enderror"
                                            name="telefono_referencia" id="telefono_referencia"
                                            placeholder="Ingrese el telefono de referencia" style="border-radius:10px"
                                            wire:model.live="telefono_referencia">
                                        @error('telefono_referencia')
                                            <div class="text-danger">
                                                <b>{{ $message }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12' wire:ignore>
                                        <label for="zona">ZONA:</label>
                                        <select wire:model.live="zona" id="zona"
                                            class="form-control  @error('zona') is-invalid @enderror select2"
                                            style="width: 100%; border-radius:10px" tabindex="-1">
                                            <option selected="selected">Selecciona
                                                la zona
                                            </option>
                                            @foreach ($zonasSelect as $zonita)
                                                <option value="{{ $zonita->id }}">{{ $zonita->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('zona')
                                            <div class="text-danger">
                                                <b>{{ $message }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nro_vivienda"> NRO
                                            VIVIENDA:</label>
                                        <input class="form-control @error('nro_vivienda') is-invalid @enderror"
                                            name="nro_vivienda" placeholder="Ingrese el nro de vivienda"
                                            style="border-radius:10px" id="nro_vivienda"
                                            wire:model.live="nro_vivienda">
                                        @error('nro_vivienda')
                                            <div class="text-danger">
                                                <b>{{ $message }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="direccion"> DIRECCION:</label>
                                        <input class="form-control @error('direccion') is-invalid @enderror"
                                            id="direccion" placeholder="Ingrese la calle y/o avenida"
                                            oninput="this.value = this.value.toUpperCase()" style="border-radius:10px"
                                            wire:model.live="direccion">
                                        @error('direccion')
                                            <div class="text-danger">
                                                <b>{{ $message }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                            style="background-color:#1E6BA2; width:70%" data-target="#collapseExample"
                                            aria-expanded="false" aria-controls="collapseExample"
                                            style="background-color:#1E6BA2">
                                            <i class="fa fa-street-view" aria-hidden="true"></i> Agregar
                                            ubicación
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"
                                                style="color:white">
                                                <path
                                                    d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                            </svg>
                                        </button>
                                        @if ($ubicacion_select == 0)
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">

                                <div class="row">
                                    <div class="col-12" style="margin-bottom: 10px;">
                                        <strong>TIPO DE ASEGURADO:</strong>
                                        <div style="display: flex; align-items: center; flex-wrap: wrap;">
                                            <strong class="text-red"
                                                style="margin-right: 10px; white-space: nowrap;">INSTITUCIONAL</strong>
                                            <div
                                                class="custom-control custom-switch custom-switch-off-red custom-switch-on-blue mx-2">
                                                <input wire:model="tipo_asegurado" id="customSwitch3" type="checkbox"
                                                    class="custom-control-input" wire:change='OcultarAgregar()'
                                                    {{ $tipo_asegurado == true ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="customSwitch3"></label>
                                            </div>
                                            <strong class="text-blue"
                                                style=" white-space: nowrap;">VOLUNTARIO</strong>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="grupo_sanguineo"> TIPO DE SANGRE:
                                        </label>
                                        <div class="col-sm-12">
                                            <select wire:model="grupo_sanguineo" style="border-radius:10px"
                                                id="grupo_sanguineo" class="select2 form-control">
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
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="alergias"> ALERGIAS: </label>
                                        <textarea class="form-control" name="alergias" placeholder="Ingrese las alergias"
                                            oninput="this.value = this.value.toUpperCase()" id="alergias" style="border-radius:10px" wire:model=alergias> </textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="observacion"> OBSERVACIONES:
                                        </label>
                                        <textarea class="form-control" name="observacion" id="observacion" placeholder="Ingrese las observaciones"
                                            oninput="this.value = this.value.toUpperCase()" style="border-radius:10px" wire:model=observacion></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12"
                        @if ($estado_api === 3 || $estado_api === 4 || $estado_api === 5) hidden @endif>
                        <br>
                        <div class="collapse" id="collapseExample" wire:ignore>
                            <div>
                                @include('livewire.Afiliacion.titulares.ubicacion_titulares')
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @elseif ($titularExiste === 1)
                @endif
                @else
                @endif --}}
            </div>
            {{-- AQUI TERMINA DONDE SE LLAMA AL MAPITA SIUUUUUUUUU --}}
            @if ($estado_api == '')
            @else
                @if ($estado_api == 1 || $estado_api == 2 || $estado_api == 0)
                    @if ($tipo_asegurado == 0)
                        <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  text-center">
                                    <br>
                                    <div class="d-flex align-items-center flex-nowrap">
                                        <strong class="mr-3">¿ASEGURADO PERMANENTE?: </strong>
                                        <strong class="text-blue">EVENTUAL</strong>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-blue custom-switch-on-red mx-2">
                                            <input wire:model="permanente" id="customSwitchpermanente"
                                                type="checkbox" class="custom-control-input"
                                                {{ $permanente == true ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="customSwitchpermanente"></label>
                                        </div>
                                        <strong class="text-red"> PERMANENTE</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if ($esRentista == true)
                                        <label for="tipo_rentista" style="color:#0b61b7">TIPO DE RENTISTA:</label>
                                        <select wire:model="tipo_rentista" id="tipo_rentista" class="form-control"
                                            style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                            <option selected="selected">Seleccione el tipo de
                                                rentista..
                                            </option>
                                            <option value="TITULAR-RENTISTA">TITULAR-RENTISTA</option>
                                            <option value="TITULAR-SORTEO">TITULAR-SORTEO</option>
                                            <option value="RENTISTA-INVALIDEZ">RENTISTA-INVALIDEZ</option>
                                            <option value="VIUDEDAD">VIUDEDAD</option>
                                            <option value="ORFANDAD">ORFANDAD</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <div>
                                <!-- Contenedor para el texto y el ícono -->
                                <div class="row">
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="#2471a3" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        <em class="ml-1 h5" style="color:#2471a3; margin-left: 2px;"><b>Ingrese
                                                los datos institucionales:</b></em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </div>
                                    <!-- Botón que aparece dependiendo de la condición -->
                                    {{-- @if ($tipo_asegurado == 0) --}}
                                    <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                        <x-agregar-button wire:click="abrirModalAddInstitucion" {{--
                                onclick="modalAddInstitucion()" --}}
                                            title="AGREGAR INSTITUCIÓN">INSTITUCIÓN</x-agregar-button>
                                    </div>
                                </div>
                                <div>
                                    <!--modal para AGREGAR INSTITUCION-->
                                    @include('livewire.Afiliacion.titulares.modalAddInstituciones')
                                </div>
                                <div wire:loading wire:target="abrirModalAddInstitucion">
                                    <x-cargando-dos />
                                </div>
                                {{-- @endif --}}
                            </div>

                            @foreach ($instituciones as $index => $institucion)
                                <hr>
                                <div class="row">
                                    <input type="hidden" value="{{ $institucion['id_institucion'] }}">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label class="h5" for="nombre_institucion" style="color:#1E6BA2">
                                            INSTITUCIÓN
                                            {{ $index + 1 }}:
                                        </label>
                                        <input class="form-control" name="nombre_institucion" id="nombre_institucion"
                                            style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                            value="{{ $institucion['institucion'] }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="codigo_titular_ver"> COD TIT.:</label>
                                        <input class="form-control" id="codigo_titular_ver" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['codigo_titular_ver'] }}">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="codigo">
                                            COD BEN.:</label>
                                        <input class="form-control" name="codigo" id="codigo" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['codigo_beneficiario_ver'] }}">
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="tipo_institucion"> TIPO INSTITUCIÓN:
                                        </label>
                                        <input class="form-control" name="tipo_institucion" id="tipo_institucion"
                                            style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                            value="{{ $institucion['tipo_institucion'] }}">
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="cargo"> CARGO:</label>
                                        <input class="form-control" name="cargo" id="cargo" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['cargo'] }}">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="fecha_ingreso"> FECHA INGRESO:</label>
                                        <input class="form-control" name="fecha_ingreso" id="fecha_ingreso" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['fecha_ingreso'] }}">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="salario"> SALARIO: </label>
                                        <input class="form-control" name="salario" id="salario" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['salario'] }}">
                                    </div>

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="item"> ITEM:</label>
                                        <input class="form-control" name="item" id="item" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['item'] }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="carga_horaria"> CARGA HORARIA: </label>
                                        <input class="form-control" name="carga_horaria" id="carga_horaria" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['carga_horaria'] }}">
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="titularidad"> TITULARIDAD:</label>
                                        <input class="form-control" name="titularidad" id="titularidad" disabled
                                            style="padding-rigth:1; border-color:white; border-radius:10px;"
                                            value="{{ $institucion['titularidad'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @elseif($titularExiste == 1)
                @endif
            @endif
        </div>
    </div>

    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarTitular">
            CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarTitular()">GUARDAR</x-save-button>
    </div>
    <style>
        .custom-control .inputSexo {

            -webkit-appearance: none;
            position: relative;
            display: block;
            width: 80px;
            /* Ancho más reducido */
            height: 40px;
            /* Alto más reducido */
            margin: 0px auto;
            /* Márgenes ajustados */
            border: 3px solid #f7f7f5;
            /* Grosor del borde reducido */
            border-radius: 10px;
            /* Radio de borde reducido */
            cursor: pointer;
            background: white;
        }

        .custom-control .inputSexo:after,
        .custom-control .inputSexo:before {

            position: absolute;
            top: 2px;
            /* Posición ajustada */
            width: 30px;
            /* Ancho más reducido */
            height: 30px;
            /* Alto más reducido */
            border: 3px solid;
            /* Grosor del borde reducido */
            border-radius: 8px;
            /* Radio de borde reducido */
            text-align: center;
            line-height: 24px;
            /* Ajuste de línea para centrado */
            font-size: 20px;
            /* Tamaño de fuente reducido */
            transition: all 0.3s ease-out;
        }

        .custom-control .inputSexo:before {
            content: "M";
            /* Cambiado a "M" */
            color: #0d47a1;
            /* Color azul oscuro */
            border-color: #1e88e5;
            /* Borde azul (tono más claro) */
            background: #bbdefb;
            /* Fondo azul claro */
            left: 2px;
            /* Posición ajustada a la izquierda */
        }

        .custom-control .inputSexo:after {
            left: auto;
            right: 2px;
            /* Posición ajustada */
            content: "F";
            /* Cambiado a "F" */
            border-color: #ff4081;
            /* Borde rosado */
            color: #d5006d;
            /* Color rosado */
            background: #ffddcc;
            /* Fondo claro */
            opacity: 0.3;
            /* Menos opaco cuando seleccionado */
        }

        .custom-control .inputSexo:checked:before {
            opacity: 0.3;
            /* Menos opaco cuando seleccionado */
        }

        .custom-control .inputSexo:checked:after {
            opacity: 1;
            /* Totalmente opaco cuando está seleccionado */
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
        var select = document.querySelector('#zona');
        $(select).select2();
        $(select).on('select2:open', function() {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#zona').on('change', function(e) {
            var data = $(this).val();
            @this.set('zona', data);
        });
    });

    document.addEventListener('recibirSelectDos', function() {
        setTimeout(() => {
            var select = document.querySelector('#zona');
            $(select).select2({
                width: '100%',
                placeholder: 'Selecciona la zona',
            });

            $(select).on('select2:open', function() {
                setTimeout(function() {
                    document.querySelector('.select2-search__field').focus();
                }, 100);
            });

            $('#zona').on('change', function(e) {
                var data = $(this).val();
                @this.set('zona', data);
            });

        }, 500); // Espera un poco para asegurarse de que el DOM está listo
    });

    window.addEventListener('modalAddInstitucion', event => {
        //alert('asdasdasdasd');
        $('#addInstitucion').modal('show');
    });

    function guardarTitular() {
        Swal.fire({
            title: '¿Guardar titular?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cargando').style.display = 'block';
                Livewire.dispatch('GuardarInstitucion');

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

    document.addEventListener('TitularRegistradoExito', function() {
        Swal.fire({
            title: "¡Registro Exitoso!",
            text: "Se guardó el registro exitosamente",
            icon: "success"

        });
    });

    document.addEventListener('titularCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });

    document.addEventListener('institucionNoIngresada', function() {
        Swal.fire({
            title: "¡Agregue Institución!",
            text: "No olvide ingresar los datos institucionales",
            icon: "warning"
        });
    });

    function addInstitucion() {
        //alert('sadfsdfsdf');
        Swal.fire({
            title: '¿Agregar Institución?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('AddInstitucionNuevo');
                $('#addInstitucion').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                $('#addInstitucion').modal('hide');

            }
        });
    }

    document.addEventListener('errorAddInstNuevo', function() {
        Swal.fire({
            title: "INGRESA TODOS LOS DATOS",
            text: "Completa todos los datos necesarios",
            icon: "error",
            iconColor: "red"
        });
    });
    document.addEventListener('errorNuevo', function() {
        Swal.fire({
            title: "Ocurrio un error!",
            text: "Vuelve a intentarlo, si el error ocurre de nuevo comunicate con la unidad de sistemas",
            icon: "error",
            iconColor: "red"
        });
    });
    document.addEventListener('errorIdInstitucionDuplicado', function() {
        Swal.fire({
            title: "Institución ya registrada",
            text: "La institucion ya fue registrada, registra otra institución",
            icon: "error",
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
    // VALIDACIONES para que no afilie a alguien menor de edad en otra institucion 
    document.addEventListener('menorNoGestora', function() {
        Swal.fire({
            title: "Verifica la edad o la institución!",
            text: "El titular seleccionado es MENOR DE EDAD, no corresponde a la institución seleccionada. Recarga la página para intentar nuevamente",
            icon: "error",
            confirmButtonText: "Entendido"
        });
    });
</script>
