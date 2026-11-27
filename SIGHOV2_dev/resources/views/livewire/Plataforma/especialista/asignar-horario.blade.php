<div>
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">ASIGNACIÓN DE HORARIO </h7>
            <x-atras-button wire:click="cancel">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>

    <div class="row">
        <!-- DATOS PRINCIPALES-->
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                    <p style="background-color:#c4dff3 ;text-align:center; "> <b>
                            DATOS DEL ESPECIALISTA</b></p>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                    <!-- FOTOGRAFIA -->
                                    <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                                        class="img-thumbnail" alt="ESPECIALISTA"
                                        style="width: 200px; height: 200px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                    style="display:flex; justify-content: center; font-size: 22px">
                                    <br>
                                    @if ($permanente)
                                        <span style="color: #2874A6;"><b>PERMANENTE</b></span>
                                    @else($permanente == false)
                                        <span style="color: #DC7633;"><b>EVENTUAL</b></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nombres">NOMBRE COMPLETO:</label>
                                    <input class="form-control" id="nombres" wire:model=nombres disabled
                                        style=" height: 30px;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="fecha_nacimiento">FECHA NAC.: </label>
                                    <input class="form-control" id="fecha_nacimiento" wire:model=fecha_nacimiento
                                        disabled style=" height: 30px;" type="date">
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="carnet">CARNET: </label>
                                    <input class="form-control" id="carnet" wire:model=carnet disabled
                                        style=" height: 30px;">
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="expedido">EXP.: </label>
                                    <input class="form-control" id="expedido" wire:model=expedido disabled
                                        style=" height: 30px;">
                                </div>
                            </div>
                            <div class ="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="doc_especialidad">ESPECIALIDAD:</label>
                                    <input class="form-control" id="doc_especialidad" wire:model=doc_especialidad
                                        disabled style=" height: 30px;  ">
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="grado">GRADO ACADEMICO:</label>
                                    <input class="form-control" id="grado" wire:model=grado disabled
                                        style=" height: 30px;">
                                </div>
                            </div>
                            <div class ="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="matricula">MATRÍCULA:</label>
                                    <input class="form-control" id="matricula" wire:model=matricula disabled
                                        style=" height: 30px;">
                                </div>
                                @if (!$permanente)
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="fecha_inicio">CONTRATO DESDE:</label>
                                        <input class="form-control" id="fecha_inicio" wire:model=fecha_inicio disabled
                                            style=" height: 30px;" type="date">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="fecha_fin">CONTRATO HASTA:</label>
                                        <input class="form-control" id="fecha_fin" wire:model=fecha_fin disabled
                                            style=" height: 30px;" type="date">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-3 mt-lg-0">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                    <p style="background-color:#c4dff3 ;text-align:center; "> <b>
                            REGISTRAR NUEVO HORARIO</b></p>
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
                                <em class="ml-2"><b>Introduzca y complete los siguientes campos.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        {{-- @if ($permanente) --}}
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicioAsig"> FECHA INICIO:</label>
                            {{-- {{$this->fecha_inicioAsig=$this->fecha_inicio}} --}}
                            @if ($permanente)
                                <input class="form-control @error('fecha_inicioAsig') is-invalid @enderror"
                                    type='date' min="{{ now()->toDateString() }}" id="fecha_inicioAsig"
                                    wire:model=fecha_inicioAsig>
                            @else
                                <input class="form-control @error('fecha_inicioAsig') is-invalid @enderror"
                                    type='date' min="{{ now()->toDateString() }}" id="fecha_inicioAsig"
                                    wire:model=fecha_inicio>
                            @endif
                        </div>
                        {{-- @endif --}}
                        {{-- <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicioAsig"> FECHA INICIO:</label>
                            <input class="form-control @error('fecha_inicioAsig') is-invalid @enderror" 
                                type='date' 
                                min="{{ now()->toDateString() }}" 
                                id="fecha_inicio" 
                                wire:model.live=fecha_inicioAsig
                                value="{{ now()->toDateString() }}" 
                                >
                        </div> --}}
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_finAsig"> FECHA FIN:</label>
                            <input class="form-control @error('fecha_finAsig') is-invalid @enderror" type='date'
                                min="{{ now()->toDateString() }}" id="fecha_finAsig" wire:model.live=fecha_finAsig>
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="dia">DÍA DE LA SEMANA: </label>
                            <select wire:model="id_diaAsig" id="id_diaAsig" name="id_diaAsig"
                                class="form-control @error('id_diaAsig') is-invalid @enderror" style="width: 100%;"
                                tabindex="-1" aria-hidden="true" required>
                                <option selected="selected">Seleccione un día.</option>
                                @foreach ($dia as $dato)
                                    <option value="{{ $dato->id }}">{{ $dato->nombre }}</option>
                                @endforeach

                            </select>
                            @error('dia')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="consultorio"> CONSULTORIO:</label>
                            <select wire:model="id_consultorioAsig" id="id_consultorioAsig" name="id_consultorioAsig"
                                class="form-control select2 @error('id_consultorioAsig') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                <option selected="selected">Seleccione el consultorio a registrar.</option>
                                @foreach ($consultorio as $dato)
                                    <option value="{{ $dato->id }}">Consul. Nro:
                                        {{ $dato->numero_consultorio }} -Sede: {{ $dato->ubicacion }} -
                                        {{ $dato->piso }}</option>
                                @endforeach

                            </select>
                            @error('dia')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                            <label for="horario"> HORARIO:</label>
                            <select wire:model="id_horarioAsig" id="id_horarioAsig" name="id_horarioAsig"
                                class="form-control select2 @error('id_horarioAsig') is-invalid @enderror "
                                style="width: 100%;" tabindex="-1" aria-hidden="true" required
                                wire:change="ValidarHorario">
                                <option selected="selected">Seleccione el horario a registrar.</option>
                                @foreach ($horario as $dato)
                                    <option value="{{ $dato->id }}">( HORA: {{ $dato->hora_inicio }} a
                                        {{ $dato->hora_fin }} ) ( INTERVALO:
                                        {{ $dato->intervalo }} min.) ( #PACIENTES: {{ $dato->numero_paciente }} )
                                    </option>
                                @endforeach

                            </select>
                            @error('horario')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Seleccione el horario.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                            <br>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
                            <x-save-button onclick="guardarAsignacion()">GUARDAR</x-save-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MOSTRAMOS LA LISTA DE HORARIOS -->
    <div class="row mt-3">
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="height: 400px; overflow-y: auto;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="text-align:center; background-color:#2471a3; color:white;">
                                            <th scope="col" width="14%">LUNES</th>
                                            <th scope="col" width="14%">MARTES</th>
                                            <th scope="col" width="14%">MIÉRCOLES</th>
                                            <th scope="col" width="14%">JUEVES</th>
                                            <th scope="col" width="14%">VIERNES</th>
                                            <th scope="col" width="14%">SÁBADO</th>
                                            <th scope="col" width="14%">DOMINGO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'LUNES' && $dato->estado == true)
                                                        <!-- LUNES -->
                                                        <p> <span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                            <x-edit-button
                                                                wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                            <hr
                                                                style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'MARTES' && $dato->estado == true)
                                                        <!-- MARTES -->
                                                        <p> <span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                            <x-edit-button
                                                                wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                            <hr
                                                                style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'MIERCOLES' && $dato->estado == true)
                                                        <!-- MIERCOLES -->
                                                        <p><span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                            <x-edit-button
                                                                wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                            <hr
                                                                style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'JUEVES' && $dato->estado == true)
                                                        <!-- JUEVES -->
                                                        <p> <span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                            <x-edit-button
                                                                wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                            <hr
                                                                style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'VIERNES' && $dato->estado == true)
                                                        <!-- VIERNES -->
                                                        <p> <span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                            <x-edit-button
                                                                wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                            <hr
                                                                style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'SABADO' && $dato->estado == true)
                                                        <!-- SABADO -->
                                                        <p> <span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                        <div>
                                                            <span></span>
                                                            <label>NOMBRE DE ESPECIALISTA</label>
                                                        </div>
                                                        <x-edit-button
                                                            wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                        <hr
                                                            style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($horarios as $dato)
                                                    @if ($dato->dia_nombre == 'DOMINGO' && $dato->estado == true)
                                                        <!-- DOMINGO -->
                                                        <p> <span><b style="color:#196f3d ;">HORAS: </b>
                                                                {{ $dato->hora_inicio }} <b>-</b>
                                                                {{ $dato->hora_fin }}</span>
                                                            <br>
                                                            <span style="font-size:90%;"><b
                                                                    style="color:#c0392b;">FECHAS:</b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_inicio)->translatedFormat('d /m/ Y') }}
                                                                <b> A </b>
                                                                {{ \Carbon\Carbon::parse($dato->fecha_fin)->translatedFormat('d /m/ Y') }}</span>
                                                            <br>
                                                            <span><b>CONSULTORIO: </b> Nro.
                                                                {{ $dato->numero_consultorio }} , {{ $dato->piso }}
                                                                , {{ $dato->ubicacion }}</span>
                                                            <br>
                                                            <span><b>NRO. PACIENTES: </b>
                                                                {{ $dato->numero_paciente }}</span>
                                                            <br>
                                                            <x-edit-button
                                                                wire:click='editar_servicio({{ $dato }})'></x-edit-button>
                                                            <hr
                                                                style="border: none; height: 2px; background-color: #1f618d ; ">
                                                        </p>
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .select2-container .select2-selection--single {
            height: 46px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 46px;
        }
    </style>
    @include('livewire.Plataforma.especialista.modalEditarAsignarHorario')
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#id_consultorioAsig');
        $(select).select2();
        $('#id_consultorioAsig').on('change', function(e) {
            var data = $(this).val();
            @this.set('id_consultorioAsig', data);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#id_horarioAsig');
        $(select).select2();
        $('#id_horarioAsig').on('change', function(e) {
            var data = $(this).val();
            @this.set('id_horarioAsig', data);
        });
    });

    function guardarAsignacion() {
        Swal.fire({
            title: '¿Asignar Horario?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('asignarHorario');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
            }
        });
    }


    // registro de horario exitoso 
    document.addEventListener('registroExitoso', function() {
        Swal.fire({
            title: "Horario añadido!",
            text: "Registro Exitoso",
            icon: "success",
            timer: 10000,
        });
    });
    // caso ya exiaste ese horario 
    document.addEventListener('yaExisteHorario', function() {
        Swal.fire({
            title: "Horario ya fue añadido",
            text: "El horario y el dia seleccionado ya existen!",
            icon: "warning",
            timer: 10000,
        });
    });

    document.addEventListener('yaExisteTurno', function() {
        Swal.fire({
            title: "Horario ya fue añadido",
            text: "El Turno ya fue asignado, seleccione otro turno!",
            icon: "warning",
            timer: 10000,
        });
    });

    document.addEventListener('yaExisteIntervalo', function() {
        Swal.fire({
            title: "Horario ya fue añadido",
            text: "Las fechas seleccionadas se encuentran dentro de un rango existente",
            icon: "warning",
            timer: 10000,
        });
    });
    document.addEventListener('editarAsignarHorario', function editarHorario() {
        $('#modalAsignarHorarioEditar').modal('show');
    })
    document.addEventListener('editarAsignarHorario_exitoso', function editarHorario() {
        $('#modalAsignarHorarioEditar').modal('hide');
    })
</script>
