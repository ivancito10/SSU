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
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REAFILIACIÓN DE
                ASEGURADO BENEFICIARIO</h7>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>
    <br>
    <div class="row">
        <!-- La foto del beneficiario  -->
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center; border-radius:8px"> <b> BENEFICIARIO</b></p>
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if ($errors->has('error_message'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error_message') }}
                            </div>
                        @endif
                        <input class="form-control form-control-sm @error('foto') is-invalid @enderror" type="file"
                            id="formFile" name="archivo" required wire:model.ignore="foto" accept=".jpg,.jpeg,.png">
                    </div>
                    <div class="col-lg-12">
                        <div wire:loading wire:target="foto"> <x-cargando-dos /> </div>
                        @if ($foto)
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <div class=" d-flex flex-column align-items-center">
                                    <img src="{{ $foto->temporaryUrl() }}"
                                        style="width:200px; height:200px; object-fit:cover; object-position: top;"
                                        class="img-thumbnail">
                                </div>
                            @endif
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
                        <span class="h5 font-weight-bold">{{ $edad }} años</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        {{--     <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoBeneficiario }} </span> --}}
                    </div>
                </div>

                @if ($file_digital)
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
                @else
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
                @endif
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    @if ($errors->has('error_message'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error_message') }}
                        </div>
                    @endif
                    <input class="form-control form-control-sm @error('file_digital') is-invalid @enderror"
                        type="file" id="formFiledigital" name="file_digital" required wire:model="file_digital"
                        accept=".pdf">
                </div>
                <div wire:loading wire:target="file_digital"> <x-cargando-dos /> </div>
                <hr>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="fecha_afiliacion_vista"> FECHA INGRESO A SSU: </label>
                    <input class="form-control" id="fecha_afiliacion_vista" wire:model=fecha_afiliacion_vista disabled
                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <label for="fechaVencimientoCarnet"> FECHA DE VENCIMIENTO CARNET: </label>
                    <input class="form-control" id="fechaVencimientoCarnet" wire:model=fechaVencimientoCarnet disabled
                        style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                </div>
            </div>
        </div>

        <!--    datos personales -->
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="nombre"> NOMBRE COMPLETO: </label>
                        <input class="form-control" id="nombre" wire:model=nombre disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                        <label for="ci"> C.I.: </label>
                        <input class="form-control" id="ci" wire:model=ci disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                        <label for="expedido_ci">EXPEDIDO:</label>
                        <input class="form-control" id="expedido_ci" wire:model="expedido_ci" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="sexo"> SEXO:</label>
                        <input class="form-control" wire:model="sexo" id="sexo" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12' wire:ignore>
                        <label for="es_extranjero">TIPO DOC.:</label>
                        <input class="form-control" wire:model="es_extranjero" id="es_extranjero" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_nacimiento"> FECHA DE NACIMIENTO: </label>
                        <input class="form-control" id="fecha_nacimiento" wire:model="fecha_nacimiento"
                            type="date" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                        <label for="id_estado_civil"> ESTADO CIVIL:</label>
                        <select wire:model="id_estado_civil" id="id_estado_civil" class="form-control select2"
                            style="width: 100%;border-radius:10px;" tabindex="-1" required>
                            <option value="{{ $id_estado_civil }}">{{ $estado_civil }}</option>
                            @foreach ($estadosCivilesSelect as $estadoCivilSelect)
                                <option value="{{ $estadoCivilSelect->id }}">
                                    {{ $estadoCivilSelect->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <label for="dep_nacimiento">DEPARTAMENTO NAC.:</label>
                        <input class="form-control" wire:model="dep_nacimiento" id="dep_nacimiento" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="pro_nacimiento"> PROVINCIA NAC.:</label>
                        <input class="form-control" wire:model.live="pro_nacimiento" id="pro_nacimiento" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="mun_nacimiento"> MUNICIPIO NAC.:</label>
                        <input class="form-control" wire:model="mun_nacimiento" id="mun_nacimiento" disabled
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                        <label for="aseguradoId">TIPO DE BENEFICIARIO:</label>
                        <select wire:model.live="aseguradoId" id="aseguradoId"
                            class="form-control @error('aseguradoId') is-invalid @enderror  select2"
                            style="width: 100%;border-radius:10px;" tabindex="-1" required
                            wire:change="cambioTipoAsegurado">
                            <option value="{{ $aseguradoId }}">{{ $tipo_aseguradoBen }}</option>
                            @foreach ($tipoBeneficiarioSelect as $tipoBeneficiario)
                                <option value="{{ $tipoBeneficiario->id }}">
                                    {{ $tipoBeneficiario->tipo_asegurado }}
                                </option>
                            @endforeach
                        </select>
                        @error('aseguradoId')
                            <div class="text-danger">
                                <b> El campo tipo de asegurado es obligatorio. </b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="customSwitch3" style="color:#1861AF">¿BENEFICIARIO CON
                            TRASPASO DE APORTES?</label>
                        <div class="col-sm-5 d-flex align-items-center">
                            <strong class=" text-red">NO</strong>
                            <div
                                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input wire:model.live="con_remuneracion" type="checkbox"
                                    class="custom-control-input" id="customSwitch3">
                                <label class="custom-control-label" for="customSwitch3"
                                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                            </div>
                            <strong class="text-green">SI</strong>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg> <em class=" h5 ml-2" style="color:#2471a3"><b>Ingrese los datos
                                referenciales:</b> </em>
                        <spam style="color:#cb4335;"><b>*</b></spam>
                    </p>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono"> TELEFONO:</label>
                                <input class="form-control @error('telefono') is-invalid @enderror" id="telefono"
                                    style="border-radius:10px" wire:model.live=telefono>
                                @error('telefono')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="correo"> CORREO:</label>
                                <input class="form-control @error('correo') is-invalid @enderror" id="correo"
                                    style="border-radius:10px" wire:model.live=correo>
                                @error('correo')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre_referencia"> NOMBRE DE REFERENCIA: </label>
                                <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                    id="nombre_referencia" wire:model.live=nombre_referencia
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px">
                                @error('nombre_referencia')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono_referencia"> TELF. REFERENCIA:</label>
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
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="zonaId">ZONA:</label>
                                <select wire:model="zonaId" name="zonaId" id="zonaId"
                                    class="form-control @error('zonaId') is-invalid @enderror select2 "
                                    style="width: 100%;border-radius:10px" tabindex="-1">
                                    <option selected="selected"> {{ $zona }} </option>
                                    @foreach ($zonasSelect as $zonaSelected)
                                        <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nro_vivienda"> NRO VIVIENDA: </label>
                                <input class="form-control @error('nro_vivienda') is-invalid @enderror"
                                    id="nro_vivienda" wire:model.live=nro_vivienda disaled style="border-radius:10px">
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
                                    oninput="this.value = this.value.toUpperCase()" style="border-radius:10px"
                                    wire:model.live=direccion>
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
                                    data-target="#collapseExample" aria-expanded="false"
                                    style="background-color:#1E6BA2; width:50%" aria-controls="collapseExample">
                                    <i class="fa fa-street-view" aria-hidden="true"></i> Agregar ubicación
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
                                        @include('livewire.Afiliacion.beneficiarios.ubicacion_beneficiario')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="grupo_sanguineo"> GRUPO SANGUINEO: </label>
                                <input class="form-control" id="grupo_sanguineo" wire:model=grupo_sanguineo disabled
                                    style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:10px;">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="alergias"> ALERGIAS: </label>
                                <textarea class="form-control" id="alergias" oninput="this.value = this.value.toUpperCase()" wire:model=alergias
                                    rows="3" style="border-radius:10px"> </textarea>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="observacion"> OBSERVACIONES: </label>
                                <textarea class="form-control" id="observacion" oninput="this.value = this.value.toUpperCase()"
                                    wire:model=observacion rows="3" style="border-radius:10px"> </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--DATOS INSITUCIONALES EN CASO DE REMUNERACION-->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if ($con_remuneracion)
                    <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        <p style="background-color:#c4dff3 ; text-align:center"> <b> DATOS
                                INSTITUCIONALES - TRANSFERENCIA</b></p>
                        <div class="row">
                            <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                                <label for="id_institucion">INSTITUCIÓN:</label>
                                <select wire:model="id_institucion" name="id_institucion" id="id_institucion"
                                    class="form-control select2 @error('id_institucion') is-invalid @enderror"
                                    style=" border-radius:10px;" tabindex="-1" required>
                                    <option selected="selected" value="{{ $id_institucion }}"> {{ $id_institucion }}
                                    </option>
                                    @foreach ($institucionSelect as $institucion)
                                        <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="fechaIngresoBenIns"> FECHA DE INGRESO: </label>
                                <input class="form-control  @error('fechaIngresoBenIns') is-invalid @enderror "
                                    type="date" style=" border-radius:10px;" id="fechaIngresoBenIns"
                                    wire:model.live="fechaIngresoBenIns">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="cargoBenIns"> CARGO: </label>
                                <input class="form-control  @error('cargoBenIns') is-invalid @enderror "
                                    id="cargoBenIns" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="Ingrese el cargo del beneficiario.." style=" border-radius:10px;"
                                    wire:model.live="cargoBenIns">

                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="salarioBenIns"> SALARIO: </label>
                                <input class="form-control  @error('salarioBenIns') is-invalid @enderror "
                                    type="numeric" style=" border-radius:10px;" id="salarioBenIns"
                                    placeholder="Ingrese el salario del beneficiario.."
                                    wire:model.live="salarioBenIns">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="itemBenIns"> ITEM: </label>
                                <input class="form-control  @error('itemBenIns') is-invalid @enderror "
                                    id="itemBenIns" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="Ingrese el item del beneficiario.." style=" border-radius:10px;"
                                    wire:model.live="itemBenIns">

                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="cargaHorariaBenIns"> CARGA HORARIA: </label>
                                <select wire:model="cargaHorariaBenIns" name="cargaHorariaBenIns"
                                    id="cargaHorariaBenIns"
                                    class="form-control select2 @error('cargaHorariaBenIns') is-invalid @enderror"
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
                                <label for="titularidadBenIns"> TITULARIDAD: </label>
                                <select wire:model="titularidadBenIns" name="titularidadBenIns"
                                    id="titularidadBenIns"
                                    class="form-control select2 @error('titularidadBenIns') is-invalid @enderror"
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
                @endif
            </div>
        </div>
        <!--  Datos del titular -->
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:{{ $color_titular }} ;text-align:center;color:white; border-radius:8px">
                    <b> DATOS DEL
                        TITULAR</b>
                </p>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @livewire('Afiliacion.beneficiarios.beneficiarios-cambio-titular')
                    </div>
                </div>
                <br>
                <div class="row d-flex flex-column align-items-center pt-0">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto_titular_mostrar) ? 'data:image/jpg;base64,' . $foto_titular_mostrar : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail"
                        style="width:200px; height:200px; object-fit:cover; object-position: top;"
                        alt="FTP.{{ $foto }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                        style="display: flex; justify-content: center; font-size: 25px">
                        @if ($vigenciaTitular == 'VIGENTE')
                            <span class="h4" style="color: green;"><b>VIGENTE</b></span>
                        @elseif($vigenciaTitular == 'CESANTE')
                            <span class="h4" style="color: yellow;"><b>CESANTE</b></span>
                        @elseif($vigenciaTitular == 'NO VIGENTE')
                            <span class="parpadeo h4" style="color: red;"><b>NO
                                    VIGENTE</b></span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matriculaTit }}
                        </span>
                       {{--  <span class=" font-weight-bold h5" style="color: #236cb6; ">
                            {{ $codigoTitular }} </span> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold">{{ $tipo_titutlar }}
                            {{ $titularPermanente }} </span>
                    </div>
                </div>
                @if ($tipo_titutlar == 'INSTITUCIONAL')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <span class=" font-weight-bold h5">{{ $nombreInstitucionTit }}
                            </span>
                        </div>
                    </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            {{ $nombreCompletoTitular }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> CARNET: </strong>
                        <p class="p-1" style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                            {{ $ciTitular }}</p>
                    </div>
                </div>
                @if ($tipo_titutlar == 'INSTITUCIONAL')
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> CARGO: </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                                {{ $cargoTit }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> SALARIO: </strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8; border-color:white; border-radius:10px;">
                                {{ $salarioTit }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button wire:click="cancelarBen"> CANCELAR</x-cancel-button>
        <x-save-button onclick="reafiliarBen()">GUARDAR</x-save-button>
    </div>
    <!-- funncion -->
    <script>
        function reafiliarBen() {
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
                    document.getElementById('cargando').style.display = 'block';
                    Livewire.dispatch('reafiliarBeneficiario');
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('beneficiarioCancelado', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "Se canceló el registro",
                icon: "error"

            });
        });

        // es menor a 19 años
        document.addEventListener('beneficiarioMenor19Años', function() {
            Swal.fire({
                title: "¡Menor de 19 años!",
                text: "El beneficiario tiene MENOS de 19 años, no se puede registrar como BENEFICIARIO MAYOR A 19 AÑOS",
                icon: "error"
            });
        });
        // es mayor a 25 años
        document.addEventListener('beneficiarioMayor25Años', function() {
            Swal.fire({
                title: "¡Mayor de 25 años!",
                text: "El beneficiario tiene MÁS de 25 años, no se puede registrar como BENEFICIARIO MAYOR A 19 AÑOS",
                icon: "error"
            });
        });
        // es mayor a 19 años
        document.addEventListener('beneficiarioMayor19años', function() {
            Swal.fire({
                title: "¡Mayor de 19 años!",
                text: "El beneficiario tiene MÁS de 19 años, no se puede registrar como BENEFICIARIO HIJO MENOR A 19 AÑOS",
                icon: "error"
            });
        });

        // es mayor a 1 año
        document.addEventListener('beneficiarioBebe', function() {
            Swal.fire({
                title: "¡Mayor de 1 año!",
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

        // CASO CAMBIO DE TITULAR ES ESTUDIANTE
        document.addEventListener('noEstitular', function() {
            Swal.fire({
                title: "¡No es titular!",
                text: "La matricula emitida no pertenece a un titular vigente",
                icon: "warning"

            });
        });

        // CASO TITULAR NO VIGFENTE
        document.addEventListener('noEncuentratitular', function() {
            Swal.fire({
                title: "¡Titular no encontrado!",
                text: "La matricula emitida no pertenece a un titular vigente",
                icon: "warning"

            });
        });
        // CASO NO INSERTO DATOS
        document.addEventListener('sinDatosIngresados', function() {
            Swal.fire({
                title: "¡No ingresó datos!",
                text: "Porfavor ingrese la matricula para realizar la busqueda",
                icon: "warning"

            });
        });
        // CASO DE QUE SEA TITULAR VIGENTE 
        document.addEventListener('esTitular', function() {
            Swal.fire({
                title: "¡No se puede realizar el registro!",
                text: "El asegurado actualmente se encuentra afiliado como Titular",
                icon: "error"

            });
        });
        // CASO DE QUE SEA BENEFICIARIO VIGENTE 
        document.addEventListener('esBeneficiario', function() {
            Swal.fire({
                title: "¡El beneficiario sigue vigente!",
                text: "El asegurado actualmente se encuentra afiliado como Beneficiario VIGENTE, si lo registras se dará de baja el anterior registro",
                icon: "warning",
                timer: 5000,

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
        // CASO DE QUE NO PONGA EL TIPO DE ASEGURADO 
        document.addEventListener('ingresaTipoAsegurado', function() {
            Swal.fire({
                title: "Ingresa el tipo de beneficiario",
                text: "Debes seleccionar el TIPO DE BENEFICIARIO para continuar con el registro",
                icon: "warning"
            });
        });
        // CASO DE QUE EL TITULAR ESTE NO VIGENTE
        document.addEventListener('titularNoVigente', function() {
            Swal.fire({
                title: "Titular NO VIGENTE",
                text: "No puede reafiliar si el titular no tiene vigencia.",
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


        document.addEventListener('beneficiarioCreado', function() {
            Swal.fire({
                title: "Guardado",
                text: "Los datos se han guardado exitosamente.",
                icon: "success"
            });
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
