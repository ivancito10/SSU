<x-small-ver-modal id="ModalVerConsultorio" labelledby="ModalVerConsultorioLabel" title="DETALLE DEL CONSULTORIO">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
            <span><b>ESTADO:</b>
                @if ($estado)
                    <span style="color: #196f3d; font-size:20px ">
                        <b>ACTIVO</b>
                    </span>
                @else
                    <span style="color: #c0392b; font-size:20px ">
                        <b>INACTIVO</b>
                    </span>
                @endif
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <strong>UBICACIÓN:</strong>
                    <p class="p-comun-disabled p-1">{{ $ubicacion }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <strong> PISO: </strong>
                    <p class="p-comun-disabled p-1">{{ $piso }}</p>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <strong> NÚMERO CONSULTORIO: </strong>
                    <p class="p-comun-disabled p-1">{{ $nro_consultorio }}</p>
                </div>

            </div>
        </div>
    </div>

</x-small-ver-modal>
<script>
    window.addEventListener('ModalVerConsultorio', event => {

        $('#ModalVerConsultorio').modal('show');
    });
</script>
