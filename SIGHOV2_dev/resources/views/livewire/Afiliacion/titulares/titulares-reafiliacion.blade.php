<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white"
                class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                <path fill-rule="evenodd"
                    d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" />
            </svg>
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REAFILIACIÓN DE
                ASEGURADO</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;border-radius:8px"> <b> TITULAR</b></p>
                <div class="row ">
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
                    <div class="col-lg-12 ">
                        @if ($errors->has('error_message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error_message') }}
                            </div>
                        @endif
                        <input class="form-control @error('foto') is-invalid @enderror" type="file" id="formFile"
                            name="archivo" required wire:model="foto" accept=".jpg,.jpeg,.png"
                            style="border-radius:8px">
                    </div>

                    <div class="col-lg-12">
                        <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                        @if ($foto)
                            <br>
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <div class=" d-flex flex-column align-items-center">
                                    <img src="{{ $foto->temporaryUrl() }}"
                                        style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                        class="img-thumbnail">
                                </div>
                            @endif
                            <br>
                        @elseif ($fotoAntigua)
                            <br>
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
                        <span class="h5 font-weight-bold">{{ $edad }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_afiliacion"> FECHA DE INGRESO A SSU: </label>
                        <input class="form-control" name="fecha_afiliacion" id="fecha_afiliacion"
                            style=" border-color:white; border-radius:10px;" disabled wire:model=fecha_afiliacion>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_reafiliacion"> FECHA DE AFILIACIÓN: </label>
                        <input class="form-control" name="fecha_reafiliacion"
                            style=" border-color:white; border-radius:10px;" id="fecha_reafiliacion" disabled
                            value="{{ $fecha_reafiliacion_r }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="fechaVencimientoCarnet"> FECHA DE VENCIMIENTO DE CARNET: </label>
                        <input class="form-control" name="fechaVencimientoCarnet"
                            style=" border-color:white; border-radius:10px;" id="fechaVencimientoCarnet" disabled
                            value="{{ $fechaVencimientoCarnet }}">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <br>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if ($file_digital)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="#2471a3" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="#2471a3" class="bi bi-upload" viewBox="0 0 16 16">
                                            <path
                                                d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                            <path
                                                d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                                        </svg><em class="ml-2" style="color:#2471a3"><b>Subir File
                                                Digital:</b> </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input class="form-control @error('file_digital') is-invalid @enderror" type="file"
                                id="formFiledigital" name="file_digital" required wire:model="file_digital"
                                style="border-radius:8px" accept=".pdf">
                            @error('file_digital')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div wire:loading wire:target="file_digital"> <x-cargando-dos /> </div>
                    </div>
                </div>
                <br>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!--  Boton para los aportes institucionales -->
                    @if ($tipo_asegurado == 0)
                        <div>
                            <x-aportes-button wire:click="verAportesIns">VER
                                APORTES</x-aportes-button>
                        </div>
                    @endif
                    <!--  Boton para los aportes Voluntarios -->
                    @if ($tipo_asegurado == 1)
                        <div>
                            <x-aportes-button wire:click="verAportesVol"> VER
                                APORTESv</x-aportes-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="nombre"> NOMBRE COMPLETO:</label>
                        <input class="form-control" style=" border-color:white; border-radius:10px;" name="nombre"
                            id="nombre" disabled wire:model=nombre_completo>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="carnet">C.I.:</label>
                        <input class="form-control" name="carnet" id="carnet"
                            style=" border-color:white; border-radius:10px;" disabled wire:model=carnet>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="departamentoExpedido"> EXP.:</label>
                        <input class="form-control" id="departamentoExpedido" disabled
                            style=" border-color:white; border-radius:10px;" wire:model=departamentoExpedido>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="tipo_documento"> TIPO C.I.:</label>
                        <input class="form-control" id="tipo_documento"
                            style=" border-color:white; border-radius:10px;" disabled wire:model=tipo_documento>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="sexo">SEXO:</label>
                        <br>
                        @if ($sexo == 'F')
                            <input class="form-control" id="sexo"
                                style=" border-color:white; border-radius:10px;" disabled value="FEMENINO">
                        @elseif($sexo == 'M')
                            <input class="form-control" id="sexo"
                                style=" border-color:white; border-radius:10px;" disabled value="MASCULINO">
                        @endif
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_nacimiento"> FECHA NACIMIENTO:</label>
                        <input class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" disabled
                            style=" border-color:white; border-radius:10px;" wire:model=fecha_nacimiento>
                    </div>

                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <label for="id_estado_civil">ESTADO CIVIL:</label>
                        <select wire:model.live="id_estado_civil" id="id_estado_civil"
                            class="form-control @error('id_estado_civil') is-invalid @enderror select2 "
                            style="padding-rigth:1;  border-radius:10px;" tabindex="-1">
                            <option selected="selected" value="{{ $id_estado_civil }}"> {{ $estado_civil }}
                            </option>
                            @foreach ($estadoCivilSelect as $civilSelected)
                                <option value="{{ $civilSelected->id }}">{{ $civilSelected->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="departamento"> DEPARTAMENTO NACIMIENTO:</label>
                        <input class="form-control" id="departamento" disabled
                            style=" border-color:white; border-radius:10px;" wire:model=departamento>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="provincia"> PROVINCIA NACIMIENTO:</label>
                        <input class="form-control" id="provincia" disabled
                            style=" border-color:white; border-radius:10px;" wire:model=provincia>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="municipio"> MUNICIPIO NACIMIENTO:</label>
                        <input class="form-control" id="municipio" disabled
                            style=" border-color:white; border-radius:10px;" wire:model=municipio>
                    </div>
                </div>

                <br>
            </div>
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <!-- DATOS REFERENCIALES -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Ingrese los datos
                                referenciales:</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono"> TELEFONO:</label>
                                <input class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                    id="telefono" style="border-radius:10px" wire:model.live=telefono>
                                @error('telefono')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="correo"> CORREO:</label>
                                <input class="form-control " name="correo" style="border-radius:10px"
                                    id="correo" wire:model.live=correo>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre_referencia"> NOMBRE DE REFERENCIA:</label>
                                <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                    name="nombre_referencia" id="nombre_referencia" style="border-radius:10px"
                                    wire:model.live=nombre_referencia oninput="this.value = this.value.toUpperCase()">
                                @error('nombre_referencia')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono_referencia"> TELEFONO DE REF.: </label>
                                <input class="form-control @error('telefono_referencia') is-invalid @enderror"
                                    name="telefono_referencia" id="telefono_referencia" style="border-radius:10px"
                                    wire:model.live=telefono_referencia>
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
                                    class="form-control @error('zonaId') is-invalid @enderror select2 "
                                    style="padding-rigth:1;  border-radius:10px;" tabindex="-1">
                                    <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                    </option>
                                    @foreach ($zonasSelect as $zonaSelected)
                                        <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nro_vivienda"> NRO. VIVIENDA: </label>
                                <input class="form-control @error('nro_vivienda') is-invalid @enderror"
                                    name="nro_vivienda" style="border-radius:10px" id="nro_vivienda"
                                    wire:model.live=nro_vivienda>
                                @error('nro_vivienda')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="direccion"> DIRECCIÓN:</label>
                                <input class="form-control @error('direccion') is-invalid @enderror" name="direccion"
                                    id="direccion" type='text' style="padding-rigth:1;  border-radius:10px;"
                                    oninput="this.value = this.value.toUpperCase()" wire:model.live=direccion>
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
                                <button class="btn btn-primary " type="button" data-toggle="collapse"
                                    style="background-color:#1E6BA2; width:70%" data-target="#collapseExample"
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
                                    <span style="font-size: 18px;" class="text-success">Seleccionado Correctamente
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-street-view fa-stack-1x"></i>

                                        </span></span>
                                @endif
                                <div class="collapse" id="collapseExample" wire:ignore>
                                    <div class="card card-body">
                                        @include('livewire.Afiliacion.titulares.ubicacion_titulares_reafiliacion')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-12" style="margin-bottom: 10px;">
                                <strong>TIPO DE ASEGURADO:</strong>
                                <div style="display: flex; align-items: center; flex-wrap: wrap;">
                                    <strong class="text-red "
                                        style=" margin-right: 5px; white-space: nowrap;font-size:">INSTITUCIONAL</strong>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-red custom-switch-on-blue mx-1">
                                        <input wire:model="tipo_asegurado" id="customSwitch3" type="checkbox"
                                            class="custom-control-input" wire:change='OcultarAgregar()'
                                            {{ $tipo_asegurado == true ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="customSwitch3"></label>
                                    </div>
                                    <strong class="text-blue"
                                        style="color: #17a2b8; white-space: nowrap;">VOLUNTARIO</strong>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="sangre"> TIPO DE SANGRE:</label>
                                <input class="form-control" name="sangre" id="sangre" disabled
                                    style="border-color:white; border-radius:10px;" wire:model=sangre>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="alergias"> ALERGIAS:</label>
                                <textarea class="form-control" name="alergias" id="alergias" oninput="this.value = this.value.toUpperCase()"
                                    style="border-radius:10px" wire:model=alergias> </textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="observacion"> OBSERVACIONES: </label>
                                <textarea class="form-control" name="observacion" id="observacion" oninput="this.value = this.value.toUpperCase()"
                                    style="border-radius:10px" wire:model=observacion></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- AGREGAR INSTITUCIÓN Y VER APORTES -->
                <div class="row">
                    <div class="col-lg-6" style="display: flex; justify-content: center;">
                        <!--modal para AGREGAR INSTITUCION-->
                        @include('livewire.Afiliacion.titulares.modalReafiliacionAddInst')
                        <!--modal para VER APORTES-->
                        <!--modal para VER APORTES insitutcionalex-->
                        @include('livewire.Afiliacion.titulares.modalVerAportesIns')
                        <!--modal para VER APORTES voluntarios-->
                        @include('livewire.Afiliacion.titulares.modalVerAportesVol')
                    </div>
                </div>
            </div>
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
                                    <input wire:model="permanente" id="customSwitchpermanente" type="checkbox"
                                        class="custom-control-input" {{ $permanente == true ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitchpermanente"></label>
                                </div>
                                <strong class="text-red"> PERMANENTE</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if ($esRentista == true)
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipo_rentista" style="color:#0b61b7">TIPO DE RENTISTA:</label>
                                <select wire:model="tipo_rentista" name="tipo_rentista" id="tipo_rentista"
                                    class="form-control" style="width: 100%; border-radius:10px" tabindex="-1"
                                    required>
                                    <option selected="selected">Seleccione..
                                    </option>
                                    <option value="TITULAR-RENTISTA">TITULAR-RENTISTA</option>
                                    <option value="TITULAR-SORTEO">TITULAR-SORTEO</option>
                                    <option value="RENTISTA-INVALIDEZ">RENTISTA-INVALIDEZ</option>
                                    <option value="VIUDEDAD">VIUDEDAD</option>
                                    <option value="ORFANDAD">ORFANDAD</option>
                                </select>
                            </div>
                        @endif
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
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
                            <x-agregar-button onclick="modalAddInstReafiliar()"
                                title="AGREGAR INSTITUCIÓN">INSTITUCIÓN</x-agregar-button>
                        </div>
                    </div>
                    {{-- 06/02/2025  copiar lo mismo qie instituciones nuevooo --}}
                    @foreach ($instituciones as $index => $institucion)
                        <hr>
                        <div class="row">
                            <input type="hidden" value="{{ $institucion['id_institucion'] }}">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre_institucion"> INSTITUCIÓN
                                    {{ $index + 1 }}:
                                </label>
                                <input class="form-control" name="nombre_institucion"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;"
                                    id="nombre_institucion" disabled value="{{ $institucion['institucion'] }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="codigo"> COD TIT.:</label>
                                <input class="form-control" name="codigo" id="codigo"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                    value="{{ $institucion['codigo_titular_ver'] }}">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="codigo"> COD BEN.:</label>
                                <input class="form-control" name="codigo" id="codigo"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                    value="{{ $institucion['codigo_beneficiario_ver'] }}">
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipo_institucion"> TIPO INSTITUCIÓN:</label>
                                <input class="form-control" name="tipo_institucion"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;"
                                    id="tipo_institucion" disabled value="{{ $institucion['tipo_institucion'] }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="cargo"> CARGO:</label>
                                <input class="form-control" name="cargo" id="cargo"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                    value="{{ $institucion['cargo'] }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_ingreso"> FECHA INGRESO:</label>
                                <input class="form-control" name="fecha_ingreso" id="fecha_ingreso"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
                                    value="{{ $institucion['fecha_ingreso'] }}">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="salario"> SALARIO: </label>
                                <input class="form-control" name="salario" id="salario"
                                    style="padding-rigth:1; border-color:white; border-radius:10px;" disabled
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
        </div>
    </div>
    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarReafiliacion"> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardarTitular()">GUARDAR</x-save-button>
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
    window.addEventListener('modalReafiliacion', event => {
        $('#modalReafiliar').modal('show');
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
                Livewire.dispatch('guardarReafiliacion');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }


    document.addEventListener('titularReafiliado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente",
            icon: "success",
            showConfirmButton: false,
            timer: 4000,

        });
    });

    document.addEventListener('titularCancelado', function() {
        Swal.fire({
            title: "Cancelado!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });

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
    document.addEventListener('institucionNoIngresada', function() {
        Swal.fire({
            title: "¡Agregue Institución!",
            text: "No olvide ingresar los datos institucionales",
            icon: "warning"
        });
    });
    // CASO DE QUE SEA TITULAR VIGENTE 
    document.addEventListener('esTitular', function() {
        Swal.fire({
            title: "¡El titular sigue vigente!",
            text: "El asegurado actualmente se encuentra afiliado como Titular VIGENTE, si lo registras se dará de baja el anterior registro",
            icon: "warning",
            timer: 5000,

        });
    });
    // CASO DE QUE SEA BENEFICIARIO VIGENTE 
    document.addEventListener('esBeneficiario', function() {
        Swal.fire({
            title: "¡No se puede realizar el registro!",
            text: "El asegurado actualmente se encuentra afiliado como Beneficiario",
            icon: "error"

        });
    });
    // CASO DE QUE SEA ESTUDAINTE VIGENTE 
    document.addEventListener('esEstudiante', function() {
        Swal.fire({
            title: "¡No se puede realizar el registro!",
            text: "El asegurado actualmente se encuentra afiliado como Estudiante",
            icon: "error"
        });
    });

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
</script>
