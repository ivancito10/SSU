<div>
    <x-ver-dos-modal id="verAgendaCompleta" labelledby="verAgendaCompletaLabel" title="LISTADO HORARIO">
        <div class="row mb-2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-1">
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <b>Consultorio:</b> <em> {{$consultorio}} </em>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <b>Ubicación:</b> <em>{{$ubicacion}} </em>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <b>Dirección:</b> <em> {{$direccion}}</em>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="background-color:#226687">
                                        <th scope="col" style="text-align: center; vertical-align: middle; color:white;">NRO</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle;  color:white;">HORA</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle;  color:white;">MATRICULA</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle; color:white;">ASEGURADO</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle; color:white;">INSTITUCION</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle; color:white;">TIPO ASEGURADO</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle; color:white;">ESTADO</th>
                                        <th scope="col" style="text-align: center; vertical-align: middle; color:white;">OBSERVACION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($horasDisponibles as $key => $hora)
                                        <tr>
                                            <th scope="row" style="text-align: center; vertical-align: middle;">{{ $key + 1 }}</th>
                                            <td style="text-align: center; vertical-align: middle;">
                                                @if($hora['estado'] == 'LIBRE' )
                                                <span class="badge badge-success" style="font-size: 15px;">{{ $hora['hora'] }}</span>
                                                @else
                                                <span class="badge badge-danger" style="font-size: 15px;">{{ $hora['hora'] }}</span>
                                                @endif
                                                
                                            </td>
                                            <td style="text-align: center; vertical-align: middle;">{{ $hora['matricula'] }}</td>
                                            <td style="text-align: left; vertical-align: middle;">{{ $hora['asegurado'] }}</td>
                                            <td style="text-align: left; vertical-align: middle;">{{ $hora['institucion'] }}</td>
                                            <td style="text-align: center; vertical-align: middle;">{{ $hora['tipo_asegurado'] }}</td>
                                            <td style="color: {{ $hora['estado'] == 'LIBRE' ? 'green' : 'red' }};text-align: center; vertical-align: middle;">
                                                <em>{{ $hora['estado'] }} {{ $hora['motivo_suspension'] }} </em>
                                            </td>
                                            <td>{{ $hora['observacion'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ver-modal>
</div>

