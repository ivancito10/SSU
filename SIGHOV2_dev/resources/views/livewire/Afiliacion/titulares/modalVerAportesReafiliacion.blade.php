<div wire:ignore>
    <x-ver-modal id="verAportesReafiliacion" labelledby="verAportesTitularLabel" title=" APORTES DE ASEGURADO ">
    <h6>APORTES REGISTRADOS</h6>

     @if($tipo_asegurado== 0){
        @foreach ($aportes as $index => $institucion)
            <div class="card-body">
                <div class="row">
                    <input type="hidden" value="{{ $institucion }}">
                    <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                        <label for="nombre_institucion" class="text-success"> INSTITUCIÓN {{ $index + 1 }}
                        </label>
                        <input class="form-control"  id="nombre_institucion" disabled
                            value="{{ $institucion->nombre_institucion }}">
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="clase_aportante"> TIPO INSTITUCION</label>
                        <input class="form-control" id="clase_aportante" disabled
                            value="{{ $institucion->clase_aportante }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="tipo_trabajador"> TIPO </label>
                        <input class="form-control" id="tipo_trabajador" disabled
                            value="{{ $institucion->tipo_trabajador }}">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="item"> ITEM </label>
                        <input class="form-control" id="item" disabled value="{{ $institucion->item }}">
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="cargo"> CARGO</label>
                        <input class="form-control"  id="cargo" disabled
                            value="{{ $institucion->cargo }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="mes"> MES</label>
                        <input class="form-control" id="mes" disabled value="{{ $institucion->mes }}">
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="gestion"> GESTION</label>
                        <input class="form-control" id="gestion" disabled value="{{ $institucion->gestion }}">
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="total_ganado"> TOTAL GANADO </label>
                        <input class="form-control" id="total_ganado" disabled value="{{ $institucion->total_ganado }}">
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                        <label for="total_aporte"> TOTAL APORTE </label>
                        <input class="form-control" name="total_aporte" id="total_aporte" disabled
                            value="{{ $institucion->total_aporte }}">
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <hr class="bg-danger">
                    </div>
                </div>
            </div>
        @endforeach
     } @else{
voluntarios
     }

@endif
    </x-ver-modal>
</div>

<script>

    function modalVerAportesReafiliacion() {
        $('#verAportesReafiliacion').modal('show');
    };
</script>
