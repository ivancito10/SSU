<div>
    <x-small-ver-modal id="modalVerFeriado" labelledby="modalVerFeriadoLabel" title="VISTA DE FERIADO">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong style="font-size:90%">ESTADO :</strong>
                @if ($estado == true)
                    <span class="h4" style="color: green;"><b> ACTIVO</b></span>
                @else
                    <span class="h4" style="color: red;"><b> INACTIVO</b></span>
                @endif
            </div>
        </div>
        <div style="height: 0.5rem;"></div>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="fecha">FECHA:</label>
                <input class="form-control" id="fecha" wire:model=fecha type="date" disabled
                    style="border-radius:10px">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <label for="dia">DÍA:</label>
                <input class="form-control" id="dia" wire:model=dia type="text" disabled
                    style="border-radius:10px">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="descripcion">DESCRIPCIÓN:</label>
                <textarea class="form-control" id="descripcion" wire:model='descripcion' style="border-radius:10px" disabled
                    class="swal2-input" rows='4' oninput="this.value = this.value.toUpperCase()"></textarea>
            </div>
        </div>

    </x-small-ver-modal>
</div>
<script>
    document.addEventListener('abrirModalVerFeriados', function() {
        $('#modalVerFeriado').modal('show');
    });
</script>
