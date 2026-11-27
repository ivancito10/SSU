<div>
    <x-ver-modal id="VerModalSeguroInterior" labelledby="VerModalSeguroInteriorLabel" title="VER SEGURO DEL INTERIOR">
        <div class="row">
            <div class="col-lg-5">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>INSTITUCIÓN SEGURO DEL INTERIOR</b></p>
                </div> -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                        <p style="background-color:#1a5276; color:white"> SEGURO DEL INTERIOR </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombre_seguro"> SEGURO:</label>
                                        <input class="form-control" id="nombre_seguro" disabled
                                            wire:model="nombre_seguro" style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="sigla_seguro"> ACRÓNIMO:</label>
                                        <input id= "sigla_seguro" class="form-control" disabled
                                            wire:model="sigla_seguro" style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nro_empleador"> NUM. DE EMPLEADOR:</label>
                                        <input id="nro_empleador" class="form-control" disabled
                                            wire:model="nro_empleador" style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nit"> NIT:</label>
                                        <input id="nit" class="form-control" disabled wire:model="nit"
                                            style=" height: 30px;">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                style="text-align:center;">
                                                <p style="background-color:#1a5276; color:white"> ACTUAL REPRESENTANTE
                                                    LEGAL </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="nombreCompletoRepLegal">NOMBRE COMPLETO: </label>
                                                <input id="nombreCompletoRepLegal" class="form-control" disabled
                                                    wire:model="nombreCompletoRepLegal" style=" height: 30px;">
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                <label for="carnetRepLegal"> CARNET: </label>
                                                <input id="carnetRepLegal" class="form-control" disabled
                                                    wire:model="carnetRepLegal" style=" height: 30px;">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                style="text-align:center;">
                                                <p style="background-color:#1a5276; color:white"> USUARIO ENCARGADO </p>
                                            </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombreCompletoResponsable">NOMBRE COMPLETO:</label>
                                        <input id="nombreCompletoResponsable" class="form-control" disabled
                                            wire:model=nombreCompletoResponsable style=" height: 30px;">
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="carnetResponsable"> CARNET:</label>
                                        <input id="carnetResponsable" class="form-control" disabled
                                            wire:model="carnetResponsable" style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="usuarioResponsable"> USUARIO:</label>
                                        <input id="usuarioResponsable" class="form-control" disabled
                                            wire:model="usuarioResponsable" style=" height: 30px;">
                                    </div>

                                </div>
                                <div class="row">
                                    <!-- <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                    <label for="acronimo">NOMBRE USUARIO</label>
                                    <input class="form-control"  disabled wire:model="v_nombre_usuario">
                                </div> -->

                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                style="text-align:center;">
                                                <p style="background-color:#1a5276; color:white"> DATOS REFERENCIALES
                                                     </p>
                                            </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="departamento"> DEPARTAMENTO:</label>
                                        <input id="departamento" class="form-control" wire:model="departamento"
                                            disabled style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="provincia"> PROVINCIA:</label>
                                        <input id="provincia" class="form-control" wire:model="provincia" disabled
                                            style=" height: 30px;">
                                    </div>
                                </div>
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="municipio"> MUNICIPIO:</label>
                                        <input id="municipio" class="form-control" wire:model="municipio" disabled
                                            style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nombreZona"> ZONA:</label>
                                        <input id="nombreZona" class="form-control" wire:model="nombreZona" disabled
                                            style=" height: 30px;">
                                    </div>
                                </div>
                                <div class="row">

                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="direccion">DIRECCIÓN:</label>
                                        <textarea class="form-control" id="direccion" disabled wire:model="direccion" style=" resize: none;"></textarea>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="nro_vivienda">NRO PUERTA:</label>
                                        <input class="form-control" id="nro_vivienda" disabled
                                            wire:model="nro_vivienda" style=" height: 30px;">
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                style="text-align:center;">
                                                <p style="background-color:#1a5276; color:white"> CONTACTO
                                                     </p>
                                            </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="correo"> CORREO ELÉCTRONICO:</label>
                                        <input class="form-control" id="correo" disabled wire:model="correo"
                                            style=" height: 30px;">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="telefono"> TELÉFONO:</label>
                                        <input class="form-control" id="telefono" disabled wire:model="telefono"
                                            style=" height: 30px;">
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <label for="fax"> FAX:</label>
                                        <input class="form-control" id="fax" disabled wire:model="fax"
                                            style=" height: 30px;">
                                    </div>

                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#2471a3;">
                                    <em><b>Abrir mapa.</b></em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <x-ver-dos-button wire:click="AbrirModalUbicacionSeguroInterior">Ubicacion
                                </x-ver-dos-button>

                                @include('livewire.Vigencia.seguro-interior.ModalUbicacionSeguroInterior')


                            </div>

                        </div>
                        <br> <br><br>

                    </div>
                </div>
            </div>

        </div>
    </x-ver-modal>
    <style>
        #UbicacionSeguroInterior {
            z-index: 1060 !important;
            /* Aumenta el z-index */
        }

        #VerModalSeguroInterior {
            z-index: 1050;
            /* z-index del modal principal */
        }
    </style>

</div>

<script>
    window.addEventListener('modalVerSeguroInterior', event => {

        $('#VerModalSeguroInterior').modal('show');
    });
</script>
