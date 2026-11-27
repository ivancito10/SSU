<div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');

        .digital-clock-style {
            font-family: 'Orbitron', 'Courier New', Courier, monospace;
            font-size: 2.5em;
            color: #1a5276;
            letter-spacing: 0.15em;
            text-shadow: 0 0 6px #1a5276, 0 0 2px #fff;
            background: none;
            border: none;
            padding: 0;
        }
    </style>
    @can('datos.agenda_web')

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="row">
                    <div class="d-flex align-items-start">
                        <div class="col-auto" style="text-align:center;">
                            <img src="{{ !empty($foto_nombre) ? 'data:image/jpg;base64,' . $foto_nombre : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="img" style="width: 90px; height: 90px; object-fit: cover;">
                        </div>
                        <div class="col" style="font-size:15px;">
                            <div style="font-size:19px;">
                                <span><b style="color: #1a5276 ;"> {{ $matricula }} </b> </span>
                            </div>
                            <span><em><b>{{ $nombre_completo }} </b> </em> </span><br>
                            <span><b> {{ $tipo_asegurado }} </b> </span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12" wire:ignore>
                        <div id="digital-clock"
                            style="display:flex;justify-content:center;align-items:center;height:100px;">
                            <span id="clock-time" class="digital-clock-style">00:00:00</span>
                        </div>
                    </div>
                    {{-- <span>{{$paso}}</span> --}}
                </div>
            </div>
            @if ($switchDentroDelRango || Auth::user()->hasPermissionTo('super-admin-only'))
                <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-2">

                    <div class="card-borde-degrade p-2">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card cabecera">
                                    <b> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                            <path
                                                d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z" />
                                            <path
                                                d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z" />
                                        </svg>
                                        DETALLES DE CITA MÉDICA </b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p class="p-1 mb-0">
                                    <span>
                                        <b>FECHA: </b>
                                        @if ($fechaElegida)
                                            {{ \Carbon\Carbon::parse($fechaElegida)->format('d/m/Y') }} - <b
                                                style="color: #1a5276 ;"> {{ $dia_semana }}</b>
                                        @else
                                            <em style="color: #285a93  ;">...</em>
                                        @endif
                                    </span><br>
                                    <span>
                                        <b>ESPECIALIDAD: </b>
                                        @if ($especialidadLiteral)
                                            <b style="color: #1a5276 ;"> {{ $especialidadLiteral }}</b>
                                        @else
                                            <em style="color: #285a93  ;"> ...</em>
                                        @endif
                                    </span><br>
                                    <span>
                                        <b>ESPECIALISTA: </b>
                                        @if ($especialistaLiteral)
                                            <b style="color: #1a5276 ;"> {{ $especialistaLiteral }}</b>
                                        @else
                                            <em style="color: #285a93  ;">...</em>
                                        @endif
                                    </span>
                                </p>
                            </div>

                        </div>
                    </div>

                </div>
            @endif
            <br>
        </div>

        @if ($switchDentroDelRango || Auth::user()->hasPermissionTo('super-admin-only'))

            @if (!$pasoAEspecialidadesSwitch)
                @if ($switchEsTitular)
                    @if ($switchTieneGrupoFamiliar)

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="cabecera-borde-azul justify-content-center">
                                    <b>AFILIADOS - GRUPO FAMILIAR</b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                <p style="color:#2471a3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Seleccione a un miembro de su familia:</b> </em>
                                </p>
                            </div>
                        </div>

                        @if ($switchMostrarBotonGrupo)
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align: center;">
                                    <x-boton-azul wire:click="seleccionarGrupoFamiliar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                            fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                        </svg>
                                        <b> ELEGIR FAMILIAR </b></x-boton-azul>
                                </div>
                            </div>
                        @endif
                        @if ($switchSeleccionoOtroFamiliar)
                            <div class="row">
                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" style="text-align: center;">
                                    <select wire:model="idPersonaGrupoFamiliar"
                                        wire:change="obtenerDatosOtroPersonaGrupoFamiliar" class="form-control"
                                        tabindex="-1" required>
                                        <option selected value="">Seleccione otro familiar... </option>
                                        @foreach ($personasGrupoFamiliar as $personaGrupoFamiliar)
                                            <option value="{{ $personaGrupoFamiliar['id_persona'] }}">
                                                {{ implode(
                                                    ' ',
                                                    array_filter([
                                                        $personaGrupoFamiliar['p_apellido'],
                                                        $personaGrupoFamiliar['s_apellido'],
                                                        $personaGrupoFamiliar['nombres'],
                                                    ]),
                                                ) }}
                                                -
                                                {{ $personaGrupoFamiliar['matricula_seguro'] }}

                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <br>
                    @endif
                @endif
            @endif
            <div class="mb-0">
                @if ($paso == 1)
                    @include('livewire.Plataforma.agendamiento.web.fechas')
                    @include('livewire.Plataforma.agendamiento.web.ModalDatosAgenda')
                    @include('livewire.Plataforma.agendamiento.web.modalDatosAgendaBeneficiario')
                @endif
                @if ($paso == 2)
                    @include('livewire.Plataforma.agendamiento.web.especialidades')
                @endif
                @if ($paso == 3)
                    @include('livewire.Plataforma.agendamiento.web.especialistas')
                @endif
                @if ($paso == 4)
                    @include('livewire.Plataforma.agendamiento.web.horario')
                @endif
            </div>

            <div id="cargando-anulacion-web" style="display: none;">
                <x-cargando-dos />
            </div>

            <div id="cargando-guardar-agenda" style="display: none;">
                <x-cargando-dos />
            </div>


        @endif
        <div wire:loading wire:target="obtenerDatosOtroPersonaGrupoFamiliar">
            <x-cargando-dos />
        </div>

    @endcan
</div>
<script>
    document.addEventListener('agendaYaExiste', function() {
        Swal.fire({
            title: " Advertencia ",
            text: "No se pudo registrar, existen agendados en el horario Asignado!!!",
            icon: "warning"

        });
    });

    document.addEventListener('familiarSeleccionado', function(event) {
        const nombreFamiliar = event.detail.nombreFamiliar;
        Swal.fire({
            title: `Se seleccionó correctamente a: <b>${nombreFamiliar}</b>`,
            text: "Se agendrá un cita médica para el familiar seleccionado",
            icon: "success"
        });
    });




    document.addEventListener('errorRegistro', function() {
        Swal.fire({
            title: " Ocurrió un error",
            text: "No se realizó el agendamiento, por favor intente nuevamente",
            icon: "warning"

        });
    });

    document.addEventListener('registroExistoso', function() {

        Swal.fire({
            title: " Agenda registrada exitosamente ",
            html: "Se registró la agenda correctamente.<br>Puede ver mas detalles en la fecha.",
            icon: "success"
        });
    });

    document.addEventListener('agendaExistente', function() {
        Swal.fire({
            title: " Advertencia ",
            text: "Ya existe una agenda registrada para esta fecha y hora",
            icon: "warning"
        });
    });

    document.addEventListener('preguntarAnularAgenda', function() {
        const motivo = "";
        Swal.fire({
            title: `¿Anular la agenda?`,
            icon: 'question',
            text: ' Describa los motivos para anular su agenda:',
            input: "text",
            showCancelButton: true,
            confirmButtonText: "SI, ANULAR",
            cancelButtonText: "CANCELAR",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca el motivo por favor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                const motivo = result.value;
                Livewire.dispatch('anularAgendaExistente', [motivo]);
                document.getElementById('cargando-anulacion-web').style.display = 'block';
            } else {
                $('#VerAgendaAsegurado').modal('hide');
                $('#VerAgendaAseguradoBeneficiario').modal('hide');
            }
        });
    });

    document.addEventListener('anulacionExitosa', function(event) {
        const mensaje = event.detail;
        $('#VerAgendaAsegurado').modal('hide');
        $('#VerAgendaAseguradoBeneficiario').modal('hide');

        Swal.fire({
            title: `Ficha anulada \n ${mensaje.detail}`,
            icon: "success"
        });
    });
    document.addEventListener('limiteAnulaciones', function(event) {
        const mensaje = event.detail;
        $('#VerAgendaAsegurado').modal('hide');
        $('#VerAgendaAseguradoBeneficiario').modal('hide');
        Swal.fire({
            title: `${mensaje.detail}`,
            icon: "warning"
        });
    });

    window.addEventListener('preguntarGuardarAgenda', event => {
        //alert('entra aqui');
        const hora_mostrar = event.detail.hora;
        Swal.fire({
            title: "¿Agendar cita a Hrs. " + hora_mostrar + "?",
            text: `Se agendará una cita con los datos especificados`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, agendar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                Livewire.dispatch('agendaWebConfirmada');
                document.getElementById('cargando-guardar-agenda').style.display = 'block';

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el agendamiento",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('existeAgendadoGrupoFamiliar', function() {
        Swal.fire({
            title: "Familiar Agendado!",
            text: "Un miembro de su grupo familiar agendó una cita para la fecha seleccionada.",
            icon: "warning"
        });
    });

    document.addEventListener('fechaFeriado', function() {
        Swal.fire({
            title: "<b>Feriado!</b>",
            text: "La fecha seleccionada es un día feriado, no puede ser agendada",
            icon: "warning"
        });
    });

    document.addEventListener('noHayEspecialistasDisponibles', function() {

        Swal.fire({
            title: "<b>Sin especialistas disponibles!</b>",
            text: "No hay especialistas disponibles para la fecha seleccionada",
            icon: "warning"
        });
    });

    document.addEventListener('sinFamiliarSeleccionado', function() {

        Swal.fire({
            title: "<b>Seleccione a un familiar</b>",
            text: "Pulsó el botón ELEGIR FAMILIAR, debe seleccionar a un familiar de la lista desplegable o seleccionarse a si mismo para proceder",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('vuelvaAIntentarlo', function() {

        Swal.fire({
            title: "<b>Vuelva a intentarlo</b>",
            text: "Seleccione nuvamente el horario, por favor",
            icon: "info",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('noHayHorasDisponibles', function() {
        Swal.fire({
            title: "<b>Sin horarios disponibles!</b>",
            text: "No hay horarios disponibles para el especialista seleccionado",
            icon: "warning"
        });
    });

    document.addEventListener('horaYaPasada', function() {
        Swal.fire({
            title: "<b>Hora seleccionada fuera de rango!</b>",
            text: "La hora seleccionada se encuentra fuera del rango",
            icon: "warning"
        });
    });

    document.addEventListener('agendoElMismo', function() {
        //alert('entra');
        Swal.fire({
            title: "Agenda existente!",
            text: "Usted ya tiene una agenda vigente registrada para la fecha seleccionada.",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Ver Agenda'
        }).then((result) => {
            $('#VerAgendaAsegurado').modal('show');
        });
    });

    document.addEventListener('agendoElMismoBeneficiario', function() {
        //alert('entra');
        Swal.fire({
            title: "Agenda existente!",
            text: "Usted ya tiene una agenda vigente registrada para la fecha seleccionada.",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Ver Agenda'
        }).then((result) => {
            $('#VerAgendaAseguradoBeneficiario').modal('show');
        });
    });

    document.addEventListener('errorRegistroAnulacion', function() {
        Swal.fire({
            title: "<b>Ocurrió un error</b>",
            text: "No se realizó la anulación de la agenda. Verifique e inténtelo nuevamente",
            icon: "error"
        });
    });

    document.addEventListener('fueraDeRango', function(event) {
        const horasDisponibles = event.detail.horasDisponibles;
        const horariosHtml = `<div style="text-align:center">${horasDisponibles.join('<br>')}</div>`;
        const relojHtml = `<span id="swal-clock" class="digital-clock-style">00:00:00</span>`;

        Swal.fire({
            title: "<b>El horario aún no se encuentra habilitado</b>",
            html: `Horarios habilitados para el agendamiento: <br><b>${horariosHtml}<br></b> <b>Hora actual:</b><br>${relojHtml}`,
            icon: "warning",
            confirmButtonText: "Intentar nuevamente",
            confirmButtonColor: "#008f39",
            showCancelButton: true,
            cancelButtonText: "Volver al inicio",
            cancelButtonColor: "#0f2f76",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                function updateSwalClock() {
                    const now = new Date();
                    const h = String(now.getHours()).padStart(2, '0');
                    const m = String(now.getMinutes()).padStart(2, '0');
                    const s = String(now.getSeconds()).padStart(2, '0');
                    const clock = document.getElementById('swal-clock');
                    if (clock) {
                        clock.textContent = `${h}:${m}:${s}`;
                    }
                }
                updateSwalClock();
                window.swalClockInterval = setInterval(updateSwalClock, 1000);
            },
            willClose: () => {
                clearInterval(window.swalClockInterval);
            }

        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('porcentajeHorario');
            } else {
                Livewire.dispatch('volverInicio');
            }
        });
    });

    function updateDigitalClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock-time').textContent = `${h}:${m}:${s}`;
    }
    setInterval(updateDigitalClock, 1000);
    updateDigitalClock();

    document.addEventListener('agendaNoDisponible', function() {
        Swal.fire({
            title: "<b>Agenda fuera del rango seleccionable</b>",
            text: "La hora seleccionada no se encuentra habilitada para su seleccion, espere las horas indicadas de agendamiento",
            icon: "warning"
        });
    });

    document.addEventListener('carnetCaduco', function(event) {
        const fechaVencimiento = event.detail.fechaVencimiento;
        Swal.fire({
            title: "<b>Su carnet de asegurado caducó</b>",
            html: `Su carnet de asegurado venció en fecha: <b>${fechaVencimiento}</b>. <br> Pase por la Unidad de Afiliación del Seguro Universitario para la regularización el proceso.`,
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    });

    document.addEventListener('agendamientoNoDisponible', function() {
        Swal.fire({
            title: "<b>Agenda fuera del rango seleccionable</b>",
            text: "La hora seleccionada no se encuentra habilitada para su seleccion, espere las horas indicadas de agendamiento",
            icon: "warning"
        });
    });

    document.addEventListener('confirmarDemandaInsatisfecha', function() {
        Swal.fire({
            title: `¿Registrar demanda insatisfecha?`,
            icon: 'question',
            text: 'Se registrará su observación',
            showCancelButton: true,
            confirmButtonText: "Si, registrar",
            cancelButtonText: "No, cancelar",
            confirmButtonColor: "#008000",
            cancelButtonColor: "#FF0000",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrarDemandaInsatisfecha');
            }
        });
    });

    document.addEventListener('demandaRegistradaCorrectamente', function() {
        Swal.fire({
            title: "Guardado",
            text: "Su observación se registró correctamente",
            icon: "success"
        });
    });

    document.addEventListener('demandaNoRegistrada', function() {
        Swal.fire({
            title: "Error en el registro",
            text: "Ocurrió un error. Por favor, comuniquese con la unidad de Sistemas",
            icon: "error"
        });
    });

    document.addEventListener('yaRegistroDemandaHoy', function() {
        Swal.fire({
            title: "Ya registró una demanda el día de hoy",
            text: "Se encontró un registro realizado el día de hoy para la misma especialidad, el mismo especialista y el mismo turno. No puede realizar esta acción más de una vez al día.",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('noHayDatosPersona', function() {
        Swal.fire({
            title: '<b>No existen datos de afiliación</b>',
            icon: 'warning',
            text: 'Sus datos no fueron encontrados en el sistema. Intente actualizar la página. Si esto no da resultado, comuniquese con la Unidad de Sistemas',
            confirmButtonText: "Volver al inicio",
            confirmButtonColor: "#003166",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('volverInicio');
            }
        });
    });

    document.addEventListener('fechaInexistenteOFueraDeLasFechasDisponibles', function() {
        Swal.fire({
            title: "<b>La fecha se encuentra fuera del rango de fechas disponibles</b>",
            text: "La fecha que seleccionó se encuentra fuera del rango de fechas disponibles. Comuniquese con la Unidad de Sistemas para verificar el error",
            icon: "warning",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    window.addEventListener('mostrarAgendaRegistrada', function(event) {
        const data = event.detail[0]; //  aquí vienen las variables del dispatch
        console.log(data);
        const toastBody = `
            <strong>Agenda Confirmada</strong><br>
            Fecha: ${data.fecha}<br>
            Hora: ${data.hora}<br>
            Especialista: ${data.especialista}<br>
            Consultorio: ${data.consultorio}<br>
            Ubicación: ${data.ubicacion}
        `;

        // Toast de AdminLTE
        $(document).Toasts('create', {
            class: 'bg-success',
            title: 'Agenda Registrada',
            body: toastBody,
            autohide: true,
            delay: 8000
        });

        // SweetAlert
        Swal.fire({
            title: "Agenda registrada exitosamente!",
            text: "La agenda se registró correctamente",
            icon: "success",
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Ver Agenda'
        }).then((result) => {
            $('#VerAgendaAsegurado').modal('show');
        });
    });

    /*document.addEventListener('mostrarAgendaRegistradaBeneficiario', function() {
        Swal.fire({
            title: "Agenda registrada exitosamente!",
            text: "La agenda se registro correctamente",
            icon: "success",
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'Ver Agenda'
        }).then((result) => {
            $('#VerAgendaAseguradoBeneficiario').modal('show');
        });
    });*/
</script>
