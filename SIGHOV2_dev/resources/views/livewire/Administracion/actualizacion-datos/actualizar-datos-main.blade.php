<div>
    <div class="row">
        <div class="col-lg-12 ">
            <div class=" card-header cabecera">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <b>ACTUALIZACIÓN DE DATOS PERSONALES </b>
            </div>
            <div style="height: 0.5rem;"></div>
        </div>
    </div>
    <fieldset class="field_estilos_sin_hover">
        <legend>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-search" viewBox="0 0 16 16">
                <path
                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
            <strong>Ingresar los siguientes datos para realizar la busqueda: </strong>
        </legend>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-5 col-5">
                <strong>CARNET: <span style="color:#cb4335;"><b>*</b></span></strong>
                <input class="form-control input-comun" type="search" id="carnet" style="margin-left: 5px;"
                    placeholder="Ingrese el C.I." wire:model="ci_buscado"
                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10);">
            </div>
            <div class="col-lg-3 col-md-4 col-sm-5 col-4">
                <strong>COMPL:</strong>
                <input class="form-control input-comun" type="search" id="complemento" style="margin-left: 5px;"
                    placeholder=" Ingrese complemento" wire:model="comp_buscado"
                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,2);">
            </div>
            <div class="col-lg-1 col-md-2 col-sm-2 col-2">
                <br>
                <button class="btn btn-primary w-100" type="submit  " wire:click="buscarPersona">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                </button>
            </div>
        </div>
    </fieldset>
    <div style="height: 0.5rem;"></div>

    <!--  MUCHOS RESULTADOS -->
    @if ($multiples_resultados == true)
        <div class="card-header cabecera">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-search" viewBox="0 0 16 16">
                <path
                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
            </svg>
            <b>RESULTADOS OBTENIDOS</b>
        </div>
        <div style="height: 0.5rem;"></div>
        <div style="max-height: 400px; overflow-y: auto;">

            <table class="table tabla-azul-oscuro">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th style="text-align: center;">CARNET</th>
                        <th style="text-align: center;">MATRICULA</th>
                        <th style="text-align: center;">NOMBRE COMPLETO</th>
                        <th style="text-align: center;">TIPO DE ASEGURADO</th>
                        <th style="text-align: center;">OPCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos_persona as $pipol)
                        <tr>
                            <td>{{ $loop->iteration }} </td>
                            <td> {{ $pipol->ci }} @if ($pipol->complemento)
                                    - {{ $pipol->complemento }}
                                @endif
                            </td>
                            <td>{{ $pipol->matricula_seguro }}</td>
                            <td>
                                @if ($pipol->p_apellido == null)
                                    {{ $pipol->s_apellido . ' ' . $pipol->nombres }}
                                @elseif ($pipol->s_apellido == null)
                                    {{ $pipol->p_apellido . ' ' . $pipol->nombres }}
                                @else
                                    {{ $pipol->p_apellido . ' ' . $pipol->s_apellido . ' ' . $pipol->nombres }}
                                @endif
                            </td>
                            <td>{{ $pipol->tipoAseguradoPersona->tipo_asegurado }}</td>
                            <td>
                                <x-seleccionar-button wire:click="seleccionarPersona('{{ $pipol->id }}')">
                                </x-seleccionar-button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif

    <!--  INFORMACION DE DATOS -->
    @if ($persona_existe == true)
        <fieldset class="field_estilos_sin_hover">
            <legend>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <strong>Ingresar los siguientes datos para actualizar la información: </strong>
            </legend>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <label for="nombres"> NOMBRES:<spam style="color:#cb4335;"><b>*</b></spam></label>
                    <input class="form-control input-comun @error('nombres') is-invalid @enderror " name="nombres"
                        id="nombres" wire:model.live=nombres oninput="this.value = this.value.toUpperCase()"
                        wire:change='GenerarMatricula()'>
                    @error('nombres')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <label for="p_apellido"> PRIMER APELLIDO:</label>
                    <input class="form-control input-comun " name="p_apellido" id="p_apellido" wire:model=p_apellido
                        oninput="this.value = this.value.toUpperCase()" wire:change='GenerarMatricula()'>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <label for="s_apellido"> SEGUNDO APELLIDO:</label>
                    <input class="form-control input-comun " name="s_apellido" id="s_apellido" wire:model=s_apellido
                        oninput="this.value = this.value.toUpperCase()" wire:change='GenerarMatricula()'>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                    <label for="ci"> C.I.: <spam style="color:#cb4335;"><b>*</b></spam></label>
                    <input class="form-control input-comun @error('ci') is-invalid @enderror" name="ci"
                        id="ci" wire:model.live=ci
                        oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10);"
                        wire:change='GenerarMatricula()'>
                    @error('ci')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
                <div class="col-lg-3 col-md-3 col-sm-2 col-5">
                    <label for="complemento"> COMP.:</label>
                    <input class="form-control input-comun @error('complemento') is-invalid @enderror"
                        oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,2);"
                        id="complemento" wire:model=complemento>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-7">
                    <label for="es_extranjero">
                        TIPO DOC.:</label>
                    <select wire:model.live="es_extranjero" name="es_extranjero" id="es_extranjero"
                        class="form-control @error('es_extranjero') is-invalid @enderror"
                        style="width: 100%; border-radius:10px;" tabindex="-1" required>

                        <option value="0">NACIONAL</option>
                        <option value="1">EXTRANJERO</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                    <label for="id_dept_exp"> EXPEDIDO:</label>
                    <select wire:model.live="id_dept_exp" id="id_dept_exp"
                        class="form-control select2 @error('id_dept_exp') is-invalid @enderror"
                        style="width: 100%; border-radius:10px;" tabindex="-1" required>
                        <option selected="" disabled>Seleccione..
                        </option>
                        @foreach ($this->departamentos as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->sigla }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-4 col-6">

                    <label for="customSwitchSexoEdit"> SEXO:</label>
                    <div class="col-sm-6 d-flex align-items-center">
                        <strong style="color:#2980b9">M</strong>
                        <div class="custom-control custom-switch custom-switch-off-info custom-switch-on-pink">
                            <input wire:model="sexo" id="customSwitchSexoEdit" type="checkbox"
                                class="custom-control-input" wire:change='GenerarMatricula()'>
                            <label class="custom-control-label" for="customSwitchSexoEdit"
                                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                        </div>
                        <strong style="color:#e452b9">F</strong>
                    </div>

                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <label for="fecha_nacimiento"> FECHA NACIMIENTO:</label>
                    <input class="form-control input-comun" name="fecha_nacimiento" wire:change='GenerarMatricula()'
                        id="fecha_nacimiento" type="date" wire:model=fecha_nacimiento>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <label for="matricula_seguro">MATRICULA:</label>
                    <input class="form-control input-comun" id="matricula_seguro" wire:model="matricula_seguro"
                        disabled>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                    <label for="id_estado_civil">ESTADO CIVIL:</label>
                    <select wire:model="id_estado_civil" id="id_estado_civil"
                        class="form-control select2 @error('id_estado_civil') is-invalid @enderror"
                        style="width: 100%; border-radius:10px;" tabindex="-1" required
                        wire:change='GenerarMatricula()'>
                        <option selected=" ">Selecciona
                            estado civil... </option>
                        @foreach ($estadoCivilSelect as $estado_civil)
                            <option value="{{ $estado_civil->id }}">
                                {{ $estado_civil->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- DATOS DE NACIMIENTO -->
                @if ($es_extranjero == 0)
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <label for="id_departamento"> DEPARTAMENTO NACIMIENTO:</label>
                        <select wire:model.live="id_departamento" id="id_departamento"
                            class="form-control select2 @error('id_departamento') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required
                            wire:change='ObtenerProvincia()'>
                            <option value ="">Seleccione el
                                departamento de nacimiento..
                            </option>
                            @foreach ($this->departamentos as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_departamento')
                            <div class="text-danger">
                                <b>El departamento es obligatorio</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <label for="id_provincia"> PROVINCIA NACIMIENTO:</label>
                        <select wire:model.live="id_provincia" id="id_provincia"
                            class="form-control select2 @error('id_provincia') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required
                            wire:change='ObtenerMunicipio()'>
                            <option selected="">Seleccione la provincia
                                de nacimiento...
                            </option>
                            @if ($this->provincias)
                                @foreach ($this->provincias as $pro)
                                    <option value="{{ $pro->id }}">
                                        {{ $pro->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('id_provincia')
                            <div class="text-danger">
                                <b>El campo provincia es obligatorio</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <label for="id_nacimiento_municipio"> MUNICIPIO NACIMIENTO:</label>
                        <select wire:model.live="id_nacimiento_municipio" name="id_nacimiento_municipio"
                            id="id_nacimiento_municipio"
                            class="form-control select2 @error('id_nacimiento_municipio') is-invalid @enderror"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required>
                            <option selected=" ">Seleccione el municipio
                                de nacimiento...
                            </option>
                            @if ($this->municipios)
                                @foreach ($this->municipios as $mun)
                                    <option value="{{ $mun->id }}">{{ $mun->nombre }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('id_nacimiento_municipio')
                            <div class="text-danger">
                                <b>El municipio es obligatorio</b>
                            </div>
                        @enderror
                    </div>

                @endif

                <div class="col-lg-4 col-md-4 col-sm-4 col-8">
                    <label for="grupo_sanguineo">TIPO DE SANGRE :</label>
                    <select wire:model="grupo_sanguineo" id="grupo_sanguineo"
                        class="select2 form-control input-comun">
                        <option value="">Selecciona un grupo sanguineo</option>
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
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="alergias">ALERGIAS :</label>
                    <textarea class="form-control input-comun" id="alergias" wire:model="alergias" rows="2">
                    </textarea>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="observaciones">OBSERVACIONES: <span style="color:#cb4335;"><b>*</b></span></label>
                    <textarea class="form-control input-comun  @error('observaciones') is-invalid @enderror " id="observaciones"
                        wire:model="observaciones" rows="2" placeholder="Ingrese el motivo de la actualización del dato ....">
                    </textarea>
                    @error('observaciones')
                        <div class="text-danger">
                            <b>{{ $message }}</b>
                        </div>
                    @enderror
                </div>
            </div>
        </fieldset>
        <br>
        <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
            <div id="cargando" style="display: none;">
                <x-cargando-dos />
            </div>
            <x-cancel-button wire:click="cancelatDatosActualizados">
                CANCELAR</x-cancel-button>
            <x-save-button onclick="guardarActualizacion()">GUARDAR</x-save-button>
        </div>
    @endif

    @if ($persona_existe == false && $multiples_resultados == false)
        <br>
        <x-cargando-lineal>INGRESA DATOS PARA RELIZAR TU ACTUALIZACION ...</x-cargando-lineal>
    @endif



</div>
<script>
    function guardarActualizacion() {
        Swal.fire({
            title: '¿Guardar actualización?',
            text: "Se modificarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // document.getElementById('cargando').style.display = 'block';
                Livewire.dispatch('actualizarDatos');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

            }
        });
    }

    document.addEventListener('personaNoExiste', function() {
        Swal.fire({
            title: "La persona no existe",
            text: "Verifique bien la busqueda, la persona no existe.",
            icon: "warning"
        });
    });

    document.addEventListener('datosActualizados', function() {
        Swal.fire({
            title: "Datos Actualizados",
            text: "Los datos fueron actualizados",
            icon: "success"
        });
    });

    document.addEventListener('usuarioModificado', function() {
        Swal.fire({
            title: "Los datos de usuario se modificaron",
            text: "Por favor, debe generar nuevamente el usuario de la persona en el apartado de ADMINISTRACIÓN",
            icon: "success",
            confirmButtonText: "Entendido",
            confirmButtonColor: "#3085d6"
        });
    });

    document.addEventListener('errorSistemas', function() {
        Swal.fire({
            title: "Ocurrió un error",
            text: "Probablemente exista doble registro, contactese con la unidad de sistemas",
            icon: "error"
        });
    });
    document.addEventListener('accionCancelada', function() {
        Swal.fire({
            title: "Acción cancelada",
            text: "Se canceló la modificación de datos",
            icon: "error"
        });
    });


    document.addEventListener('porfavorIngreseDAtos', function() {
        Swal.fire({
            title: "Agrega campos para buscar",
            text: "Porfavor ingresa datos para realizar la busqueda.",
            icon: "warning"
        });
    });
</script>
