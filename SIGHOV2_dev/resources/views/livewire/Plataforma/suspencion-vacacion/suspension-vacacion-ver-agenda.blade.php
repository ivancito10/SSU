<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if ($this->tiene_agendas)
                <div class="scroll-container-agendados">
                    <table class="tabla-celeste" width="100%">
                        <thead>
                            <tr>
                                <th>NRO</th>
                                <th>ASEGURADO</th>
                                <th>MATRICULA</th>
                                <th>CARNET </th>
                                <th>FECHA DE AGENDA</th>
                                <th>HORA DE AGENDA</th>
                                <th>TIPO DE FICHA</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($this->agenda as $key => $dato)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    @if ($dato->id_suspension_horario === null)
                                        <td>{{ $dato->personaAgenda->nombres . ' ' . $dato->personaAgenda->p_apellido . ' ' . $dato->personaAgenda->s_apellido }}
                                        </td>
                                        <td> {{ $dato->personaAgenda->matricula_seguro }} </td>
                                        <td> {{ $dato->personaAgenda->ci }} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($dato->fecha_agenda)->Format('d /m/ Y') ?? 'S/D' }}
                                        </td>
                                        <td>{{ $dato->hora_agenda }} </td>
                                        <td>{{ $dato->servicioPlataformaAgenda->nombre_servicio }} - AGENDA </td>
                                    @else
                                        @if ($dato->id_persona)
                                            <td>{{ $dato->personaAgenda->nombres . ' ' . $dato->personaAgenda->p_apellido . ' ' . $dato->personaAgenda->s_apellido }}
                                            </td>
                                            <td> {{ $dato->personaAgenda->matricula_seguro }} </td>
                                            <td> {{ $dato->personaAgenda->ci }} </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($dato->fecha_agenda)->Format('d /m/ Y') ?? 'S/D' }}
                                            </td>
                                            <td>{{ $dato->hora_agenda }} </td>
                                            <td>{{ $dato->servicioPlataformaAgenda->nombre_servicio }} - SUSPENSIÓN
                                                POR:
                                                {{ $dato->suspensionHorarioAgenda->motivoSuspensionHorario->nombre_motivo }}
                                            </td>
                                        @else
                                            <td>SUSPENSIÓN POR:
                                                {{ $dato->suspensionHorarioAgenda->motivoSuspensionHorario->nombre_motivo }}
                                            </td>
                                            <td> - </td>
                                            <td> - </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($dato->fecha_agenda)->Format('d /m/ Y') ?? 'S/D' }}
                                            </td>
                                            <td>{{ $dato->hora_agenda }} </td>
                                            <td>{{ $dato->servicioPlataformaAgenda->nombre_servicio }} - SUSPENSIÓN
                                            </td>
                                        @endif
                                    @endif


                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @else
                <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
                    <div class="text-center border border-danger rounded p-4"
                        style="max-width: 600px; background-color:#F5D5C9;">
                        <h2 class="text-danger mb-0" style="font-family: Arial, sans-serif; font-size: 20px;">
                            EL ESPECIALISTA NO CUENTA CON AGENDADOS ENTRE LAS FECHAS: {{ $fechaInicio }} y {{$fechaFin}}
                        </h2>
                    </div>
                </div>

            @endif

        </div>
    </div>
    <style>
        .scroll-container-agendados {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</div>
