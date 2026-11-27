<div>
    <x-ver-dos-modal id="modalAsignacionHorario" labelledby="modalAsignacionHorarioLabel"
        title="VER ASIGNACIÓN DE HORARIO">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail" alt="ESPECIALISTA"
                                    style="width: 200px; height: 200px; object-fit: cover;">

                                <br>
                                @if ($permanente)
                                    <span style='color:#2874A6 ;' class="h4"><b>PERMANENTE</b></span>
                                @else
                                    <span style='color:#DC7633 ;' class="h4"><b>EVENTUAL</b></span>
                                @endif

                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <p style="background-color:#1a5276; color:white;text-align:center;"> ESPECIALISTA</p>
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombres"> NOMBRE
                                            COMPLETO:</label>
                                        <input id="nombres" class="form-control" type="text" wire:model='nombres'
                                            disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:</label>
                                        <input id="fecha_nacimiento" class="form-control" wire:model='fecha_nacimiento'
                                            disabled style=" height: 30px;" type="date">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="carnet"> CARNET:</label>
                                        <input id="carnet" class="form-control" type="text" wire:model='carnet'
                                            disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <label for="expedido">EXPEDIDO.:</label>
                                        <input id="expedido" class="form-control" type="text" wire:model='expedido'
                                            disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="es_extranjero"> TIPO DOC.:</label>
                                        <input id="es_extranjero" class="form-control"
                                            value="{{ $es_extranjero ? 'EXTRANJERO' : 'NACIONAL' }}" disabled
                                            style="height: 30px;" type="text">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matricula">
                                            MATRÍCULA:</label>
                                        <input id="matricula" class="form-control" type="text" wire:model='matricula'
                                            disabled style=" height: 30px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matriculaAgen">
                                            ESPECIALIDAD:</label>
                                        <input id="matriculaAgen" class="form-control" type="text"
                                            wire:model='matricula' disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matriculaAgen">
                                            GRADO ACADÉMICO:</label>
                                        <input id="matriculaAgen" class="form-control" type="text"
                                            wire:model='matricula' disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label for="matriculaAgen">
                                            CONTRATO DESDE:</label>
                                        <input id="matriculaAgen" class="form-control" type="date"
                                            wire:model='fecha_inicio' disabled style=" height: 30px;">
                                    </div>
                                    @if (!$permanente)
                                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                            <label for="matriculaAgen">
                                                CONTRATO HASTA:</label>
                                            <input id="matriculaAgen" class="form-control" type="date"
                                                wire:model='fecha_fin' disabled style=" height: 30px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOSTRAMOS LA LISTA DE HORARIOS -->
        <div class="row mt-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:white ;">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead style="background:#2471a3; color:white; text-align:center;">
                                    <tr>
                                        <th scope="col">DÍA</th>
                                        <th scope="col">HORA INICIO</th>
                                        <th scope="col">HORA FIN</th>
                                        <th scope="col">FECHA INICIO</th>
                                        <th scope="col">FECHA FIN</th>
                                        <th scope="col">CONSULTORIO</th>
                                        <th scope="col">UBICACIÓN</th>
                                        <th scope="col">PACIENTES</th>
                                        <th scope="col">ESTADO</th>
                                        <th scope="col">OPCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($horarios as $asignacionHorario)
                                        <tr class="text-center">
                                            @if ($asignacionHorario->estado)
                                                <td>{{ $asignacionHorario->dia_nombre }}</td>
                                                <td>{{ $asignacionHorario->hora_inicio }}</td>
                                                <td>{{ $asignacionHorario->hora_fin }}</td>
                                                <td>{{ \Carbon\Carbon::parse($asignacionHorario->fecha_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($asignacionHorario->fecha_fin)->format('d/m/Y') }}</td>
                                                <td>{{ $asignacionHorario->numero_consultorio }}</td>
                                                <td>{{ $asignacionHorario->piso }} - {{ $asignacionHorario->ubicacion }}</td>
                                                <td>{{ $asignacionHorario->numero_paciente }}</td>
                                                <td style="color:green;">ACTIVO</td>

                                                <td>
                                                    <x-edit-button class="btn btn-warning" 
                                                        wire:click="actualizarHorario({{ $asignacionHorario->id_especialista }}, {{ $asignacionHorario->id }})">
                                                    </x-edit-button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ver-dos-modal>
    <style>
        .scroll-container {
            width: 100%;
            /* Ajusta el ancho según sea necesario */
            height: 300px;
            /* Ajusta la altura según sea necesario */
            overflow-y: auto;
            /* Habilita el scroll vertical */
            overflow-x: auto;
            /* Habilita el scroll horizontal si es necesario */
        }
    </style>
</div>

<script>
    window.addEventListener('modalVerHorarios', event => {
        $('#modalAsignacionHorario').modal('show');
    });
</script>
