<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;"> ACTUALIZAR REGISTRO INSTITUCIÓN</h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>

    <div class="row">
        <!-- AQUI VA EL REPRESENTANTE LEGAL -->

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="card-body">

                    <div class="row">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <label for="institucion">NOMBRE DE LA INSTITUCIÓN: </label>
                            <input wire:model="institucion" type="text" name="institucion" id="institucion"
                                class="form-control" style="border-radius: 10px;"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>

                            <label for="nit" style="color:#1a5276">NIT<spam style="color:#cb4335;"><b>*</b></spam>
                                :</label>
                            <input wire:model="nit" name="nit" id="nit" type="number" class="form-control "
                                style="border-radius: 10px;" min="0">

                        </div>

                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <label for="file_nit" style="color:#1a5276">SUBIR EN PDF EL NIT (HASTA 1MB):<spam style="color:#cb4335;">
                                    <b>*</b>
                                </spam>:</label>
                            <input class="form-control @error('file_digital_nit') is-invalid @enderror"
                                type="file" id="formFiledigital" name="file_digital_nit" required
                                wire:model="file_digital_nit" accept=".pdf">

                            @error('file_digital_nit')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror

                            <div wire:loading wire:target="file_digital_nit">
                                <x-cargando-dos />
                            </div>
                        </div>


                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nro_empleador">NÚMERO DE EMPLEADOR:</label>
                            <input wire:model="nro_empleador" name="nro_empleador" id="nro_empleador" type="text"
                                class="form-control " style="border-radius: 10px;"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>

                    </div>

                    <div class="row">

                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="acronimo">ACRÓNIMO / SIGLA:</label>
                            <input wire:model="acronimo" name="acronimo" id="acronimo" type="text"
                                class="form-control " style="border-radius: 10px;"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="actividad_empleador">ACTIVIDAD EMPLEADOR:</label>
                            <input wire:model="actividad_empleador" name="actividad_empleador" id="actividad_empleador"
                                type="text" class="form-control" style="border-radius: 10px;"
                                oninput="this.value = this.value.toUpperCase()">

                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                            <label for="clase_aportante_id">TIPO DE APORTANTE:</label>
                            <select wire:model="clase_aportante_id" name="clase_aportante_id"
                                class="form-control  @error('clase_aportante_id') is-invalid @enderror"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" aria-hidden="true" required>

                                <option selected="selected" value="{{ $clase_aportante_id }}" disabled>
                                    {{ $clase_aportante }}
                                </option>
                                @foreach ($claseSelect as $clase)
                                    <option value="{{ $clase->id }}">{{ $clase->tipo }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tipo_comercio">CÓDIGO DE COMERCIO:</label>
                            <select wire:model.live="tipo_comercio" id="tipo_comercio" name="tipo_comercio"
                                class="form-control select2 @error('tipo_comercio') is-invalid @enderror"
                                style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                <option selected="selected" disabled>Seleccione el código de comercio</option>
                                <option value="NINGUNO">NINGUNO</option>
                                <option value="SOCIEDAD COLECTIVA">SOCIEDAD COLECTIVA</option>
                                <option value="SOCIEDAD EN COMANDITA SIMPLE">SOCIEDAD EN COMANDITA SIMPLE</option>
                                <option value="SOCIEDAD DE RESPONSABILIDAD LIMITADA (S.R.L.) ">SOCIEDAD DE
                                    RESPONSABILIDAD LIMITADA (S.R.L.)</option>
                                <option value="SOCIEDAD ANÓNIMA (S.A.)">SOCIEDAD ANÓNIMA (S.A.)</option>
                                <option value="SOCIEDAD EN COMANDITA POR ACCIONES">SOCIEDAD EN COMANDITA POR ACCIONES
                                </option>
                                <option value="ASOCIACIÓN ACCIDENTAL O DE CUENTAS POR PARTICIPACIÓN">ASOCIACIÓN
                                    ACCIDENTAL O DE CUENTAS POR PARTICIPACIÓN</option>
                                <option value="EMPRESA UNIPERSONAL">EMPRESA UNIPERSONAL</option>

                            </select>
                            @error('tipo_comercio')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Este campo es
                                        obligatorio.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>
                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_afiliacion">FECHA DE AFILIACIÓN:</label>
                            <input wire:model="fecha_afiliacion" name="fecha_afiliacion" id="fecha_afiliacion"
                                type="date" class="form-control" style="border-radius: 10px;">

                        </div>


                    </div>

                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Seleccione los siguientes estados.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>
                    <div class="row">

                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="tgn">ES TGN:</label>
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <label class="mr-md-2 mb-2 mb-md-0">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="tgn" name="tgn" type="checkbox"
                                        class="custom-control-input" id="customSwitch2">
                                    <label class="custom-control-label" for="customSwitch2"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="mr-md-2 mb-2 mb-md-0">SI</label>
                            </div>
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="tipo_institucion">ES DE TIPO:</label>
                            <div class="col-sm-10 d-flex align-items-center">
                                <label class="mr-2">Empresa</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="tipo_institucion" name="tipo_institucion" type="checkbox"
                                        class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                </div>
                                <label class="ml-2">Institución</label>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="publica">ES:</label>
                            <div class="d-flex flex-column flex-md-row align-items-center">
                                <label class="mr-md-2 mb-2 mb-md-0">Privada</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="publica" name="publica" type="checkbox"
                                        class="custom-control-input" id="customSwitch2publica">
                                    <label class="custom-control-label mx-3" for="customSwitch2publica"></label>
                                </div>
                                <label class="ml-md-2 mt-2 mt-md-0">Pública</label>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Agregar nuevos datos de dirección y seleccionar en el mapa</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>

                    <div class="row">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <div class="row">
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="direccion">DIRECCIÓN:</label>
                                    <input wire:model="direccion" name="direccion" id="direccion" type="text"
                                        class="form-control @error('direccion') is-invalid @enderror"
                                        placeholder="  Avenida y/o Calle"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;"
                                        required>
                                    @error('direccion')
                                        @if ($message == 'validation.required')
                                            <span class="form-text text-danger">Este campo es obligatorio.</span>
                                        @else
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="numero">NÚMERO DE PUERTA:</label>
                                    <input wire:model="numero" name="numero" id="numero" type="text"
                                        class="form-control @error('numero') is-invalid @enderror"
                                        placeholder=" Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('numero')
                                        @if ($message == 'validation.required')
                                            <span class="form-text text-danger">Este campo es obligatorio.</span>
                                        @else
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                                    <label for="zonaId">ZONA:</label>
                                    <select wire:model="zonaId" id="zonaId" class="form-control select2 "
                                        style="width: 100%; border-radius: 10px;" tabindex="-1">
                                        <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                        </option>
                                        @foreach ($zonasSelect as $zonaSelected)
                                            <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="direccion_referencia">REFERENCIA DE LA DIRECCIÓN:</label>
                                    <input wire:model="direccion_referencia" name="direccion_referencia"
                                        id="direccion_referencia" type="text" class="form-control"
                                        placeholder="Ingrese el Número de Puerta o Edificio"
                                        oninput="this.value = this.value.toUpperCase()" style="border-radius: 10px;">
                                    @error('direccion_referencia')
                                        @if ($message == 'validation.required')
                                            <span class="form-text text-danger">Este
                                                campo es obligatorio.</span>
                                        @else
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <label for="correo">CORREO ELÉCTRONICO:</label>
                                    <input wire:model="correo" name="correo" id="correo" type="text"
                                        class="form-control @error('correo') is-invalid @enderror"
                                        placeholder=" Correo Electrónico" style="border-radius: 10px;">
                                    @error('correo')
                                        @if ($message == 'validation.required')
                                            <span class="form-text text-danger">Este campo es obligatorio.</span>
                                        @else
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono">1er. TELÉFONO:</label>
                                    <input wire:model="telefono" name="telefono" id="telefono" type="number"
                                        class="form-control @error('telefono') is-invalid @enderror"
                                        placeholder="Teléfono Referencia" style="border-radius: 10px;" min="0">
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
                                <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                                    <label for="telefono_dos">2do. TELÉFONO:</label>
                                    <input wire:model="telefono_dos" name="telefono_dos" id="telefono_dos"
                                        type="number" class="form-control" placeholder="Ingrese el Teléfono"
                                        style="border-radius: 10px;" min="0">
                                    @error('telefono_dos')
                                        @if ($message == 'validation.numeric')
                                            <span class="form-text text-danger">El valor
                                                ingresado debe ser
                                                numérico.</span>
                                        @elseif ($message == 'validation.required')
                                            <span class="form-text text-danger">Este
                                                campo es obligatorio.</span>
                                        @else
                                            <span class="form-text text-danger">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-primary p-2" type="button" data-toggle="collapse"
                                        data-target="#collapseExample" aria-expanded="true"
                                        aria-controls="collapseExample">
                                        <i class="fa fa-street-view" aria-hidden="true"></i> Agregar Ubicación

                                    </button>
                                    @if ($this->ubicacion_select == 0)
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

                                    <div wire:ignore>
                                        <div class="card card-body">
                                            @include('livewire.Aportes.institucion.ubicacion_institucion')
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Actual representante legal.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nombres">NOMBRE COMPLETO: </label>
                            <input class="form-control" type="text" id="nombres" wire:model='nombres' disabled
                                style=" height: 30px; border-radius: 10px;">
                        </div>

                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET: </label>
                            <input class="form-control" type="text" id="ci" wire:model='ci' disabled
                                style=" height: 30px; border-radius: 10px;">

                        </div>

                        <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA DE NACIMIENTO: </label>
                            <input class="form-control" type="date" wire:model='fecha_nacimiento'
                                id="fecha_nacimiento" disabled style=" height: 30px; border-radius: 10px;">
                        </div>


                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                            <label for="tipo_doc">TIPO DE DOCUMENTO:</label>
                            <input class="form-control" type="text" id="tipo_doc" wire:model='tipo_doc' disabled
                                style=" height: 30px; border-radius: 10px;">

                        </div>
                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <label for="expedido_ci">EXPEDIDO:</label>
                            <input class="form-control" type="text" id="expedido_ci" wire:model='expedido_ci'
                                disabled style=" height: 30px; border-radius: 10px;">


                        </div>

                    </div>

                    <br>
                    <!--GUARDAR Y CANCELAR-->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <x-cancel-button wire:click="atras">CANCELAR</x-cancel-button>
                            <x-save-button onclick="saveEditInsti()">GUARDAR</x-save-button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading
                            wire:target="saveInstitucion">
                            <x-cargando-dos />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
            padding: 6px 12px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 25px;
        }
    </style>
</div>


<script>
    // esto es lo de las zonas
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.querySelector('#zonaId');
        $(select).select2();
        $(select).on('select2:open', function() {
            setTimeout(function() {
                document.querySelector('.select2-search__field').focus();
            }, 100);
        });

        $('#zonaId').on('change', function(e) {
            var data = $(this).val();
            @this.set('zonaId', data);
        });
    });

    // FUNCION DEL BOTON GUARDAR para SWEETALERT2
    function saveEditInsti() {
        Swal.fire({
            title: '¿Modificar registro?',
            text: "Se modificarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, modificar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizar');
            }
        });
    }

    document.addEventListener('institucionModificada', function() {
        Swal.fire({
            title: "Modificado!",
            text: "La instittución se modificó correctamente",
            icon: "success",
            showConfirmButton: false,
            timer: 2000 // Opcional: Cierra la alerta automáticamente después de 2 segundos
        });
    });

    document.addEventListener('ordenCreada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La orden de servicio se registró correctamente.",
            icon: "success",
            showConfirmButton: false,
            timer: 2000 // Opcional: Cierra la alerta automáticamente después de 2 segundos
        });
    });
    document.addEventListener('InstitucionNoActualizado', function() {
        Swal.fire({
            title: "No se modifico!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Advertencia!",
            text: "Debe completar todos los campos. Sugerencia, si cambia el NIT también debe subir el nuvo archivo file del NIT, de lo contrario no es necesario.",
            icon: "warning"
        });
    });
</script>
