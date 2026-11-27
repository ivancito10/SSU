<div>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Título de la Página</title>

        <style>
            /* Cambiar el color de las letras a negro */
            .fc .fc-col-header-cell-cushion,
            .fc .fc-daygrid-day-number,
            .fc .fc-daygrid-day-events,
            .fc .fc-daygrid-day-frame {
                color: #000;
            }

            /* Cambiar el tamaño de la letra en el título del calendario */
            .fc .fc-toolbar-title {
                font-size: 30px;
                font-weight: bold;
                color: #2471a3;
                text-transform: uppercase;
                background-color: #e5f0f9;
                padding: 2px;
                border-radius: 8px;
            }

            .fc .fc-col-header-cell-cushion {
                font-size: 20px;
                color: #000;
                text-transform: uppercase;
            }

            /* Cambiar el color de las líneas del calendario a negro */
            .fc .fc-daygrid-day-frame,
            .fc .fc-daygrid-day-top,
            .fc .fc-col-header-cell,
            .fc .fc-scrollgrid-sync-inner {
                border-color: #143D80;
            }

            .fc .fc-button.fc-today-button {
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 6px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .fc .fc-day-today {
                background-color: #baebb1ff !important;
                border: 2px solid #7fcfa0ff;
            }

            .fc .fc-daygrid-day:hover {
                background-color: #f2f2f2;
                cursor: pointer;
            }
        </style>


        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                let fechasConAgendas = @json($agenda_por_fecha);

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',

                    buttonText: {
                        today: 'Hoy',
                    },

                    customButtons: {
                        prev: {
                            text: 'MES ANTERIOR',
                            click: function() {
                                calendar.prev();
                            },
                        },
                        next: {
                            text: 'MES SIGUIENTE',
                            click: function() {
                                calendar.next();
                            },
                        },
                    },


                    dayCellDidMount: function(info) {
                        let fecha = info.date.toISOString().split('T')[0]; // YYYY-MM-DD

                        if (fechasConAgendas.includes(fecha)) {
                            let icono = document.createElement('span');
                            icono.innerHTML = `
                       <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2471a3" class="bi bi-calendar-plus-fill" viewBox="0 0 16 16">
                          <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M8.5 8.5V10H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V11H6a.5.5 0 0 1 0-1h1.5V8.5a.5.5 0 0 1 1 0"/>
                        </svg>`;
                            info.el.style.position = 'relative';
                            icono.style.position = 'absolute';
                            icono.style.bottom = '4px';
                            icono.style.right = '4px';
                            info.el.appendChild(icono);
                        }
                    },

                    dateClick: function(fecha) {
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('fechaSeleccionada', [fecha.dateStr]);
                        }

                        Livewire.on('openModal', () => {
                            $('#agenda').modal('show');
                            setTimeout(() => {
                                calendar.render();
                            }, 100);
                        });

                        Livewire.on('renderizar', () => {
                            setTimeout(() => {
                                calendar.render();
                            }, 100);
                        });
                    }
                });

                calendar.render();
            });
        </script>
    </head>

    <body>

        <div
            style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
            <div style="display: flex; align-items: center;">

                <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">AGENDA DEL
                    ESPECIALISTA</h6>
            </div>
            <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
        </div>
        <br>

        <div class="row">
            <!-- Aqui los datos del especialista  -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="card card-comun p-2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:center;">
                            <!-- FOTOGRAFIA -->
                            <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="ESPECIALISTA"
                                style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12"
                            style="display:flex; justify-content: center; font-size: 22px">
                            <br>
                            @if ($permanente)
                                <span style="color: #2874A6;"><b>PERMANENTE</b></span>
                            @else($permanente == false)
                                <span style="color: #DC7633;"><b>EVENTUAL</b></span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-8 col-sm-8 col-12">
                            <strong>NOMBRE COMPLETO:</strong>
                            <p class="p-comun-disabled p-1 ">
                                {{ $nombres }}</p>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-4 col-6">
                            <strong>CARNET:</strong>
                            <p class="p-comun-disabled p-1 ">
                                {{ $carnet }} {{ $expedido }} </p>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-4 col-6">
                            <strong>MATRICULA:</strong>
                            <p class="p-comun-disabled p-1 ">
                                {{ $matricula }} </p>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-4 col-6">
                            <strong>FECHA NACIMIENTO:</strong>
                            <p class="p-comun-disabled p-1 ">
                                {{ \Carbon\Carbon::parse($fecha_nacimiento)->Format('d /m/ Y') }}</p>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-4 col-6">
                            <strong>GRADO ACADÉMICO:</strong>
                            <p class="p-comun-disabled p-1 ">
                                {{ $grado }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <strong>INICIO CONTRATO:</strong>
                            <p class="p-comun-disabled p-1 ">
                                {{ $fecha_inicio }}</p>
                        </div>
                        @if (!$permanente)
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <strong>FIN CONTRATO :</strong>
                                <p class="p-comun-disabled p-1 ">
                                    {{ $fecha_fin }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Aquí el calendario -->
            <div class="col-lg-8 col-md-12 col-sm-12 col-12 mt-3 mt-lg-0" wire:ignore>
                <div class="card card-comun p-2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-right">
                            <strong>ESPECIALIDAD:</strong>
                            <span style="color:#2c3e50;" class="h3">
                                <b>{{ $doc_especialidad }} </b>
                            </span>
                        </div>
                    </div>
                    <hr class="p-0 m-0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12"
                            style="display: flex; justify-content: center; align-items: center; height: 100%;">
                            <div id="calendar" style="height: 50vh; width: 100%; text-align:center;"></div>
                        </div>
                    </div>

                </div>
            </div>


            <div wire:loading>
                <x-cargando-dos />
            </div>
        </div>

        <!-- Abre el modal de horario -->
        @include('livewire.Plataforma.agenda-especialista.agendaEspecialista')
    </body>

    </html>
</div>


<script>
    // esto es por si se repite 
    document.addEventListener('sinRegistro', function() {
        Swal.fire({
            title: "SIN AGENDAS",
            text: "No tiene registros de agenda para esta fecha.",
            icon: "warning",
            timer: 10000,
        });
    });

    document.addEventListener('noExistenAgendasNoAnuladas', function() {
        Swal.fire({
            title: "SIN AGENDAS VALIDAS",
            text: "El especialista no tiene registros de agenda vigentes para esta fecha.",
            icon: "warning",
            timer: 10000,
        });
    });
</script>
