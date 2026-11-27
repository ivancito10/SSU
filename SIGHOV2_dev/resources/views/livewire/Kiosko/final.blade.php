<div>
    <div class="container-fluid">
        <br>
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 p-5">
                <div class="card"
                    style=" padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); transition: left 0.3s ease; background-color: rgba(255, 255, 255, 0.4);">
                    <div style="display:flex">
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <div class="card-body d-flex flex-column align-items-center">
                                <!-- FOTOGRAFIA -->
                                <img src="data:image/jpg;base64,{{ $this->fotografia }}" class="img-thumbnail"
                                    width="200" height="200" alt="FTP.{{ $fotografia }}">
                            </div>
                            <div class="col-lg-12 col-md-6 col-sm-12"
                                style="font-size: 30px; display: flex; justify-content: center; align-items: center;">
                                <label for="es_permanente">
                                    @if ($estado_vigencia == 'VIGENTE')
                                        <span style="color:#229954 ; "><b>VIGENTE</b></span>
                                    @elseif ($estado_vigencia == 'SESANTE')
                                        <span style="color:#f4d03f; "><b>SESANTE</b></span>
                                    @else($estado_vigencia == 'NO VIGENTE')
                                        <span style="color:#c0392b; "><b>NO
                                                VIGENTE</b></span>
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="matricula">
                                    <b>MATRICULA</b> </label>
                                <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                    {{ $matricula_seguro }} </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci">
                                    <b>CARNET</b></label>
                                <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                    {{ $ci }} </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="sexo">
                                    <b>SEXO</b></label>
                                <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                    {{ $sexoText }} </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="sexo">
                                    <b>EDAD</b></label>
                                <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                    {{ $edad }} </p>
                            </div>
                        </div>
                    </div>
                    <!-- -------  DATOS ------- -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                {{ $nombreCompleto }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo"> <b>FECHA DE NACIMIENTO</b></label>
                            <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                {{ $fechaNacimientoText }} </p>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="edad">
                                <b>TIPO DE
                                    ASEGURADO</b></label>
                            <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                {{ $tipo_asegurado }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo"> <b>INSTITUCIÓN</b></label>
                            <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                {{ $nombre_institucion }} </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="edad">
                                <b>CODIGO INST.</b></label>
                            <p style="background:white; border-radius:3px; text-align:center;padding:5;">
                                {{ $codigo_institucional }} </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 p-5">
                <div class="card"
                    style=" padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); transition: left 0.3s ease; background-color: rgba(255, 255, 255, 0.4);">
                    <div class="card-body">
                        <h6
                            style=" font-family: 'Alfa Slab One'; font-size: 30px;font-weight: 600; color:#273746; text-align:center">
                            DATOS SELECCIONADOS</h6>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="edad" style="color:#0054DC;font-size:"><b>FECHA </b></label>
                                <div class="input-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                    <input style="background:#e2e4e6;border-color:#e2e4e6;" class="form-control"
                                        id="edad" wire:model="fechaIngresada" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="edad" style="color:#0054DC;font-size:"><b>TURNO </b></label>
                                <div class="input-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                    <input style="background:#e2e4e6;border-color:#e2e4e6;" class="form-control"
                                        id="edad" wire:model="turnoAsigHorario" disabled>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                <label for="edad" style="color:#0054DC;font-size:"><b>ESPECIALIDAD </b></label>
                                <div class="input-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-clipboard2-pulse-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5" />
                                        <path
                                            d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M9.98 5.356 11.372 10h.128a.5.5 0 0 1 0 1H11a.5.5 0 0 1-.479-.356l-.94-3.135-1.092 5.096a.5.5 0 0 1-.968.039L6.383 8.85l-.936 1.873A.5.5 0 0 1 5 11h-.5a.5.5 0 0 1 0-1h.191l1.362-2.724a.5.5 0 0 1 .926.08l.94 3.135 1.092-5.096a.5.5 0 0 1 .968-.039Z" />
                                    </svg>
                                    <input style="background:#e2e4e6;border-color:#e2e4e6;" class="form-control"
                                        id="edad" wire:model="especialidadSelected" disabled>
                                </div>

                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="edad" style="color:#0054DC;font-size:"><b>HORARIO </b></label>
                                <div class="input-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-person-standing" viewBox="0 0 16 16">
                                        <path
                                            d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M6 6.75v8.5a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2.75a.75.75 0 0 0 1.5 0v-2.5a.25.25 0 0 1 .5 0" />
                                    </svg>
                                    <input style="background:#e2e4e6;border-color:#e2e4e6;" class="form-control"
                                        id="nombreEspecialista" wire:model="horaSelect" disabled>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="edad" style="color:#0054DC;font-size:"><b>ESPECIALISTA </b></label>
                                <div class="input-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-person-standing" viewBox="0 0 16 16">
                                        <path
                                            d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M6 6.75v8.5a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2.75a.75.75 0 0 0 1.5 0v-2.5a.25.25 0 0 1 .5 0" />
                                    </svg>
                                    <input style="background:#e2e4e6;border-color:#e2e4e6;" class="form-control"
                                        id="nombreEspecialista" wire:model="nombreEspecialista" disabled>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="edad" style="color:#0054DC;font-size:"><b>CONSULTORIO </b></label>
                                <div class="input-group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="currentColor" class="bi bi-person-standing" viewBox="0 0 16 16">
                                        <path
                                            d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M6 6.75v8.5a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2.75a.75.75 0 0 0 1.5 0v-2.5a.25.25 0 0 1 .5 0" />
                                    </svg>
                                    <input style="background:#e2e4e6;border-color:#e2e4e6;" class="form-control"
                                        id="nombreEspecialista" wire:model="consultorioSelected" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- aqui los botones  -->
        <div style="position: absolute; bottom: 20px; right: 20px;">
            <x-volver-button wire:click="botonCasa">VOLVER</x-volver-button>
        </div>
    </div>
</div>
<style>
    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group svg {
        margin-right: 10px;
        color: #dc7633;
    }
</style>
