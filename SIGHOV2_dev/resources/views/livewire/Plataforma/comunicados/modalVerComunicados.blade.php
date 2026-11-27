<div>
    <x-small-ver-modal id="modalVerComunidado" labelledby="modalVerComunidadoLabel" title="VISTA DE COMUNICADO">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right ">
                <strong style="font-size:90%">ESTADO :</strong>
                @if ($estadoMostrar == true)
                    <span class="h4" style="color: green;"><b> VIGENTE</b></span>
                @else
                    <span class="h4 " style="color: red;"><b> PASADO</b></span>
                @endif
            </div>
        </div>
        <div style="height: 0.5rem;"></div>

        @if ($permanenteMostrar === 'TEMPORAL')
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-left ">
                    <strong>PERIODO:</strong>
                    <p class="p-1 p-comun-disabled">{{ $permanenteMostrar }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <strong>FECHA INICIAL:</strong>
                    <p class="p-1 p-comun-disabled">{{ $fecha_inicio }}</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <strong>FECHA FINAL:</strong>
                    <p class="p-1 p-comun-disabled">{{ $fecha_fin }}</p>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left ">
                    <strong>PERIODO:</strong>
                    <p class="p-1 p-comun-disabled">{{ $permanenteMostrar }}</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <strong>FECHA INICIAL:</strong>
                    <p class="p-1 p-comun-disabled">{{ $fecha_inicio }}</p>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <strong>UNIDAD:</strong>
                <p class="p-1 p-comun-disabled">{{ $nombre_unidad }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <strong>DESCRIPCIÓN:</strong>
                <div id="mensaje-content" class="p-comun-disabled p-1" style="height: 150px; overflow-y: auto;">
                    {!! $mensaje !!}
                </div>
            </div>
        </div>

    </x-small-ver-modal>
</div>
<script>
    document.addEventListener('abrirModalVerComunidados', function() {
        $('#modalVerComunidado').modal('show');
    });
</script>
