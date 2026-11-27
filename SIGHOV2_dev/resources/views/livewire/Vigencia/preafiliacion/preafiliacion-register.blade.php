<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white"
                class="bi bi-file-person-fill" viewBox="0 0 16 16">
                <path
                    d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m-1 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-3 4c2.623 0 4.146.826 5 1.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-1.245C3.854 11.825 5.377 11 8 11" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRO
                DE PREAFILIACIÓN</h6>
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
        <div class="col-lg-5 col-md-12 col-sm-12 col-12 mb-2">
            <div class="card-comun p-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="cabecera text-center">
                            <b>DATOS DE PREAFILIADO</b>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
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
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="display: flex; align-items: center;">
                        <button class="btn btn-primary" type="submit" wire:click="search_persona">
                            <div wire:loading wire:target="search_persona"> <x-cargando-dos /> </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>

                        <input class="form-control input-comun " type="number" id="search"
                            style="width: calc(60% - 20px); margin-left: 5px;" placeholder="Ej: 123456"
                            wire:model="carnetBusqueda">
                        <input class="form-control input-comun" type="search" id="complemento"
                            style="width: calc(40% - 20px); margin-left: 5px;" placeholder="Ej: B1"
                            wire:model="complementoBusqueda">
                    </div>
                </div>
                <div class="row">
                    <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                    @if ($estado_api == '')
                    @else
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12" role="alert">
                            @if ($estado_api == 1)
                                <br>
                                <div class="alert alert-success p-1"> <em>Se validaron los datos correctamente a través
                                        del ASUSS.</em></div>
                            @elseif ($estado_api == 2)
                                <br>
                                <div class="alert alert-success p-1">Se validaron los datos correctamente a través de la
                                    Base de Datos</div>
                            @elseif ($estado_api == 0)
                                <br>
                                <div class="alert alert-danger p-1">No se encontraron registros, ingrese manualmente los
                                    datos con cuidado.</div>
                            @elseif ($estado_api == 3)
                                <br>
                                <div class="alert alert-danger p-1">Los datos introducidos corresponden a un afiliado.
                                    Verifique e intente nuevamente</div>
                            @endif
                        </div>
                    @endif
                </div>

                @if ($estado_api == '' || $estado_api == 3)
                @else
                    <!--REGISTRAR MANUALMENTE-->
                    <p style="color:#2471a3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2"><b>Debe completar los siguientes datos.</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                    <div class="row">
                        <div class='col-lg-5 col-md-4 col-sm-5 col-5'>
                            <label for="ci">C.I.:</label>
                            <input type="number" class="form-control input-comun @error('ci') is-invalid @enderror"
                                placeholder="  Carnet" wire:model.live="ci" id="ci"
                                @if ($personaExiste) disabled @endif
                                oninput="this.value = this.value.toUpperCase()" disabled>

                            @error('tipo_doc')
                                <div class="text-danger">
                                    <b>El campo carnet es obligatorio</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-3 col-md-4 col-sm-3 col-3'>
                            <label for="complemento_m">COMP.: </label>
                            <input type="text" class="form-control input-comun" placeholder=" Complemento"
                                wire:model="complemento_m" name="complemento_m" id="complemento_m"
                                @if ($personaExiste) disabled @endif
                                oninput="this.value = this.value.toUpperCase()" disabled>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-4 col-4'>
                            <label for="es_extranjero">TIPO DOC.:</label>
                            <select wire:model="es_extranjero" name="es_extranjero" id="es_extranjero"
                                @if ($extranjero_switch) disabled @endif
                                class="form-control input-comun select2 @error('es_extranjero') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required>
                                <option selected="selected">Seleccione.. </option>
                                <option value="false">NACIONAL </option>
                                <option value="true">EXTRANJERO
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-12 col-md-4 col-sm-12 col-12">
                            <label for="nombre"> NOMBRES:</label>
                            <input class="form-control input-comun @error('nombre') is-invalid @enderror"
                                id="nombre" placeholder=" Introduzca el nombre o nombres" type="text"
                                wire:change="GenerarMatricula" wire:model.live=nombre
                                @if ($personaExiste) disabled @endif
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nombre')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                                <label for="ap_paterno"> PRIMER APELLIDO:</label>
                                <input class="form-control input-comun @error('ap_paterno') is-invalid @enderror"
                                    id="ap_paterno" placeholder=" Apellido paterno" type="text"
                                    wire:change="GenerarMatricula" wire:model.live=ap_paterno
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('ap_paterno')
                                    <div class="text-danger">
                                        <b>El campo apellido paterno es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                                <label for="ap_materno"> SEGUNDO APELLIDO:</label>
                                <input class="form-control input-comun @error('ap_materno') is-invalid @enderror"
                                    id="ap_materno" placeholder=" Apellido materno" type="text"
                                    wire:change="GenerarMatricula" wire:model.live=ap_materno
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('ap_materno')
                                    <div class="text-danger">
                                        <b>El campo apellido materno es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-7 col-md-4 col-sm-6 col-6'>
                                <label for="fecha_nacimiento">FECHA NACIMIENTO:</label>
                                <input type="date"
                                    class="form-control input-comun  @error('fecha_nacimiento') is-invalid @enderror"
                                    placeholder=" Fecha Nacimiento" wire:model.live="fecha_nacimiento"
                                    name="fecha_nacimiento" id="fecha_nacimiento" wire:change="GenerarMatricula"
                                    @if ($fecha_nacimiento_switch) disabled @endif
                                    min="{{ \Carbon\Carbon::now()->subYears(110)->toDateString() }}"
                                    max="{{ \Carbon\Carbon::now()->toDateString() }}"
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('fecha_nacimiento')
                                    <div class="text-danger">
                                        <b>El campo fecha de nacimiento es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-5 col-md-4 col-sm-6 col-6">
                                <label for="sexo"> SEXO:</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <label class="mr-2 text-blue">M</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-blue custom-switch-on-pink">
                                        <input wire:model="sexo" type="checkbox" class="custom-control-input"
                                            id="customSwitch2" wire:change="GenerarMatricula"
                                            @if ($matricula_switch) disabled @endif>
                                        <label class="custom-control-label" for="customSwitch2"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <label class="ml-2 text-pink">F</label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-6 col-6">
                                <label for="matricula"> MATRICULA:</label>
                                <input class="form-control input-comun" id="matricula" wire:model.live="matricula"
                                    disabled>

                            </div>

                            <div class='col-lg-6 col-md-4 col-sm-6 col-6'>
                                <label for="zona">EXPEDIDO:</label>
                                <select wire:model.live="expedido_ci" name="zona" id="zonas-select"
                                    class="form-control input-comun select2 @error('expedido_ci') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1"
                                    @if ($expedido_ci_switch) disabled @endif required>
                                    <option selected="selected">Seleccione.. </option>
                                    @foreach ($depa as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
                                    @endforeach
                                </select>
                                @error('expedido_ci')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-12 col-md-8 col-sm-12 col-12">
                                <label for="id_estado_civil"> ESTADO CIVIL:</label>
                                <select wire:model="id_estado_civil"
                                    class="form-control input-comun select2 @error('id_estado_civil') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required
                                    @if ($estado_civil_switch) disabled @endif>
                                    <option selected="selected">Selecciona estado civil... </option>
                                    @foreach ($estados_civil as $data)
                                        <option value="{{ $data->id }}">{{ $data->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_estado_civil')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                @endif

            </div>
        </div>
        <!-- vista para el registro convenio-->

        <div class="col-lg-7 col-md-12 col-sm-12 col-12" @if (!$mostrarFormulario) hidden @endif>
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5"><b>Ingresar los siguientes datos solicitados.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-6 col-7">
                            <label for="nro_documento_respaldo"> NRO. DOCUMENTO DE RESPALDO:</label>
                            <input
                                class="form-control input-comun @error('nro_documento_respaldo') is-invalid @enderror"
                                id="nro_documento_respaldo" wire:model="nro_documento_respaldo"
                                placeholder='Escriba en nro. de documento de respaldo'
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nro_documento_respaldo')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-6 col-5">
                            <label for="nroPreafiliacion"> NRO. PREAF.: </label>
                            <input class="form-control input-comun" id="nroPreafiliacion" disabled
                                value="PRE-{{ $nroPreafiliacion . '/' . \Carbon\Carbon::now()->format('Y') }}">
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <label for="fecha_inicio"> FECHA INICIO: </label>
                            <input class="form-control input-comun @error('fecha_inicio') is-invalid @enderror"
                                id="fecha_inicio" type='date' wire:model=fecha_inicio {{-- wire:change='dias_diferencia()' --}}
                                disabled>
                            {{-- @error('fecha_inicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror --}}
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <label for="fecha_fin"> FECHA FIN: </label>
                            <input class="form-control input-comun @error('fecha_fin') is-invalid @enderror"
                                id="fecha_fin" type='date' wire:model="fecha_fin" {{-- wire:change='dias_diferencia()' --}} disabled>
                            {{-- @error('fecha_fin')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror --}}
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <label for="dias"> TOTAL DÍAS: </label>
                            <input class="form-control input-comun" id="dias" wire:model="dias" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-5 col-sm-4 col-12">
                            <label for="telefonoPreafiliado"> NRO. TELÉFONO:</label>
                            <input class="form-control input-comun" id="telefonoPreafiliado"
                                wire:model="telefonoPreafiliado" placeholder='Ej: 71727454' type="number">
                        </div>
                        <div class="col-lg-8 col-md-7 col-sm-8 col-12">
                            <label for="tipoPreafiliado">TIPO DE PREAFILIACIÓN:</label>
                            <select wire:model="tipoPreafiliado"
                                class="form-control input-comun select2 @error('tipoPreafiliado') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required
                                wire:change="tipoPreafiliacionSeleccionado">
                                <option selected="selected">Selecciona un tipo de preafiliación... </option>
                                <option value="PREAFILIACION TITULAR">PREAFILIACIÓN TITULAR</option>
                                <option value="PREAFILIACION BENEFICIARIO">PREAFILIACIÓN BENEFICIARIO</option>
                            </select>
                            @error('tipoPreafiliado')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    @if ($switchTipoAportante)
                        <div class="row">
                            <div class="col-lg-2 col-md-12 col-sm-12 col-12 ">

                            </div>
                            <div class="col-lg-10 col-md-12 col-sm-12 col-12 ">
                                <label for="tipoAportante"> TIPO APORTANTE:</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <label class="mr-2" style="color: green">INSTITUCIONAL</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-green custom-switch-on-red">
                                        <input wire:model="tipoAportante" type="checkbox"
                                            class="custom-control-input" id="customSwitchAportante"
                                            wire:change="mostrarInstituciones">
                                        <label class="custom-control-label" for="customSwitchAportante"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <label class="ml-2" style="color: red">VOLUNTARIO</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($switchTipoAfiliado === 1)

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="idInstitucion">INSTITUCIÓN DE PREAFILIADO INSTITUCIONAL: </label>
                                <select wire:model="idInstitucion" id="idInstitucion"
                                    class="form-control input-comun @error('idInstitucion') is-invalid @enderror select2"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Selecciona la institución... </option>
                                    @foreach ($instituciones as $institucion)
                                        <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idInstitucion')
                                    <div class="text-danger">
                                        <b>El campo institución es obligatorio</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if ($switchTipoAfiliado === 2)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <p style="color:#2471a3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2 h5"><b>Seleccione un titular</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:center;">
                                @livewire('Vigencia.preafiliacion.preafiliacion-busqueda-titular')
                            </div>

                        </div>
                    @endif

                    @if ($switchTitularSeleccionado)
                        <br>
                        <div class="card-borde p-2">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                                    <label for="nombreTitular"> TITULAR: </label>
                                    <input class="form-control input-comun" id="nombreTitular"
                                        wire:model="nombreTitular" disabled>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                                    <label for="matriculaTitular"> MATRICULA: </label>
                                    <input class="form-control input-comun" id="matriculaTitular"
                                        wire:model="matriculaTitular" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <label for="institucionTitular"> INSTITUCIÓN: </label>
                                    <input class="form-control input-comun" id="institucionTitular"
                                        wire:model="institucionTitular" disabled>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if ($switchTipoAfiliado === 3)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="nombreInstitucion"> INSTITUCIÓN DE PREAFILIADO VOLUNTARIO: </label>
                                <input class="form-control input-comun" id="nombreInstitucion"
                                    value="APORTANTE VOLUNTARIO" disabled>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="idInstitucion"> INSTITUCION: </label>
                                <select wire{{  }}el="idInstitucion" id="idInstitucionRent" name="idInstitucionRent"
                                    class={{  }}m-control input-comun select2 @error('idInstitucion') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Selecciona la institución... </option>
                                    @foreach ($instituciones as $institucion)
                                        <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idInstitucion')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div> --}}
                    @endif

                    <div class="row">
                        <br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12" wire:ignore>
                            <label for="observacion" style="display: block; text-align: left;">OBSERVACIONES:</label>
                            <!-- <textarea class="form-control input-comun" id="observacion" wire:model='observacion' class="swal2-input"
                                rows='4' oninput="this.value = this.value.toUpperCase()"></textarea> -->

                            <div id="observaciones" style="height: 100px;">
                                <!-- <p>introduzca texto</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;"
        @if (!$mostrarFormulario) hidden @endif>
        <x-cancel-button wire:click="cancel">CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarRegistro()">GUARDAR</x-save-button>

    </div>
    <div wire:loading wire:target="search_persona">
        <x-cargando-dos />
    </div>
    {{-- <div wire:loading wire:target="registrarPreafiliado">
        <x-cargando-dos />
    </div> --}}

    <div id="loader-registrar" style="display:none;">
        <x-cargando-dos />
    </div>




    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <script>
        let quill2;

        setTimeout(function() { // Llamamos a la función para crear el nuevo mapa
            if (!quill2) {
                quill2 = new Quill('#observaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca observaciones'
                });
            } else {
                quill2.setText('');
                quill2 = new Quill('#otras-observaciones', {
                    theme: 'snow',
                    placeholder: 'Introduzca observaciones'
                });
            }
        }, 500);

        // GUARDAR BAJA MEDICA
        function guardarRegistro() {
            document.getElementById('loader-registrar').style.display = 'block';
            Swal.fire({
                title: '¿Registrar preafiliación?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarPreafiliado', {
                        observaciones: quill2.getSemanticHTML()
                    });
                } else {
                    document.getElementById('loader-registrar').style.display = 'none';
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('preafiliacionGuardada', function() {
            document.getElementById('loader-registrar').style.display = 'none';
            Swal.fire({
                title: '<b>Preafliación registrada correctamente</b>',
                text: "La preafiliación se registro con éxito.",
                icon: 'success',
                showConfirmButton: false,
            });
        });

        window.addEventListener('titularAgregado', event => {
            Swal.fire({
                title: "<b>Titular seleccionado</b>",
                text: "Se seleccionó al titular correctamente.",
                icon: "success",
                showConfirmButton: false,
                timer: 1000
            });
            $('#buscarTitularModal').modal('hide');
        });

        document.addEventListener('errorPreafiliacion', function() {
            document.getElementById('loader-registrar').style.display = 'none';
            Swal.fire({
                title: 'Error en el registro',
                text: "Verifique e intente nuevamente",
                icon: 'error'
            });
        });

        document.addEventListener('personaAfiliada', function() {
            Swal.fire({
                title: "El carnet pertenece a un afiliado vigente",
                text: "El carnet introducido pertenece a un afiliado titular, beneficiario o estudiante aún vigente",
                icon: "warning"
            });
        });

        document.addEventListener('preafiliacionExistente', function() {
            Swal.fire({
                title: "<b>El carnet pertenece a un preafiliado</b>",
                text: "El carnet introducido pertenece a un preafiliado con una preafiliación vigentes",
                icon: "warning"
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#idInstitucion');
            $(select).select2();
            $('#idInstitucion').on('change', function(e) {
                var data = $(this).val();
                @this.set('idInstitucion', data);
            });
        });


        document.addEventListener('ciInvalidoPreaf', function() {
            Swal.fire({
                title: "¡Invalido!",
                text: "Por favor ingresa un numero de carnet válido para realizar la busqueda",
                icon: "warning",
            });
        });

        document.addEventListener('sinTitularSeleccionado', function() {
            Swal.fire({
                title: "<b>Sin titular seleccionado</b>",
                text: "Seleccione un asegurado titular para continuar con el registro",
                icon: "warning",
            });
        });

        document.addEventListener('cancelar', function() {
            Swal.fire({
                title: "<b>Cancelado</b>",
                text: "Se canceló el registro",
                icon: "error",
                showConfirmButton: false,
            });
        });
    </script>



</div>
