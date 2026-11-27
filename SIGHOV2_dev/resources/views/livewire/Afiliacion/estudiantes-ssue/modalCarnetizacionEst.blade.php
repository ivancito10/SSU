<div>
    <x-ver-modal id="carnetModalEst" labelledby="carnetModalEstLabel" title=" CARNETIZACIÓN ">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#1b5989; color:white;text-align:center; "> <b> REIMPRESIÓN DE CARNET
                        </b></p>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                            <label for="fecha_emision">FECHA DE EMISIÓN:</label>
                            <input class="form-control" id="fecha_emision" disabled
                                style="background-color:#f2f4f4; padding-right:1px; border-color:white; border-radius:10px;"
                                wire:model="fecha_emision_formatted">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                            <label for="fecha_vencimiento">FECHA DE VENCIMIENTO:</label>
                            <input class="form-control" id="fecha_vencimiento" disabled
                                style="background-color:#f2f4f4; padding-right:1px; border-color:white; border-radius:10px;"
                                wire:model="fecha_vencimiento_formatted">
                        </div>
                    </div>
                    <br>
                    @if ($this->fecha_emision == null)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <x-carnet-azul-button disabled> REIMPRIMIR CARNET</x-carnet-azul-button>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <x-carnet-azul-button wire:click="recarnetizacion"> REIMPRIMIR
                                    CARNET</x-carnet-azul-button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#c4dff3 ;text-align:center; "> <b> NUEVO CARNET </b></p>
                   
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                            <label for="fecha_emision">FECHA DE EMISIÓN:</label>
                            <input class="form-control" id="fecha_emision" min="{{ now()->toDateString() }}"  style=" border-radius:10px;" type="date"
                                wire:model="fecha_emision_r">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 ">
                            <label for="fecha_vencimiento">FECHA DE VENCIMIENTO:</label>
                            <input class="form-control" id="fecha_vencimiento" type="date"  min="{{ now()->toDateString() }}"
                                style="border-radius:10px;" wire:model="fecha_vencimiento_r">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <x-carnet-rojo-button wire:click="crear_h_carnet">IMPRIMIR CARNET</x-carnet-rojo-button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalCarnetizacionEst', event => {
        $('#carnetModalEst').modal('show');
    });
</script>
