<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">ARCHIVO CLÍNICO - <b>BOUCHERS</b></h4>


    </div>
    @can('archivo_clinico.bouchers')
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card card-comun p-2">
                    <div class="cabecera text-center">
                        <b>
                            IMPRESION DE BOUCHERS</b>
                    </div>



                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" wire:ignore>
                            <label for="fecha">FECHA: <b style="color:#cb4335;">*</b></label>
                            <input class="form-control input-comun" wire:model="fecha" type="date"
                                wire:change="seleccionFecha" 
                                min="{{ Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"
                                max="{{ Carbon\Carbon::now()->addDays(1)->format('Y-m-d') }}"  onkeydown="return false;">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" wire:ignore>
                            <label for="idEspecialidad">ESPECIALIDAD: <b style="color:#cb4335;">*</b></label>
                            <select wire:model.live="idEspecialidad" name="idEspecialidad" id="idEspecialidad"
                                class="form-control input-comun select2  @error('idEspecialidad') is-invalid @enderror"
                                style="width: 100%;" tabindex="-1" required wire:change="seleccionEspecialidad">
                                <option selected="selected">Seleccione la especialidad </option>
                                @foreach ($especialidades as $esp)
                                    <option value="{{ $esp->id }}">{{ $esp->especialidad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                            <label for="idEspecialista"> ESPECIALISTA: <b style="color:#cb4335;">*</b></label>
                            <select wire:model="idEspecialista" id="idEspecialista"
                                class="form-control select2 @error('idEspecialista') is-invalid @enderror"
                                style="width: 100%; border-radius:8px;" tabindex="-1" wire:change="seleccionEspecialista"
                                required>
                                <option selected="selected">Seleccione..</option>
                                @foreach ($especialistas as $especialista)
                                    <option value="{{ $especialista->id }}">
                                        {{ $especialista->personaEspecialista->p_apellido }}
                                        {{ $especialista->personaEspecialista->s_apellido }}
                                        {{ $especialista->personaEspecialista->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($mostrarturnos === 1)
                            <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                                <label for="idTurno"> TURNO: <b style="color:#cb4335;">*</b></label>
                                <select wire:model="idTurno" id="idTurno"
                                    class="form-control select2 @error('idTurno') is-invalid @enderror"
                                    style="width: 100%; border-radius:8px;" tabindex="-1" wire:change="seleccionTurno"
                                    required>
                                    <option selected="selected">Seleccione..</option>
                                    @foreach ($turnos as $turno)
                                        <option value="{{ $turno['id'] }}">
                                            {{ $turno['turno'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div><br>
                    @if ($mostrarBoton === 1)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="pr-2">
                                    <button class="btn btn-warning" type="button" wire:click="imprimir_boucher_mañana">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                            fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                            <path
                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                                        </svg> GENERAR BOUCHER </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endcan
</div>
<script>
    document.addEventListener("FechaSeleccionada", function(event) {
        const fecha = event.detail.fecha;
        const [year, month, day] = fecha.split("-");
        const fechaFormateada = `${day}-${month}-${year}`;

        Swal.fire({
            title: "<b>Fecha actualizada</b>",
            text: `Has seleccionado la fecha: ${fechaFormateada}`,
            icon: "success",
        });
    });
    document.addEventListener("NoHayHorarios", function(event) {

        Swal.fire({
            title: "<b>Error en Horario</b>",
            text: "No existe un Horario para la fecha seleccionada.",
            icon: "warning",
        });
    });
    document.addEventListener("NoExistenAgendas", function(event) {

        Swal.fire({
            title: "<b>Alerta en Agenda</b>",
            text: "No existe Agendas del especialista Seleccionado.",
            icon: "warning",
        });
    });
    document.addEventListener("BoucherGenerado", function(event) {

        Swal.fire({
            title: "<b>Boucher Generado</b>",
            text: "Se generó el boucher correctamnete.",
            icon: "success",
        });
    });
    
</script>
