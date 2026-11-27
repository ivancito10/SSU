<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                REGISTRO DE CONVENIO DE EMERGENCIA</h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>
    <div class="row">
        <!-- vista para el asegurado -->
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> AGREGAR ASEGURADO POR CONVENIO</b></p>
                <div class="card-body">
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
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->

                            <button class="btn btn-primary" type="submit" wire:click="search_persona">
                                <div wire:loading wire:target="search_persona"> <x-cargando-dos /> </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>

                            <input class="form-control " type="search" id="search"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el carnet"
                                wire:model="carnetBusqueda">
                            <input class="form-control" type="search" id="complemento"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Ingrese complemento"
                                wire:model="complementoBusqueda">
                        </div>

                        <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                        @if ($estado_api == '')
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                                @if ($estado_api == 1)
                                    <br>
                                    <div class="alert alert-success"> <em>Se validaron los datos correctamente a través
                                            del ASUSS.</em></div>
                                @elseif ($estado_api == 2)
                                    <br>
                                    <div class="alert alert-success">Se validaron los datos correctamente a través de la
                                        Base de Datos</div>
                                @elseif ($estado_api == 0)
                                    <br>
                                    <div class="alert alert-danger">No se encontraron registros</div>
                                @elseif ($estado_api == 3)
                                    <br>
                                    <div class="alert alert-danger">Los datos introducidos corresponden a un afiliado.
                                        Verifique e intente nuevamente</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if ($estado_api == '' || $estado_api == 3)
                    @else
                        <div class="d-flex flex-column align-items-center">
                            <!--  guardar la foto del asegurado en base 64 -->
                            <!-- <img src="" class="img-thumbnail" width="200" height="200" alt="FTP">
                         -->
                            @if ($foto_asegurado)
                                @if (Str::startsWith($foto_asegurado->getMimeType(), 'image/'))
                                    <img src="{{ $foto_asegurado->temporaryUrl() }}"
                                    style=" box-shadow: 1 0 10px 5px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; object-fit: cover;"
                                    class="img-thumbnail">
                                @endif

                            @endif

                            <label for="formFile" class="form-label" style='color:#0D47A1 ;'>SELECCIONE LA FOTO:
                            </label>

                            <input class="form-control @error('foto_asegurado') is-invalid @enderror" type="file"
                                id="formFile" name="archivo" required wire:model="foto_asegurado" accept=".jpg,.jpeg,.png">
                            @error('foto_asegurado')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                            <div wire:loading wire:target="foto_asegurado"> <x-cargando-dos /> </div>
                        </div>
                         
                        <br>



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
                            <div class='col-lg-5 col-md-4 col-sm-6 col-xs-12'>
                                <label for="ci">CARNET</label>
                                <input type="number" class="form-control @error('ci') is-invalid @enderror"
                                    placeholder="  Carnet" wire:model.live="ci" id="ci"
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()" disabled>

                                @error('tipo_doc')
                                    <div class="text-danger">
                                        <b>El campo carnet es obligatorio</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="complemento_m">COMP. </label>
                                <input type="text" class="form-control" placeholder=" Complemento"
                                    wire:model="complemento_m" name="complemento_m" id="complemento_m"
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()" disabled>
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="es_extranjero">TIPO DOC.</label>
                                <select wire:model="es_extranjero" name="es_extranjero" id="es_extranjero"
                                    @if ($extranjero_switch) disabled @endif
                                    class="form-control select2 @error('es_extranjero') is-invalid @enderror"
                                    style="width: 100%;" tabindex="-1" required>
                                    <option selected="selected">Seleccione.. </option>
                                    <option value="false">NACIONAL </option>
                                    <option value="true">EXTRANJERO
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre"> NOMBRES</label>
                                <input class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                    placeholder=" Introduzca el nombre" type="text" wire:model.live=nombre wire:change="GenerarMatricula"
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('nombre')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="ap_paterno"> PRIMER APELLIDO</label>
                                <input class="form-control @error('ap_paterno') is-invalid @enderror" id="ap_paterno"
                                    placeholder=" Apellido paterno" type="text" wire:model.live=ap_paterno wire:change="GenerarMatricula"
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('ap_paterno')
                                    <div class="text-danger">
                                        <b>El campo apellido paterno es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="ap_materno"> SEGUNDO APELLIDO</label>
                                <input class="form-control @error('ap_materno') is-invalid @enderror" id="ap_materno"
                                    placeholder=" Apellido materno" type="text" wire:model.live=ap_materno wire:change="GenerarMatricula"
                                    @if ($personaExiste) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('ap_materno')
                                    <div class="text-danger">
                                        <b>El campo apellido materno es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                <label for="fecha_nacimiento">FECHA NACIMIENTO </label>
                                <input type="date"
                                    class="form-control  @error('fecha_nacimiento') is-invalid @enderror"
                                    placeholder=" Fecha Nacimiento" wire:model.live="fecha_nacimiento"
                                    name="fecha_nacimiento" id="fecha_nacimiento" wire:change="GenerarMatricula"
                                    @if ($fecha_nacimiento_switch) disabled @endif
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('fecha_nacimiento')
                                    <div class="text-danger">
                                        <b>El campo fecha de nacimiento es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="id_estado_civil"> ESTADO CIVIL</label>
                                <select wire:model="id_estado_civil"
                                    class="form-control select2 @error('id_estado_civil') is-invalid @enderror"
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

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="sexo"> SEXO</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <label class="mr-2">M</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-blue custom-switch-on-pink">
                                        <input wire:model="sexo" type="checkbox" class="custom-control-input"
                                            id="customSwitch2" wire:change="GenerarMatricula"
                                            @if ($matricula_switch) disabled @endif>
                                        <label class="custom-control-label" for="customSwitch2"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <label class="ml-2">F</label>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="matricula"> MATRICULA</label>
                                <input class="form-control" id="matricula" wire:model.live="matricula" disabled>
                                <br>
                            </div>

                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="zona">EXPEDIDO.</label>
                                <select wire:model.live="expedido_ci" name="zona" id="zonas-select"
                                    class="form-control select2 @error('expedido_ci') is-invalid @enderror"
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- vista para el registro convenio-->

        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12" @if (!$mostrarFormulario) hidden @endif>
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_documento_respaldo"> NRO. DOCUMENTO DE RESPALDO:</label>
                            <input class="form-control @error('nro_documento_respaldo') is-invalid @enderror"
                                id="nro_documento_respaldo" wire:model="nro_documento_respaldo"
                                placeholder='Escriba en nro. de documento de respaldo'
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicio"> FECHA INICIO: </label>
                            <input class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio"
                                type='date' wire:model.live=fecha_inicio wire:change='dias_diferencia()'>
                            @error('fecha_inicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> FECHA FIN: </label>
                            <input class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin"
                                type='date' wire:model.live=fecha_fin wire:change='dias_diferencia()'
                                min="{{ $fecha_inicio }}">
                            @error('fecha_fin')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <label for="dias"> TOTAL DÍAS: </label>
                            <input class="form-control" id="dias" wire:model="dias" disabled>
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <label for="cod_convenio"> COD. CONVENIO: </label>
                            <input class="form-control" id="dias" wire:model="cod_convenio" disabled>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <br>
                            <label for="especialidades" style='color:black ;'>ESPECIALIDADES A SOLICITAR:</label>
                            <select wire:model="especialidades" id="especialidades" class="form-control select2 "
                                style="width: 100%" multiple="multiple" tabindex="-1" required>
                                @foreach ($especialidadesSelect as $especialidad)
                                    <option value="{{ $especialidad->id }}">{{ $especialidad->especialidad }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <br>
                            <label for="servicios" style="color:black  ">SERVICIOS A SOLICITAR:</label>
                            <select wire:model="servicios" id="servicios" class="form-control select2"
                                style="width: 100%; color: black; background-color: yellow;" multiple="multiple"
                                tabindex="-1" required>

                                @foreach ($serviciosSelect as $servicio)
                                    <option value="{{ $servicio->id }}">{{ $servicio->servicio }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <br>
                            <label for="medicamentos_liname">MEDICAMENTOS
                                LINAME:</label>
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 d-flex align-items-center">
                                <label class="mr-2 ml-5">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="medicamentos_liname" type="checkbox"
                                        class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">SI</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <br>
                            <label for="medicamentos_extraliname">MEDICAMENTOS
                                EXTRALINAME:</label>
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 d-flex align-items-center">
                                <label class="mr-2 ml-5">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="medicamentos_extraliname" type="checkbox"
                                        class="custom-control-input" id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">SI</label>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5"><b>Agregar al Personal SSU que dio la autorización.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="id_personal_ssu">AUTORIZADO POR:</label>
                                    <select wire:model.live="id_personal_ssu" name="autoriza" id="id_personal_ssu"
                                        class="form-control select2 @error('id_personal_ssu') is-invalid @enderror"
                                        style="width: 100%;" tabindex="-1" required wire:change='autorizado_por'>
                                        <option selected="selected">Seleccione una autoridad </option>
                                        @foreach ($personal_ssu as $dato)
                                            @if (
                                                $dato->cargo == 'GERENTE GENERAL' ||
                                                    $dato->cargo == 'GERENCIA DE SALUD' ||
                                                    $dato->cargo == 'ADMINISTRACION FINANCIERA')
                                                <option value="{{ $dato->id }}">{{ $dato->cargo }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('id_personal_ssu')
                                        <div class="text-danger">
                                            <b>El campo autoridad es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <!-- Aqui aparecera los datos del autorizado -->

                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                            <label for="nombre_autoriza"> NOMBRE COMPLETO:</label>
                                            <input class="form-control @error('nombre_autoriza') is-invalid @enderror"
                                                id="nombre_autoriza" disabled wire:model=nombre_autoriza>
                                            @error('nombre_autoriza')
                                                <div class="text-danger">
                                                    <b>El campo nombre de autoridad es obligatorio.</b>
                                                </div>
                                            @enderror

                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <label for="carnet_autoriza"> CARNET:</label>
                                            <input class="form-control @error('carnet_autoriza') is-invalid @enderror"
                                                id="carnet_autoriza" disabled wire:model=carnet_autoriza>
                                            @error('carnet_autoriza')
                                                <div class="text-danger">
                                                    <b>El campo carnet de autoridad es obligatorio.</b>
                                                </div>
                                            @enderror

                                        </div>
                                        {{-- <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                            <label for="complemento_autoriza"> COMPL.:</label>
                                            <input class="form-control" id="complemento_autoriza" disabled
                                                wire:model=complemento_autoriza>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud"> FECHA SOLICITUD:</label>
                            <input class="form-control @error('fecha_solicitud') is-invalid @enderror"
                                id="fecha_solicitud" type='date' wire:model.live=fecha_solicitud>
                            @error('fecha_solicitud')
                                <div class="text-danger">
                                    <b>{{ $message }} </b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            {{-- <label for="hoja_ruta"> NRO. HOJA RUTA: </label>
                            <input class="form-control @error('hoja_ruta') is-invalid @enderror" id="hoja_ruta"
                                type='text' wire:model.live=hoja_ruta
                                oninput="this.value = this.value.toUpperCase()">
                            @error('hoja_ruta')
                                <div class="text-danger">
                                    <b>El campo nro hoja de ruta es obligatorio.</b>
                                </div>
                            @enderror --}}
                        </div>
                        <div class='col-lg-5 col-md-4 col-sm-6 col-xs-12' style="text-align:center;">
                            <label for="tipo_convenio" style='color:black ;'>TIPO DE CONVENIO:</label>
                            <br>
                            <p>EMERGENCIA</p>
                            <br>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="otras_limitaciones" style="display: block; text-align: left;">OTRAS
                                LIMITACIONES:</label>
                            <!--<textarea class="form-control" id="otras_limitaciones" wire:model='otras_limitaciones' class="swal2-input"
                                rows='4' oninput="this.value = this.value.toUpperCase()"></textarea>-->
                            <!-- Libreria Quill para docs -->
                            <div id="otras-limitaciones" style="height: 200px;">
                                <!-- <p>introduzca texto</p> -->
                            </div>



                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="observacion" style="display: block; text-align: left;">OBSERVACIONES:</label>
                            <!-- <textarea class="form-control" id="observacion" wire:model='observacion' class="swal2-input" rows='4'
                                oninput="this.value = this.value.toUpperCase()"></textarea> -->

                            <div id="observaciones" style="height: 200px;">
                                <!-- <p>introduzca texto</p> -->
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- vista para el titular -->
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> AGREGAR TITULAR DEPENDIENTE</b></p>

                <div class="card-body">
                    <div class='row'>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2"><b>Ingresar los datos necesarios para la búsqueda.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                        <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; align-items: center;">

                            <!-- Aquí va el botón con icono de lupa -->

                            <button class="btn btn-primary" type="submit" wire:click="titular_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>

                            <input class="form-control " type="search" id="ci_titular"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el carnet"
                                wire:model="carnetTitularBusqueda">
                            <input class="form-control" type="search" id="complemento_titular"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Ingrese complemento"
                                wire:model="complementoTitularBusqueda">

                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <!--FUNCION MOSTRAR TITULAR-->
                            @if ($alerta_titular == '')
                            @else
                                <div class='row'>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                                        @if ($alerta_titular == 1)
                                            <div class="alert alert-success">TITULAR ENCONTRADO</div>
                                        @elseif ($alerta_titular == 2)
                                            <div class="alert alert-warning">LOS DATOS INTRODUCIDOS NO PERTENECEN A UN
                                                TITULAR</div>
                                        @elseif ($alerta_titular == 3)
                                            <div class="alert alert-danger">NO SE ENCONTRÓ NINGÚN REGISTRO</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($alerta_titular === 1)
                            <!-- Aqui va la foto del titular -->
                            <div class="card-body d-flex flex-column align-items-center">
                                <br>
                                <!--  guardar la foto del asegurado en base 64 -->
                                <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail" width="200" height="200" alt="FTP">

                                <h3 style='color:green;'><b> {{ $estado_vigente }}</b></>

                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <!--AQUI SE MUESTRAN LOS DATOS RECUPERADOS DEL TITULAR-->
                                <div class="row mt-2">
                                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="ci">CARNET </label>
                                        <input id ="ci" class="form-control" type="text"
                                            wire:model='carnet_titular' disabled style=" height: 30px;">
                                    </div>
                                    <div class='col-lg-9 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="nombre_titular">NOMBRE COMPLETO</label>
                                        <input id ="nombre_titular" class="form-control" type="text"
                                            wire:model='nombre_titular' disabled style=" height: 30px;">
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matricula"> MATRICULA</label>
                                        <input id ="matricula" class="form-control" type="text"
                                            wire:model='matricula_titular' disabled style=" height: 30px;">
                                    </div>
                                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="fecha_nacimiento">FECHA NAC. </label>
                                        <input id ="fecha_nacimiento" class="form-control" type="date"
                                            wire:model='fecha_titular' disabled style=" height: 30px;">
                                    </div>

                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="sexo_titular"> SEXO</label>
                                        <input id ="sexo_titular" class="form-control" type="text"
                                            wire:model='sexo_titular' disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="estado_civil"> ESTADO CIVIL</label>
                                        <input id ="estado_civil" class="form-control" type="text"
                                            wire:model='estado_civil_titular' disabled style=" height: 30px;">
                                    </div>
                                </div>

                                <div class="row mt-2">

                                    <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="nombre_institucion">INSTITUCION </label>
                                        <input id ="nombre_institucion" class="form-control" type="text"
                                            wire:model='nombre_institucion' disabled style=" height: 30px;">
                                    </div>

                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>



    </div>

    <div class="modal-footer" @if (!$mostrarFormulario) hidden @endif>
        <x-cancel-button wire:click="cancel">CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarRegistro()">GUARDAR</x-save-button>
    </div>




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

        //para seleccionar las diferentes especialidades
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
        //para seleccionar los diferentes servicios

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

        // GUARDAR BAJA MEDICA
        function guardarRegistro() {

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
                    Livewire.dispatch('save', {
                        otraslimitaciones: quill.getSemanticHTML(),
                        observaciones: quill2.getSemanticHTML()
                    });
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('guardado', function() {
            Swal.fire({
                title: 'Guardado!',
                text: "Se registro con éxito.",
                icon: 'success'
            });
        });


        document.addEventListener('ConvenioRegistrado', function() {
            Swal.fire({
                title: 'Guardado!',
                text: "El convenio de emergencia fue registrado exitosamente.",
                icon: 'success',
                showConfirmButton: false,
                timer: 2000 // Opcional: Cierra la alerta automáticamente después de 2 segundos
            });
        });

        document.addEventListener('ConvenioNoRegistrado', function() {
            Swal.fire({
                title: 'Cancelado!',
                text: "Se cancelo el registro.",
                icon: 'error'
            });
        });

        document.addEventListener('seleccioneTitular', function() {
            Swal.fire({
                title: 'Seleccione un Titular Responsable',
                text: "Inténtelo nuevamente",
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
    </script>
    <!-- Estilos específicos de select2 -->
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
