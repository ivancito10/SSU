<x-ver-modal id="verModalTitular" labelledby="verModalTitularLabel"
    title=" VISTA  INSTITUCIÓN DE CONVENIO">
    <div class="row ">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card"
                style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <!--  institucion convenio -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="text-align:center;">
                            <p
                                style="background-color:#1a5276; color:white;">INSTITUCIÓN
                                CONVENIO</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nombre_institucion">INSTITUCIÓN:</label>
                            <input wire:model="nombre_institucion" type="text"
                                name="nombre_institucion"
                                id="nombre_institucion" class="form-control"
                                disabled style=" height: 30px; ">
                        </div>
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="sigla">SIGLA:</label>
                            <input wire:model="sigla" name="sigla" id="sigla"
                                type="text" class="form-control"
                                disabled style=" height: 30px; ">
                        </div>
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="servicio">SERVICIO:</label>
                            <input wire:model="servicio" name="servicio"
                                id="servicio" type="text"
                                class="form-control" disabled
                                style=" height: 30px; ">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">
                            <label for="descripcion">DESCRIPCIÓN DEL SERVICIO
                                QUE BRINDA LA INSTITUCIÓN:</label>
                            <textarea id="descripcion" class="form-control"
                                type="text" wire:model="descripcion" disabled
                                style=" resize:none; border-radius: 10px; "
                                placeholder="No tiene descripción"></textarea>
                        </div>
                    </div>
                    <br>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-3"
                style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <!-- direccion-->
                <div class="row">
                    <div class="col-lg-12" style="text-align:center;">
                        <p
                            style="background-color:#1a5276; color:white;">DIRECCIÓN
                            ACTUAL</p>

                    </div>

                </div>
                <div class="row">
                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <label for="direccion">DIRECCIÓN:</label>
                        <input wire:model="direccion" name="direccion"
                            id="direccion" type="text"
                            class="form-control" disabled
                            style=" height: 30px; ">
                    </div>
                </div>
                <div class="row">
                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                        <label for="nro_vivienda">NÚMERO DE PUERTA:</label>
                        <input wire:model="nro_vivienda" name="nro_vivienda"
                            id="nro_vivienda" type="text"
                            class="form-control" disabled
                            style=" height: 30px; ">
                    </div>
                    <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                        <label for="nombreZona">ZONA:</label>
                        <input wire:model="nombreZona" name="nombreZona"
                            id="nombreZona" type="text"
                            class="form-control" disabled
                            style=" height: 30px; ">
                    </div>
                </div>

                <div class="row">
                    <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                        <br>
                        <label for="correo">CORREO ELÉCTRONICO:</label>
                        <input wire:model="correo" name="correo" id="correo"
                            type="text" class="form-control"
                            disabled style=" height: 30px; ">
                    </div>
                    <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                        <br>
                        <label for="telefono">TELÉFONO:</label>
                        <input wire:model="telefono" name="telefono"
                            id="telefono" type="number"
                            class="form-control" disabled
                            style=" height: 30px; ">
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">&nbsp;</div>
                    <div class="col-lg-12 col-md-4 col-sm-6 col-xs-12">

                        <x-ver-dos-button
                            wire:click="AbrirModalUbicacionInstitucionConvenio">Ubicación
                        </x-ver-dos-button>
                        @include('livewire.Vigencia.instituciones.ModalUbicacionInstitucionConvenio')
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-ver-modal>

<script>
    window.addEventListener('modalVerInstitucionConvenio', event => {


        $('#verModalTitular').modal('show');
    });
</script>
