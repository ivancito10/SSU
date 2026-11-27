<div>
    <x-small-ver-modal id="modalVerControlHorario" labelledby="modalVerControlHorarioLabel"
        title="VISTA DE CONTROL DE HORARIO">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong style="font-size:90%">ESTADO :</strong>
                @if ($estadoMostrar == true)
                    <span class="h4" style="color: green;"><b>ACTIVO </b></span>
                @else
                    <span class="h4 " style="color: red;"><b>INACTIVO</b></span>
                @endif
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 ">
                <label for="posicion"> POSICIÓN: </label>
                <input id="posicion" class="form-control" type="number" wire:model='posicion' disabled
                    style="height: 40px; border-radius:8px">
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 ">
            </div>
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 ">
                <label for="porcentaje"> PORCENTAJE %: </label>
                <input id="porcentaje" class="form-control" type="number" min="0" max="100" disabled
                    wire:model='porcentaje' style="height: 40px; border-radius:8px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <label for="hora_inicio"> HORA INICIO: </label>
                <input id="hora_inicio" class="form-control" type="time" wire:model='hora_inicio' disabled
                    style="height: 40px; border-radius:8px">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <label for="hora_fin"> HORA FIN: </label>
                <input id="hora_fin" class="form-control" type="time" wire:model='hora_fin' disabled
                    style="height: 40px; border-radius:8px">
            </div>
        </div>

    </x-small-ver-modal>
</div>
<script>
    document.addEventListener('abrirmodalVerControlHorarios', function() {
        $('#modalVerControlHorario').modal('show');
    });
</script>
