<div>

    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                ASIGNACIÓN DE SERVICIO A ESPECIALISTA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="cancelarServicio" wire:loading.attr="disabled" wire:target="cancelarServicio">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="cancelarServicio">
                <x-cargando-dos />
            </div>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2 card-comun">
                <div class="cabecera text-center">
                    <b>ESPECIALISTA</b>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class="row ">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail" width="150px" height="150px" alt="FTP.{{ $foto }}">

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">

                        @if ($es_permanente)
                            <p style="color: #2874A6;" class="h4"><b>PERMANENTE</b></p>
                        @else($es_permanente == false)
                            <p style="color: #DC7633;" class="h4"><b>EVENTUAL</b></p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                        <span style="font-size:18px;"> <b>MATRÍCULA: </b>{{ $matricula_seguro }}</span>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>NOMBRE COMPLETO:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $nombre_completo }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6">
                        <strong>CARNET:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $ci_complemento }} </p>
                    </div>
                    {{-- <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong>EXP.:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $expedido }} </p>
                    </div> --}}
                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-6">
                        <strong>FECHA NACIMIENTO:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $fecha_nacimiento }} </p>
                    </div>
                </div>

            </div>
        </div>


        @include('livewire.Plataforma.especialista.especialista-servicio.modalAgregarHorario')
        @include('livewire.Plataforma.especialista.especialista-servicio.modalAgregarServicio')

        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2 card-comun">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <strong>ESPECIALIDAD:</strong>
                        <span style="color:#2c3e50;" class="h3">
                            <b>{{ $especialidad }} </b>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <strong>GRADO ACADEMICO:</strong>
                        <p class="p-1"
                            style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $grado_academico }} </p>
                    </div>
                    @if (!$es_permanente)
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <strong>INICIO CONTRATO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                {{ $contrato_desde }} </p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <strong>FIN CONTRATO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                {{ $contrato_hasta }} </p>
                        </div>
                    @else
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <strong>INICIO DE CONTRATO:</strong>
                            <p class="p-1"
                                style="background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                {{ $contrato_desde }} </p>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="color:#1a5276 ;" class="h5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                            <em class="ml-2"><b>Agregar nuevos horarios y servicios:</b> </em>
                            <spam style="color:#cb4335;"><b>*</b></spam>
                        </p>
                    </div>
                </div>
                <div class="row  d-flex justify-content-end">
                    <div class="col-auto ">
                        <x-reloj-button wire:click="abrirHorario({{ $idEsp }})" title="AGREGAR HORARIO">
                            AGREGAR HORARIO</x-reloj-button>
                    </div>
                    <div class="col-auto ">
                        <x-asignar-button wire:click="abrirServicio" title="AGREGAR SERVICIO">
                            AGREGAR SERVICIO</x-asignar-button>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive" style="height: 450px;">
                            <table class="table tabla-ploma">
                                <thead>
                                    <tr>
                                        <th>LUNES</th>
                                        <th>MARTES</th>
                                        <th>MIÉRCOLES</th>
                                        <th>JUEVES</th>
                                        <th>VIERNES</th>
                                        <th>SÁBADO</th>
                                        <th>DOMINGO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach (['1' => 'LUNES', '2' => 'MARTES', '3' => 'MIÉRCOLES', '4' => 'JUEVES', '5' => 'VIERNES', '6' => 'SÁBADO', '0' => 'DOMINGO'] as $diaId => $diaNombre)
                                            <td style="border: 1px solid #d1d5d8; padding: 6px; vertical-align: top;">
                                                {{-- Mostrar servicios --}}
                                                @php
                                                    $serviciosDia = $servicioSelected->filter(
                                                        fn($servicio) => $servicio->id_dia == $diaId,
                                                    );
                                                @endphp
                                                @if ($serviciosDia->isNotEmpty())
                                                    <div class="cabecera text-center">
                                                        <b>SERVICIOS:</b>
                                                    </div>
                                                    @foreach ($serviciosDia as $servicio)
                                                        <li style="font-size: 90%; margin-left: 16px; margin: 2px 0;">
                                                            {{ $servicio->servicioEspecialistaHabilitadoServicio->nombre_servicio }}
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <p
                                                        style="color: #e74c3c; font-weight: bold; text-align: center; margin: 2px 0;">
                                                        SIN SERVICIO SELECCIONADO</p>
                                                @endif

                                                {{-- Mostrar horarios --}}
                                                @php
                                                    $horariosDia = $horarioSelected->filter(
                                                        fn($horario) => $horario->id_dia == $diaId,
                                                    );
                                                @endphp

                                                @if ($horariosDia->isNotEmpty())
                                                    @foreach ($horariosDia as $horario)
                                                        <div class="cabecera text-center">
                                                            <b>
                                                                {{ $horariosDia->count() === 1 ? 'HORARIO' : 'HORARIO ' . $loop->iteration }}
                                                            </b>
                                                        </div>
                                                        <div style="height: 0.5rem;"></div>

                                                        <div
                                                            style="margin-bottom: 6px; padding: 4px; border: 1px solid #dcdcdc; border-radius: 5px; background-color: #ffffff;">
                                                            <p style="margin: 2px 0;">De:
                                                                <b>{{ \Carbon\Carbon::parse($horario->horarioAsignacionHorario->hora_inicio)->format('H:i') }}</b>
                                                                a
                                                                <b>{{ \Carbon\Carbon::parse($horario->horarioAsignacionHorario->hora_fin)->format('H:i') }}</b>
                                                            </p>
                                                            <p style="margin: 2px 0;">Pacientes:
                                                                <b>{{ $horario->horarioAsignacionHorario->numero_paciente }}</b>
                                                            </p>
                                                            <p style="margin: 2px 0;">Turno:
                                                                <b>{{ $horario->horarioAsignacionHorario->turno }}</b>
                                                            </p>
                                                            <p style="margin: 2px 0;">Consultorio:
                                                                <b>{{ $horario->consultorioAsignacionHorario->numero_consultorio }}</b>
                                                            </p>
                                                            <p style="margin: 2px 0;">Ubicación:
                                                                <b>{{ $horario->consultorioAsignacionHorario->consultorioSedes->piso }}</b>,
                                                                <b>{{ $horario->consultorioAsignacionHorario->consultorioSedes->ubicacion }}</b>
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p
                                                        style="color: #1167c9ff; font-weight: bold; text-align: center; margin: 2px 0;">
                                                        SIN HORARIO ASIGNADO</p>
                                                @endif
                                            </td>
                                        @endforeach
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


<script>
    function guardarAsignacion() {
        Swal.fire({
            title: '¿Asignar Servicio?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrarServicio');

            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

            }
        });
    }

    function eliminarAsignacion(id) {
        console.log(id);
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminara permanentemente la asignacion",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Emitir el evento para eliminar la asignación
                Livewire.dispatch('eliminarAsignacion', {
                    id
                });
            }
        });
    }

    document.addEventListener('existeOtroEspecialistaConMismoHorarioMismoConsultorio', function() {
        Swal.fire({
            title: "Existe otro especialista asignado al mismo consultorio en el mismo horario",
            text: "Se encontró a otro especialista registrado para la atención en el mismo consultorio y en el mismo horario que seleccionó. Verifique los datos e intente nuevamente",
            icon: "warning",
        });
    });

    // REgistro exitoso de asignacion de especialidades con servicios
    document.addEventListener('registroExitoso', function() {
        Swal.fire({
            title: "Registro de servicio exitoso!",
            text: "Se guardó el registro",
            icon: "success",
            timer: 10000,
        });
    });

    document.addEventListener('eliminacionExitosa', function() {
        Swal.fire({
            title: "Eliminado!",
            text: "La asignación ha sido eliminada!",
            icon: "success",
            timer: 10000,
        });
    });
    // SErvicio Existe
    document.addEventListener('servicioExiste', function() {
        Swal.fire({
            title: "Ya existe este servicio!",
            text: "Registra otro servicio",
            icon: "error",
            timer: 50000,
        });
    });

    document.addEventListener('mensajeError', function() {
        Swal.fire({
            title: "Error",
            text: "Ocurrio un error",
            icon: "error",
            timer: 50000,
        });
    });

    //alertaAgendasExistentes
    document.addEventListener('alertaAgendasEnHorario', function(event) {
        const datosAgendas = event.detail.agendasAunExistentes;
        const listaAgendas = Array.isArray(datosAgendas) ? datosAgendas : [datosAgendas];
        if (listaAgendas.length === 0) return;
        const makeTableBody = (agendas) => {
            return agendas.map(agenda => {

                let fechaFormateada = agenda.fecha_agenda ?? '';
                if (fechaFormateada && fechaFormateada.includes('-')) {
                    const [year, month, day] = fechaFormateada.split('-');
                    fechaFormateada = `${day}/${month}/${year}`;
                }

                let horaFormateada = agenda.hora_agenda ?? '';
                if (horaFormateada && horaFormateada.includes(':')) {
                    const partesHora = horaFormateada.split(':');
                    horaFormateada =
                        `${partesHora[0]}:${partesHora[1]}`;
                }

                let nombreFormateado = agenda.persona_agenda ?? 'SUSPENSION DE HORARIO';
                if (agenda.persona_agenda) {
                    const p = agenda.persona_agenda;
                    nombreFormateado =
                        `${p.nombres ?? ''} ${p.p_apellido ?? ''} ${p.s_apellido ?? ''}`.trim();
                }

                let matriculaFormateadita = agenda.persona_agenda ?? '-';
                if (agenda.persona_agenda) {
                    matriculaFormateadita = agenda.persona_agenda.matricula_seguro;
                }

                return `
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: center;">${fechaFormateada}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: center;">${horaFormateada}</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: center;"><strong>${nombreFormateado}</strong><br></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: center;"><strong>${matriculaFormateadita}</strong><br></td>
                </tr>
            `;
            }).join('');
        };

        const tableHtml = `
        <p style="margin-bottom: 15px; text-align: left;">Las siguientes agendas ya se encuentran registradas para esta asignación:</p>
        <div style="overflow-x: auto; max-height: 300px; border: 1px solid #eee; border-radius: 4px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; font-family: sans-serif;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 10px; text-align: center;">Fecha</th>
                        <th style="padding: 10px; text-align: center;">Hora</th>
                        <th style="padding: 10px; text-align: center;">Asegurado</th>
                        <th style="padding: 10px; text-align: center;">Matrícula</th>
                    </tr>
                </thead>
                <tbody>
                    ${makeTableBody(listaAgendas)}
                </tbody>
            </table>
        </div>
    `;

        Swal.fire({
            title: "¡Existen agendas registradas en este horario!",
            html: tableHtml,
            icon: "warning",
            customClass: {
                popup: 'swal-wide'
            },
            width: '650px',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: "Entendido",
            confirmButtonColor: "#3085d6"
        });
    });

    document.addEventListener('alertaAsignacionActiva', function() {
        Swal.fire({
            title: "Existe un horario similar activo",
            text: "Existe otro horario actualmente vigente para el especialista seleccionado. Verifique los datos e intente nuevamente",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });
    
</script>
