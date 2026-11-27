<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;"> AGREGAR  NUEVA INSTITUCIÓN </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                    <p class="h6" 
                    style="border: 2px solid #d4ac0d; border-radius: 5px; background-color: #f7dc6f; padding-left: 15px; display: inline-block;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                            <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13.113 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                            <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                        </svg> <em><b> Los campos marcados con (<span style="color:#cb4335;">*</span>) son obligatorios para continuar.</b></em> 
                    </p>
                </div>

            </div>
            <div class="row">
                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                    <label for="institucion">NOMBRE DE LA INSTITUCIÓN: <span style="color:#cb4335;">*</span></label>
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
                    <label for="nit">NIT: <span style="color:#cb4335;">*</span></label>
                    <input wire:model.live="nit" name="nit" id="nit" type="number"
                        class="form-control @error('nit') is-invalid @enderror" placeholder="Ingrese el Nit" required
                        style="border-radius: 10px;">
                    @error('institucion')
                        <div class="text-danger">
                            <b>El campo NIT de la institución es obligatorio.</b>
                        </div>
                    @enderror
                </div>

                <div class='col-lg-5 col-md-4 col-sm-6 col-xs-12'>
                    <label for="nit">SUBIR EN PDF EL NIT (HASTA 1MB): <span style="color:#cb4335;">*</span></label>
                    <input class="form-control form-control-sm @error('file_digital_nit') is-invalid @enderror"
                        type="file" id="formFiledigital" name="file_digital_nit" required
                        style="border-radius: 10px;" wire:model.live="file_digital_nit" accept=".pdf">
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
                    <label for="nro_empleador">NÚMERO DE EMPLEADOR: <span style="color:#cb4335;">*</span></label>
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
                    <label for="acronimo">ACRÓNIMO / SIGLA: <span style="color:#cb4335;">*</span></label>
                    <input wire:model="acronimo" name="acronimo" id="acronimo" type="text"
                        class="form-control @error('acronimo') is-invalid @enderror"
                        placeholder="Ingrese el acrónimo o abreviatura " oninput="this.value = this.value.toUpperCase()"
                        style="border-radius: 10px;">
                    @error('acronimo')
                        <div class="text-danger">
                            <b>El campo acrónimo o abreviatura de la institución es obligatorio.</b>
                        </div>
                    @enderror
                </div>
                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                    <label for="actividad_empleador">ACTIVIDAD DEL EMPLEADOR: <span style="color:#cb4335;">*</span></label>
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
                    <label for="clase_aportante_id">TIPO DE APORTANTE: <span style="color:#cb4335;">*</span></label>
                    <select wire:model.live="clase_aportante_id" id="clase_aportante_id" name="clase_aportante_id"
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
                    <label for="tipo_comercio">CÓDIGO DE COMERCIO: <span style="color:#cb4335;">*</span></label>
                    <select wire:model.live="tipo_comercio" id="tipo_comercio" name="tipo_comercio"
                        class="form-control select2 @error('tipo_comercio') is-invalid @enderror"
                        style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                        <option selected="selected">Seleccione el código de comercio</option>
                        <option value="NINGUNO">NINGUNO</option>
                        <option value="SOCIEDAD COLECTIVA">SOCIEDAD COLECTIVA</option>
                        <option value="SOCIEDAD EN COMANDITA SIMPLE">SOCIEDAD EN COMANDITA SIMPLE</option>
                        <option value="SOCIEDAD DE RESPONSABILIDAD LIMITADA (S.R.L.) ">SOCIEDAD DE RESPONSABILIDAD
                            LIMITADA (S.R.L.)</option>
                        <option value="SOCIEDAD ANÓNIMA (S.A.)">SOCIEDAD ANÓNIMA (S.A.)</option>
                        <option value="SOCIEDAD EN COMANDITA POR ACCIONES">SOCIEDAD EN COMANDITA POR ACCIONES</option>
                        <option value="ASOCIACIÓN ACCIDENTAL O DE CUENTAS POR PARTICIPACIÓN">ASOCIACIÓN ACCIDENTAL O DE
                            CUENTAS POR PARTICIPACIÓN</option>
                        <option value="EMPRESARIO INDIVIDUAL">EMPRESARIO INDIVIDUAL</option>

                    </select>
                    @error('tipo_comercio')
                        <div class="text-danger">
                            <b>El campo tipo de comercio es obligatorio.</b>
                        </div>
                    @enderror
                </div>
                <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                    <label for="fecha_afiliacion">FECHA DE AFILIACIÓN: <span style="color:#cb4335;">*</span></label>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2">
                            <b>Seleccionar los siguientes estados.</b> </em>
                    </p>
                </div>

            </div>
            <div class="row">
                <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                    <label for="tgn">ES TGN (TESORO GENERAL DE LA NACIÓN): <span style="color:#cb4335;">*</span></label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <label class="mr-1" style="color:#c0392b ">NO</label>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input wire:model="tgn" name="tgn" type="checkbox" class="custom-control-input"
                                id="customSwitch2">
                            <label class="custom-control-label" for="customSwitch2"
                                style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                        </div>
                        <label class="ml-1" style="color:green">SI</label>
                    </div>
                </div>
                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                    <label for="tipo_institucion">ES DE TIPO: <span style="color:#cb4335;">*</span></label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <label class="mr-2" style="color:#c0392b ">Empresa</label>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input wire:model="tipo_institucion" name="tipo_institucion" type="checkbox"
                                class="custom-control-input" id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1"
                                style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                        </div>
                        <label class="ml-2" style="color:green">Institución</label>
                    </div>
                </div>
                <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                    <label for="publica"> ES: <span style="color:#cb4335;">*</span></label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <label class="mr-2" style="color:#c0392b ">Privada</label>
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input wire:model="publica" name="publica" type="checkbox" class="custom-control-input"
                                id="customSwitch2publica">
                            <label class="custom-control-label" for="customSwitch2publica"
                                style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                        </div>
                        <label class="ml-2" style="color:green">Pública</label>
                    </div>
                </div>


            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p style="color:#1a5276" class="h5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2">
                            <b>Agregar los datos de dirección y seleccionar en el mapa.</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <label for="direccion">DIRECCIÓN: <span style="color:#cb4335;">*</span></label>
                            <input wire:model.live="direccion" name="direccion" id="direccion" type="text"
                                class="form-control @error('direccion') is-invalid @enderror"
                                placeholder="Ingrese la Calle y/o Avenida"
                                oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                            @error('direccion')
                                <div class="text-danger">
                                    <b>El campo dirección de la institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="numero">NÚMERO DE PUERTA: <span style="color:#cb4335;">*</span></label>
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
                            <label for="zona">ZONA: <span style="color:#cb4335;">*</span></label>
                            <select wire:model.live="zona" name="zona" id="zona"
                                class="form-control select2 @error('zona') is-invalid @enderror "
                                style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                <option selected="selected">Seleccione
                                    la Zona </option>
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
                            <label for="direccion_referencia">REFERENCIA DE LA DIRECCIÓN: <span style="color:#cb4335;">*</span></label>
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
                            <label for="correo">CORREO ELÉCTRONICO: <span style="color:#cb4335;">*</span></label>
                            <input wire:model="correo" name="correo" id="correo" type="text"
                                class="form-control " placeholder="Ingrese el Correo Eléctronico"
                                oninput="this.value = this.value.toLowerCase();" style="border-radius: 10px;">
                        </div>
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="telefono">1er. TELÉFONO: <span style="color:#cb4335;">*</span></label>
                            <input wire:model.live="telefono" name="telefono" id="telefono" type="number"
                                class="form-control @error('telefono') is-invalid @enderror"
                                placeholder="Ingrese el Teléfono" style="border-radius: 10px;">
                            @error('telefono')
                                <div class="text-danger">
                                    <b>El campo nombre de institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="telefono_dos">2do. TELÉFONO:</label>
                            <input wire:model="telefono_dos" name="telefono_dos" id="telefono_dos" type="number"
                                class="form-control" placeholder="Ingrese el Teléfono" style="border-radius: 10px;">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                data-target="#collapseExample" aria-expanded="true" aria-controls="collapseExample">
                                <i class="fa fa-street-view" aria-hidden="true"></i> Agregar Ubicación
                            </button>

                            @if ($this->ubicacion_select == 0)
                                <span style="font-size: 16px;" class=" text-danger">No Seleccionado
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-street-view fa-stack-1x"></i>
                                        <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                    </span></span>
                            @else
                                <span style="font-size: 16px;" class="text-success">Seleccionado
                                    Correctamente
                                    <span class="fa-stack fa-lg">
                                        <I class="fa fa-street-view fa-stack-1x"></i>

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
            <!-- INICIO DE REGISTRO DE REPRESENTNTE LEGAL----------------->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p style="color:#1a5276" class="h5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <em class="ml-2">
                            <b>Agregar los datos del representante legal buscando el carnet.</b> </em>
                        
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">
                    <!-- Aquí va el botón con icono de lupa -->
                    <button class="btn btn-primary" type="submit" wire:click="search_persona">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                        </svg>
                    </button>
                    <input class="form-control" type="search" id="busquedaCarnet"
                        style="width: calc(100% - 20px); margin-left: 5px;border-radius: 10px;"
                        placeholder=" Ingrese carnet" wire:model="busquedaCarnet">
                    <input class="form-control" type="search" id="busquedaComplemento"
                        style="width: calc(100% - 20px); margin-left: 5px;border-radius: 10px;"
                        placeholder="Complemento" wire:model="busquedaComplemento">
                </div>
            </div>
            <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
            @if ($estado_api == '')
            @else
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" role="alert">
                        <br>
                        @if ($estado_api == 1)
                            <div class="alert alert-success"> <em> Se validó
                                    correctamente!</em></div>
                        @else
                            <div class="alert alert-danger"> <em>No se
                                    validó, debe registrar manualmente</em></div>
                        @endif
                    </div>
                </div>
            @endif
            <div wire:loading wire:target="search_persona">
                <div class="spinner-grow text-warning" role="status">

                </div>
                <b><i>Cargando datos espere porfavor...</i></b>
            </div>
            @if ($esperandoDatos)
                <div class="alert alert-info" role="alert">Esperando datos...</div>
            @endif
            <!--DATOS DE REPRESENTANTE LEGAL-->
            @if ($estado_api == 1)
                <!--AQUI SE MUESTRAN LOS DATOS RECUPERADOS DEL ASUSS-->
                <div class="row">
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="ci">CARNET: </label>
                        <input class="form-control" type="text" wire:model='ci' disabled
                            style=" background-color: white; border-radius: 10px;">
                    </div>
                    <div class='col-lg-2 col-md-12 col-sm-12 col-xs-12'>
                        <label for="complemento">COMPLEMENTO:
                        </label>
                        <input class="form-control" type="text" wire:model='complemento_m' disabled
                            style=" background-color: white; border-radius: 10px;">
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="ap_paterno">PRIMER APELLIDO:</label>
                        <input class="form-control" type="text" wire:model='ap_paterno' disabled
                            style=" background-color: white; border-radius: 10px;">
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="ap_materno">SEGUNDO APELLIDO:
                        </label>
                        <input class="form-control" type="text" wire:model='ap_materno' disabled
                            style=" background-color: white; border-radius: 10px;">
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="nombre">NOMBRES:
                        </label>
                        <input class="form-control" type="text" wire:model='nombre' disabled
                            style=" background-color: white; border-radius: 10px;">
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="fecha_nacimiento">FECHA
                            DE NACIMIENTO: </label>
                        <input class="form-control" type="date" wire:model='fecha_nacimiento' disabled
                            style=" background-color: white; border-radius: 10px;">
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="tipo_doc">TIPO DOCUMENTO: <span style="color:#cb4335;">*</span></label>
                        <select wire:model.live="tipo_doc" id="tipo_doc"
                            class="form-control select2 @error('tipo_doc') is-invalid @enderror" tabindex="-1"
                            required style="border-radius: 10px;">
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
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="expedido_ci">EXPEDIDO: <span style="color:#cb4335;">*</span></label>
                        <select wire:model.live="expedido_ci" id="expedido_ci"
                            class="form-control select2 @error('expedido_ci') is-invalid @enderror" tabindex="-1"
                            required style="border-radius: 10px;">
                            <option selected="selected">Seleccione el expedido
                            </option>
                            @foreach ($this->departamento as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                </option>
                            @endforeach
                        </select>

                        @error('expedido_ci')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <label for="nit">SUBIR EN PDF EL CARNET DE IDENTIDAD: <span style="color:#cb4335;">*</span></label>
                        <input class="form-control form-control-sm @error('file_digital_ci') is-invalid @enderror"
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
            @else
                <!--REGISTRAR MANUALMENTE-->
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
                                <b>Ingresar y completar los siguientes datos.</b> </em>
                            
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <label for="ci">CARNET: <span style="color:#cb4335;">*</span></label>
                        <input type="number" class="form-control @error('ci') is-invalid @enderror"
                            placeholder=" Carnet de Identidad" wire:model="ci" name="ci" id="ci"
                            style="border-radius: 10px;">
                        @error('ci')
                            <div class="text-danger">
                                <b>El campo carnet de identidad es obligatorio.</b>
                            </div>
                        @enderror
                    </div>
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <label for="complemento_m">COMPLEMENTO: </label>
                        <input type="number" class="form-control " placeholder=" Carnet de Identidad"
                            wire:model="complemento_m" name="complemento_m" id="complemento_m"
                            style="border-radius: 10px;">

                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                        <label for="ap_paterno">PRIMER APELLIDO: </label>
                        <input type="text" class="form-control" placeholder="Apellido Paterno"
                            wire:model="ap_paterno" name="ap_paterno" id="ap_paterno"
                            oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                        @error('ap_paterno')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este
                                    campo es
                                    obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>
                    <div class='col-lg-3 col-md-12 col-sm-6 col-xs-12'>
                        <label for="ap_materno"> SEGUNDO APELLIDO: </label>
                        <input type="text" class="form-control" placeholder="Apellido Materno"
                            wire:model="ap_materno" name="ap_materno" id="ap_materno"
                            oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                        @error('ap_materno')
                            @if ($message == 'validation.required')
                                <span class="form-text text-danger">Este
                                    campo es
                                    obligatorio.</span>
                            @else
                                <span class="form-text text-danger">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="nombre">NOMBRES:  <span style="color:#cb4335;">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                            placeholder=" Nombre Completo" wire:model="nombre" name="nombre" id="nombre"
                            oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                        @error('nombre')
                            <div class="text-danger">
                                <b>El campo nombre es obligatorio.</b>
                            </div>
                        @enderror
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="fecha_nacimiento">FECHA DE NACIMIENTO: <span style="color:#cb4335;">*</span></label>
                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                            placeholder="Fecha Nacimiento" wire:model="fecha_nacimiento" name="fecha_nacimiento"
                            id="fecha_nacimiento" style="border-radius: 10px;">
                        @error('fecha_nacimiento')
                            <b class="form-text text-danger">El
                                campo fecha de nacimiento es
                                obligatorio.</b>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="tipo_doc">TIPO DOCUMENTO: <span style="color:#cb4335;">*</span></label>
                        <select wire:model.live="tipo_doc" id="tipo_doc"
                            class="form-control select2 @error('tipo_doc') is-invalid @enderror" tabindex="-1"
                            required style="border-radius: 10px;">
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
                    <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                        <label for="expedido_ci">EXPEDIDO: <span style="color:#cb4335;">*</span></label>
                        <select wire:model.live="expedido_ci" id="expedido_ci" class="form-control select2 "
                            tabindex="-1" required style="border-radius: 10px;">
                            <option selected="selected">Seleccione el expedido
                            </option>
                            @foreach ($this->departamento as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <label for="nit">SUBIR EN PDF EL CARNET DE IDENTIDAD: <span style="color:#cb4335;">*</span></label>
                        <input class="form-control form-control-sm @error('file_digital_ci') is-invalid @enderror"
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
            {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading>
                    <x-cargando-dos />
                </div> --}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                <br><br>
                <x-cancel-button wire:click="cancel">CANCELAR</x-cancel-button>
                <x-save-button onclick="saveInstitucion()" wire:loading.attr="disabled">GUARDAR</x-save-button>
            </div>

        </div>
    </div>
    <style>
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

    // Aqui se busca el C.I
    function buscarDatos() {
        var carnet = document.getElementById('search').value;
        var complemento = document.getElementById('complemento').value;
        console.log('Valor del carnet:', carnet, complemento);
        Livewire.dispatch('search_persona', [carnet, complemento]);
    }

    // FUNCION DEL BOTON GUARDAR
    function saveInstitucion() {
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
                Livewire.dispatch('guardarInsti');
            }
        });
    }
    document.addEventListener('RepresenanteModificada', function() {
        $('#ModalEditRep').modal('hide');
        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "Se modificó el presentante legal!!!",
            icon: "success"

        });
    });


    document.addEventListener('institucionCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La institución se registro correctamente.",
            icon: "success"

        });
    });
    document.addEventListener('institucionCancelado', function() {
        Swal.fire({
            title: "Registro cancelado",
            text: "Usted canceló el registro",
            icon: "error"

        });
    });

    document.addEventListener('ingreseRepresentante', function() {
        Swal.fire({
            title: "Ingrese al representante legal",
            text: "El registro del representante legal es obligatorio.",
            icon: "error"

        });
    });
</script>
