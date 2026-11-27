<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                AGREGAR NUEVA AGENDA - ECOGRAFÍA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="volver" wire:loading.attr="disabled" wire:target="volver">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="volver">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); ">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#2471a3" class="h5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b>A continuación busque al asegurado.</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        @livewire('Plataforma.agendamiento.ecografia.agenda-ecografia.agenda-register')
                    </div>
                </div>
                @if ($idAsegurado != null)
                    <br>
                    <div style="height: 0.5rem;"></div>
                    <div class="cabecera-borde-azul justify-content-center">
                        <b>DATOS ASEGURADO </b>
                    </div>
                    <div style="height: 0.5rem;"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12"
                            style="display: flex; justify-content: center; align-items: center;">
                            <img src="{{ !empty($foto_nombre) ? 'data:image/jpg;base64,' . $foto_nombre : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="ASEGURADO"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                            <div class="col-12 text-center">
                                @if ($estado_vigencia == 'VIGENTE')
                                    <p style="color: green; font-size: 22px; margin: 0;"><b>VIGENTE</b></p>
                                @else
                                    <p style="color: orange; font-size: 22px; margin: 0;"><b>CESANTE</b></p>
                                @endif
                            </div>
                            <div class="col-12 text-center">
                                <p style="color:#115272; font-size: 25px; margin: 0;"><b>{{ $tipo_asegurado }}</b></p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <span><b>MATRICULA: </b> {{ $matricula }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <span><b>CARNET: </b> {{ $carnet }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <span><b>NOMBRE COMPLETO:</b> {{ $nombreCompleto }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($institucion == null && $nombreTitularAutoriza == null && $nombre_seguro != null)
                                    <span><b>SEGURO DE ORIGEN:</b> {{ $institucion }} </span>
                                @elseif($nombre_seguro == null && $nombreTitularAutoriza == null && $institucion != null)
                                    <span><b>INSTITUCIÓN :</b> {{ $institucion }} </span>
                                @elseif($institucion == null && $nombre_seguro == null && $nombreTitularAutoriza != null)
                                    <span><b>TITULAR QUE AUTORIZA:</b> {{ $institucion }} </span>
                                @endif
                            </div>
                        </div>
                        <div style="height: 0.5rem;"></div>
                        <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12 text-center">
                            @if ($limitacionesConvenio)
                                @include('livewire.Plataforma.agendamiento.ecografia.agenda-ecografia.modalVerDatosConvenio')
                            @endif
                        </div>
                    </div>
                @endif
                <hr>       
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <x-redirigir-agendamiento-button wire:click="redirigirAgendamientoNormal">IR A: AGENDAMIENTO PLATAFORMA</x-redirigir-agendamiento-button>
                    </div>
                </div>

            </div>
        </div>
        @if ($idAsegurado != null)
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 mt-3 mt-lg-0">
                <div class="card p-2 card-comun">
                    <div class="row">
                        <div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
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
                                        <em class="ml-2"><b>Seleccionar los siguientes campos.</b>
                                        </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    <label for="fechaAgenda"> FECHA:</label>
                                    <input class="form-control input-comun" id="fechaAgenda" type="date"
                                        wire:model="fechaAgenda" wire:change="cambiarFecha"
                                        min="{{ \Carbon\Carbon::now()->subDays(7)->format('Y-m-d') }}">
                                </div>
                                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                    <label for="id_especialidad">ESPECIALIDAD:</label>
                                    <select wire:model.live="id_especialidad" id="id_especialidad"
                                        class="form-control input-comun select2 " style="width: 100%;" tabindex="-1"
                                        required wire:change="mostrarEspecialistas">
                                        <option value="" selected>Seleccione la especialidad</option>
                                        @foreach ($especialidades as $esp)
                                            <option value="{{ $esp->id }}">{{ $esp->especialidad }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="id_especialista"> ESPECIALISTA:</label>
                                    <select wire:model="id_especialista" id="id_especialista"
                                        class="form-control input-comun" style="width: 100%;" tabindex="-1" required
                                        wire:change="datosEspecialista">
                                        <option value="" selected>Seleccione al Especialista</option>
                                        @foreach ($lista_especialistas as $listesp)
                                            <option value="{{ $listesp->id }}">
                                                {{ $listesp->personaEspecialista->p_apellido .
                                                    ' ' .
                                                    $listesp->personaEspecialista->s_apellido .
                                                    ' ' .
                                                    $listesp->personaEspecialista->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                    @if ($variosTurnos == 1)
                                        <label for="turnoSeleccionado">TURNO:</label>
                                        <select wire:model="turnoSeleccionado" id="turnoSeleccionado"
                                            class="form-control input-comun" style="width: 100%;" tabindex="-1"
                                            required wire:change="horarioDisponible">
                                            <option value="" selected>Selecciona el turno</option>
                                            @foreach ($turnos as $turno)
                                                <option value="{{ $turno['id'] }}">
                                                    {{ $turno['horario_asignacion_horario']['turno'] }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($variosTurnos == 2)
                                        <strong>TURNO:</strong>
                                        <p class="p-1 p-comun-disabled">
                                            {{ $turnoSolo }}</p>
                                    @else
                                    @endif

                                </div>
                                 <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <br>
                                    @if ($horasDisponibles != null)
                                        <x-agregar-button style="padding:4px" wire:click="agendaFichaExtra">AGREGAR FICHA
                                            EXTRA</x-agregar-button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- Datos de especialista seleccionado --}}
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            @if ($id_especialista != null)
                                <div class="card-borde p-2">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                            style="text-align:center;">
                                            <p class="h4">
                                                <b>{{ $especialidad_e }}</b>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12"
                                            style="display: flex; justify-content: center; align-items: center;">
                                            <!--  Mostramos la foto del asegurado en base 64 -->
                                            <img src="{{ !empty($foto_e) ? 'data:image/jpg;base64,' . $foto_e : asset('images/default_afiliacion.jpg') }}"
                                                class="img-thumbnail" alt="FTP.{{ $foto_e }}"
                                                style="width: 160px; height: 160px; object-fit: cover;">
                                        </div>
                                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                    <strong>ESPECIALISTA: </strong>
                                                    <p class="p-1">
                                                        {{ $nombre_completo_e }}</p>
                                                </div>
                                                <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12 ">
                                                    @if ($mostrarBoton)
                                                        @include('livewire.Plataforma.agendamiento.ecografia.agenda-ecografia.modalVerAgenda')
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                    {{-- <div id="printSection" style="display: none;">
                        <h2>Datos de la Agenda</h2>
                        <p><strong>Fecha:</strong> {{ $this->fechaAgenda }}</p>
                        <p><strong>Hora:</strong> {{ $this->horaSelect }}</p>
                        <p><strong>Nombre Completo:</strong> {{ $this->nombreCompleto }}</p>
                        <p><strong>Especialista:</strong> {{ $this->nombre_completo_e }}</p>
                        <p><strong>Especialidad:</strong> {{ $this->especialidad_e }}</p>
                    </div> --}}



                    @if ($horasDisponibles != null)
                        <!-- <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="text-decoration:underline; color:#1a5276 ;" class="h5"><b> <em>Listado de horarios asignados al médico</em></b></p>
                            </div>

                    </div> -->
                        <br>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left;">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill=" #f1c40f" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="8" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                        fill="currentColor" class="bi bi-alarm" viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9z" />
                                        <path
                                            d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1zm1.038 3.018a6 6 0 0 1 .924 0 6 6 0 1 1-.924 0M0 3.5c0 .753.333 1.429.86 1.887A8.04 8.04 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5M13.5 1c-.753 0-1.429.333-1.887.86a8.04 8.04 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1" />
                                    </svg>
                                    <b><em> FUERA DE HORARIO</em> </b>
                                </p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left;">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill=" #1e8449" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="8" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                        fill="currentColor" class="bi bi-alarm" viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9z" />
                                        <path
                                            d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1zm1.038 3.018a6 6 0 0 1 .924 0 6 6 0 1 1-.924 0M0 3.5c0 .753.333 1.429.86 1.887A8.04 8.04 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5M13.5 1c-.753 0-1.429.333-1.887.86a8.04 8.04 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1" />
                                    </svg>

                                    <b><em> DISPONIBLE</em></b>
                                </p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left;">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill=" #c0392b" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="8" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                        fill="currentColor" class="bi bi-alarm" viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9z" />
                                        <path
                                            d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1zm1.038 3.018a6 6 0 0 1 .924 0 6 6 0 1 1-.924 0M0 3.5c0 .753.333 1.429.86 1.887A8.04 8.04 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5M13.5 1c-.753 0-1.429.333-1.887.86a8.04 8.04 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1" />
                                    </svg>

                                    <b><em> HORARIO OCUPADO</em> </b>
                                </p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left;">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        fill=" #1a5276" class="bi bi-circle-fill" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="8" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                        fill="currentColor" class="bi bi-alarm" viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9z" />
                                        <path
                                            d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1zm1.038 3.018a6 6 0 0 1 .924 0 6 6 0 1 1-.924 0M0 3.5c0 .753.333 1.429.86 1.887A8.04 8.04 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5M13.5 1c-.753 0-1.429.333-1.887.86a8.04 8.04 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1" />
                                    </svg>

                                    <b><em> FICHA EXTRA</em> </b>
                                </p>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <div style="height: 420px; overflow-y: auto;">
                                    <table class="tabla-celeste" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width: 1%; text-align: center;">HORA</th>
                                                <th scope="col" style="width: 8%; text-align: center;">MATRICULA
                                                </th>
                                                <th scope="col" style="width: 35%; text-align: center;">ASEGURADO
                                                </th>
                                                <th scope="col" style="width: 15%; text-align: center;">TIPO
                                                    ASEGURADO</th>
                                                <th scope="col" style="width: 6%; text-align: center;">SUSPENSIÓN
                                                </th>
                                                {{-- <th scope="col">SERVICIO</th> --}}
                                                <th scope="col" style="width: 1%; text-align: center;">IMPRIMIR
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody style="text-align:center;">
                                            @foreach ($horasDisponibles as $key => $hora)
                                                <tr>
                                                    <td>
                                                        <button wire:click="agendar('{{ $hora['hora'] }}')"
                                                            type="button"
                                                            class="btn {{ $hora['estado'] == 'LIBRE' ? 'btn-success' : ($hora['ficha_extra'] ? 'btn-azulcito' : 'btn-danger') }} {{ $hora['hora'] < $horaActual ? 'btn-warning' : '' }}"
                                                            {{ $hora['estado'] == 'OCUPADO' ? 'disabled' : '' }}
                                                            {{ $hora['estado'] == 'SUSPENDIDO' ? 'disabled' : '' }}
                                                            {{ $hora['ficha_extra'] ? 'disabled' : '' }}
                                                            wire:loading.attr="disabled">
                                                            <span wire:loading.remove
                                                                wire:target="agendar('{{ $hora['hora'] }}')">
                                                                {{ $hora['hora'] }}
                                                            </span>
                                                            <span wire:loading
                                                                wire:target="agendar('{{ $hora['hora'] }}')"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </button>
                                                        <div wire:loading
                                                            wire:target="agendar('{{ $hora['hora'] }}')">
                                                            <x-cargando-dos />
                                                        </div>
                                                    </td>
                                                    @if ($hora['persona_asociada'])
                                                        <td>{{ $hora['matricula'] }}</td>
                                                        <td>{{ $hora['asegurado'] }}</td>
                                                        <td>{{ $hora['tipo_asegurado'] }}</td>
                                                    @else
                                                        <td colspan="3">{{ $hora['observacion'] }}</td>
                                                    @endif

                                                    <td>{{ $hora['motivo_suspension'] }}</td>
                                                    {{--  <td>{{ $hora['servicio'] }}</td> --}}
                                                    <td>
                                                        @if ($hora['estado'] == 'OCUPADO')
                                                            <x-imprimir-button
                                                                wire:click="fichaAgenda('{{ $hora['id_agenda'] }}')"></x-imprimir-button>
                                                        @endif
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
            </div>
        @endif

    </div>

    <style>
        .btn-azulcito {
            background-color: #1a5276;
            color: white;
            border-color: #1a5276;
        }

        .btn-azulcito:hover {
            background-color: #154360;
            border-color: #154360;
        }
    </style>
</div>

<script>
    document.addEventListener('crearAgendaNueva', function() {
        //alert('entra aqui');
        const gestionActual = new Date().getFullYear();
        const gestionAnterior = gestionActual - 1;

        Swal.fire({
            title: 'Agendar?',
            text: 'Se requiere el Nro. de Orden emitida para el registro',
            icon: 'warning',
            html: `
                    <div style="display: flex; gap: 10px; margin-top: 15px; text-align: left;">
                        <div style="flex: 2;">
                            <label style="display:block; margin-bottom:5px; font-weight:bold;">Nro. de Orden:</label>
                            <input id="swal-input-orden" class="swal2-input" type="text" inputmode="numeric" pattern="[0-9]*" 
                                placeholder="Escriba el número aquí..." style="width: 100%; margin: 0;"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div style="flex: 1;">
                            <label style="display:block; margin-bottom:5px; font-weight:bold;">Año:</label>
                            <select id="swal-input-gestion" class="swal2-select" style="width: 100%; margin: 0; height: 50px;">
                                <option value="${gestionAnterior}">${gestionAnterior}</option>
                                <option value="${gestionActual}" selected>${gestionActual}</option>
                            </select>
                        </div>
                    </div>
                `,

            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success mr-2',
                cancelButton: 'btn btn-danger'
            },

            // 2. Antes de confirmar, recuperamos los valores de los dos elementos HTML
            preConfirm: () => {
                const orden = document.getElementById('swal-input-orden').value;
                const gestion = document.getElementById('swal-input-gestion').value;

                if (!orden) {
                    Swal.showValidationMessage('Introduzca el número de orden por favor');
                    return false;
                }

                return {
                    orden: orden,
                    gestion: gestion
                };
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('agendaConfirmar', {
                    data: result.value
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el agendamiento",
                    icon: "error"
                });
            }
        });
    });


    document.addEventListener('crearAgendaNuevaFichaExtra', function() {
        //alert('entra aqui');
        const gestionActual = new Date().getFullYear();
        const gestionAnterior = gestionActual - 1;

        Swal.fire({
            title: 'Agendar?',
            text: 'Se requiere el Nro. de Orden emitida para el registro',
            icon: 'warning',
            html: `
                    <div style="display: flex; gap: 10px; margin-top: 15px; text-align: left;">
                        <div style="flex: 2;">
                            <label style="display:block; margin-bottom:5px; font-weight:bold;">Nro. de Orden:</label>
                            <input id="swal-input-orden" class="swal2-input" type="text" inputmode="numeric" pattern="[0-9]*" 
                                placeholder="Escriba el número aquí..." style="width: 100%; margin: 0;"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div style="flex: 1;">
                            <label style="display:block; margin-bottom:5px; font-weight:bold;">Año:</label>
                            <select id="swal-input-gestion" class="swal2-select" style="width: 100%; margin: 0; height: 50px;">
                                <option value="${gestionAnterior}">${gestionAnterior}</option>
                                <option value="${gestionActual}" selected>${gestionActual}</option>
                            </select>
                        </div>
                    </div>
                `,

            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success mr-2',
                cancelButton: 'btn btn-danger'
            },

            // 2. Antes de confirmar, recuperamos los valores de los dos elementos HTML
            preConfirm: () => {
                const orden = document.getElementById('swal-input-orden').value;
                const gestion = document.getElementById('swal-input-gestion').value;

                if (!orden) {
                    Swal.showValidationMessage('Introduzca el número de orden por favor');
                    return false;
                }

                return {
                    orden: orden,
                    gestion: gestion
                };
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('fichaExtra', {
                    data: result.value
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el agendamiento",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('imprimirAgenda', function() {
        //alert('entra');
        var printSection = document.getElementById('printSection').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printSection;
        window.print();
        document.body.innerHTML = originalContents;
    });


    document.addEventListener('agendaCreada', function() {
        Swal.fire({
            title: "<b>Felicidades!</b>",
            text: "Se agendó la cita!",
            icon: "success"

        });
    });

    document.addEventListener('agendaNoCreada', function() {
        Swal.fire({
            title: "<b>Error!</b>",
            text: "No se agendó la cita",
            icon: "error"

        });
    });

    document.addEventListener('agendaYaExiste', function() {
        Swal.fire({
            title: "<b>Agenda existente</b>",
            text: "La hora seleccionada ya se encuentra ocupada",
            icon: "warning"

        });
    });

    document.addEventListener('seleccionePersona', function() {
        Swal.fire({
            title: "<b>Seleccione al asegurado</b>",
            text: "Seleccione a un persona para realizar el agendamiento",
            icon: "warning"

        });
    });

    document.addEventListener('noTieneAgenda', function() {
        Swal.fire({
            title: "<b>No existen horarios disponibles</b>",
            text: "El especialista no tiene horarios habilitados en la fecha seleccionada",
            icon: "warning"

        });
    });

    document.addEventListener('horaPasada', function() {
        Swal.fire({
            title: "<b>Fuera de rango</b>",
            text: "El horario seleccionado esta fuera de rango",
            icon: "warning"
        });
    });

    document.addEventListener('fichaExtraExiste', function() {
        Swal.fire({
            title: "<b>Ya existe ficha extra para el asegurado</b>",
            text: "El asegurado seleccionado tiene una ficha extra registrada para el dia especificado",
            icon: "warning"

        });
    });

    document.addEventListener('alertaAgendaExistente', function(event) {
        const datosAgendaExistente = event.detail.datosAgendaExistenteArray;
        const item = Array.isArray(datosAgendaExistente) ? datosAgendaExistente[0] : datosAgendaExistente;

        const labels = {
            matricula: 'Matrícula',
            hora: 'Hora agenda',
            fecha_agenda: 'Fecha agenda',
            especialidad: 'Especialidad',
            nombre_completo: 'Asegurado(a)',
            tipo_asegurado: 'Tipo asegurado',
            especialista: 'Especialista'
        };

        const makeList = (obj) => {
            if (!obj) return '<p><em>(Sin datos)</em></p>';
            if (typeof obj !== 'object')
                return `<ul style="text-align:left;"><li><strong>${String(obj)}</strong></li></ul>`;
            return '<ul style="text-align:left; padding-left:18px; margin:0;">' + Object.entries(obj)
                .map(([k, v]) => {
                    const label = labels[k] ?? k.replace(/_/g, ' ').replace(/\b\w/g, c => c
                        .toUpperCase());
                    return `<li>${label}: <strong>${v ?? ''}</strong></li>`;
                }).join('') + '</ul>';
        };

        Swal.fire({
            title: "Familiar agendado en la misma fecha",
            html: `<p style="margin-bottom:6px;">Datos de la agenda registrada:</p>${makeList(item)}`,
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('cambioFechaNoHayEspecialistas', function() {
        Swal.fire({
            title: "<b>Se cambió la fecha</b>",
            html: "Los datos han sido actualizados, <b>no existen especialistas disponibles</b> para la fecha seleccionada",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000,
        })
    })

    document.addEventListener('advertenciaFechasPasadas', function() {
        Swal.fire({
            title: "<b>La fecha no se encuentra disponible</b>",
            html: "La fecha no puede ser seleccionada. Verifique e intente nuevamente",
            icon: "warning",
            showConfirmButton: true
        })
    })
    

    document.addEventListener('cambioFechaNoHayEspecialidades', function() {
        Swal.fire({
            title: "<b>Se cambió la fecha</b>",
            html: "Los datos han sido actualizados, <b>no existen especialidades disponibles</b> para la fecha seleccionada",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000,
        })
    })

    document.addEventListener('carnetCaduco', function(event) {
        const fechaVencimiento = event.detail.fechaVencimiento;
        Swal.fire({
            title: "<b>El carnet del asegurado caducó</b>",
            html: `El carnet de asegurado venció en fecha: <b>${fechaVencimiento}</b>`,
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    });
</script>
