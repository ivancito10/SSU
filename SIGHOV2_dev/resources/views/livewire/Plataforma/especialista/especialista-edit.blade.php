<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:4px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                MODIFICAR ESPECIALISTA
            </h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="cancelarEspecialista" wire:loading.attr="disabled"
                wire:target="cancelarEspecialista">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="cancelarEspecialista">
                <x-cargando-dos />
            </div>
        </div>
    </div>
    <div style="height: 0.5rem;"></div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <div class="card card-comun p-2">
                <div class="cabecera text-center">
                    <b>ESPECIALISTA</b>
                </div>
                <div style="height: 0.5rem;"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <input class="form-control form-control-sm @error('foto') is-invalid @enderror" type="file"
                            id="formFile" name="archivo" required wire:model="foto" style="border-radius:8px"
                            accept=".jpg,.jpeg,.png" wire:change="cambioFoto">
                        @error('foto')
                            <span class="form-text text-danger">Debe ingresar una fotografía de tipo JPG O PNG.</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        @if ($switchFotoNueva)
                            @if (Str::startsWith($foto->getMimeType(), 'image/'))
                                <img src="{{ $foto->temporaryUrl() }}"
                                    style=" width: 150px; height: 150px; object-fit: cover;" class="img-thumbnail">
                            @endif
                        @else
                            <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" alt="img"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @endif

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>NOMBRE COMPLETO:</strong>
                        <p class="p-1"
                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                            {{ $nombreCompleto }} </p>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>MATRÍCULA:</strong>
                        <p class="p-1"
                            style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $matricula }} </p>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>CARNET:</strong>
                        <p class="p-1"
                            style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                            {{ $carnet }} </p>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="card card-comun p-3">
                <div class="cabecera text-center">
                    <b>DATOS DE CONTRATO Y ESPECIALIDAD</b>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <strong>ESPECIALIDAD:</strong>
                        <span style="color:#2c3e50;" class="h3">
                            <b>{{ $especialidad }} </b>
                        </span>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong>TIPO DE ATENCIÓN A POBLACIÓN ASEGURADA: </strong>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="afiliados" id="afiliados"
                                wire:model="afiliados" style="transform: scale(1.5); margin-right: 10px;">
                            <label class="form-check-label" for="afiliados">
                                AFILIADO
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="convenios" id="convenios"
                                wire:model="convenios" style="transform: scale(1.5); margin-right: 10px;">
                            <label class="form-check-label" for="convenios">
                                CONVENIO
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="estudiantes" id="estudiantes"
                                wire:model="estudiantes" style="transform: scale(1.5); margin-right: 10px;">
                            <label class="form-check-label" for="estudiantes">
                                SSUE
                            </label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row justify-content-start">
                    <div class=" col-lg-6 col-md-6 col-sm-5 col-xs-5">
                        <strong>ESPECIALISTA PERTENECIENTE A IMAGENOLOGÍA: <b style="color:#cb4335;">*</b></strong>
                    </div>
                    {{-- <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 d-flex align-items-center">
                        <label class="mr-2" style="color: red">NO</label>
                        <div class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                            <input wire:model="especialistaImagenologia" type="checkbox" class="custom-control-input"
                                id="customSwitchEspecialistaImagenologia">
                            <label class="custom-control-label" for="customSwitchEspecialistaImagenologia"l
                                style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                        </div>
                        <label class="ml-2" style="color: green">SI</label>
                    </div> --}}
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 d-flex align-items-center">
                        @if ($especialistaImagenologiaMostrar)
                            <h3 style='color: green'><strong>SI</strong></h3>
                        @else
                            <h3 style='color: red'><strong>NO</strong></h3>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <strong>GRADO ACADÉMICO:</strong>
                        <p class="p-1"
                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                            {{ $gradoAcademico }} </p>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="permanente">TIPO DE CONTRATO: <b style="color:#cb4335;">*</b></label>
                        <div class="col-sm-6 d-flex align-items-center">
                            <label class="text-red">EVENTUAL</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-blue">
                                <input wire:model.live="permanente" name="permanente" type="checkbox"
                                    class="custom-control-input" id="switchpermanente">
                                <label class="custom-control-label" for="switchpermanente"
                                    style="margin-left: 0.5rem;"></label>
                            </div>
                            <label class="text-blue">PERMANENTE</label>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">

                    @if (!$permanente)
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fechaContratoInicio">FECHA INICIO DE CONTRATO: <b
                                    style="color:#cb4335;">*</b></label>
                            <input id="fechaContratoInicio" class="form-control" type="date"
                                wire:model='fechaContratoInicio' style="height: 30px; border-radius:8px"
                                {{-- min="{{ now()->toDateString() }}" --}}>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fechaContratoFin">FECHA FIN DE CONTRATO: <b
                                    style="color:#cb4335;">*</b></label>
                            <input id="fechaContratoFin" class="form-control" type="date"
                                wire:model="fechaContratoFin" style="height: 30px; border-radius:8px"
                                {{-- min="{{ now()->toDateString() }}" --}}>
                        </div>
                    @endif
                </div>

            </div>
            <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                <x-cancel-button wire:click="cancelarEspecialista">
                    CANCELAR</x-cancel-button>
                <x-save-button onclick="actualizarEspecialista()">GUARDAR</x-save-button>
            </div>
        </div>
    </div>


    <script>
        function actualizarEspecialista() {
            Swal.fire({
                title: '¿Registrar Especialista?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('modificarEspecialista');

                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('eventualSinContrato', function() {
            Swal.fire({
                title: "<b>Sin fechas de contrato registradas</b>",
                text: "Si el especialista es eventual, debe especificar las fechas correspondientes a su contrato. Verifique e inténtelo nuevamente",
                icon: "warning"
            });
        });

        document.addEventListener('especialistaModificado', function() {
            Swal.fire({
                title: "<b>Guardado</b>",
                text: "El especialista se registró correctamente",
                icon: "success",
                showConfirmButton: false
            });
        });

        document.addEventListener('especialistaNoModificado', function() {
            Swal.fire({
                title: "<b>Error en el registro</b>",
                text: "Ocurrió un error en el registro. Verifique los datos e intente nuevamente",
                icon: "error"
            });
        });
    </script>


</div>
