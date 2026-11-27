<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                REGISTRO DE NUEVO ESPECIALISTA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="cancelarEspecializacion" wire:loading.attr="disabled"
                wire:target="cancelarEspecializacion">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="cancelarEspecializacion">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <div style="height: 0.5rem;"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-comun p-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cabecera text-center">
                            <b>DATOS PERSONALES DE ESPECIALISTA</b>
                        </div>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>

                <div class="row">
                    <div class="col-auto ">
                        <button class="btn btn-primary" type="submit" wire:click="buscaBD">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>
                    </div>
                    <div class="col-auto ">
                        <input class="form-control input-comun" type="search" id="search"
                            placeholder="Carnet de identidad" wire:model="ciBusqueda" wire:keydown.enter="buscaBD">
                    </div>
                    <div class="col-auto">
                        <input class="form-control input-comun" type="search" id="complemento"
                            oninput="this.value = this.value.toUpperCase()" placeholder="Complemento"
                            wire:model="complementoBusqueda" wire:keydown.enter="buscaBD">
                    </div>
                    <!-- Aquí va el botón con icono de lupa -->
                    <div wire:loading wire:target="buscaBD">
                        <div class="spinner-grow text-warning" role="status">
                        </div>
                        <b><i>Cargando datos espere porfavor...</i></b>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <div style="height: 0.5rem;"></div>
                        <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                        @if (!empty($estadoBusqueda))
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                                @if ($estadoBusqueda == 1)
                                    <div class="alert alert-success p-1 pl-3">
                                        <em>Se valido correctamente a traves del ASUSS.</em>
                                    </div>
                                @elseif ($estadoBusqueda == 2)
                                    <div class="alert alert-success p-1 pl-3">
                                        <em>Se valido correctamente en la BD.</em>
                                    </div>
                                @elseif ($estadoBusqueda == 3)
                                    <div class="alert alert-danger p-1 pl-3">No se valido, ingrese los datos de manera
                                        manual.</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Aqui empiezan los datos personales -->
                <!-- Para caso existe y esta asegurado en la BD -->
                @if (!is_null($personaExisteEnBd))
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <p style="color:#2471a3" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2"><b>Completar los datos correspondientes:</b> </em>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-lg-3 col-md-3 col-sm-4 col-xs-12'>
                            @if (!is_null($personaExisteEnBd))
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        @if ($foto_doctor)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="#2471a3" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                            </svg>
                                            <strong style="color:#2471a3">FOTOGRAFÍA SELECCIONADA</strong>
                                        @else
                                            <strong>SELECCIONAR FOTOGRAFÍA: <b
                                                    style="color:#2471a3; ">(OPCIONAL)</b></strong>
                                        @endif
                                        <div class=" d-flex flex-column align-items-center">
                                            <!--   vista de la foto  -->
                                            @if ($foto_doctor)
                                                @if (Str::startsWith($foto_doctor->getMimeType(), 'image/'))
                                                    <img src="{{ $foto_doctor->temporaryUrl() }}"
                                                        style=" box-shadow: 1 0 10px 5px rgba(0, 0, 0, 0.5); width: 200px; height: 200px; object-fit: cover;"
                                                        class="img-thumbnail">
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class=" d-flex flex-column align-items-center">
                                    <!--  guardar la foto del asegurado en base 64 -->
                                    <!-- <img src="" class="img-thumbnail" width="200" height="200" alt="FTP">-->
                                    <input
                                        class="form-control form-control-sm @error('foto_doctor') is-invalid @enderror"
                                        type="file" id="formFile" name="archivo" required wire:model="foto_doctor"
                                        accept=".jpg,.jpeg,.png">
                                    @error('foto_doctor')
                                        <div class="text-danger">
                                            <b>El campo foto es obligatorio. Debe ser de tipo JPG o PNG</b>
                                        </div>
                                    @enderror
                                    <div wire:loading wire:target="foto_doctor">
                                        <x-cargando-dos />
                                    </div>
                                </div>

                            @endif
                        </div>
                        <div class='col-lg-9 col-md-9 col-sm-8 col-xs-12'>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="nombres">NOMBRES: <b style="color:#cb4335;">*</b></label>
                                    <input class="form-control input-comun @error('nombres') is-invalid @enderror"
                                        oninput="this.value = this.value.toUpperCase()" id="nombres"
                                        wire:model.live=nombres placeoholder="Introduzca los nombres"
                                        @if ($personaExisteEnBd) disabled @endif>
                                    @error('nombres')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="p_apellido">PRIMER APELLIDO: <b style="color:#cb4335;">*</b> </label>
                                    <input class="form-control input-comun @error('p_apellido') is-invalid @enderror"
                                        oninput="this.value = this.value.toUpperCase()" id="p_apellido"
                                        wire:model.live=p_apellido @if ($personaExisteEnBd) disabled @endif>
                                    @error('p_apellido')
                                        <div class="text-danger">
                                            <b>El campo primer apellido es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="s_apellido">SEGUNDO APELLIDO: </label>
                                    <input class="form-control input-comun @error('s_apellido') is-invalid @enderror"
                                        oninput="this.value = this.value.toUpperCase()" id="s_apellido"
                                        wire:model.live=s_apellido @if ($personaExisteEnBd) disabled @endif>
                                    @error('s_apellido')
                                        <div class="text-danger">
                                            <b>El campo segundo apellido es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label for="ci">CARNET: <b style="color:#cb4335;">*</b></label>
                                    <input class="form-control input-comun @error('ci') is-invalid @enderror"
                                        id="ci" wire:model.live=ci
                                        @if ($personaExisteEnBd) disabled @endif>
                                    @error('ci')
                                        <div class="text-danger">
                                            <b>El campo carnet es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label for="complemento">COMPLEMENTO: </label>
                                    <input class="form-control input-comun" id="complemento" wire:model=complemento
                                        @if ($personaExisteEnBd) disabled @endif>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label for="id_dept_exp">EXPEDIDO: <b style="color:#cb4335;">*</b></label>
                                    <select wire:model.live="id_dept_exp" id="id_dept_exp"
                                        class="form-control input-comun @error('id_dept_exp') is-invalid @enderror  select2"
                                        style="width: 100%;" tabindex="-1" required
                                        @if ($id_dept_expSwitch) disabled @endif>
                                        <option selected="selected">Seleccione
                                        </option>
                                        @foreach ($departamentos as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->sigla }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                                    <label for="es_extranjero">TIPO DOCUMENTO: <b style="color:#cb4335;">*</b></label>
                                    <select wire:model.live="es_extranjero" id="es_extranjero"
                                        @if ($es_extranjeroSwitch) disabled @endif
                                        class="form-control input-comun select2 @error('es_extranjero') is-invalid @enderror"
                                        style="width: 100%; border-radius:10px;" tabindex="-1" required>
                                        <option selected="selected">Seleccione...</option>
                                        <option value="false">NACIONAL</option>
                                        <option value="true">EXTRANJERO</option>
                                    </select>
                                    @error('es_extranjero')
                                        <div class="text-danger">
                                            <b>El campo tipo de documento es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <label for="fecha_nacimiento"> FECHA NACIMIENTO: <b style="color:#cb4335;">*</b>
                                    </label>
                                    <input
                                        class="form-control input-comun @error('fecha_nacimiento') is-invalid @enderror"
                                        id="fecha_nacimiento" type="date" wire:model.live="fecha_nacimiento"
                                        wire:change="validarFechaNacimiento"
                                        @if ($fecha_nacimientoSwitch) disabled @endif>
                                    @error('fecha_nacimiento')
                                        <div class="text-danger">
                                            <b>El campo fecha de nacimiento es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                    <label for="sexo"> SEXO <b style="color:#cb4335;">*</b></label>
                                    <div class="col-sm-6 d-flex align-items-center">
                                        <label class="mr-2" style="color:#2980b9">M</label>
                                        <div
                                            class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                                            <input wire:model="sexo" name="sexo" type="checkbox"
                                                class="custom-control-input" id="customSwitch2"
                                                wire:change='generarMatriculayGradoAcad'
                                                @if ($matricula_seguroSwitch) disabled @endif>
                                            <label class="custom-control-label" for="customSwitch2"
                                                style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                        </div>
                                        <label class="ml-2" style="color:#e452b9">F</label>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                    <label for="matricula_seguro"> MATRICULA:<b style="color:#cb4335;">*</b> </label>
                                    <input
                                        class="form-control input-comun @error('matricula_seguro') is-invalid @enderror"
                                        id="matricula_seguro" wire:model.live="matricula_seguro" disabled>
                                    @error('matricula_seguro')
                                        <div class="text-danger">
                                            <b>El campo matricula es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="id_estado_civil"> ESTADO CIVIL: <b
                                            style="color:#cb4335;">*</b></label>
                                    <select wire:model.live="id_estado_civil" wire:change="generarMatricula"
                                        id="id_estado_civil"
                                        class="form-control input-comun @error('id_estado_civil') is-invalid @enderror  select2"
                                        style="width: 100%;" tabindex="-1" required
                                        @if ($id_estado_civilSwitch) disabled @endif>
                                        <option selected="selected">Seleccione
                                        </option>
                                        @foreach ($estadosCiviles as $estadoCivil)
                                            <option value="{{ $estadoCivil->id }}">{{ $estadoCivil->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_estado_civil')
                                        <div class="text-danger">
                                            <b>El campo estado civil es obligatorio.</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- @if (!is_null($personaExisteEnBd)) --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" @if (is_null($personaExisteEnBd)) hidden @endif>
            <div class="card card-comun p-2">
                <div class="cabecera text-center">
                    <b>
                        REGISTRO DE CONTRATO Y ESPECIALIDAD</b>
                </div>

                <div style="height: 0.5rem;"></div>
                <div class="row justify-content-end">
                    <div class=" col-lg-6 col-md-6 col-sm-5 col-xs-5">
                        <strong>TIPO DE ATENCIÓN A POBLACIÓN ASEGURADA: <b style="color:#cb4335;">*</b></strong>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checkAfiliado" id="checkAfiliado"
                                wire:model.live="checkAfiliado" style="transform: scale(1.5); margin-right: 10px;">
                            <label class="form-check-label" for="checkAfiliado">
                                AFILIADO
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checkConvenio" id="checkConvenio"
                                wire:model.live="checkConvenio" style="transform: scale(1.5); margin-right: 10px;">
                            <label class="form-check-label" for="checkConvenio">
                                CONVENIO
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="checkSsue" id="checkSsue"
                                wire:model.live="checkSsue" style="transform: scale(1.5); margin-right: 10px;">
                            <label class="form-check-label" for="checkSsue">
                                SSUE
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row justify-content-start">
                    <div class=" col-lg-6 col-md-6 col-sm-5 col-xs-5">
                        <strong>ESPECIALISTA PERTENECIENTE A IMAGENOLOGÍA: <b style="color:#cb4335;">*</b></strong>
                    </div>
                    {{-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 d-flex align-items-center">
                        <label class="mr-2" style="color: red">NO</label>
                        <div class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                            <input wire:model="especialistaImagenologia" type="checkbox" class="custom-control-input"
                                id="customSwitchEspecialistaImagenologia">
                            <label class="custom-control-label" for="customSwitchEspecialistaImagenologia"
                                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                        </div>
                        <label class="ml-2" style="color: green">SI</label>
                    </div> --}}
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 d-flex align-items-center">
                        @if ($especialistaImagenologiaMostrar)
                            <h3 style='color: green'><strong>SI</strong></h3>
                        @else
                            <h3 style='color: red'><strong>NO</strong></h3>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label for="permanente">TIPO DE CONTRATO: <b style="color:#cb4335;">*</b></label>
                        <div class="col-sm-6 d-flex align-items-center p-0">
                            <label class="mr-2 mb-0 text-red">EVENTUAL</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-blue">
                                <input wire:model.live="permanente" name="permanente" type="checkbox"
                                    class="custom-control-input" id="switchpermanente">
                                <label class="custom-control-label mb-0" for="switchpermanente"
                                    style="margin: 0;"></label>
                            </div>
                            <label class="ml-2 mb-0 text-blue">PERMANENTE</label>
                        </div>
                    </div>

                    @if ($permanente)
                    @else
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <label for="fecha_contrato_inicio"> FECHA INICIO: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control @error('fecha_contrato_inicio') is-invalid @enderror"
                                id="fecha_contrato_inicio" type="date" wire:model.live="fecha_contrato_inicio"
                                style="border-radius:8px;">
                            @error('fecha_contrato_inicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <label for="fecha_contrato_fin">FECHA FIN: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control @error('fecha_contrato_fin') is-invalid @enderror"
                                id="fecha_contrato_fin" type="date" wire:model.live="fecha_contrato_fin"
                                style="border-radius:8px;">
                            @error('fecha_contrato_fin')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" wire:ignore>
                        <label for="id_especialidad">ESPECIALIDAD: <b style="color:#cb4335;">*</b></label>
                        <select wire:model.live="id_especialidad" name="id_especialidad" id="id_especialidad"
                            class="form-control input-comun select2  @error('id_especialidad') is-invalid @enderror"
                            style="width: 100%;" tabindex="-1" required>
                            <option selected="selected">Seleccione la especialidad </option>
                            @foreach ($especialidades as $esp)
                                <option value="{{ $esp->id }}">{{ $esp->especialidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                        <label for="grado_academico"> GRADO ACADÉMICO: <b style="color:#cb4335;">*</b></label>
                        <select wire:model="grado_academico" id="grado_academico"
                            class="form-control select2 @error('grado_academico') is-invalid @enderror"
                            style="width: 100%; border-radius:8px;" tabindex="-1" required>
                            <option selected="selected">Seleccione..</option>
                            @foreach ($grados_academicos as $grado)
                                <option value="{{ $grado }}">{{ $grado }}</option>
                            @endforeach
                        </select>
                        @error('grado_academico')
                            <div class="text-danger">
                                <b>El campo grado academico es obligatorio.</b>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}


    @if (!is_null($personaExisteEnBd))
        <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
            <x-cancel-button wire:click="cancelarEspecializacion">
                CANCELAR</x-cancel-button>
            <x-save-button onclick="guardarEspecialista()">GUARDAR</x-save-button>
        </div>
    @endif

    <style>
        .select2-container .select2-selection--single {
            height: 48px !important;
            padding: 6px 12px;
            border-radius: 8px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 45px;
        }
    </style>
</div>

<script>
    function guardarEspecialista() {
        Swal.fire({
            title: '¿Registrar Especialista?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registroEspecialista');

            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }

    // esto es por si se repite 
    document.addEventListener('especialidadExiste', function() {
        Swal.fire({
            title: "La especialidad ya fue registrada para ese especialista!",
            text: "Cambia de especialidad",
            icon: "error",
            timer: 10000,
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#id_especialidad');
        $(select).select2();

        // Enfoca el campo de búsqueda al abrir el select2
        $('#id_especialidad').on('select2:open', function(e) {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#id_especialidad').on('change', function(e) {
            var data = $(this).val();
            @this.set('id_especialidad', data);
            @this.dispatch('mostrarPerteneceImagenologia', {idEspecialidad: data});
        });
    });

    // VALIDACIONES CON FECHAS 
    document.addEventListener('FechaNacimientoInvalidaHoy', function() {
        Swal.fire({
            title: "Fecha no válida",
            text: "La fecha de nacimiento no puede ser hoy. Por favor, selecciona una fecha de nacimiento anterior.",
            icon: "error",
            confirmButtonText: "Entendido"
        });
    });

    document.addEventListener('FechaNacimientoInvalidaManana', function() {
        Swal.fire({
            title: "Fecha no válida",
            text: "La fecha de nacimiento no puede ser posterior a la fecha de hoy. Por favor, selecciona una fecha válida.",
            icon: "error",
            confirmButtonText: "Entendido"
        });
    });

    document.addEventListener('FechaNacimientoInvalidaMenorEdad', function() {
        Swal.fire({
            title: "Edad no válida",
            text: "La persona debe ser mayor de 18 años. Por favor, selecciona una fecha de nacimiento que indique una edad mayor a 18 años.",
            icon: "error",
            confirmButtonText: "Entendido"
        });
    });
    // VALIDACIONES PARA INGRESO DE DATOS EN BUSQUEDA DE CI Y COMPLEMENTO
    document.addEventListener('ciInvalido', function() {
        Swal.fire({
            title: "Carnet Inválido!",
            text: "Porfavor ingrese un número de carnet válido para realizar la búsqueda.",
            icon: "warning",
        });
    });

    document.addEventListener('especialistaRegistrado', function() {
        Swal.fire({
            title: "¡Guardado!",
            text: "El especialista fue registrado exitosamente.",
            icon: "success",
            showConfirmButton: false,
        });
    });
</script>
