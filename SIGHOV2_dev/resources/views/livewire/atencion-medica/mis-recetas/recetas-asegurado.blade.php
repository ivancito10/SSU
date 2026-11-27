<div>
    <div class="row">
        <div class="col-lg-7 col-md-6 col-sm-12 col-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class=" card card-comun p-2 " style=" border: 1px solid #dee2e6; border-radius: 8px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#22c197"
                                        class="bi bi-calendar-week-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M9.5 7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m3 0h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M2 10.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                                    </svg>
                                    <b style="color:#1863db; font-size:20px">DETALLE DE TUS RECETAS MÉDICAS</b> </span>
                            </div>
                        </div>
                        <hr class="p-0 m-1">
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-8 col-8">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <span><b>NOMBRE: </b>{{ $nombreCompleto }} </span>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <span><b>MATRÍCULA: </b>{{ $matricula }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-4 col-4 text-right pt-3 pr-3">
                                <button type="button" class="btn btn-success" wire:click="consultarRecetas"
                                    style="background-color:#008a65">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                                        </svg>
                                        <b> RECETAS</b>
                                    </span>
                                </button>
                            </div>

                        </div>
                        <hr class="p-0 m-1">
                        {{-- <div style="max-height: 300px; overflow-y: auto;">
                            @if ($agendasTitular->isNotEmpty())
                                <table class="table">
                                    <thead style="background-color:#f7f8ff; position: sticky; top: 0; z-index: 2; ">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">ESPECIALIDAD</th>
                                            <th scope="col">MÉDICO</th>
                                            <th scope="col">FECHA</th>
                                            <th scope="col">HORA</th>
                                            <th scope="col">TIPO DE FICHA</th>
                                            <th scope="col">ESTADO</th>
                                            <th scope="col">ANULAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @foreach ($agendasTitular as $key => $item)
                                            <tr style="font-size: 0.8rem"></tr>
                                            <th scope="row">{{ $key + 1 }} </th>
                                            <td>{{ $item['especialidad'] }} </td>
                                            <td>{{ $item['especialista'] }} </td>
                                            <td> {{ \Carbon\Carbon::parse($item['fecha'])->format('d/m/Y') }} </td>
                                            <td>{{ $item['hora'] }} </td>
                                            <td>{{ $item['tipo'] }} </td>
                                            <td>
                                                @if ($item['esSuspension'])
                                                    <p class="text-orange"><b>SUSPENSIÓN</b>
                                                    </p>
                                                @else
                                                    @if ($item['estado'] === 'ANULADO')
                                                        <p class="text-red"><b>ANULADO</b></p>
                                                    @else
                                                        <p class="text-green"><b>AGENDADO</b></p>
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">
                                                @php
                                                    $fechaHoraAgenda = \Carbon\Carbon::parse(
                                                        $item['fecha'] . ' ' . $item['hora'],
                                                    );
                                                    $horaActual = \Carbon\Carbon::now();
                                                    $minutosRestantes = $horaActual->diffInMinutes(
                                                        $fechaHoraAgenda,
                                                        false,
                                                    );
                                                @endphp
                                                @if ($item['esSuspension'])
                                                    <x-eliminar-button title="ANULAR AGENDA" disabled>
                                                    </x-eliminar-button>
                                                @else
                                                    @if ($item['estado'] === 'AGENDADO' && $item['fecha'] > \Carbon\Carbon::now()->format('Y-m-d'))
                                                        <x-eliminar-button title="ANULAR AGENDA"
                                                            wire:click="$dispatch('confirmar-anulacion', { id: '{{ $item['id_agenda'] }}' })">
                                                        </x-eliminar-button>
                                                    @else
                                                        <x-eliminar-button title="ANULAR AGENDA" disabled>
                                                        </x-eliminar-button>
                                                    @endif
                                                @endif
                                            </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                                        class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                                        <path transform="rotate(180, 8, 8)"
                                            d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                                    </svg>
                                    <b> AÚN NO TIENES CITAS MÉDICAS </b>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fab32d"
                                        class="bi bi-exclamation-lg" viewBox="0 0 16 16">
                                        <path
                                            d="M7.005 3.1a1 1 0 1 1 1.99 0l-.388 6.35a.61.61 0 0 1-1.214 0zM7 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                                    </svg>
                                </div>

                            @endif
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
       
        {{-- @include('livewire.Administracion.dashboard.plataforma.modalDetalleAgenda') --}}

    </div>
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

</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('mostrarSweetAlert', (event) => {
            // Livewire v3 envía los parámetros dentro de un array en event[0]
            const data = event[0];

            Swal.fire({
                title: data.titulo,
                text: data.mensaje,
                icon: data.tipo, // 'error', 'warning', 'info', etc.
                // showConfirmButton: false,
                // timer: 1500
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6',
            });
        });
    });
</script>
