<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                REGISTRO DE CONVENIO DEL INTERIOR
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="atras" wire:loading.attr="disabled" wire:target="atras">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="atras">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <!-- VISTA DE REGISTRO NUEVO ASEGURADO -->
        <!-- vista para el asegurado -->
        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
            <div class="card card-comun p-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="cabecera text-center">
                            <b>AGREGAR ASEGURADO</b>
                        </div>
                    </div>
                </div>
                <div>
                    <p style="color:#2471a3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2"><b>Introduzca los datos solicitados</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="display: flex; align-items: center;">
                        <!-- Aquí va el botón con icono de lupa -->
                        <button class="btn btn-primary d-flex align-items-center justify-content-center" type="submit"
                            wire:click="search_persona">
                            <div wire:loading wire:target="search_persona"> <x-cargando-dos /> </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>
                        <input class=" form-control input-comun " type="search" id="search"
                            style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Carnet"
                            wire:model="carnetBusqueda">
                        <input class=" form-control input-comun flex-grow-1" type="search" id="complemento"
                            style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Complemento"
                            wire:model="complementoBusqueda">
                    </div>

                    <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                    @if ($estadoBusqueda == '')
                    @else
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12" role="alert">
                            <br>
                            @if ($estadoBusqueda == 1)
                                <div class="alert alert-success"> <em>Se valido correctamente!</em></div>
                            @elseif($estadoBusqueda == 2)
                                <div class="alert alert-success"> <em>Se valido correctamente! (Existe en la
                                        BD)</em></div>
                            @elseif($estadoBusqueda == 5)
                                <div class="alert alert-danger">Los datos introducidos pertenecen a un afiliado
                                    vigente. Verifique e intente nuevamente</div>
                            @else
                                <div class="alert alert-danger">No se encontró ningún registro!</div>
                            @endif
                        </div>
                    @endif
                </div>

                @if ($estadoBusqueda === '' || $estadoBusqueda === null || $estadoBusqueda === 5)
                @else
                    <p style="color:#2471a3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2 h5"><b>Debe completar los siguientes campos.</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                    <div class=" d-flex flex-column align-items-center">
                        <!--  guardar la foto del asegurado en base 64 -->
                        <!-- <img src="" class="img-thumbnail" width="200" height="200" alt="FTP"> -->
                        @if ($foto_asegurado)
                            @if (Str::startsWith($foto_asegurado->getMimeType(), 'image/'))
                                <img src="{{ $foto_asegurado->temporaryUrl() }}"
                                    style=" box-shadow: 1 0 10px 5px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; object-fit: cover;"
                                    class="img-thumbnail">
                            @endif
                        @endif
                        <label for="formFile" class="form-label" style='color:#0D47A1 ;'>SELECCIONE LA FOTO:
                        </label>
                        <input class=" form-control input-comun @error('foto_asegurado') is-invalid @enderror"
                            type="file" id="formFile" name="archivo" required wire:model="foto_asegurado"
                            accept=".jpg,.jpeg,.png">
                        @error('foto_asegurado')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                        <div wire:loading wire:target="foto_asegurado"> <x-cargando-dos /> </div>

                    </div>
                    <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                    <br>

                    @if ($estadoBusqueda == 0 || $estadoBusqueda == 1 || $estadoBusqueda == 2)

                        <!--DATOS PERSONALES-->

                        <div class="row">
                            <div class='col-lg-5 col-md-4 col-sm-4 col-6'>
                                <label for="ci">CARNET</label>
                                <input type="number"
                                    class=" form-control input-comun @error('ci') is-invalid @enderror"
                                    placeholder="Cédula de Identidad" wire:model.live="ci" name="ci" id="ci"
                                    @if ($personaExiste) disabled @endif>
                                @error('ci')
                                    <div class="text-danger">
                                        <b>El campo carnet es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-3 col-md-3 col-sm-4 col-6'>
                                <label for="complemento">COMP. </label>
                                <input type="text" class=" form-control input-comun " placeholder="Complemento"
                                    wire:model="complemento" name="complemento" id="complemento"
                                    oninput="this.value = this.value.toUpperCase()"
                                    @if ($personaExiste) disabled @endif>
                            </div>
                            <div class='col-lg-4 col-md-5 col-sm-4 col-12'>
                                <label for="es_extranjero">TIPO DOC.</label>
                                <select wire:model="es_extranjero" name="es_extranjero" id="es_extranjero"
                                    @if ($extranjero_switch) disabled @endif
                                    class=" form-control input-comun select2 @error('es_extranjero') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Seleccione.. </option>
                                    <option value="false">NACIONAL </option>
                                    <option value="true">EXTRANJERO
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-12 col-md-4 col-sm-12 col-12">
                                <label for="nombre"> NOMBRES</label>
                                <input class=" form-control input-comun @error('nombre') is-invalid @enderror"
                                    id="nombre" placeholder=" Introduzca el nombre" type="text"
                                    wire:model.live="nombre" wire:change="GenerarMatricula"
                                    oninput="this.value = this.value.toUpperCase()"
                                    @if ($personaExiste) disabled @endif>
                                @error('nombre')
                                    <div class="text-danger">
                                        <b>El campo nombre es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-4 col-sm-12 col-12">
                                <label for="p_apellido"> AP. PATERNO</label>
                                <input class=" form-control input-comun @error('p_apellido') is-invalid @enderror"
                                    id="p_apellido" placeholder="Primer apellido" type="text"
                                    wire:model.live=p_apellido wire:change="GenerarMatricula"
                                    oninput="this.value = this.value.toUpperCase()"
                                    @if ($personaExiste) disabled @endif>
                                @error('p_apellido')
                                    <div class="text-danger">
                                        <b>El campo primer apellido es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-4 col-sm-12 col-12">
                                <label for="s_apellido"> AP. MATERNO</label>
                                <input class=" form-control input-comun @error('s_apellido') is-invalid @enderror"
                                    id="s_apellido" placeholder="Segundo Apellido" type="text"
                                    wire:model=s_apellido wire:change="GenerarMatricula"
                                    oninput="this.value = this.value.toUpperCase()"
                                    @if ($personaExiste) disabled @endif>
                                @error('p_apellido')
                                    <div class="text-danger">
                                        <b>El campo segundo apellido es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-12 col-md-3 col-sm-4 col-5">
                                <label for="fecha_nacimiento">FECHA NACIMIENTO </label>
                                <input type="date"
                                    class=" form-control input-comun @error('fecha_nacimiento') is-invalid @enderror"
                                    placeholder=" Fecha Nacimiento" wire:model.live="fecha_nacimiento"
                                    name="fecha_nacimiento" id="fecha_nacimiento"
                                    @if ($fecha_nacimiento_switch) disabled @endif wire:change="GenerarMatricula">
                                @error('fecha_nacimiento')
                                    <div class="text-danger">
                                        <b>El campo fecha de nacimiento es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-5 col-sm-8 col-7">
                                <label for="id_estado_civil"> ESTADO CIVIL</label>
                                <select wire:model.live="id_estado_civil" name="id_estado_civil"
                                    class=" form-control input-comun @error('id_estado_civil') is-invalid @enderror"
                                    tabindex="-1" required @if ($estado_civil_switch) disabled @endif
                                    wire:change="GenerarMatricula">
                                    <option selected="selected">Selecciona estado civil </option>
                                    @foreach ($estadoCivil as $estCivil)
                                        <option value="{{ $estCivil->id }}">{{ $estCivil->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_estado_civil')
                                    <div class="text-danger">
                                        <b>El campo estado civil es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-6 col-6">
                                <label for="sexo"> SEXO</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <label class="text-blue">M</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-blue custom-switch-on-pink">
                                        <input wire:model="sexo" name="sexo" type="checkbox"
                                            class="custom-control-input" id="customSwitch2"
                                            wire:change='GenerarMatricula'
                                            @if ($matricula_switch) disabled @endif>
                                        <label class="custom-control-label" for="customSwitch2"
                                            style="margin-left: 1.5rem; margin-right: 0.5rem;"></label>
                                    </div>
                                    <label class="text-pink">F</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-6 col-6">
                                <label for="matricula"> MATRICULA</label>
                                <input class=" form-control input-comun @error('matricula') is-invalid @enderror"
                                    id="matricula" wire:model="matricula" disabled style=" height: 30px;">
                                @error('fecha_nacimiento')
                                    <div class="text-danger">
                                        <b>El campo matricula es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-12 col-md-8 col-sm-12 col-12'>
                                <label for="id_tipo_asegurado">TIPO ASEGURADO</label>
                                <select wire:model.live="id_tipo_asegurado" id="id_tipo_asegurado"
                                    class=" form-control input-comun @error('id_tipo_asegurado') is-invalid @enderror"
                                    tabindex="-1" required>
                                    <option selected="">Seleccione el tipo de asegurado </option>
                                    <option value="9">TITULAR DEL INTERIOR</option>
                                    <option value="10">BENEFICIARIO DEL INTERIOR</option>
                                </select>
                                @error('id_tipo_asegurado')
                                    <div class="text-danger">
                                        <b>El campo tipo de asegurado es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- vista para el registro-->

        {{-- @if ($mostrarFormulario) --}}
        <div class="col-lg-8 col-md-12 col-sm-12 col-12" @if (!$mostrarFormulario) hidden @endif>
            <div class="card card-comun">
                <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>NUEVO CONVENIO DEL INTERIOR</b></p>
                </div> -->
                <div class="card-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2 h5"><b>Introduzca todos los datos a continuación.</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-3 col-sm-4 col-6">
                            <label for="nro_hoja_de_ruta"> NRO. HOJA DE RUTA:</label>
                            <input class=" form-control input-comun @error('nro_hoja_de_ruta') is-invalid @enderror"
                                id="nro_hoja_de_ruta" wire:model.live=nro_hoja_de_ruta
                                placeholder='Introduzca el Nro de Hoja de Ruta'
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nro_hoja_de_ruta')
                                <div class="text-danger">
                                    <b>El campo nro de hoja de ruta es necesario</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-4 col-6">
                            <label for="fecha_solicitud_hr"> FECHA SOLICITUD:</label>
                            <input class=" form-control input-comun @error('fecha_solicitud_hr') is-invalid @enderror"
                                type='date' id="fecha_solicitud_hr" wire:model.live=fecha_solicitud_hr
                                placeholder='Introduzca la fecha de la solicitud'>
                            @error('fecha_solicitud_hr')
                                <div class="text-danger">
                                    <b>El campo fecha de solicitud es obligatorio</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-4 col-6">
                            <label for="cod_cite_int"> CÓDIGO CITE INTERIOR:</label>
                            <input class=" form-control input-comun @error('cod_cite_int') is-invalid @enderror"
                                id="cod_cite_int" wire:model.live=cod_cite_int
                                placeholder='Introduzca el codigo del documento recibido'
                                oninput="this.value = this.value.toUpperCase()">
                            @error('cod_cite_int')
                                <div class="text-danger">
                                    <b>El campo codigo cite interior es necesario</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-3 col-6">
                            <label for="cod_interior_m"> CÓDIGO INTERIOR:</label>
                            <input class=" form-control input-comun " id="cod_interior_m"
                                value="{{ $cod_interior_m }}" disabled>

                            <br>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-9 col-12">
                            <label for="cod_interior_m"> SEGURO DE ORIGEN:</label>
                            <select wire:model.live="id_seguro_interior" id="id_seguro_interior"
                                class=" form-control input-comun select2 @error('id_seguro_interior') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required>
                                <option selected="selected">Selecciona el Seguro
                                    de Origen
                                </option>
                                @foreach ($seguros as $seguro)
                                    <option value="{{ $seguro->id }}">{{ $seguro->nombre_seguro }}</option>
                                @endforeach
                            </select>
                            @error('id_seguro_interior')
                                <div class="text-danger">
                                    <b>El campo seguro de origen es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-9 col-12">
                            <b style="text-align: center">VALIDEZ DE LA SOLICITUD:</b>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                                    <b>DESDE: </b>
                                    <input
                                        class=" form-control input-comun @error('fecha_inicio') is-invalid @enderror"
                                        id="fecha_inicio" type='date' wire:model=fecha_inicio
                                        wire:change="calcularDiasVigencia">
                                    @error('fecha_inicio')
                                        <div class="text-danger">
                                            <b>El campo fecha de inicio es necesario</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 ">
                                    <b>HASTA:</b>
                                    <input class=" form-control input-comun  @error('fecha_fin') is-invalid @enderror"
                                        id="fecha_fin" type='date' wire:model=fecha_fin min="{{ $fecha_inicio }}"
                                        wire:change="calcularDiasVigencia">
                                    @error('fecha_fin')
                                        <div class="text-danger">
                                            <b>El campo fecha fin es obligatorio</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-3 col-12">
                            <div style="height: 0.8rem;"></div>
                            <label for="diasVigencia"> DÍAS DE VIGENCIA:</label>
                            <input class=" form-control input-comun" id="diasVigencia" type="number"
                                wire:model="diasVigencia" disabled>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6" wire:ignore>
                            <label for="especialidades">ESPECIALIDADES A
                                SOLICITAR:</label>
                            <select wire:model.live="especialidades" id="especialidades"
                                class=" form-control input-comun select2" style="width: 100%" multiple="multiple"
                                tabindex="-1" required>
                                @foreach ($especialidadesSelect as $especialidad)
                                    <option value="{{ $especialidad->id }}">{{ $especialidad->especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6" wire:ignore>
                            <label for="servicios">SERVICIOS A
                                SOLICITAR:</label>
                            <select wire:model.live="servicios" id="servicios"
                                class=" form-control input-comun select2 @error('servicios') is-invalid @enderror"
                                style="width: 100%;" multiple="multiple" tabindex="-1" required>

                                @foreach ($serviciosSelect as $servicio)
                                    <option value="{{ $servicio->id }}">{{ $servicio->servicio }}</option>
                                @endforeach
                            </select>
                            @error('servicios')
                                <div class="text-danger">
                                    <b>El campo servicios es obligatorio.</b>
                                </div>
                            @enderror
                        </div>


                    </div>


                    <div class="row mt-4">
                        <div class="col-lg-2 col-md-4 col-sm-4 col-4">
                            <div class="cabecera">
                                <b>FARMACIA:</b>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-4 col-sm-6 col-12 d-flex  card-borde">
                            <label for="medicamentos_liname"> MEDICAMENTOS LINAME:</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <label class="text-danger mr-1">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-green">
                                    <input wire:model="medicamentos_liname" name="medicamentos_liname"
                                        type="checkbox" class="custom-control-input" id="medicamentos_liname">
                                    <label class="custom-control-label" for="medicamentos_liname"></label>
                                </div>
                                <label class=" text-success">SI</label>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-4 col-sm-6 col-12 d-flex card-borde">
                            <label for="medicamentos_extraliname"> MEDICAMENTOS EXTRALINAME:</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <label class="mr-1 text-danger">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-green">
                                    <input wire:model="medicamentos_extraliname" name="medicamentos_extraliname"
                                        type="checkbox" class="custom-control-input" id="medicamentos_extraliname">
                                    <label class="custom-control-label" for="medicamentos_extraliname"></label>
                                </div>
                                <label class=" text-success">SI</label>
                            </div>
                        </div>

                        {{-- @if ($id_tipo_asegurado === '' || $id_tipo_asegurado === 9 || $titularYaSeleccionado === true || $id_tipo_asegurado === 8 || $id_tipo_asegurado === 7 || $id_tipo_asegurado === 6 || $id_tipo_asegurado === 5 || $id_tipo_asegurado === 4 || $id_tipo_asegurado === 3 || $id_tipo_asegurado === 2 || $id_tipo_asegurado === 1 || $id_tipo_asegurado === 11 || $id_tipo_asegurado === null)
                        @else
                                <div class="col-lg-6 col-md-12 col-sm-12 col-12"
                                style="display: flex; justify-content: center;">
                                <x-agregar-button wire:click="abrirTablaTitularesInterior"> REGISTRAR TITULAR DEL
                                    INTERIOR
                                </x-agregar-button>
                                @include('livewire.Vigencia.convenios.interior.modalRelacionarTitular')
                            </div>
                        @endif --}}

                        {{-- @if ($titularYaSeleccionado == true)
                            <strong style="color: green;">TITULAR SELECCIONADO EXITOSAMENTE</strong>
                        @endif --}}
                    </div>

                    {{-- @if ($titularYaSeleccionado == true)
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#e5eff9  ; color:#1e5886; ">
                                        <b>TITULAR SELECCIONADO</b>
                                    </div>
                                    <div class="card-body">
                                        <div class='row'>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr align="center"
                                                        style="background-color:#2471a3 ; color:white;">
                                                        <th scope="col">#</th>
                                                        <th scope="col">NOMBRE COMPLETO</th>
                                                        <th scope="col">MATRICULA</th>
                                                        <th scope="col">CARNET</th>
                                                        <th scope="col">PARENTESCO</th>
                                                        <th scope="col">ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="padding: 15px;">
                                                        <td align="center" style="padding: 4px;">
                                                            1</td>
                                                        <td align="center" style="padding: 4px;">
                                                            {{ $nombreCompletoTitularInterior }}
                                                        </td>
                                                        <td align="center" style="padding: 4px;">
                                                            {{ $matriculaTitularInterior }}</td>
                                                        <td align="center" style="padding: 4px;">
                                                            {{ $carnetTitularInterior }}
                                                        </td>
                                                        <td align="center" style="padding: 4px;">
                                                            {{ $parentescoTitularInterior }}</td>

                                                        <td align="center" style="padding: 4px;">
                                                            <div class="row">
                                                                <!--    Boton Agregar o Quitar -->
                                                                <div class="col-sm-12">
                                                                    <x-cancel-button
                                                                        onclick="quitarTitularInterior('{{ $nombreCompletoTitularInterior }}', '{{ $id_persona_titular }}')"></x-cancel-button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif --}}


                    <div class="row mt-2">
                        {{-- <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <label for="grupo_sanguineo"> GRUPO SANGUINEO:</label>
                            <input class=" form-control input-comun" id="grupo_sanguineo" wire:model="grupo_sanguineo"
                                placeholder='Introduzca el codigo del documento recibido'
                                oninput="this.value = this.value.toUpperCase()">
                            <br>
                        </div> --}}

                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <label for="grupo_sanguineo"> GRUPO SANGUINEO: </label>
                            <div class="col-sm-12">
                                <select wire:model="grupo_sanguineo" id="grupo_sanguineo"
                                    class=" form-control input-comun">
                                    <option value="" selected>Seleccione un tipo.. </option>
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
                            <br>
                        </div>

                        <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                            <label for="alergias"> ALERGIAS:</label>
                            <textarea class=" form-control input-comun" id="alergias" wire:model="alergias"
                                placeholder='Introduzca el codigo del documento recibido'></textarea>

                            <br>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                            <label for="telefono"> TELÉFONO:</label>
                            <input class=" form-control input-comun" id="telefono" wire:model="telefono"
                                placeholder='Introduzca el número de teléfono' type="number">
                            <br>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <label for="datos_referencia"> DATO DE REFERENCIA:</label>
                            <input class=" form-control input-comun" id="datos_referencia"
                                wire:model="datos_referencia" placeholder='Introduzca el nombre o dato de referencia'
                                oninput="this.value = this.value.toUpperCase()">
                            <br>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                            <label for="telefono_referencia"> TELEFONO DE REFERENCIA:</label>
                            <input class=" form-control input-comun" id="telefono_referencia"
                                wire:model="telefono_referencia" placeholder='Introduzca el numero de telefono'
                                type="number">
                            <br>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <label for="direccion_referencia"> DIRECCIÓN DE REFERENCIA DEL ASEGURADO DEL
                                INTERIOR:</label>
                            <input class=" form-control input-comun" id="direccion_referencia"
                                wire:model="direccion_referencia" placeholder='Introduzca la dirección de referencia'>
                            <br>
                        </div>
                    </div>



                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12" wire:ignore>
                            <label for="otras_limitaciones" style="display: block; text-align: left;">OTRAS
                                LIMITACIONES:</label>
                            <!--<textarea class=" form-control input-comun" id="otras_limitaciones" wire:model='otras_limitaciones'
                                class="swal2-input" rows='4' oninput="this.value = this.value.toUpperCase()"></textarea>-->
                            <!-- Libreria Quill para docs -->
                            <div id="otras-limitaciones" style="height: 200px;">
                                <!-- <p>introduzca texto</p> -->
                            </div>



                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12" wire:ignore>
                            <label for="observacion" style="display: block; text-align: left;">OBSERVACIONES:</label>
                            <!-- <textarea class=" form-control input-comun" id="observacion" wire:model='observacion' class="swal2-input"
                                rows='4' oninput="this.value = this.value.toUpperCase()"></textarea> -->

                            <div id="observaciones" style="height: 200px;">
                                <!-- <p>introduzca texto</p> -->
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
        {{-- @endif --}}
    </div>

    <div id="cargando-convenio-interior" style="display: none;">
        <x-cargando-dos />
    </div>

    {{-- @if ($mostrarFormulario) --}}
    <div class="modal-footer" @if (!$mostrarFormulario) hidden @endif>
        <x-cancel-button wire:click="cancelarAutorizacion"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarAutorizacion()">GUARDAR</x-save-button>
        <div wire:loading wire:target="registrarConvenioInterior"> {{-- <x-cargando-dos /> --}} </div>
    </div>
    {{-- @endif --}}





    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        let quill;
        let quill2;
        // Aqui se busca el carnet
        setTimeout(function() { // Llamamos a la función para crear el nuevo mapa
            if (!quill) {
                console.log('inicia');
                quill = new Quill('#otras-limitaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca otras limitaciones'
                });
            } else {
                console.log('iniciaDenuevo');
                quill.setText('');
                quill = new Quill('#otras-limitaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca otras limitaciones'
                });
            }
        }, 500);

        setTimeout(function() { // Llamamos a la función para crear el nuevo mapa
            if (!quill2) {
                console.log('inicia');
                quill2 = new Quill('#observaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca observaciones'
                });
            } else {
                console.log('iniciaDenuevo');
                quill2.setText('');
                quill2 = new Quill('#otras-limitaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca observaciones'
                });
            }
        }, 500);

        function buscarDatos() {
            var carnet = document.getElementById('search').value;
            var complemento = document.getElementById('complemento').value;
            Livewire.dispatch('search_persona', [carnet, complemento]);
        }

        document.addEventListener('DOMContentLoaded2', function() {
            var select = document.querySelector('#id_seguro_interior');
            $(select).select2();
        });

        document.addEventListener('DOMContentLoaded', function() {
            var selectMultipleEsp = document.querySelector('#especialidades');
            $(selectMultipleEsp).select2({
                placeholder: 'Selecciona las especialidades'
            });

            $(selectMultipleEsp).on('change', function(e) {
                var selectedValuesEsp = $(this).val();
                // Enviar los valores seleccionados al componente Livewire
                @this.set('especialidades', selectedValuesEsp);
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var selectMultipleServ = document.querySelector('#servicios');
            $(selectMultipleServ).select2({
                placeholder: 'Selecciona los servicios'
            });

            $(selectMultipleServ).on('change', function(e) {
                var selectedValuesServ = $(this).val();
                // Enviar los valores seleccionados al componente Livewire
                @this.set('servicios', selectedValuesServ);
            });
        });

        function guardarAutorizacion() {
            Swal.fire({
                title: '¿Registrar Convenio del Interior?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarConvenioInterior', {
                        otraslimitaciones: quill.getSemanticHTML(),
                        observaciones: quill2.getSemanticHTML()
                    });
                    document.getElementById('cargando-convenio-interior').style.display = 'block';

                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }


        // MODALS
        window.addEventListener('modalTablaTitularInterior', event => {

            $('#modalTablaTitularInterior').modal('show');

        });

        document.addEventListener('titularSel', function() {

            Swal.fire({
                title: 'Relacionar con el Titular del Interior?',
                text: "Se guardara la informacion.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('titularSelected');
                    $('#modalTablaTitularInterior').modal('hide');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló la acción",
                        icon: "error"
                    });
                }
            });
        });

        document.addEventListener('AutorizacionRegistrada', function() {
            Swal.fire({
                title: "Guardado!",
                text: "El convenio interior fue registrado exitosamente.",
                icon: "success",
                showConfirmButton: false,

            });
        });

        document.addEventListener('AutorizacionNoRegistrada', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "Se canceló el registro.",
                icon: "error"

            });
        });

        document.addEventListener('solRetirado', function() {
            Swal.fire({
                title: "Guardado",
                text: "El solicitante fue retirado de la solicitud existosamente",
                icon: "success"

            });
        });

        document.addEventListener('solNoRetirado', function() {
            Swal.fire({
                title: "Error",
                text: "El solicitante no pudo ser retirado de la solicitud. Intente nuevamente",
                icon: "error"

            });
        });

        document.addEventListener('personaAfiliada', function() {
            Swal.fire({
                title: "El carnet pertenece a un afiliado vigente",
                text: "El carnet introducido pertenece a un afiliado titular, beneficiario o estudiante aún vigente",
                icon: "warning"
            });
        });



        function quitarTitularInterior(persona, idPersona) {
            Swal.fire({
                title: `¿Está seguro de quitar al Titular del Interior ${persona}?`,
                text: "Se guardarán los cambios.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, quitar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('quitarTitularConvenioInterior', [idPersona]);
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }
    </script><!-- Estilos específicos de select2 -->
    <style>
        /* Cambia el fondo y color del select cuando tiene elementos seleccionados */
        .select2-container--default .select2-selection--multiple {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
            border-color: #ccc !important;
            /* Borde */
        }

        /* Cambia el color de los ítems seleccionados */
        .select2-container--default .select2-selection__choice {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
            border-color: #2874A6 !important;
            /* Borde del ítem */
        }

        /* Cambia los colores de los ítems en la lista desplegable */
        .select2-container--default .select2-results__option {
            background-color: white !important;
            /* Fondo blanco */
            color: #2874A6 !important;
            /* Texto azul */
        }

        /* Cambia el color del ítem que está resaltado al hacer hover o está seleccionado en la lista */
        .select2-container--default .select2-results__option--highlighted {
            background-color: #2874A6 !important;
            /* Fondo azul */
            color: white !important;
            /* Texto blanco */
        }
    </style>

</div>
