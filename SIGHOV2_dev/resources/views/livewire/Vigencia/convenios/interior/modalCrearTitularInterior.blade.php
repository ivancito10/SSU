<div>
    <x-agregar-button data-toggle="modal" data-target="#crearTitularInteriorModal"> REGISTRAR TITULAR DEL
        INTERIOR</x-agregar-button>
    <x-small-modal id="crearTitularInteriorModal" labelledby="crearTitularInteriorModalLabel"
        title="REGISTRAR TITULAR DEL INTERIOR" saveAction="">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header" style="background-color:#154360; color:white">
                        <p class='h6'><b>AGREGAR ASEGURADO DEL INTERIOR</b></p>
                    </div>
                    <div class="card-body">
                        <div class="card-body d-flex flex-column align-items-center">
                            <!--  guardar la foto del asegurado en base 64 -->
                            <img src="" class="img-thumbnail" width="200" height="200" alt="FTP">

                        </div>
                        <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                        <div class="row mb-2">
                            <div class="col-lg-12" style="display: flex; align-items: center;">
                                <!-- Aquí va el botón con icono de lupa -->

                                <button class="btn btn-primary" type="submit  " onclick="buscarDatos()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                    </svg>
                                </button>

                                <input class="form-control " type="search" id="search"
                                    style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el carnet">
                                <input class="form-control" type="search" id="complemento"
                                    style="width: calc(100% - 20px); margin-left: 5px;"
                                    placeholder=" Ingrese complemento">
                            </div>

                            <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                            @if ($estadoBusqueda == '')
                            @else
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                                    @if ($estadoBusqueda == 1)
                                        <div class="alert alert-success"> <em>Se valido correctamente!</em></div>
                                    @elseif($estadoBusqueda == 2)
                                        <div class="alert alert-success"> <em>Se valido correctamente! (Existe en la
                                                BD)</em></div>
                                    @else
                                        <div class="alert alert-danger">No se validó!</div>
                                    @endif
                                </div>

                            @endif
                        </div>

                        @if ($estadoBusqueda == '')
                        @else
                            @if ($estadoBusqueda == 1)
                                <!--AQUI SE MUESTRAN LOS DATOS RECUPERADOS DEL ASUSS-->

                                <div class="row mb-2">

                                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="ci">CARNET </label>
                                        <input class="form-control" name="ci" id="ci" wire:model="ci"
                                            @if ($estadoBusqueda == 1) disabled @endif>
                                    </div>
                                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="complemento">COMP. </label>
                                        <input class="form-control" name="complemento" id="complemento"
                                            wire:model="complemento" @if ($estadoBusqueda == 1) disabled @endif>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="nombre">NOMBRE </label>
                                        <input class="form-control" name="nombre" id="nombre" wire:model="nombre"
                                            @if ($estadoBusqueda == 1) disabled @endif>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="p_apellido">PRIMER APELLIDO </label>
                                        <input class="form-control" name="p_apellido" id="p_apellido"
                                            wire:model="p_apellido" @if ($estadoBusqueda == 1) disabled @endif>
                                    </div>
                                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="s_apellido">SEGUNDO APELLIDO </label>
                                        <input class="form-control" name="s_apellido" id="s_apellido"
                                            wire:model="s_apellido" @if ($estadoBusqueda == 1) disabled @endif>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="fecha_nacimiento">FECHA NAC. </label>
                                        <input class="form-control" name="fecha_nacimiento" id="fecha_nacimiento"
                                            wire:model="fecha_nacimiento"
                                            @if ($estadoBusqueda == 1) disabled @endif>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matricula_seguro"> MATRICULA</label>
                                        <input class="form-control" name="matricula_seguro" id="matricula_seguro"
                                            wire:model="matricula_seguro" disabled>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <p style='color:red;'><em>Complete los siguientes campos porfavor.</em></p>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="sexo"> SEXO</label>
                                        <div class="col-sm-6 d-flex align-items-center">
                                            <label class="mr-2">M</label>
                                            <div
                                                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input wire:model="sexo" name="sexo" type="checkbox"
                                                    class="custom-control-input" id="customSwitch2"
                                                    wire:change='GenerarMatricula'>
                                                <label class="custom-control-label" for="customSwitch2"
                                                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                            </div>
                                            <label class="ml-2">F</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="id_estado_civil"> ESTADO CIVIL</label>
                                        <select wire:model="id_estado_civil" name="id_estado_civil"
                                            class="form-control" tabindex="-1" required>
                                            <option selected="selected">Selecciona estado civil </option>
                                            @foreach ($estadoCivil as $estCivil)
                                                <option value="{{ $estCivil->id }}">{{ $estCivil->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                        <label for="id_tipo_asegurado">TIPO ASEGURADO</label>
                                        <select wire:model.live="id_tipo_asegurado" name="id_tipo_asegurado"
                                            id="id_tipo_asegurado" class="form-control" tabindex="-1" required>
                                            <option selected="selected">Seleccione.. </option>
                                            <option value="9">TITULAR DEL INTERIOR</option>
                                            <option value="10">BENEFICIARIO DEL INTERIOR</option>
                                        </select>
                                    </div>
                                    <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                        <label for="es_extranjero">TIPO DOC.</label>
                                        <select wire:model="es_extranjero" name="es_extranjero" id="es_extranjero"
                                            class="form-control" tabindex="-1" required>
                                            <option selected="selected">Seleccione.. </option>
                                            <option value=0>Nacional</option>
                                            <option value=1>Extranjero</option>
                                        </select>
                                    </div>
                                </div>
                            @elseif($estadoBusqueda == 0)
                                <!--REGISTRAR MANUALMENTE-->
                                <p style='color:red;'><em>Complete los siguientes campos porfavor.</em></p>

                                <div class="row mb-2">
                                    <div class='col-lg-5 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="ci">CARNET</label>
                                        <input type="number" class="form-control @error('ci') is-invalid @enderror"
                                            wire:model="ci" name="ci" id="ci">

                                        @error('ci')
                                            @if ($message == 'validation.numeric')
                                                <span class="form-text text-danger">El valor ingresado debe ser
                                                    numérico.</span>
                                            @elseif ($message == 'validation.required')
                                                <span class="form-text text-danger">Este campo es obligatorio.</span>
                                            @else
                                                <span class="form-text text-danger">{{ $message }}</span>
                                            @endif
                                        @enderror
                                    </div>
                                    <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="complemento">COMP. </label>
                                        <input type="text" class="form-control" placeholder=" Complemento"
                                            wire:model="complemento" name="complemento" id="complemento"
                                            oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                        <label for="es_extranjero">TIPO DOC.</label>
                                        <select wire:model="es_extranjero" name="es_extranjero" id="es_extranjero"
                                            class="form-control select2 @error('tipo_doc') is-invalid @enderror"
                                            style="width: 100%;" tabindex="-1" required>
                                            <option selected="selected">Seleccione.. </option>
                                            <option value="0">Nacional</option>
                                            <option value="1">Extrangero</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombre"> NOMBRES</label>
                                        <input class="form-control" id="nombre"
                                            placeholder=" Introduzca el nombre" type="text" wire:model=nombre>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="p_apellido"> PRIMER APELLIDO</label>
                                        <input class="form-control" id="p_apellido" placeholder="Primer apellido"
                                            type="text" wire:model=p_apellido>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="s_apellido"> SEGUNDO APELLIDO</label>
                                        <input class="form-control" id="s_apellido" placeholder="Segundo Apellido"
                                            type="text" wire:model=s_apellido>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                        <label for="fecha_nacimiento">FECHA NACIMIENTO </label>
                                        <input type="date" class="form-control" placeholder=" Fecha Nacimiento"
                                            wire:model="fecha_nacimiento" name="fecha_nacimiento"
                                            id="fecha_nacimiento">
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="id_estado_civil"> ESTADO CIVIL</label>
                                        <select wire:model="id_estado_civil" name="id_estado_civil"
                                            class="form-control" tabindex="-1" required>
                                            <option selected="selected">Selecciona estado civil </option>
                                            @foreach ($estadoCivil as $estCivil)
                                                <option value="{{ $estCivil->id }}">{{ $estCivil->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="sexo"> SEXO</label>
                                        <div class="col-sm-6 d-flex align-items-center">
                                            <label class="mr-2">M</label>
                                            <div
                                                class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input wire:model="sexo" name="sexo" type="checkbox"
                                                    class="custom-control-input" id="customSwitch2"
                                                    wire:change='GenerarMatricula()'>
                                                <label class="custom-control-label" for="customSwitch2"
                                                    style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                            </div>
                                            <label class="ml-2">F</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matricula_seguro"> MATRICULA</label>
                                        <input class="form-control" name="matricula_seguro" id="matricula_seguro"
                                            wire:model="matricula_seguro" disabled>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                        <label for="id_tipo_asegurado">TIPO ASEGURADO</label>
                                        <select wire:model="id_tipo_asegurado" name="id_tipo_asegurado"
                                            id="id_tipo_asegurado" class="form-control" tabindex="-1" required>
                                            <option selected="selected">Seleccione el tipo de asegurado </option>
                                            <option value="9">TITULAR DEL INTERIOR</option>
                                            <option value="10">BENEFICIARIO DEL INTERIOR</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-small-modal>
</div>
<script></script>
