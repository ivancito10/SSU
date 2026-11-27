<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">  ACTUALIZAR REGISTRO INSTITUCIÓN </h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="card-body">
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
                                style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()">
                            @error('institucion')
                                <div class="text-danger">
                                    <b>El campo nombre de institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nit" style="color:#1a5276">NIT: <span style="color:#cb4335;">*</span>
                                </label>
                            <input wire:model.live="nit" name="nit" id="nit" type="number"
                                class="form-control @error('nit') is-invalid @enderror " style="border-radius: 10px;">
                            @error('nit')
                                <div class="text-danger">
                                    <b>El campo NIT de institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>

                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <label for="file_nit" style="color:#1a5276">SUBIR EN PDF EL NIT: <span style="color:#cb4335;">
                                    * </span></label>
                            <input class="form-control form-control-sm @error('file_digital_nit') is-invalid @enderror"
                                type="file" id="formFiledigital" name="file_digital_nit" required
                                wire:model.live="file_digital_nit" accept=".pdf"> 
                            <div wire:loading wire:target="file_digital_nit">
                                <x-cargando-dos />
                            </div>
                            @error('file_digital_nit')
                                <div class="text-danger">
                                    <b>El campo   es obligatorio, de tipo PDF.</b>
                                </div>
                            @enderror 
                        </div>
 
                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nro_empleador">NÚMERO DE EMPLEADOR: <span style="color:#cb4335;">*</span></label>
                            <input wire:model.live="nro_empleador" name="nro_empleador" id="nro_empleador"
                                type="text" class="form-control @error('nro_empleador') is-invalid @enderror"
                                style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()">
                            @error('nro_empleador')
                                <div class="text-danger">
                                    <b>El campo numero del empleador es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="acronimo">ACRÓNIMO / SIGLA: <span style="color:#cb4335;">*</span></label>
                            <input wire:model.live="acronimo" name="acronimo" id="acronimo" type="text"
                                class="form-control @error('acronimo') is-invalid @enderror "
                                style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()">
                            @error('acronimo')
                                <div class="text-danger">
                                    <b>El campo acronimo de la institución es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="actividad_empleador">ACTIVIDAD EMPLEADOR: <span style="color:#cb4335;">*</span></label>
                            <input wire:model.live="actividad_empleador" name="actividad_empleador"
                                id="actividad_empleador" type="text"
                                class="form-control @error('actividad_empleador') is-invalid @enderror"
                                style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()">
                            @error('actividad_empleador')
                                <div class="text-danger">
                                    <b>El campo actividad del empleador es obligatorio.</b>
                                </div>
                            @enderror

                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                            <label for="clase_aportante_id">TIPO DE APORTANTE: <span style="color:#cb4335;">*</span></label>
                            <select wire:model.live="clase_aportante_id" name="clase_aportante_id"
                                class="form-control  @error('clase_aportante_id') is-invalid @enderror"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" aria-hidden="true" required>
                                <option selected="selected" value="{{ $clase_aportante_id }}" disabled>
                                    {{ $clase_aportante }}
                                </option>
                                @foreach ($claseSelect as $clase)
                                    <option value="{{ $clase->id }}">{{ $clase->tipo }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tipo_comercio">CÓDIGO DE COMERCIO: <span style="color:#cb4335;">*</span></label>
                            <select wire:model.live="tipo_comercio" id="tipo_comercio" name="tipo_comercio"
                                class="form-control select2 @error('tipo_comercio') is-invalid @enderror"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                <option selected="selected" >Seleccione el código de comercio</option>
                                <option value="NINGUNO">NINGUNO</option>
                                <option value="SOCIEDAD COLECTIVA">SOCIEDAD COLECTIVA</option>
                                <option value="SOCIEDAD EN COMANDITA SIMPLE">SOCIEDAD EN COMANDITA SIMPLE</option>
                                <option value="SOCIEDAD DE RESPONSABILIDAD LIMITADA (S.R.L.) ">SOCIEDAD DE
                                    RESPONSABILIDAD LIMITADA (S.R.L.)</option>
                                <option value="SOCIEDAD ANÓNIMA (S.A.)">SOCIEDAD ANÓNIMA (S.A.)</option>
                                <option value="SOCIEDAD EN COMANDITA POR ACCIONES">SOCIEDAD EN COMANDITA POR ACCIONES
                                </option>
                                <option value="ASOCIACIÓN ACCIDENTAL O DE CUENTAS POR PARTICIPACIÓN">ASOCIACIÓN
                                    ACCIDENTAL O DE CUENTAS POR PARTICIPACIÓN</option>
                                <option value="EMPRESARIO INDIVIDUAL">EMPRESARIO INDIVIDUAL</option>

                            </select>
                        </div>
                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_afiliacion">FECHA DE AFILIACIÓN: <span style="color:#cb4335;">*</span></label>
                            <input wire:model="fecha_afiliacion" name="fecha_afiliacion" id="fecha_afiliacion"
                                type="date" class="form-control @error('fecha_afiliacion') is-invalid @enderror"
                                style="border-radius: 10px;">
                            @error('fecha_afiliacion')
                                <div class="text-danger">
                                    <b>El campo fecha de afiliacion es obligatorio.</b>
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
                                
                            </p>
                        </div>

                    </div>
                    <div class="row">

                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tgn">ES TGN (TESORO GENERAL DE LA NACIÓN): <span style="color: #c0392b ;">*</span></label>
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <label class="mr-md-2 mb-2 mb-md-0 " style="color:#c0392b ">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="tgn" name="tgn" type="checkbox"
                                        class="custom-control-input" id="customSwitch2">
                                    <label class="custom-control-label" for="customSwitch2"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="mr-md-2 mb-2 mb-md-0" style="color:green">SI</label>
                            </div>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="tipo_institucion">ES DE TIPO: <span style="color:#cb4335;">*</span></label>
                            <div class="col-sm-10 d-flex align-items-center">
                                <label class="mr-2" style="color:#c0392b ">Empresa</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="tipo_institucion" name="tipo_institucion" type="checkbox"
                                        class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                </div>
                                <label class="ml-2" style="color:green">Institución</label>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="publica">ES: <span style="color:#cb4335;">*</span></label>
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <label class="mr-md-2 mb-2 mb-md-0" style="color: #c0392b ">Privada</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="publica" name="publica" type="checkbox"
                                        class="custom-control-input" id="customSwitch2publica">
                                    <label class="custom-control-label mx-3" for="customSwitch2publica"></label>
                                </div>
                                <label class="ml-md-2 mt-2 mt-md-0" style="color:green">Pública</label>
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
                                    <b>Agregar nuevos datos de dirección y seleccionar en el mapa</b> </em>
                                
                            </p>
                        </div>

                    </div>

                    <div class="row">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <div class="row">
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="direccion">DIRECCIÓN: <span style="color:#cb4335;">*</span></label>
                                    <input wire:model.live="direccion" name="direccion" id="direccion"
                                        type="text" class="form-control @error('direccion') is-invalid @enderror"
                                        placeholder="  Avenida y/o Calle"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;"
                                        required>
                                    @error('direccion')
                                        <div class="text-danger">
                                            <b>El campo dirección es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="numero">NÚMERO DE PUERTA: <span style="color:#cb4335;">*</span></label>
                                    <input wire:model.live="numero" name="numero" id="numero" type="text"
                                        class="form-control @error('numero') is-invalid @enderror"
                                        placeholder=" Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('numero')
                                        <div class="text-danger">
                                            <b>El campo número es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                                    <label for="zonaId">ZONA: <span style="color:#cb4335;">*</span></label>
                                    <select wire:model="zonaId" id="zonaId" class="form-control select2 "
                                        style="width: 100%; border-radius: 10px;" tabindex="-1">
                                        <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                        </option>
                                        @foreach ($zonasSelect as $zonaSelected)
                                            <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="direccion_referencia">REFERENCIA DE LA DIRECCIÓN: <span style="color:#cb4335;">*</span></label>
                                    <input wire:model.live="direccion_referencia" name="direccion_referencia"
                                        id="direccion_referencia" type="text"
                                        class="form-control @error('direccion_referencia') is-invalid @enderror"
                                        placeholder="Ingrese el Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('direccion_referencia')
                                        <div class="text-danger">
                                            <b>El campo direccion de referencia es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <label for="correo">CORREO ELÉCTRONICO: <span style="color:#cb4335;">*</span></label>
                                    <input wire:model="correo" name="correo" id="correo" type="text"
                                        class="form-control  @error('correo') is-invalid @enderror" placeholder=" Correo Electrónico"
                                        style="border-radius: 10px;">
                                        @error('correo')
                                            <div class="text-danger">
                                                <b>El campo  es obligatorio.</b>
                                            </div>
                                        @enderror

                                </div>
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono">1er. TELÉFONO: <span style="color:#cb4335;">*</span></label>
                                    <input wire:model.live="telefono" name="telefono" id="telefono" type="number"
                                        class="form-control @error('telefono') is-invalid @enderror"
                                        placeholder="Teléfono Referencia" style="border-radius: 10px;">
                                    @error('telefono')
                                        <div class="text-danger">
                                            <b>El campo telefono es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono_dos">2do. TELÉFONO:</label>
                                    <input wire:model="telefono_dos" name="telefono_dos" id="telefono_dos"
                                        type="number" class="form-control" placeholder="Ingrese el Teléfono"
                                        style="border-radius: 10px;">
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
                                    <b>Actual representante legal registrado en el sistema.</b> </em>
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nombres">NOMBRE COMPLETO: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nombres }} </p>
                        </div>

                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $ci }} </p>

                        </div>

                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA DE NACIMIENTO: </label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $fecha_nacimiento }} </p>
                        </div>


                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                            <label for="tipo_doc">TIPO DE DOCUMENTO:</label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $tipo_doc }} </p>

                        </div>
                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <label for="expedido_ci">EXPEDIDO:</label>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $expedido_ci }} </p>
                        </div>

                    </div>

                    <br>
                    <!--GUARDAR Y CANCELAR-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <x-cancel-button wire:click="atras">CANCELAR</x-cancel-button>
                            <x-save-button onclick="saveEditInsti()">GUARDAR</x-save-button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading
                            wire:target="saveInstitucion">
                            <x-cargando-dos />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
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
    });

    // FUNCION DEL BOTON GUARDAR para SWEETALERT2
    function saveEditInsti() {
        Swal.fire({
            title: '¿Modificar registro?',
            text: "Se modificarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, modificar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizar');
            }
        });
    }

    document.addEventListener('institucionModificada', function() {
        Swal.fire({
            title: "Modificado!",
            text: "La instittución se modificó correctamente",
            icon: "success",
            showConfirmButton: false,
            timer: 2000 // Opcional: Cierra la alerta automáticamente después de 2 segundos
        });
    });

    document.addEventListener('ordenCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La orden de servicio se registró correctamente.",
            icon: "success",
            showConfirmButton: false,
            timer: 2000 // Opcional: Cierra la alerta automáticamente después de 2 segundos
        });
    });
    document.addEventListener('InstitucionNoActualizado', function() {
        Swal.fire({
            title: "No se modifico!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });

    document.addEventListener('errorNnit', function() {
        Swal.fire({
            title: "Advertencia!",
            text: "Debe completar todos los campos. Sugerencia, si cambia el NIT también debe subir el nuevo archivo file del NIT, de lo contrario no es necesario.",
            icon: "warning"
        });
    });
</script>
