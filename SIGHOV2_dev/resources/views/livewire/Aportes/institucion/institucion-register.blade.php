<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">AGREGAR NUEVA INSTITUCIÓN </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <label for="institucion">NOMBRE DE LA INSTITUCIÓN: </label>
                            <input wire:model.live="institucion" type="text" name="institucion" id="institucion"
                                class="form-control @error('institucion') is-invalid @enderror"
                                placeholder="Ingrese el Nombre de la Institución"
                                oninput="this.value = this.value.toUpperCase()" required style="border-radius: 10px;">
                            @error('institucion')
                                <div class="text-danger">
                                    <b>El campo nombre de institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nit">NIT:</label>
                            <input wire:model.live="nit" name="nit" id="nit" type="number"
                                class="form-control @error('nit') is-invalid @enderror" placeholder="Ingrese el Nit"
                                required style="border-radius: 10px;" min="0">
                            @error('institucion')
                                <div class="text-danger">
                                    <b>El campo NIT de la institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                        <div class='col-lg-5 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nit">SUBIR EN PDF EL NIT (HASTA 1MB):</label>
                            <input class="form-control @error('file_digital_nit') is-invalid @enderror" type="file"
                                id="file_digital_nit" name="file_digital_nit" required style="border-radius: 10px;"
                                wire:model.live="file_digital_nit" accept=".pdf">
                            @error('file_digital_nit')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                            <div wire:loading wire:target="file_digital_nit">
                                <x-cargando-dos />
                            </div>
                        </div>

                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nro_empleador">NÚMERO DE EMPLEADOR:</label>
                            <input wire:model.live="nro_empleador" name="nro_empleador" id="nit" type="text"
                                class="form-control @error('nro_empleador') is-invalid @enderror"
                                placeholder="Ingrese el Nro. Empleador" oninput="this.value = this.value.toUpperCase()"
                                style="border-radius: 10px;">
                            @error('nro_empleador')
                                <div class="text-danger">
                                    <b>El número del empleador es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="acronimo">ACRÓNIMO / SIGLA:</label>
                            <input wire:model="acronimo" name="acronimo" id="acronimo" type="text"
                                class="form-control @error('acronimo') is-invalid @enderror"
                                placeholder="Ingrese el acrónimo o abreviatura "
                                oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                            @error('acronimo')
                                <div class="text-danger">
                                    <b>El campo acrónimo o abreviatura de la institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="actividad_empleador">ACTIVIDAD DEL EMPLEADOR:</label>
                            <input wire:model="actividad_empleador" name="actividad_empleador" id="actividad_empleador"
                                type="text" class="form-control @error('actividad_empleador') is-invalid @enderror"
                                placeholder="Ingrerse la Actividad del Empleador"
                                oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                            @error('actividad_empleador')
                                <div class="text-danger">
                                    <b>El campo actividad del empleador es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="clase_aportante_id">TIPO DE
                                APORTANTE:</label>
                            <select wire:model.live="clase_aportante_id" id="clase_aportante_id"
                                name="clase_aportante_id"
                                class="form-control select2 @error('clase_aportante_id') is-invalid @enderror"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                <option selected="selected">Seleccione tipo de
                                    aportante.</option>
                                @foreach ($clase_aportantes as $clase)
                                    <option value="{{ $clase->id }}">{{ $clase->tipo }}</option>
                                @endforeach
                            </select>
                            @error('clase_aportante_id')
                                <div class="text-danger">
                                    <b>El campo clase aportante es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tipo_comercio">CÓDIGO DE COMERCIO:</label>
                            <select wire:model.live="tipo_comercio" id="tipo_comercio" name="tipo_comercio"
                                class="form-control select2 @error('tipo_comercio') is-invalid @enderror"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                <option selected="selected">Seleccione el código de comercio</option>
                                <option value="NINGUNO">NINGUNO</option>
                                <option value="SOCIEDAD COLECTIVA">SOCIEDAD COLECTIVA</option>
                                <option value="SOCIEDAD EN COMANDITA SIMPLE">SOCIEDAD EN COMANDITA SIMPLE</option>
                                <option value="SOCIEDAD DE RESPONSABILIDAD LIMITADA (S.R.L.) ">SOCIEDAD DE
                                    RESPONSABILIDAD
                                    LIMITADA (S.R.L.)</option>
                                <option value="SOCIEDAD ANÓNIMA (S.A.)">SOCIEDAD ANÓNIMA (S.A.)</option>
                                <option value="SOCIEDAD EN COMANDITA POR ACCIONES">SOCIEDAD EN COMANDITA POR ACCIONES
                                </option>
                                <option value="ASOCIACIÓN ACCIDENTAL O DE CUENTAS POR PARTICIPACIÓN">ASOCIACIÓN
                                    ACCIDENTAL O DE
                                    CUENTAS POR PARTICIPACIÓN</option>
                                <option value="EMPRESA UNIV">EMPRESA UNIV</option>

                            </select>
                            @error('tipo_comercio')
                                <div class="text-danger">
                                    <b>El campo tipo de comercio es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_afiliacion">FECHA DE
                                AFILIACIÓN:</label>
                            <input wire:model="fecha_afiliacion" name="fecha_afiliacion" id="fecha_afiliacion"
                                type="date" class="form-control @error('fecha_afiliacion') is-invalid @enderror"
                                placeholder="Ingrese la Fecha de Registro" style="border-radius: 10px;">
                            @error('fecha_afiliacion')
                                <div class="text-danger">
                                    <b>El campo fecha de afiliación es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Seleccione los siguientes estados.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tgn">ES TGN:</label>
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <label class="mr-md-2 mb-2 mb-md-0">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="tgn" name="tgn" type="checkbox"
                                        class="custom-control-input" id="customSwitch2">
                                    <label class="custom-control-label" for="customSwitch2"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="mr-md-2 mb-2 mb-md-0">SI</label>
                            </div>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="tipo_institucion">ES DE TIPO:</label>
                            <div class="col-sm-10 d-flex align-items-center">
                                <label class="mr-2">Empresa</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="tipo_institucion" name="tipo_institucion" type="checkbox"
                                        class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                </div>
                                <label class="ml-2">Institución</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="publica">ES:</label>
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <label class="mr-md-2 mb-2 mb-md-0">Privada</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="publica" name="publica" type="checkbox"
                                        class="custom-control-input" id="customSwitch2publica">
                                    <label class="custom-control-label mx-3" for="customSwitch2publica"></label>
                                </div>
                                <label class="ml-md-2 mt-2 mt-md-0">Pública</label>
                            </div>
                        </div>
                        
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Agregar los datos de dirección y seleccionar en el mapa</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <div class="row">
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="direccion">DIRECCIÓN:</label>
                                    <input wire:model.live="direccion" name="direccion" id="direccion"
                                        type="text" class="form-control @error('direccion') is-invalid @enderror"
                                        placeholder="Ingrese la Calle y/o Avenida"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('direccion')
                                        <div class="text-danger">
                                            <b>El campo dirección de la institución es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="numero">NÚMERO DE PUERTA:</label>
                                    <input wire:model.live="numero" name="numero" id="numero" type="text"
                                        class="form-control @error('numero') is-invalid @enderror"
                                        placeholder="Ingrese el Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('numero')
                                        <div class="text-danger">
                                            <b>El campo número de puerta de la institución es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12' wire:ignore>
                                    <label for="zona">ZONA:</label>
                                    <select wire:model.live="zona" name="zona" id="zona"
                                        class="form-control select2 @error('zona') is-invalid @enderror "
                                        style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                        <option selected="selected">Seleccione la zona </option>
                                        @foreach ($zonas as $zona)
                                            <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('zona')
                                        <div class="text-danger">
                                            <b>El campo zona de la institución es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="direccion_referencia">REFERENCIA DE LA DIRECCIÓN:</label>
                                    <input wire:model="direccion_referencia" name="direccion_referencia"
                                        id="direccion_referencia" type="text"
                                        class="form-control @error('direccion_referencia') is-invalid @enderror"
                                        placeholder="Ingrese el Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('direccion_referencia')
                                        <div class="text-danger">
                                            <b>El campo referencia de la dirección es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <label for="correo">CORREO
                                        ELÉCTRONICO:</label>
                                    <input wire:model="correo" name="correo" id="correo" type="text"
                                        class="form-control " placeholder="Ingrese el Correo Eléctronico"
                                        oninput="this.value = this.value.toLowerCase();" style="border-radius: 10px;">
                                </div>
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono">1er. TELÉFONO:</label>
                                    <input wire:model.live="telefono" name="telefono" id="telefono" type="number"
                                        class="form-control @error('telefono') is-invalid @enderror"
                                        placeholder="Ingrese el Teléfono" style="border-radius: 10px;" min="0">
                                    @error('telefono')
                                        <div class="text-danger">
                                            <b>El campo nombre de institución es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono_dos">2do. TELÉFONO:</label>
                                    <input wire:model="telefono_dos" name="telefono_dos" id="telefono_dos"
                                        type="number" class="form-control" placeholder="Ingrese el Teléfono"
                                        style="border-radius: 10px;" min="0">
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <div class="row">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                        data-target="#collapseExample" aria-expanded="true"
                                        aria-controls="collapseExample">
                                        <i class="fa fa-street-view" aria-hidden="true"></i> Agregar Ubicación

                                    </button>
                                    @if ($this->ubicacion_select == 0)
                                        <span style="font-size: 16px;" class=" text-danger">No Seleccionado <span
                                                class="fa-stack fa-lg">
                                                <i class="fa fa-street-view fa-stack-1x"></i>
                                                <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                            </span></span>
                                    @else
                                        <span style="font-size: 16px;" class="text-success">Seleccionado!
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-street-view fa-stack-1x"></i>

                                            </span></span>
                                    @endif

                                    <div wire:ignore>
                                        <div class="card card-body">
                                            @include('livewire.Aportes.institucion.ubicacion_institucion')
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Agregar los datos del representante legal.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <!--  Buscador  -->
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->
                            <button class="btn btn-primary" type="submit" wire:click="search_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>
                            <input class="form-control " type="search" id="carnet"
                                style="width: calc(100% - 20px); margin-left: 5px; background-color:white; border-radius:10px;"
                                placeholder="Ingrese carnet" wire:model="carnet">
                            <input class="form-control" type="search" id="complemento"
                                style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                placeholder="Complemento" wire:model="complemento">
                        </div>
                        <div wire:loading wire:target="search_persona">
                            <div class="spinner-grow text-warning" role="status">
                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>
                        @if ($esperandoDatos)
                            <div class="alert alert-info" role="alert">Esperando datos...</div>
                        @endif
                        <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                        @if ($estado_api == '')
                        @else
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" role="alert">
                                <br>
                                @if ($estado_api == 1)
                                    <div class="alert alert-success"> Se validó correctamente!</div>
                                @elseif($estado_api == 0)
                                    <div class="alert alert-danger">No se validó, debe registrar manualmente
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    @if ($estado_api == 1)
                        <div class="row">
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="ci">CARNET: </label>
                                <input class="form-control" type="text" id="ci" wire:model='ci' disabled
                                    style="  border-radius: 10px;" >

                            </div>
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for=" complemento_m">COMPLEMENTO: </label>
                                <input class="form-control" type="text" id="complemento_m"
                                    wire:model='complemento_m' disabled style="  border-radius: 10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="ap_paterno">PRIMER APELLIDO:</label>
                                <input class="form-control" type="text" id="ap_paterno" wire:model='ap_paterno'
                                    disabled style="  border-radius: 10px;">
                            </div>
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="ap_materno">SEGUNDO APELLIDO: </label>
                                <input class="form-control" type="text" id="ap_materno" wire:model='ap_materno'
                                    disabled style="  border-radius: 10px;">
                            </div>
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="nombre">NOMBRES: </label>
                                <input class="form-control" type="text" id="nombre" wire:model='nombre'
                                    disabled style="  border-radius: 10px;">
                            </div>

                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <label for="fecha_nacimiento_real">FECHA DE NACIMIENTO: </label>
                                <input class="form-control" type="date" wire:model='fecha_nacimiento_real'
                                    id="fecha_nacimiento_real" disabled style="  border-radius: 10px;">
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                                <label for="tipo_doc">TIPO DOCUMENTO:</label>
                                <select wire:model.live="tipo_doc" id="tipo_doc"
                                    class="form-control select2 @error('tipo_doc') is-invalid @enderror"
                                    tabindex="-1" required style="border-radius: 10px;">
                                    <option selected="selected">Seleccione tipo documento
                                    </option>
                                    <option value="0">NACIONAL</option>
                                    <option value="1">EXTRANJERO</option>
                                </select>

                                @error('tipo_doc')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="expedido_ci">EXPEDIDO.:</label>
                                <select wire:model.live="expedido_ci" id="expedido_ci"
                                    class="form-control select2 @error('expedido_ci') is-invalid @enderror"
                                    tabindex="-1" required style="border-radius: 10px;">
                                    <option selected="selected">Seleccione expedido
                                    </option>
                                    @foreach ($this->departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('expedido_ci')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="nit">SUBIR EN PDF EL CARNET DE IDENTIDAD:</label>
                                <input
                                    class="form-control @error('file_digital_ci') is-invalid @enderror"
                                    type="file" id="formFiledigital" name="file_digital_ci" required
                                    wire:model="file_digital_ci" accept=".pdf">

                                @error('file_digital_ci')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                                <div wire:loading wire:target="file_digital_ci">
                                    <x-cargando-dos />
                                </div>
                            </div>
                        </div>
                    @else
                        <!--REGISTRAR MANUALMENTE-->
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci">CARNET: </label>
                                <input type="number" class="form-control @error('ci') is-invalid @enderror"
                                    placeholder="Ingrese carnet"id="ci" wire:model="ci" name="ci"
                                    style="border-radius: 10px;" min="0">

                                @error('ci')
                                    @if ($message == 'validation.numeric')
                                        <span class="form-text text-danger">El valor ingresado debe ser
                                            numérico.</span>
                                    @elseif ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="  complemento_m">COMPLEMENTO: </label>
                                <input type="text"
                                    class="form-control @error('complemento_m') is-invalid @enderror"
                                    placeholder=" Complemento" wire:model="complemento_m" name="complemento_m"
                                    id="complemento_m" oninput="this.value = this.value.toUpperCase()"
                                    style="border-radius: 10px;">

                                @error('complemento')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="ap_paterno">PRIMER APELLIDO:</label>
                                <input type="text" class="form-control @error('ap_paterno') is-invalid @enderror"
                                    placeholder="Apellido paterno" id="ap_paterno" wire:model.blur="ap_paterno"
                                    name="ap_paterno" oninput="this.value = this.value.toUpperCase()"
                                    style="border-radius: 10px;">
                                @error('ap_paterno')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror

                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="ap_materno">SEGUNDO APELLIDO: </label>
                                <input type="text" class="form-control @error('ap_materno') is-invalid @enderror"
                                    placeholder="Apellido materno" id="ap_materno" wire:model.blur="ap_materno"
                                    name="ap_materno" oninput="this.value = this.value.toUpperCase()"
                                    style="border-radius: 10px;">
                                @error('ap_materno')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror

                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre">NOMBRES:</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    placeholder="Nombre Completo" id="nombre" wire:model.blur="nombre"
                                    name="nombre" oninput="this.value = this.value.toUpperCase()"
                                    style="border-radius: 10px;">
                                @error('nombre')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es
                                            obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror

                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_nacimiento_real">FECHA DE NACIMIENTO: </label>
                                <input type="date"
                                    class="form-control @error('fecha_nacimiento_real') is-invalid @enderror"
                                    placeholder="Fecha Nacimiento" id="fecha_nacimiento_real"
                                    wire:model.live="fecha_nacimiento_real" name="fecha_nacimiento_real"
                                    style="border-radius: 10px;">
                                @error('fecha_nacimiento_real')
                                    <b class="form-text text-danger">El
                                        campo fecha de nacimiento es
                                        obligatorio.</b>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="tipo_doc">TIPO DOCUMENTO:</label>
                                <select wire:model.live="tipo_doc" id="tipo_doc"
                                    class="form-control select2 @error('tipo_doc') is-invalid @enderror"
                                    tabindex="-1" required style="border-radius: 10px;">
                                    <option selected="selected">Seleccione tipo documento
                                    </option>
                                    <option value="0">NACIONAL</option>
                                    <option value="1">EXTRANJERO</option>
                                </select>

                                @error('tipo_doc')
                                    <div class="text-danger">
                                        <b>El campo tipo de documento es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="expedido_ci">EXPEDIDO.:</label>
                                <select wire:model.live="expedido_ci" id="expedido_ci"
                                    class="form-control select2 @error('expedido_ci') is-invalid @enderror"
                                    tabindex="-1" required style="border-radius: 10px;">
                                    <option selected="selected">Seleccione expedido
                                    </option>
                                    @foreach ($departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('expedido_ci')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                                    @else
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @endif
                                @enderror
                            </div>
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="nit">SUBIR EN PDF EL CARNET DE IDENTIDAD:</label>
                                <input
                                    class="form-control form-control-sm @error('file_digital_ci') is-invalid @enderror"
                                    type="file" id="formFiledigital" name="file_digital_ci" required
                                    style="border-radius: 10px;" wire:model="file_digital_ci" accept=".pdf">
                                @error('file_digital_ci')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                                <div wire:loading wire:target="file_digital_ci">
                                    <x-cargando-dos />
                                </div>

                            </div>
                        </div>
                    @endif

                    <!--GUARDAR Y CANCELAR-->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                        <br>
                        <x-cancel-button wire:click="cancel">CANCELAR</x-cancel-button>
                        <x-save-button onclick="saveInstitucion()" wire:loading.attr="disabled"
                            id="save-button">GUARDAR</x-save-button>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="saveInstitucion">
                        <x-cargando-dos />
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Personaliza el texto de la etiqueta de selección de archivo */
        .custom-file-label::after {
            content: "Subir PDF";
            /* Cambia el texto de "Browse" por el que quieras */
        }

        /* Estilo para el label del input */
        .custom-file-label.bg-success {
            background-color: #28a745;
            /* Color verde (success) de Bootstrap */
            color: white;
            /* Texto blanco */
            border-radius: 5px;
            /* Bordes redondeados */
            padding: 8px 15px;
            /* Espaciado alrededor del texto */
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 25px;
        }
    </style>
</div>


<!-- FUNCIONES -->
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

    // esto es lo de la clase aportante

    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#clase_aportante_id');
        $(select).select2();
        $('#clase_aportante_id').on('change', function(e) {
            var data = $(this).val();
            @this.set('clase_aportante_id', data);
        });
    });

    // Aqui se busca el C.I  //LA FUNCION YA NO ES NECESARIA, LA COMENTO. -RISBOu
    /*function buscarDatos() {
        var carnet = document.getElementById('search').value;
        var complemento = document.getElementById('complemento').value;
        //console.log('Valor del carnet:', carnet, complemento);
        Livewire.dispatch('search_persona', [carnet, complemento]);
    }*/

    // FUNCION DEL BOTON GUARDAR
    function saveInstitucion() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarInsti');
            }
        });
    }

    document.addEventListener('instiCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La institución se registró correctamente.",
            icon: "success"

        });
    });
    document.addEventListener('instiCancelado', function() {
        Swal.fire({
            title: "Registro cancelado!",
            text: "Usted canceló el registro.",
            icon: "error"

        });
    });
    document.addEventListener('noExiste', function() {
        Swal.fire({
            title: "No existe la persona!",
            text: "Ingrese nuevamente los datos.",
            icon: "warning"

        });
    });

    document.addEventListener('menorEdad', function() {
        Swal.fire({
            title: "No es mayor de edad!",
            text: "Vuelva a ingresar los datos por favor.",
            icon: "warning"
        });
    });

    document.addEventListener('existeActiva', function() {
        Swal.fire({
            title: "La institución ya fue registrada",
            text: "Verifica en el listado de las instituciones",
            icon: "warning"
        });
    });
    document.addEventListener('existeInactiva', function() {
        Swal.fire({
            title: "La institución ya fue registrada , pero esta inactiva",
            text: "Verifica en el listado de las instituciones y activa la institución",
            icon: "warning"
        });
    });
</script>
