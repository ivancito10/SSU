<div>
    <x-small-ver-modal id="modalVerGestionAgenda" labelledby="modalVerGestionAgendaLabel" title="GESTIONAR AGENDA"
        saveAction="registroGestionAgenda()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong style="font-size:90%">ESTADO :</strong>
                @if ($estado == true)
                    <span class="h4" style="color: green;"><b>ACTIVO</b></span>
                @else
                    <span class="h4 " style="color: red;"><b>INACTIVO</b></span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="servicioNombre">SERVICIO: </label>
                <input id="servicioNombre" class="form-control" type="text" disabled wire:model='servicioNombre'
                    style="height: 40px; border-radius:8px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <label for="hoy"> INICIA: </label>
                <input id="hoy" class="form-control" type="text" disabled wire:model='hoy'
                    style="height: 40px; border-radius:8px">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <label for="nro_dias">NRO DE DIAS: </label>
                <input id="nro_dias" class="form-control" type="number" disabled wire:model='nro_dias'
                    style="height: 40px; border-radius:8px">
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <label for="cantidad_turnos">CANTIDAD TURNOS: </label>
                <input id="cantidad_turnos" class="form-control" type="number" disabled wire:model='cantidad_turnos'
                    style="height: 40px; border-radius:8px">
            </div>
        </div>
        <br>
        @foreach ($turnos as $index => $turno)
            <div class="card p-2"
                style="border-radius:6px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); background-color:#e9ecef">
                <strong style="color: #2471a3"> TURNO {{ $index + 1 }} : </strong>
                <div class="row mt-2">
                    <div class="col-lg-4">
                        <label>HORA INICIO: <b style="color:#cb4335;">*</b></label>
                        <input class="form-control " type="time" value="{{ $turno['hora_inicio'] }}" disabled
                            style="height: 40px; border-radius:8px">
                    </div>
                    <div class="col-lg-4">
                        <label>HORA FIN: <b style="color:#cb4335;">*</b></label>
                        <input class="form-control" type="time" value="{{ $turno['hora_fin'] }}" disabled
                            style="height: 40px; border-radius:8px">
                    </div>
                    <div class="col-lg-4">
                        <label>PORCENTAJE %: <b style="color:#cb4335;">*</b></label>
                        <input class="form-control" type="number" value="{{ $turno['porcentaje'] }}" disabled
                            style="height: 40px; border-radius:8px" min="1" max="100">
                    </div>
                </div>
            </div>
        @endforeach
    </x-small-ver-modal>

</div>
<script>
    document.addEventListener('abrirModalVerGestionarAgenda', function() {
        $('#modalVerGestionAgenda').modal('show');
    });
</script>
