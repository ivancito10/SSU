<div>
    <x-ver-modal id="agendados" labelledby="agendadosLabel" title="LISTA DE AGENDADOS PARA CONSULTA">
        {{--<div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if ($this->tiene_agendas)
                    <div style="overflow-y: auto; max-height: 400px; border: 1px solid #ccc; border-radius: 5px;">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>NRO</th>
                                    <th>ASEGURADO</th>
                                    <th>MATRICULA</th>
                                    <th>CARNET </th>
                                    <th>FECHA DE AGENDA</th>
                                    <th>HORA DE AGENDA</th>
                                    <th> TIPO DE FICHA </th>
                                </tr>
                            </thead>
                            <tbody style="text-align:left;">
                                @foreach ($this->agenda as $dato)
                                    <tr>
                                        <td>{{ $count = $count + 1 }}</td>
                                        <td>{{ $dato->personaAgenda->nombres . ' ' . $dato->personaAgenda->p_apellido . ' ' . $dato->personaAgenda->s_apellido }}
                                        </td>
                                        <td> {{ $dato->personaAgenda->matricula_seguro }} </td>
                                        <td> {{ $dato->personaAgenda->ci }} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($dato->fecha_agenda)->Format('d /m/ Y') ?? 'S/D' }}
                                        </td>
                                        <td>{{ $dato->hora_agenda }} </td>
                                        <td>{{ $dato->servicioPlataformaAgenda->nombre_servicio }} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="height: 50vh;">
                        <h2
                            style=" text-align: center;
                     
                    color: #c0392b; /* Un rojo más suave y accesible */
                    font-family: Arial, sans-serif;
                    font-size: 18px;
                    background-color: #f5c6cb;
                    padding: 20px;
                    border-radius: 5px;">
                            EL ESPECIALISTA NO CUENTA CON AGENDAS DURANTE ESAS FECHAS
                        </h2>
                    </div>
                @endif

            </div>
        </div>--}}
        @livewire('Plataforma.suspencion-vacacion.suspension-vacacion-ver-agenda')
    </x-ver-modal>
    <script>
        window.addEventListener("modalVerAgendados", (event) => {
            $("#agendados").modal("show");
            //$("#verVacacion").modal("hide");
        });
    </script>
</div>
