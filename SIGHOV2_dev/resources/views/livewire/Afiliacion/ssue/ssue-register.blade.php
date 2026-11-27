<div>
    @can('registro.afiliacion_ssue')
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#1d4e6f ; color:white;text-align:center; border-radius:10px"> <b>
                            ESTUDIANTE</b></p>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#2471a3"
                                class="bi bi-cloud-upload-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0" />
                            </svg>
                            @if ($foto)
                                <label for="formFile" class="form-label" style="color:#2471a3">SELECCIONAR
                                    FOTO: <spam style="color:#cb4335;"><b>*</b></spam> </label>
                            @else
                                <label for="formFile" class="form-label" style="color:#66a9d7">SELECCIONAR
                                    FOTO: <spam style="color:#cb4335;"><b>*</b></spam> </label>
                            @endif
                            @if ($errors->has('error_message'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('error_message') }}
                                </div>
                            @endif
                            <input class="form-control @error('foto') is-invalid @enderror" type="file" id="formFile"
                                name="archivo" required wire:model.ignore="foto"
                                accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                            @error('foto')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror

                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <div wire:loading wire:target="foto">
                                <x-cargando-dos />
                            </div>
                            @if ($foto)
                                @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                    <img src="{{ $foto->temporaryUrl() }}"
                                        style=" width: 200px; height: 200px; object-fit: cover;" class="img-thumbnail">
                                @endif
                                <p style="font-size: 18px;" text-align="center">Edad: <strong>{{ $this->edad }}</strong>
                                    Años.</p>
                            @endif
                        </div>
                    </div>
                    <div class="card-header p-1"style="background-color:#e5f0f9 ; border: 1px solid #59a9d7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#2471a3"
                            class="bi bi-cloud-upload-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0" />
                        </svg>
                        <b>Subir documentos en formato PDF</b>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($file_fotocopiaCarnet)
                                <label for="formfile_fotocopiaCarnet" class="form-label" style="color:#1f5c85">SUBIR
                                    FOTOCOPIA
                                    DE CARNET:
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                </label>
                            @else
                                <label for="formfile_fotocopiaCarnet" class="form-label" style="color:#66a9d7">SUBIR
                                    FOTOCOPIA
                                    DE CARNET: <spam style="color:#cb4335;"><b>*</b></spam>
                                </label>
                            @endif
                            <input class="form-control @error('file_fotocopiaCarnet') is-invalid @enderror" type="file"
                                id="formfile_fotocopiaCarnet" required wire:model.live="file_fotocopiaCarnet"
                                accept=".pdf">
                        </div>
                        <div wire:loading wire:target="file_fotocopiaCarnet">
                            <x-cargando-dos />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($file_matricula)
                                <label for="formfile_matricula" class="form-label" style="color:#1f5c85">SUBIR MATRICULA:
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                </label>
                            @else
                                <label for="formfile_matricula" class="form-label" style="color:#66a9d7">SUBIR MATRICULA:
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </label>
                            @endif
                            <input class="form-control @error('file_matricula') is-invalid @enderror" type="file"
                                id="formfile_matricula" required wire:model.live="file_matricula" accept=".pdf">
                        </div>
                        <div wire:loading wire:target="file_matricula">
                            <x-cargando-dos />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($file_boletaInscripcion)
                                <label for="formfile_boletaInscripcion" class="form-label" style="color:#1f5c85">SUBIR
                                    BOLETA DE
                                    INSCRIPCIÓN: <spam style="color:#cb4335;"><b>*</b></spam>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                </label>
                            @else
                                <label for="formfile_boletaInscripcion" class="form-label" style="color:#66a9d7">SUBIR
                                    BOLETA DE
                                    INSCRIPCIÓN: <spam style="color:#cb4335;"><b>*</b></spam>
                                </label>
                            @endif
                            <input class="form-control @error('file_boletaInscripcion') is-invalid @enderror"
                                type="file" id="formfile_boletaInscripcion" required
                                wire:model.live="file_boletaInscripcion" accept=".pdf">
                        </div>
                        <div wire:loading wire:target="file_boletaInscripcion">
                            <x-cargando-dos />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($file_enteGestor)
                                <label for="formfile_enteGestor" class="form-label" style="color:#1f5c85">SUBIR FORM NO
                                    AFLIACÍÓN- GESTORA: <spam style="color:#cb4335;"><b>*</b></spam>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg></label>
                            @else
                                <label for="formfile_enteGestor" class="form-label" style="color:#66a9d7">SUBIR FORM NO
                                    AFLIACÍÓN- GESTORA: <spam style="color:#cb4335;"><b>*</b></spam> </label>
                            @endif
                            <input class="form-control @error('file_enteGestor') is-invalid @enderror" type="file"
                                id="formfile_enteGestor" required wire:model.live="file_enteGestor" accept=".pdf">
                        </div>
                    </div>
                    <div wire:loading wire:target="file_enteGestor">
                        <x-cargando-dos />
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if ($file_certificadoCajas)
                                <label for="formfile_certificadoCajas" class="form-label" style="color:#1f5c85">SUBIR
                                    FORMULARIO 101: <spam style="color:#cb4335;"><b>*</b></spam>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green"
                                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                </label>
                            @else
                                <label for="formfile_certificadoCajas" class="form-label" style="color:#66a9d7">SUBIR
                                    FORMULARIO 101: <spam style="color:#cb4335;"><b>*</b></spam>
                                </label>
                            @endif
                            <input class="form-control @error('file_certificadoCajas') is-invalid @enderror"
                                type="file" id="formfile_certificadoCajas" required
                                wire:model.live="file_certificadoCajas" accept=".pdf">
                        </div>
                        <div wire:loading wire:target="file_certificadoCajas">
                            <x-cargando-dos />
                        </div>
                    </div>
                    <br>
                    @if ($tiene_grupo_sanguineo == false)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($file_tipo_sangre)
                                    <label for="formfile_tipo_sangre" class="form-label" style="color:#1f5c85">SUBIR TIPO
                                        DE
                                        SANGRE:
                                        <spam style="color:#106faf;"><b> (opcional)</b>
                                        </spam>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    </label>
                                @else
                                    <label for="formfile_tipo_sangre" class="form-label" style="color:#66a9d7">SUBIR TIPO
                                        DE
                                        SANGRE:

                                        <spam style="color:#106faf;"><b> (opcional)</b>
                                        </spam>
                                    </label>
                                @endif
                                <input class="form-control " type="file" id="formfile_tipo_sangre" required
                                    wire:model.live="file_tipo_sangre" accept=".pdf">
                            </div>
                            <div wire:loading wire:target="file_tipo_sangre">
                                <x-cargando-dos />
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                <!-- Datos personales -->

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <span class=" font-weight-bold">ESTUDIANTE :</span>
                    <span class="h4 font-weight-bold" style="color:#1a4c72">{{ $afiliacion_txt }} </span>
                </div>

                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header p-1" style="background-color:#e5f0f9 ; border: 1px solid #59a9d7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2471a3"
                            class="bi bi-pencil-square " viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <b class="pl-2 ">Ingrese los datos personales: </b>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombres"> NOMBRES:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control" id="nombres" disabled wire:model=nombres
                                style=" border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="p_apellido"> PRIMER APELLIDO:</label>
                            <input class="form-control" id="p_apellido" disabled wire:model=p_apellido
                                style=" border-radius:10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="s_apellido"> SEGUNDO APELLIDO:</label>
                            <input class="form-control" id="s_apellido" disabled wire:model=s_apellido
                                style=" border-radius:10px;">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> C.I.:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control" id="ci" disabled wire:model=ci
                                style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="complemento"> COMP.:</label>
                            <input class="form-control" id="complemento" disabled wire:model=complemento
                                style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                        </div>
                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="expedido_ci">EXPEDIDO:</label>
                            <select wire:model.live="expedido_ci" id="expedido_ci"
                                @if ($expedido_ciSwitch) disabled @endif class="form-control  select2"
                                style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                <option selected="selected">Seleccione..
                                </option>
                                @foreach ($this->departamento as $dep)
                                    <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:<spam style="color:#cb4335;"><b>*</b>
                                </spam></label>
                            <input class="form-control" type="date" id="fecha_nacimiento" wire:model=fecha_nacimiento
                                wire:change="GenerarMatricula" @if ($fecha_nacimientoSwitch) disabled @endif
                                style="border-radius:10px;" min="{{ $fechaNacimientoMaximoConvenio }}">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo"> SEXO:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <label class="mr-2">M</label>
                                <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                                    <input wire:model="sexo" name="sexo" type="checkbox" class="custom-control-input"
                                        id="customSwitch2" wire:change="GenerarMatricula"
                                        @if ($matriculaSwitch) disabled @endif>
                                    <label class="custom-control-label" for="customSwitch2"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">F</label>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula">MATRICULA:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control @error('matricula') is-invalid @enderror " id="matricula" disabled
                                wire:model=matricula disabled style="  border-radius:10px;">
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="es_extranjero">TIPO C.I.:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <select wire:model.live="es_extranjero" name="es_extranjero" id="es_extranjero"
                                class="form-control @error('es_extranjero') is-invalid @enderror select2"
                                wire:change='esExtranjeroFunction()' style="width: 100%; border-radius:10px;"
                                tabindex="-1" required disabled>
                                <option selected="selected" disabled>Seleccione..
                                </option>
                                <option value="false">NACIONAL</option>
                                <option value="true">EXTRANJERO</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="id_estado_civil"> ESTADO CIVIL:<spam style="color:#cb4335;"><b>*</b></spam>
                            </label>
                            <select wire:model="id_estado_civil" name="id_estado_civil"
                                @if ($id_estado_civilSwitch) disabled @endif
                                class="form-control @error('id_estado_civil') is-invalid @enderror select2"
                                style="width: 100%; border-radius:10px;" tabindex="-1" required
                                wire:change="GenerarMatricula">
                                <option selected="selected">Selecciona estado
                                    civil... </option>
                                @foreach ($estadosCiviles as $estado_civil)
                                    <option value="{{ $estado_civil->id }}">{{ $estado_civil->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        @if ($datosLugarNacimiento === true)
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="dep_nacimiento_nombre">DEP. NAC.:</label>
                                <input class="form-control" id="dep_nacimiento_nombre" wire:model=dep_nacimiento_nombre
                                    disabled style="border-radius:10px">
                            </div>

                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="pro_nacimiento_nombre">PRO. NAC.:</label>
                                <input class="form-control" id="pro_nacimiento_nombre" wire:model=pro_nacimiento_nombre
                                    disabled style="border-radius:10px">
                            </div>

                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="mun_nacimiento_nombre">MUN. NAC.:</label>
                                <input class="form-control" id="mun_nacimiento_nombre" wire:model=mun_nacimiento_nombre
                                    disabled style="border-radius:10px">
                            </div>
                        @else
                            @if ($es_extranjero === 'false')
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="dep_nacimiento">DEP. NAC.:</label>
                                    <select wire:model.live="dep_nacimiento" name="dep_nacimiento" id="dep_nacimiento"
                                        class="form-control select2 @error('dep_nacimiento') is-invalid @enderror"
                                        style="width: 100%;  border-radius:10px;" tabindex="-1" required
                                        wire:change='ObtenerProvincia()'>
                                        <option selected="selected">Departamento de nacimiento </option>
                                        @foreach ($this->departamento as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="pro_nacimiento">PRO. NAC.:</label>
                                    <select wire:model.live="pro_nacimiento" name="pro_nacimiento" id="pro_nacimiento"
                                        class="form-control @error('pro_nacimiento') is-invalid @enderror select2"
                                        style="width: 100%; border-radius:10px;" tabindex="-1" required
                                        wire:change='ObtenerMunicipio()'>
                                        <option selected="selected">Provincia de nacimiento </option>
                                        @if ($this->provincias)
                                            @foreach ($this->provincias as $pro)
                                                <option value="{{ $pro->id }}">{{ $pro->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="mun_nacimiento">MUN. NAC.:</label>
                                    <select wire:model.live="mun_nacimiento" id="mun_nacimiento"
                                        class="form-control @error('mun_nacimiento') is-invalid @enderror  select2"
                                        style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                        <option selected="selected">Municipio de nacimiento </option>
                                        @if ($this->municipios)
                                            @foreach ($this->municipios as $mun)
                                                <option value="{{ $mun->id }}">{{ $mun->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!--  datos universitarios -->
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header p-1" style="background-color:#e5f0f9 ; border: 1px solid #59a9d7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <b class="pl-2">Ingrese los datos universitarios: </b>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="registro_universitario"> REGISTRO UNIVERSITARIO:<spam style="color:#cb4335;">
                                    <b>*</b>
                                </spam></label>
                            <input class="form-control @error('registro_universitario') is-invalid @enderror"
                                placeholder="Ingrese su registro universitario" id="registro_universitario"
                                wire:model.live=registro_universitario @if ($switch_ru) disabled @endif
                                style="border-radius:10px">
                            @error('registro_universitario')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="gestion_ingreso">GESTION DE
                                INGRESO:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input type="number" class="form-control @error('gestion_ingreso') is-invalid @enderror"
                                placeholder="Ingrese su gestión de ingreso" id="gestion_ingreso"
                                wire:model.live=gestion_ingreso @if ($swith_gestion) disabled @endif
                                style="border-radius:10px" min="1900" max="{{ date('Y') }}">
                            @error('gestion_ingreso')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="semestre_actual"> SEMESTRE
                                ACTUAL:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <select wire:model.live="semestre_actual" style="border-radius:10px"
                                class="select2 form-control @error('semestre_actual') is-invalid @enderror">
                                <option value="selected" selected>Seleccione un
                                    semestre </option>
                                <option value="PRIMERO">PRIMERO </option>
                                <option value="SEGUNDO">SEGUNDO </option>
                                <option value="TERCERO">TERCERO </option>
                                <option value="CUARTO">CUARTO </option>
                                <option value="QUINTO"> QUINTO </option>
                                <option value="SEXTO">SEXTO </option>
                                <option value="SEPTIMO">SEPTIMO</option>
                                <option value="OCTAVO">OCTAVO </option>
                                <option value="NOVENO">NOVENO </option>
                                <option value="DECIMO">DECIMO </option>
                                <option value="EGRESADO">EGRESADO </option>
                            </select>
                            @error('semestre_actual')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="correo_institucional"> CORREO
                                INSTITUCIONAL: <spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control @error('correo_institucional') is-invalid @enderror"
                                placeholder="Ingrese su correo institucional" id="correo_institucional"
                                wire:model.live=correo_institucional style="border-radius:10px">
                            @error('correo_institucional')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="carrera"> CARRERA:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <select wire:model.live="id_carrera" id="carrera"
                                class="form-control @error('id_carrera') is-invalid @enderror" style="border-radius:10px"
                                tabindex="-1" required wire:change="obtenerFacultad()">
                                <option selected="selected">Seleccione la
                                    carrera</option>
                                @foreach ($carrera as $car)
                                    <option value="{{ $car->id }}">
                                        {{ $car->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_carrera')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="facultad"> FACULTAD:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control  " id="facultad" wire:model.live="facultad" readonly
                                style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                        </div>

                    </div>
                </div>
                <!--  datos REFERENCIALES -->
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-header p-1" style="background-color:#e5f0f9 ; border: 1px solid #59a9d7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2471a3"
                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                        </svg>
                        <b class="pl-2">Ingrese los datos referenciales: </b>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_referencia"> NOMBRE DE
                                REFERENCIA:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control @error('nombre_referencia') is-invalid @enderror"
                                oninput="this.value = this.value.toUpperCase()"
                                placeholder="Ingrese su nombre de referencia" name="nombre_referencia"
                                id="nombre_referencia" wire:model.live=nombre_referencia style="border-radius:10px">
                            @error('nombre_referencia')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="telefono_referencia"> TELEFONO DE
                                REFERENCIA:<spam style="color:#cb4335;"><b>*</b></spam> </label>
                            <input class="form-control @error('telefono_referencia') is-invalid @enderror"
                                placeholder="Ingrese su número de referencia" name="telefono_referencia"
                                id="telefono_referencia" wire:model.live=telefono_referencia style="border-radius:10px">
                            @error('telefono_referencia')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud"> FECHA DE SOLICITUD:<spam style="color:#cb4335;"><b>*</b></spam>
                            </label>
                            <input class="form-control" id="fecha_solicitud" wire:model=fecha_solicitud_literal disabled
                                style="background-color:#f2f4f4; border-color:white; border-radius:10px;">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="telefono"> TELEFONO:<spam style="color:#cb4335;"><b>*</b></spam> </label>
                            <input class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                placeholder="Ingrese su número de telefono" id="telefono" wire:model.live=telefono
                                style="border-radius:10px">
                            @error('telefono')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="grupo_sanguineo"> TIPO DE SANGRE:<spam style="color:#cb4335;"><b>*</b></spam>
                            </label>

                            <div class="col-sm-12">
                                <select wire:model="grupo_sanguineo" style="border-radius:10px"
                                    class="form-control @error('grupo_sanguineo') is-invalid @enderror select2"
                                    @disabled($tiene_grupo_sanguineo)>
                                    <option value="selected" selected>Seleccione
                                        un tipo de sangre </option>
                                    <option value="A Rh+">A Rh+</option>
                                    <option value="A Rh-">A Rh- </option>
                                    <option value="B Rh+">B Rh+ </option>
                                    <option value="B Rh-"> B Rh- </option>
                                    <option value="AB Rh+">AB Rh+ </option>
                                    <option value="AB Rh-">AB Rh- </option>
                                    <option value="O Rh+">O Rh+ </option>
                                    <option value="O Rh-">O Rh- </option>
                                    <option value="NINGUNO">NINGUNO </option>
                                </select>
                                @error('grupo_sanguineo')
                                    <div class="text-danger">
                                        <b>{{ $message }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="alergias"> ALERGIAS: </label>
                            <textarea class="form-control" name="alergias" id="alergias" wire:model=alergias placeholder="Ingrese sus alergias"
                                style="border-radius:10px" oninput="this.value = this.value.toUpperCase()"> </textarea>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="zona">ZONA:</label>
                            <select wire:model="zona" id="zona" style="border-radius:10px"
                                class="form-control select2  @error('zona') is-invalid @enderror" style="width: 100%;"
                                tabindex="-1" required>
                                <option value="" selected>Selecciona la zona</option>
                                @foreach ($zonasSelect as $zona)
                                    <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                @endforeach
                            </select>
                            @error('zona')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_vivienda"> NRO VIVIENDA:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control @error('nro_vivienda') is-invalid @enderror" name="nro_vivienda"
                                placeholder="Ingrese su nro de vivienda" id="nro_vivienda" wire:model.live=nro_vivienda
                                style="border-radius:10px">
                            @error('nro_vivienda')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="direccion"> DIRECCIÓN:<spam style="color:#cb4335;"><b>*</b></spam></label>
                            <input class="form-control @error('direccion') is-invalid @enderror"
                                placeholder="Ingrese la calle y/o avenida de su vivienda" name="direccion"
                                oninput="this.value = this.value.toUpperCase()" id="direccion" wire:model.live=direccion
                                style="border-radius:10px">
                            @error('direccion')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
                    {{--    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"
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

                                    </span></span>
                            @endif
                            <div class="collapse" id="collapseExample" wire:ignore>
                                <div class="card card-body">
                                    @include('livewire.Afiliacion.titulares.ubicacion_titulares')
                                </div>
                            </div>
                        </div>
                    </div> --}}


                </div>
            </div>
        </div>
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <div class="modal-footer" style="border: none">
            <div id="cargando" style="display: none;">
                <x-cargando-dos />
            </div>
            <x-cancel-button wire:click="cancelarEstudiante">
                CANCELAR</x-cancel-button>
            <x-save-button onclick="registrarEstu()">GUARDAR</x-save-button>
        </div>
    @endcan
    <!--   funstion -->
    <script>
        function registrarEstu() {
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
                    Livewire.dispatch('registrarEstudiante');
                    document.getElementById('cargando').style.display = 'block';
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('estudianteRegistrado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente.",
                icon: "success"
            });
        });

        document.addEventListener('estudianteCancelado', function() {
            Swal.fire({
                title: "Cancelado!",
                text: "Se cancelo el registro",
                icon: "error"

            });
        });

        // CASO DE QUE SEA TITULAR VIGENTE 
        document.addEventListener('esTitular', function() {
            Swal.fire({
                title: "¡No se puede realizar el registro!",
                text: "Actualmente se encuentra afiliado como Titular",
                icon: "error"

            });
        });
        // CASO DE QUE SEA BENEFICIARIO VIGENTE 
        document.addEventListener('esBeneficiario', function() {
            Swal.fire({
                title: "¡No se puede realizar el registro!",
                text: "Actualmente se encuentra afiliado como Beneficiario",
                icon: "error"

            });
        });
        // CASO DE QUE SEA ESTUDAINTE VIGENTE 
        document.addEventListener('esEstudiante', function() {
            Swal.fire({
                title: "¡No se puede realizar el registro!",
                text: "Actualmente se encuentra afiliado como Estudiante",
                icon: "error"
            });
        });

        document.getElementById('formFile').addEventListener('change', function() {
            Swal.fire({
                title: "¡ADVERTENCIA!",
                text: "DEBE SUBIR UNA FOTO CON FONDO BLANCO. RECUERDA SUBIR UNA FOTO REAL DE LO CONTRARIO SU REGISTRO SERA ANULADO Y NO PODRÁ AFILIARSE MAS.",
                icon: "warning",
                confirmButtonText: 'OK'
            });
        });
    </script>

</div>
