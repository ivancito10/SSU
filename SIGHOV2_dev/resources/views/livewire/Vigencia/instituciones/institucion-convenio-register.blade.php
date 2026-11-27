<div>
    <!--//-->
    <!--CARD DATOS PRINCIPALES-->
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">AGREGAR NUEVA INSTITUCIÓN DE CONVENIO
            </h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> DATOS DE LA INSTITUCIÓN</b></p>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2 h5"><b>Debe completar los siguientes campos.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nombre_institucion">INSTITUCIÓN:</label>
                            <input wire:model.live="nombre_institucion" type="text" name="nombre_institucion"
                                id="nombre_institucion"
                                class="form-control @error('nombre_institucion') is-invalid @enderror"
                                placeholder=" Nombre de la Institución" oninput="this.value = this.value.toUpperCase()"
                                required>
                            @error('nombre_institucion')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>

                            <label for="sigla">SIGLA:</label>
                            <input wire:model.live="sigla" name="sigla" id="sigla" type="text"
                                class="form-control @error('sigla') is-invalid @enderror" placeholder="Sigla"
                                oninput="this.value = this.value.toUpperCase()" required>
                            @error('sigla')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12'>
                            <label for="servicio">SERVICIO:</label>
                            <input wire:model.live="servicio" name="servicio" id="servicio" type="text"
                                class="form-control @error('servicio') is-invalid @enderror" placeholder="Servicio"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('servicio')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class= "col-lg-12 col-md-4 col-sm-6 col-xs-12">
                            <label for="descripcion">DESCRIPCION DEL SERVICIO QUE BRINDA LA INSTITUCIÓN:</label>
                            <textarea wire:model.live="descripcion" name="descripcion" id="descripcion" type="text"
                                class="form-control  @error('descripcion') is-invalid @enderror" placeholder=" Descripción" rows="3"
                                oninput="this.value = this.value.toUpperCase()"></textarea>
                            @error('descripcion')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>
                <br><br><br><br>
            </div>

        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> DIRECCIÓN ACTUAL </b></p>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="color:#2471a3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        <em class="ml-2 h5"><b>Ingresar y completar los siguientes campos.</b> </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="direccion">DIRECCIÓN</label>
                                    <input wire:model.live="direccion" name="direccion" id="direccion" type="text"
                                        class="form-control @error('direccion') is-invalid @enderror"
                                        placeholder="  Avenida y/o Calle"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('direccion')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="nro_vivienda">NÚMERO DE PUERTA</label>
                                    <input wire:model.live="nro_vivienda" name="nro_vivienda" id="nro_vivienda"
                                        type="text"
                                        class="form-control @error('nro_vivienda') is-invalid @enderror"
                                        placeholder=" Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('nro_vivienda')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12' wire:ignore>
                                    <label for="id_zona">ZONA</label>
                                    <select wire:model.live="id_zona" name="id_zona" id="id_zona"
                                        class="form-control select2  @error('id_zona') is-invalid @enderror"
                                        style="width: 100%;" tabindex="-1" required>
                                        <option selected="selected">Seleccione la Zona </option>
                                        @foreach ($zonas as $zona)
                                            <option value="{{ $zona->id }}">{{ $zona->nombre }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <label for="correo">@CORREO ELÉCTRONICO</label>
                                    <input wire:model.live="correo" name="correo" id="correo" type="text"
                                        class="form-control @error('correo') is-invalid @enderror "
                                        placeholder=" Correo Electrónico">
                                    @error('correo')
                                        <div class="text-danger">
                                            <b>{{ $message }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono">TELÉFONO</label>
                                    <input wire:model.live="telefono" name="telefono" id="telefono" type="number"
                                        class="form-control @error('telefono') is-invalid @enderror"
                                        placeholder="Teléfono Referencia">
                                    @error('telefono')
                                        @if ($message == 'validation.numeric')
                                            <span class="form-text text-danger">El valor ingresado debe ser
                                                numérico.</span>
                                        @elseif ($message == 'validation.required')
                                            <span class="form-text text-danger">Este campo es obligatorio.</span>
                                        @else
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="color:#2471a3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        <em class="ml-2 h5"><b>Debe sleccionar la ubicación.</b> </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary" type="button" data-toggle="collapse"
                                        data-target="#collapseExample" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        <i class="fa fa-street-view" aria-hidden="true"></i> Agregar Ubicación
                                    </button>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    @if ($ubicacion_select == 0)
                                        <span style="font-size: 16px;" class=" text-danger">No Seleccionado <span
                                                class="fa-stack fa-lg">
                                                <i class="fa fa-street-view fa-stack-1x"></i>
                                                <i class="fa fa-ban fa-stack-2x text-danger"></i>
                                            </span></span>
                                    @else
                                        <span style="font-size: 16px;" class="text-success">Seleccionado!
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-street-view fa-stack-1x"></i>

                                            </span></span>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="collapse" id="collapseExample" wire:ignore>
                                        <div class="card card-body">
                                            @include('livewire.Aportes.institucion.ubicacion_institucion')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                <div id="cargando" style="display: none;">
                    <x-cargando-dos />
                </div>
                <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
                <x-save-button onclick="saveInstitucion()" wire:loading.attr="disabled"
                    id="save-button">GUARDAR</x-save-button>
            </div>

        </div>

    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 50px;
        }
    </style>

    <!-- FUNCIONES -->
    <script>
        // esto es lo de las zonas
        document.addEventListener('DOMContentLoaded', function() {
            var select = document.querySelector('#id_zona');
            $(select).select2();
            $('#id_zona').on('change', function(e) {
                var data = $(this).val();
                @this.set('id_zona', data);
            });
        });

        // FUNCION DEL BOTON GUARDAR
        function saveInstitucion() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarInstitucionConvenio');
                }
            });
        }

        document.addEventListener('institucionCreada', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La institución se registró correctamente.",
                icon: "success",
                showConfirmButton: false

            });
        });
        document.addEventListener('institucionNoCreada', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Usted canceló el registro.",
                icon: "error",
                showConfirmButton: false

            });
        });
    </script>
</div>
