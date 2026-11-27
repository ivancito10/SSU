<div>
    <div class="row">
        <div class="col-sm-4">

            <div class="card">
                <div class="card-body">
                    <!--DATOS DE LA PERSONA-->

                    <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                    <div class="row">
                        <div class="col-lg-12" style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->

                            <button class="btn btn-primary" type="submit  " wire:click="search_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>

                            <input class="form-control " type="search" id="search"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el C.I"
                                wire:model="ci_b">
                            <input class="form-control" type="search" id="complemento"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Ingrese complemento"
                                wire:model="complemento_b">
                        </div>
                        <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                        @if ($estado_api == '')
                        @else
                        <div class="col-lg-12 " role="alert">
                            @if ($estado_api == 1)
                            <div class="alert alert-success"> Se validó
                                correctamente!</div>
                            @else
                            <div class="alert alert-danger">No se validó!</div>
                            @endif
                        </div>
                        @endif
                        <div wire:loading wire:target="search_persona">
                            <div class="spinner-grow text-warning" role="status">

                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>

                    </div>
                    <br>
                    <!--DATOS DE LA NUEVA PERSONA-->

                    <!--AQUI SE MUESTRAN LOS DATOS RECUPERADOS DEL ASUSS-->

                    <div class="row">

                        <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET </label>
                            <input type="number" class="form-control" disabled placeholder=" C.I" wire:model.live="ci"
                                name="ci" id="ci" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="complemento_m">COMPLEMENTO </label>
                            <input type="number" class="form-control" disabled placeholder=" Complemento"
                                wire:model.live="complemento_m" name="complemento_m" id="complemento_m"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nombre">NOMBRE </label>
                            <input type="text" class="form-control" disabled placeholder=" Nombre"
                                wire:model.live="nombre" name="nombre" id="nombre"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_paterno">PRIMER APELLIDO </label>
                            <input type="text" class="form-control" disabled placeholder=" Paterno"
                                wire:model.live="ap_paterno" name="ap_paterno" id="ap_paterno"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_materno">SEGUNDO APELLIDO </label>
                            <input type="text" class="form-control" disabled placeholder=" Materno"
                                wire:model.live="ap_materno" name="ap_materno" id="ap_materno"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA DE NACIMIENTO </label>
                            <input type="text" class="form-control" disabled placeholder=" Fecha Nacimiento"
                                wire:model.live="fecha_nacimiento" name="fecha_nacimiento" id="fecha_nacimiento"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula"> MATRICULA</label>
                            <input class="form-control" name="matricula" id="matricula" wire:model.live="matricula"
                                disabled>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo"> SEXO</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <label class="mr-2">M</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="sexo" name="sexo" type="checkbox" class="custom-control-input"
                                        id="customSwitch2" wire:change='GenerarMatricula()'>
                                    <label class="custom-control-label" for="customSwitch2"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">F</label>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>DATOS
                        REFERENCIALES</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud"> FECHA SOLICITUD </label>
                            <input class="form-control" name="fecha_solicitud" id="fecha_solicitud"
                                wire:model=fecha_solicitud type="date" wire:change="calcularFechaFin()">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> FECHA FIN </label>
                            <input class="form-control" name="fecha_fin" id="fecha_fin"
                                wire:model=fecha_fin type="date" disabled >
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <label for="fecha_inicio_contrato"> FECHA INICIO CONTRATO </label>
                            <input class="form-control" name="fecha_inicio_contrato" id="fecha_inicio_contrato"
                                wire:model=fecha_inicio_contrato type="date">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="cargo"> CARGO  </label>
                            <input class="form-control" name="cargo" id="cargo"
                                wire:model=cargo type="text">
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="observacion"> OBSERVACIONES </label>
                            <textarea class="form-control" name="observacion" id="observacion"
                                wire:model=observacion></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <x-cancel-button> CANCELAR</x-cancel-button>
            <x-save-button wire:click="guardarExamenPreocupacional()">GUARDAR</x-save-button>
        </div>
    </div>