<div>
    <x-ver-modal id="verDetalleReceta" labelledby="verDetalleRecetaLabel" title="DETALLE DE LA RECETA">
        <div class="row">
            <!-- Cabecera -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card cabecera">
                    <b>DATOS DE LA RECETA</b>
                </div>
            </div>

            <!-- Nro. Receta -->
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-hash" viewBox="0 0 16 16">
                        <path
                            d="M8.39 12.648a1 1 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1 1 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.51.51 0 0 0-.523-.516.54.54 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532s.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531s.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                    </svg>
                    NRO. RECETA:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->nro_receta ?? 'N/A' }}</p>
            </div>

            <!-- Especialidad -->
            <div class="col-lg-5 col-md-6 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-hospital" viewBox="0 0 16 16">
                        <path
                            d="M8.5 5.034v1.1l.953-.55.5.867L9 7l.953.55-.5.866-.953-.55v1.1h-1v-1.1l-.953.55-.5-.866L7 7l-.953-.55.5-.866.953.55v-1.1h1ZM13.25 9a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5ZM13 11.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-.5Zm.25 1.75a.25.25 0 0 0-.25.25v.5c0 .138.112.25.25.25h.5a.25.25 0 0 0 .25-.25v-.5a.25.25 0 0 0-.25-.25h-.5Z" />
                        <path
                            d="M5 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 1 1v4h3a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h3V3a1 1 0 0 1 1-1V1Zm2 1h2V1H7v1Zm3 0h1V2h-1V2ZM6 2v1h1V2H6Zm0 2v3h4V3H6v1Zm7 4H3v7h10V8Zm-1 2v1h1v-1h-1Zm0 3v1h1v-1h-1Zm-9-3v1h1v-1H3Zm0 3v1h1v-1H3Z" />
                    </svg>
                    ESPECIALIDAD:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->especialidad ?? 'N/A' }}</p>
            </div>

            <!-- Tipo de atención -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                    </svg>
                    TIPO DE ATENCIÓN:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->tipo_consulta_receta ?? 'N/A' }}</p>
            </div>

            <!-- Especialista -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person-badge" viewBox="0 0 16 16">
                        <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path
                            d="M9.5 0a.5.5 0 0 1 .5.5V1h3a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h3V.5a.5.5 0 0 1 .5-.5zM3 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-3v1h1a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h1V2z" />
                    </svg>
                    ESPECIALISTA:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->especialista_full ?? 'N/A' }}</p>
            </div>

            <!-- Fecha -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-calendar-event" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>
                    FECHA:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->fecha ?? 'N/A' }}</p>
            </div>

            <!-- Fecha Transcripción -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-calendar-check" viewBox="0 0 16 16">
                        <path
                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                        <path
                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>
                    FECHA TRANSCRIPCIÓN:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->fecha_transcripcion ?? 'N/A' }}</p>
            </div>

            <!-- Hora -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-clock" viewBox="0 0 16 16">
                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    </svg>
                    HORA:
                </strong>
                <p class="p-comun-disabled p-2">{{ $receta->hora ?? 'N/A' }}</p>
            </div>
        </div>
        @if ($modalDetalleReceta)
            <div class="document-container p-2">
                @if ($fecha_proxima_consulta != null)
                    <div>
                        <b class="text-blue">FECHA PROXIMA CONSULTA:</b>
                        {{ $fecha_proxima_consulta ?? 'N/A' }}
                    </div>
                    <hr>
                @endif
                <div class="table-responsive">
                    <table class="tabla-ploma">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 45%;">Medicamento</th>
                                <th style="width: 15%;">Forma Farm.</th>
                                <th style="width: 15%;">Dosificación</th>
                                <th style="width: 15%;">Forma Adm.</th>
                                <th style="width: 5%;">Cant.</th>
                                {{-- <th style="width: 15%;">Incio de tratamiento</th> --}}
                                <th style="width: 15%;">Fin de tratamiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detalle_receta as $index => $item)
                                {{-- Fila Principal del Medicamento --}}
                                <tr class="bg-white">
                                    <td style="text-align:center;" rowspan="2">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $item->medicamento }}</strong>
                                        <br><small class="text-muted">{{ $item->concentracion }}</small>
                                    </td>
                                    <td>{{ $item->forma_famacetica }}</td>
                                    <td>{{ $item->indicaciones }}</td> {{-- Ella lo llama Dosificación en su tabla --}}
                                    <td>{{ $item->forma_administracion }}</td>
                                    <td style="text-align:center;">{{ $item->cantidad }}</td>
                                    {{-- <td style="text-align:center;">{{ $item->fecha_ini }}</td> --}}
                                    <td style="text-align:center; background-color: #e74900;color : white;">
                                        {{ $item->fecha_fin }}</td>
                                </tr>

                                {{-- Fila de Indicaciones y Recomendaciones con Colspan --}}
                                <tr class="bg-light">

                                    <td colspan="7">
                                        <div class="px-2">
                                            <strong>Indicaciones:</strong>
                                            <span class="text-dark">{{ $item->indicaciones }}</span>
                                            <br>
                                            <strong>Recomendación:</strong>
                                            <span class="text-muted italic">{{ $item->recomendacion }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay detalles para esta receta.</td>
                                </tr>
                            @endforelse
                        </tbody>

                        {{-- Pie de tabla para datos globales --}}
                        <tfoot>
                            <tr>
                                <td colspan="8">
                                    <b>FECHA ENTREGA:</b> {{ $fechaEntrega ?? 'N/A' }}
                                </td>
                                {{-- <td colspan="3" class="text-right text-danger">
                                    <b>RECETA VALIDA HASTA:</b> {{ $fechaCaducidad ?? 'N/A' }}
                                </td> --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </x-ver-modal>

    <style>
        /* Estilos para complacer al Front-End */
        .data-table-products {
            border: 1px solid #000 !important;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .data-table-products thead th {
            background-color: #f2f2f2;
            color: #000;
            text-align: center;
            border: 1px solid #000 !important;
            text-transform: uppercase;
        }

        .data-table-products td {
            border: 1px solid #ccc !important;
            vertical-align: top;
            padding: 5px !important;
        }

        .bg-light {
            background-color: #f9f9f9 !important;
        }

        .italic {
            font-style: italic;
        }
    </style>
</div>
{{-- <x-ver-modal id="verDetalleReceta" labelledby="verDetalleRecetaLabel" title=" DETALLE DE LA RECETA ">
    @if ($modalDetalleReceta)
        <div class="container-fluid py-2" wire:key="modal-receta-{{ count($detalle_receta) }}-{{ now()->timestamp }}">
            @forelse($detalle_receta as $index => $item)
                @livewire('atencion-medica.mis-recetas.detalle-receta', 
                    ['detalle' => $item], 
                    key('item-receta-'.$index.'-'.now()->timestamp)
                )
            @empty
                <div class="alert alert-info text-center">
                    No hay detalles para esta receta.
                </div>
            @endforelse
        </div>
    @endif
</x-ver-modal> --}}

<script>
    window.addEventListener('modalDetalleReceta', event => {
        $('#verDetalleReceta').modal('show');
    });
</script>
