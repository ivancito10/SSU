<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                REGISTRAR SUSPENSIÓN DE HORARIO
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
            <div class="card card-comun p-2">
                <div class="cabecera text-center">
                    <b>ESPECIALISTA</b>
                </div>
                <div style="height: 0.5rem;"></div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <!-- FOTOGRAFIA -->
                            <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
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
                            @else
                                <span style="color: #DC7633;"><b>EVENTUAL</b></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-8 col-sm-8 col-xs-8">
                            <strong>NOMBRE COMPLETO:</strong>
                            <p class="p-comun-disabled p-1">{{ $nombres }} </p>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-4 col-xs-4">
                            <strong>CARNET: </strong>
                            <p class="p-comun-disabled p-1">{{ $carnet }} </p>
                        </div>
                    </div>
                    <hr class="p-0 m-0">
                    <div class ="row">
                        <div class="col-lg-12 col-md-5 col-sm-5 col-xs-12">
                            <strong>ESPECIALIDAD:</strong>
                            <p class="p-comun-disabled p-1">{{ $especialidad }} </p>
                        </div>
                        <div class="col-lg-12 col-md-3 col-sm-3 col-xs-6">
                            <strong>GRADO ACADEMICO:</strong>
                            <p class="p-comun-disabled p-1">{{ $grado_academico }} </p>
                        </div>
                        @if (!$permanente)
                            <div class="col-lg-12 col-md-4 col-sm-4 col-xs-6">
                                <strong> FECHA CONTRATO:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ \Carbon\Carbon::parse($fecha_contrato_inicio)->Format('d /m/ Y') }} <b>-</b>
                                    {{ \Carbon\Carbon::parse($fecha_contrato_fin)->Format('d /m/ Y') }}</p>
                            </div>
                        @endif
                    </div>
                    <br>
                    {{-- @if ($switchMotivo)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <x-ver-button wire:click="AbrirModal">Ver Horario</x-ver-button>
                                    @include('livewire.Plataforma.suspension-horario.modalVerAgenda')
                                </div>
                            </div>
                        @endif --}}
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 mt-3 mt-lg-0">
            <div class="card card-comun p-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <p style="color:#2471a3" class="h5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b>Ingresar y completar los siguientes campos:</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <label for="fecha_suspension"> FECHA SUSPENSIÓN: </label>
                        <input class="form-control input-comun @error('fecha_suspension') is-invalid @enderror"
                            id="fecha_suspension" type="date" wire:model=fecha_suspension
                            wire:change="verificarEspecialistaHabilitado()">
                        @error('fecha_suspension')
                            <div class="text-danger">
                                <b>El campo fecha de suspension es obligatorio.</b>
                            </div>
                        @enderror
                    </div>

                    @if ($switchTurno)
                        @if ($variosTurnos)
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="turnoSeleccionado">TURNO: </label>
                                <select wire:model="turnoSeleccionado" id="turnoSeleccionado"
                                    class="form-control input-comun @error('turnoSeleccionado') is-invalid @enderror"
                                    wire:change='mostrarMotivos'>
                                    <option value="0" selected>Seleccione el turno...</option>
                                    @foreach ($turnos as $turno)
                                        <option value={{ $turno->id }}>
                                            {{ $turno->horarioAsignacionHorario->turno }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('turnoSeleccionado')
                                    <div class="text-danger">
                                        <b>El campo turno es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                        @else
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="turnoLiteral"> TURNO: </label>
                                <input class="form-control input-comun" id="turnoLiteral" type="text" disabled
                                    wire:model=turnoLiteral>
                            </div>
                        @endif

                    @endif


                    @if ($switchMotivo)
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <label for="idMotivoSuspension"> MOTIVO SUSPENSIÓN:</label>
                            <select wire:model="idMotivoSuspension" id="idMotivoSuspension"
                                class="form-control input-comun @error('idMotivoSuspension') is-invalid @enderror"
                                wire:change="mostrarHorarios">
                                <option value="0" selected>Seleccione el motivo...</option>
                                @foreach ($motivos as $motivo)
                                    <option value={{ $motivo->id }}>{{ $motivo->nombre_motivo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idMotivoSuspension')
                                <div class="text-danger">
                                    <b>El campo motivo de suspension es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                    @endif
                </div>

                @if ($switchMotivo)
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="observacion"> OBSERVACIÓN: </label>
                            <textarea class="form-control input-comun @error('observacion') is-invalid @enderror" id="observacion"
                                placeholder="Ingrese la observación..." wire:model="observacion" oninput="this.value = this.value.toUpperCase()"></textarea>
                            @error('observacion')
                                <div class="text-danger">
                                    <b>El campo observacion es obligatorio.</b>
                                </div>
                            @enderror
                            <br>
                        </div>
                    </div>
                @endif

                @if ($switchPersona)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                        <em class="ml-2"><b>Ingresar los datos correspondientes para la búsqueda:</b>
                                        </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                    <br>
                                    <button class=" form-control btn-primary" wire:click="buscarPersona"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg></button>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                    <label for="matriculaPersona">MATRÍCULA DEL ASEGURADO:</label>
                                    <input type="text" class="form-control input-comun"
                                        placeholder="Introduzca la matrícula..." wire:model="matriculaPersona"
                                        id="matriculaPersona" oninput="this.value = this.value.toUpperCase()">
                                </div>

                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <div wire:loading wire:target="matriculaPersona">
                                        <div class="spinner-grow text-warning" role="status">
                                        </div>
                                        <b><i>Cargando datos espere porfavor...</i></b>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="carnetPersona">CARNET:</label>
                                        <input id="carnetPersona" class="form-control" type="number"
                                            placeholder="Introduzca el carnet " wire:model="carnetPersona">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="complementoPersona">COMPLEMENTO:</label>
                                        <input type="text" class="form-control" id="complementoPersona"
                                            placeholder="Introduzca el complemento" max="3"
                                            wire:model="complementoPersona"
                                            oninput="this.value = this.value.toUpperCase()">
                                    </div> --}}
                            </div>

                            {{-- <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombrePersona">NOMBRES:</label>
                                        <input type="text" class="form-control"
                                            placeholder="Introduzca los nombres" wire:model="nombrePersona"
                                            id="nombrePersona" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="primerApellidoPersona">PRIMER APELLIDO:</label>
                                        <input type="text" id="primerApellidoPersona" class="form-control"
                                            placeholder="Introduzca el primer apellido" wire:model="primerApellidoPersona"
                                            oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="segundoApellidoPersona">SEGUNDO APELLIDO:</label>
                                        <input type="text" id="segundoApellidoPersona" class="form-control"
                                            placeholder="Introduzca el segundo apellido" wire:model="segundoApellidoPersona"
                                            oninput="this.value = this.value.toUpperCase()">
                                    </div> 
                                </div> --}}
                        </div>
                    </div>
                @endif

                @if ($switchMultiplesResultados)

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card p-2 card-comun">
                            <div class="cabecera-tabla-celeste justify-content-center ">
                                <b>ASEGURADOS ENCONTRADOS</b>
                            </div>
                            <div style="height: 0.5rem;"></div>
                            <div class="row" style="height: 320px; overflow-y: auto;">
                                <table class="tabla-azul-oscuro">
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
                                                        title="SELECCIONAR"
                                                        wire:click="seleccionarPersona('{{ $persona->id }}')">
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

                @if ($switchSeleccionado)
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12  ">
                        <div class="card card-borde p-3" style="background-color:#e5f0f9;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="pl-3" style="color:#2471a3;"><strong>ASEGURADO SELECCIONADO</strong>
                                    </h5>
                                    <p class="mb-0 text-dark">
                                        <strong>NOMBRE:</strong> {{ $persona->p_apellido }} {{ $persona->s_apellido }}
                                        {{ $persona->nombres }}<br>
                                        <strong>MATRÍCULA:</strong> {{ $persona->matricula_seguro }}<br>
                                        <strong>C.I.:</strong> {{ $persona->ci }} {{ $persona->complemento }}
                                    </p>
                                </div>
                                <div class="text-center pl-3">
                                    <p class="mb-1 text-danger"><strong>ELIMINAR</strong></p>
                                    <x-cancel-button wire:click="eliminarPersonaSeleccionada"></x-cancel-button>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
                @if ($switchMostrarHorarios)
                    <br>
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
                                <em class="ml-2"><b>Registrar los siguientes campos para el horario:</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="horaInicio"> HORA INICIO: </label>
                            <select wire:model="horaInicio" id="horaInicio"
                                class="form-control input-comun @error('horaInicio') is-invalid @enderror"
                                wire:change="actualizarHorasFinales">
                                <option value=" " selected>Seleccione el horario
                                    inicio...
                                </option>
                                @foreach ($horariosInicio as $horarioInicio)
                                    <option value="{{ $horarioInicio['hora'] }}"
                                        @if ($horarioInicio['estado'] === 'OCUPADO' || $horarioInicio['estado'] === 'SUSPENDIDO') disabled style="color: red;" @endif>
                                        {{ $horarioInicio['hora'] }} - {{ $horarioInicio['estado'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('horaInicio')
                                <div class="text-danger">
                                    <b>El campo hora inicio es obligatorio.</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="horaFin"> HORA FIN: </label>
                            <select wire:model="horaFin" id="horaFin"
                                class="form-control input-comun @error('horaFin') is-invalid @enderror">
                                <option value=" " selected>Seleccione el horario
                                    fin...
                                </option>
                                @foreach ($horariosFin as $horarioFin)
                                    <option value="{{ $horarioFin['hora'] }}">
                                        {{ $horarioFin['hora'] }}
                                    </option>
                                @endforeach
                                @error('horaFin')
                                    <div class="text-danger">
                                        <b>El campo hora fin es obligatorio.</b>
                                    </div>
                                @enderror
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <x-save-button wire:click="guardarAnulacion">GUARDAR</x-save-button>
                        </div>
                        <div wire:loading wire:loading.attr="disabled" wire:target="guardarAnulacion">
                            <x-cargando-dos />
                        </div>
                        <div id="cargando-anulacion" style="display: none;">
                            <x-cargando-dos />
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('agendaYaExiste', function() {

        Swal.fire({
            title: "Error",
            text: "Se cancelo el registro, ya existe un dato en la hora seleccionada!!!",
            icon: "error"

        });
    });
    document.addEventListener('AgendaRegistrada', function() {

        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "Se registró correctamente la suspensión!!!",
            icon: "success"

        });
    });

    document.addEventListener('ExistenAgendados', function() {
        Swal.fire({
            title: "<b>Advertencia</b>",
            text: "No se pudo registrar, existen agendados en este intervalo!!!",
            icon: "warning"

        });
    });

    document.addEventListener('RegistroExistoso', function() {

        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "Se registró correctamente la suspensión!!!",
            icon: "success"

        });
    });

    document.addEventListener('AbrilModal', function() {
        $('#verAgendaCompleta').modal('show');
    });

    // validacion para cuando no tenga horarios asignados
    document.addEventListener('sinHorario', function() {
        Swal.fire({
            title: "<b>No tiene Horarios</b>",
            text: "El especialista no tiene horarios en la fecha  seleccionada",
            icon: "warning"

        });
    });
    // validacion para cuando elija horario inicio meno  a fin
    document.addEventListener('horarioMenor', function() {
        Swal.fire({
            title: "<b>Error</b>",
            text: "Selecciona una hora fin mayor a la hora de inicio",
            icon: "warning"
        });
    });
    // validacion para el caso de que el asegurado no se encuentre:
    document.addEventListener('noEstaAsegurado', function() {
        Swal.fire({
            title: "<b>Error</b>",
            text: "La persona seleccionada no se encuentra afiliada",
            icon: "warning"
        });
    });

    //VALIDACIONES
    document.addEventListener('tieneVacacion', function() {
        Swal.fire({
            title: "<b>El especialista se encuentra con vacación</b>",
            text: "Seleccione otra fecha",
            icon: "warning"
        });
    });

    document.addEventListener('noTieneHorario', function() {

        Swal.fire({
            title: "<b>El especialista no cuenta con horarios disponibles</b>",
            text: "Seleccione otra fecha",
            icon: "warning"

        });
    });

    //PREGUNTA SI GUARDAR O NO
    document.addEventListener('preguntarGuardar', function() {
        Swal.fire({
            title: '¿Registrar suspensión de horario?',
            text: 'Se registrará la suspensión de horario del especialista',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                Livewire.dispatch('guardarAnulacion');
                document.getElementById('cargando-anulacion').style.display = 'block';

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el agendamiento",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('existeAgenda', function() {
        Swal.fire({
            title: "<b>El especialista tiene horarios o suspensiones registradas en el rango seleccionado</b>",
            text: "Seleccione otro rango o registre una nueva suspensión",
            icon: "warning"
        });
    });

    document.addEventListener('registroExistoso', function() {
        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "La suspensión se registró correctamente",
            icon: "success",
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('sinPersona', function() {
        Swal.fire({
            title: "<b>Seleccione un asegurado</b>",
            text: "Debe registrar un beneficiario para guardar la suspensión",
            icon: "warning"
        });
    });

    document.addEventListener('noSeEncontraronDatos', function() {
        Swal.fire({
            title: "<b>La persona seleccionada no se encuentra afiliada.</b>",
            html: "Intente ingresar nuevamente. <br> Recuerde ingresar la <b>MATRÍCULA</b> del asegurado.",
            icon: "error"
        });
    });
</script>
