<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                REGISTRAR BAJA INSTITUCIONAL
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="atras" wire:loading.attr="disabled" wire:target="atras">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="atras">
                <x-cargando-dos />
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2 card-comun">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="cabecera text-center">
                            <b>BUSQUEDA DEL ASEGURADO</b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2 h5"><b>Ingrese el carnet o la matricula del asegurado: </b>
                            </em>
                        </p>
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">

                        <button class="btn btn-primary" type="submit  " wire:click="buscarAsegurado">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>

                        <input class="form-control " type="search" id="carnet"
                            style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el C.I."
                            wire:model="carnetBusqueda" oninput="this.value = this.value.toUpperCase();">
                        <input class="form-control" type="search" id="complemento"
                            style="width: 60%; margin-left: 5px; margin-left: 5px;" placeholder=" Ingrese complemento"
                            wire:model="complementoBusqueda" oninput="this.value = this.value.toUpperCase();">
                        <p style="color:#2471a3" class="mt-3"><em class="ml-2 h5"><b> o </b></em></p>

                        <input class="form-control" type="search" id="matricula"
                            style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Ingrese la matricula"
                            wire:model="matriculaBusqueda" oninput="this.value = this.value.toUpperCase();">

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#2471a3">
                            <em class="ml-2 h5"><b>O ingrese los nombres o apellidos del asegurado: </b>
                            </em>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <strong>NOMBRES:</strong>
                        <input class="form-control" type="search" id="nombresBusqueda"
                            placeholder=" Ingrese los nombres" wire:model="nombresBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <strong>PRIMER APELLIDO:</strong>
                        <input class="form-control" type="search" id="pApellidoBusqueda"
                            placeholder=" Ingrese el primer apellido" wire:model="pApellidoBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <strong>SEGUNDO APELLIDO:</strong>
                        <input class="form-control" type="search" id="sApellidoBusqueda"
                            placeholder=" Ingrese el segundo apellido" wire:model="sApellidoBusqueda"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if ($busquedaExistosa)
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-2 card-comun">
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="cabecera text-center">
                                <b>DATOS DEL ASEGURADO</b>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex flex-column align-items-center">
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail" width="150" height="150" alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">

                            @if ($estado_vigencia == 'VIGENTE')
                                <span style="color: green;"><b>VIGENTE</b></span>
                            @elseif($estado_vigencia == 'CESANTE')
                                <span class="h3 parpadeo" style="color: #ed8a03;"><b>CESANTE</b></span>
                            @elseif($estado_vigencia == 'NO VIGENTE')
                                <span style="color: red;"><b>NO
                                        VIGENTE</b></span>
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
                            <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            @foreach ($nombreInsPatronal as $key => $institucion)
                                <strong style="font-size:90%">INSTITUCIÓN {{ $key + 1 }}:</strong>
                                <br>
                                <span class=" font-weight-bold h5"
                                    style="color:#a50601">{{ $institucion['nombre_institucion'] }}
                                </span>
                                <br>
                            @endforeach

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <strong style="font-size:90%">TIPO DE ASEGURADO:</strong><br>
                            <span class=" font-weight-bold h5" style="color:#030e7a">{{ $tipoAsegurado }}
                            </span>
                        </div>
                    </div>
                    <hr class="p-0 m-0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO:</strong>
                            <p class="p-1 p-comun-disabled" id="nombre">
                                {{ $nombre }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> CARNET:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $carnet }}</p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>SEXO:</strong>
                            <p class="p-1 p-comun-disabled">
                                {{ $sexo }} </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE NACIMIENTO: </strong>
                            <p class="p-1 p-comun-disabled">
                                {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE AFILIACIÓN: </strong>
                            <p class="p-1 p-comun-disabled">
                                {{ \Carbon\Carbon::parse($fechaReafiliacion)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE VENCIMIENTO DE CARNET: </strong>
                            <p class="p-1 p-comun-disabled">
                                @if ($vencimientoCarnet != null)
                                    @if ($vencimientoCarnet == 'INDEFINIDO')
                                        <strong>INDEFINIDO</strong>
                                    @else
                                        <strong>{{ \Carbon\Carbon::parse($vencimientoCarnet)->format('d/m/Y') }}</strong>
                                    @endif
                                @else
                                    SIN DATOS
                                @endif
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                @if ($busquedaExistosa)
                    <div class="card p-2 card-comun">
                        <div class="row mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="cabecera text-center">
                                    <b>DATOS DE LA BAJA</b>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-lg-7 col-md-12 col-sm-12 col-xs-12'>
                            </div>
                            <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fechaRegistro">FECHA DE REGISTRO DE LA BAJA:</label>
                                <input wire:model="fechaRegistro" type="date" id="fechaRegistro"
                                    class="form-control" disabled>
                            </div>
                        </div>
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
                                    <em class="ml-2 h5"><b>Debe completar los siguientes campos.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                <label for="idInstitucionBaja">INSTITUCION:</label>
                                @if (count($nombreInsPatronal) == 1)
                                    <input type="text" name="idInstitucionBaja" id="idInstitucionBaja"
                                        class="form-control @error('idInstitucionBaja') is-invalid @enderror"
                                        value="{{ $nombreInsPatronal[0]['nombre_institucion'] }}" readonly>
                                    <input type="hidden" wire:model.live="idInstitucionBaja"
                                        value="{{ $nombreInsPatronal[0]['id_institucion'] }}">
                                @else
                                    <select wire:model.live="idInstitucionBaja" name="idInstitucionBaja"
                                        id="idInstitucionBaja"
                                        class="form-control @error('idInstitucionBaja') is-invalid @enderror"
                                        style="width: 100%;border-radius:10px" tabindex="-1" required>
                                        <option selected="">Seleccione la institucion a dar de baja...</option>
                                        @foreach ($nombreInsPatronal as $institucion)
                                            <option value="{{ $institucion['id_institucion'] }}">
                                                {{ $institucion['nombre_institucion'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('idInstitucionBaja')
                                        <div class="text-danger">
                                            <b>El campo institucion es obligatorio.</b>
                                        </div>
                                    @enderror
                                @endif

                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="id_motivo_baja">MOTIVO DE BAJA INSTITUCIONAL:</label>
                                <select wire:model.live="id_motivo_baja" name="id_motivo_baja" id="id_motivo_baja"
                                    class="form-control @error('id_motivo_baja') is-invalid @enderror"
                                    style="width: 100%;border-radius:10px" tabindex="-1" required>
                                    <option selected="">Seleccione el motivo...</option>
                                    @foreach ($motivosBaja as $motivo)
                                        <option value="{{ $motivo->id }}">{{ $motivo->motivo_baja }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_motivo_baja')
                                    <div class="text-danger">
                                        <b>El campo motivo baja es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>

                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_baja_inst">FECHA DE INICIO DE CESANTIA:</label>
                                <input wire:model="fecha_baja_inst" type="date" wire:change="calcularCesantia"
                                    id="fecha_baja_inst"
                                    class="form-control @error('fecha_baja_inst') is-invalid @enderror">
                                @error('fecha_baja_inst')
                                    <div class="text-danger">
                                        <b>El campo fecha inicio cesantia es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>

                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="baja_definitivaSwitch"> BAJA DEFINITIVA:</label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <strong class="text-red">NO</strong>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                                        <input wire:model="baja_definitiva" id="baja_definitivaSwitch"
                                            type="checkbox" wire:change="calcularCesantia"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="baja_definitivaSwitch"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <strong class="text-green">SI</strong>
                                </div>
                            </div>

                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_baja_ssu">FECHA DE BAJA DEFINITIVA:</label>
                                <input wire:model.live="fecha_baja_ssu" type="date" disabled id="fecha_baja_ssu"
                                    class="form-control">
                            </div>

                        </div>


                        <div class="row mb-2">
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nro_documento_referencia">NRO DOCUMENTO DE REFERENCIA:</label>
                                <input wire:model.live="nro_documento_referencia" type="text"
                                    name="nro_documento_referencia" id="nro_documento_referencia"
                                    class="form-control @error('nro_documento_referencia') is-invalid @enderror"
                                    placeholder="Nro del documento de respaldo"
                                    oninput="this.value = this.value.toUpperCase()" required>
                                @error('nro_documento_referencia')
                                    <div class="text-danger">
                                        <b>El numero de documento de referencia es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="descripcion_motivo">DESCRIPCIÓN DEL MOTIVO DE LA BAJA:</label>
                                <textarea wire:model.live="descripcion_motivo" name="descripcion_motivo" id="descripcion_motivo"
                                    class="form-control @error('nombre_institucion') is-invalid @enderror"
                                    placeholder="Descripcion del motivo de la baja" oninput="this.value = this.value.toUpperCase()" required>
                                    </textarea>

                            </div>
                        </div>
                    </div>
                @endif




                <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                    <div id="cargando" style="display: none;">
                        <x-cargando-dos />
                    </div>
                    <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
                    <x-save-button onclick="guardarBajaInstitucional()" id="save-button">GUARDAR</x-save-button>
                </div>

            </div>

        </div>
    @else
        <div class="row">
            @if ($multiplesResultados)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card p-2 card-comun">
                        <div class="cabecera-tabla-azul-oscuro justify-content-center">
                            <b>RESULTADOS OBTENIDOS</b>
                        </div>
                        <div style="height: 0.5rem;"></div>
                        <div style="height: 400px; overflow-y: auto;">
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
                                    @foreach ($existePersona as $persona)
                                        <tr>
                                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                                            <td>{{ $persona->ci }} @if ($persona->complemento)
                                                    - {{ $persona->complemento }}
                                                @endif
                                            </td>
                                            <td>{{ $persona->matricula_seguro }}</td>
                                            <td>
                                                @if ($persona->p_apellido == null)
                                                    {{ $persona->s_apellido . ' ' . $persona->nombres }}
                                                @elseif ($persona->s_apellido == null)
                                                    {{ $persona->p_apellido . ' ' . $persona->nombres }}
                                                @else
                                                    {{ $persona->p_apellido . ' ' . $persona->s_apellido . ' ' . $persona->nombres }}
                                                @endif
                                            </td>
                                            <td>{{ $persona->tipoAseguradoPersona->tipo_asegurado }}</td>
                                            <td style="text-align: center;">
                                                <button class="btn btn-success" type="submit"
                                                    wire:click="seleccionarPersona('{{ $persona->id }}')"
                                                    title="SELECCIONAR ASEGURADO">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor"
                                                        class="bi bi-person-fill-check" viewBox="0 0 16 16">
                                                        <path
                                                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                        <path
                                                            d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            @endif
        </div>
    @endif

    <div wire:loading wire:target="registrarBaja">
        <x-cargando-dos />
    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 50px;
        }
    </style>



    <!-- FUNCIONES -->
    <script>
        // FUNCION DEL BOTON GUARDAR
        function guardarBajaInstitucional() {
            Swal.fire({
                title: 'Esta seguro de registrar la baja institucional?',
                text: "Se registrarán los datos ingresados.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarBaja');
                }
            });
        }

        document.addEventListener('bajaRegistrada', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La institución se registró correctamente.",
                icon: "success",
                showConfirmButton: false

            });
        });
        document.addEventListener('bajaNoRegistrada', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Usted canceló el registro.",
                icon: "error",
                showConfirmButton: false

            });
        });

        document.addEventListener('noVigente', function() {
            Swal.fire({
                title: "Asegurado en estado de CESANTIA",
                text: "El asegurado ya se encuentra en estado de cesantia",
                icon: "warning"
            });
        });

        document.addEventListener('noAseguradoRegular', function() {
            Swal.fire({
                title: "Los datos no corresponden a ningún asegurado",
                text: "Los datos no corresponden a ningún asegurado Titular, Beneficiario o Estudiante",
                icon: "warning"
            });
        });

        document.addEventListener('cancelarRegistro', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Usted canceló el registro",
                icon: "error",
                showConfirmButton: false
            });
        });
    </script>



</div>
