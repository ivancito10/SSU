<div>
    <x-ver-modal id="carnetModalBen" labelledby="carnetModalBenLabel" title=" CARNETIZACIÓN ">

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#1b5989; color:white;text-align:center; border-radius:8px"> <b>
                            REIMPRESIÓN DE CARNET
                        </b></p>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                            <label for="fecha_emision">FECHA DE EMISIÓN:</label>
                            <input class="form-control" id="fecha_emision" disabled
                                style="background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:10px;"
                                wire:model="fecha_emision_formatted">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <label for="fecha_vencimiento">FECHA DE VENCIMIENTO:</label>
                            <input class="form-control" id="fecha_vencimiento" disabled
                                style="background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:10px;"
                                wire:model="fecha_vencimiento_formatted">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12 col-12 ">
                            <label for="motivo_carnet">MOTIVO DE EMISIÓN DE CARNET</label>
                            <div class="col-sm-12">
                                <select wire:model="motivo_carnet" id="motivo_carnet" class="select2 form-control"
                                    style="border-radius:10px">
                                    <option> Seleccione el motivo ...</option>
                                    <option value="PÉRDIDA DE CARNET"> PÉRDIDA DE CARNET</option>
                                    <option value="DUPLICIDAD DE CARNET"> DUPLICIDAD DE CARNET</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <label for="nro_recibo_reimpresion">NÚMERO DE RECIBO: <span
                                    style="color:#cb4335;"><b>*</b></span></label>
                            <input class="form-control input-comun" id="nro_recibo_reimpresion"
                                wire:model="nro_recibo_reimpresion" placeholder="Ej: 123456">
                        </div>
                    </div>
                    <br>
                    @if ($this->fecha_emision == null)
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <x-carnet-azul-button disabled> REIMPRIMIR CARNET</x-carnet-azul-button>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <x-carnet-azul-button wire:click="recarnetizacion"> REIMPRIMIR
                                    CARNET</x-carnet-azul-button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                    <p style="background-color:#c4dff3 ;text-align:center;border-radius:8px "> <b> NUEVO CARNET </b></p>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                            <label for="fecha_emision">FECHA DE EMISIÓN:</label>
                            <input class="form-control" id="fecha_emision" style=" border-radius:10px;"
                                min="{{ now()->toDateString() }}" type="date" wire:model="fecha_emision_r">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12 ">
                            <label for="fecha_vencimiento">FECHA DE VENCIMIENTO:</label>
                            <input class="form-control" id="fecha_vencimiento" type="date"
                                min="{{ now()->toDateString() }}" style="border-radius:10px;"
                                wire:model="fecha_vencimiento_r">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-sm-12 col-12">
                            <label for="motivo_carnet">MOTIVO DE EMISIÓN DE CARNET</label>
                            <input class="form-control" id="motivo_carnet" type="text" style="border-radius:10px;"
                                value="NUEVO CARNET" readonly>

                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <label for="nro_recibo">NÚMERO DE RECIBO: <span
                                    style="color:#cb4335;"><b>*</b></span></label>
                            <input class="form-control input-comun" id="nro_recibo" wire:model="nro_recibo"
                                placeholder="Ej: 123456">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                            <x-carnet-rojo-button wire:click="crear_h_carnet">IMPRIMIR CARNET</x-carnet-rojo-button>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-ver-modal>
</div>
<script>
    window.addEventListener('modalCarnetizacionBen', event => {
        $('#carnetModalBen').modal('show');
    });
</script>
