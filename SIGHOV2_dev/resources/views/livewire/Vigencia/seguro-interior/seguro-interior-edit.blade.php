<div>
    <div class="card-header" style="background-color:#34495e ">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h7 style="font-family: Verdana, sans-serif; color: white; margin: 0;">
                MODIFICAR DATOS - SEGURO DEL INTERIOR</h7>
            <x-atras-button wire:click="atras">ATRÁS</x-atras-button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>INSTITUCIÓN SEGURO DEL INTERIOR</b></p>
                </div> -->
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> SEGURO DEL INTERIOR </b></p>
                <div class="card-body">
                    <!--  <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <p style="background-color:#e5eff9  ; color:#1e5886; ">
                                <b> INSITUCIÓN - SEGURO DEL INTERIOR</b>
                            </p>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_seguro"> SEGURO:</label>
                            <input id="nombre_seguro" class="form-control" disabled wire:model="nombre_seguro"
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="sigla_seguro"> SIGLA:</label>
                            <input id="sigla_seguro" class="form-control" disabled wire:model="sigla_seguro"
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_empleador"> NUM. EMPLEADOR:</label>
                            <input id="nro_empleador" class="form-control" disabled wire:model="nro_empleador"
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="nit"> NIT:</label>
                            <input id="nit" class="form-control" disabled wire:model="nit"
                                style=" height: 30px; ">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> DATOS REFERENCIALES - UBICACIÓN </b></p>
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
                                <em class="ml-2"><b>Actualizar datos referenciales.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="direccion"> DIRECCIÓN:</label>
                            <input id="direccion" class="form-control @error('direccion') is-invalid @enderror"
                                wire:model.live="direccion" oninput="this.value = this.value.toUpperCase()">
                            @error('direccion')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="id_zona">ZONA:</label>
                            <select wire:model.live="id_zona" name="id_zona" id="id_zona"
                                class="form-control @error('id_zona') is-invalid @enderror" style="width: 100%"
                                tabindex="-1">
                                <option selected="selected" value="{{ $id_zona }}"> {{ $nombreZona }}
                                </option>
                                @foreach ($zonas as $zona)
                                    <option value="{{ $zona->id }}">{{ $zona->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_zona')
                                <div class="text-danger">
                                    <b> El campo zona es obligatorio</b>
                                </div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_vivienda"> NRO VIVIENDA:</label>
                            <input id="nro_vivienda" class="form-control @error('nro_vivienda') is-invalid @enderror "
                                wire:model.live="nro_vivienda" type="text"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('nro_vivienda')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="telefono"> TÉLEFONO REFERENCIA:</label>
                            <input id="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                type="number" wire:model.live="telefono">
                            @error('telefono')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fax"> FAX:</label>
                            <input id="fax" class="form-control @error('fax') is-invalid @enderror"
                                type="numeric" wire:model.live="fax" oninput="this.value = this.value.toUpperCase()">
                            @error('fax')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="correo"> CORREO ELÉCTRONICO:</label>
                            <input class="form-control @error('correo') is-invalid @enderror" name="correo"
                                id="correo" type="email" wire:model="correo">
                            @error('correo')
                                <div class="text-danger">
                                    <b>{{ $message }}</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> REPRESENTANTE LEGAL </b></p>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombresRepLegal"> NOMBRES:</label>
                            <input class="form-control @error('nombresRepLegal') is-invalid @enderror"
                                id="nombresRepLegal" wire:model.live=nombresRepLegal
                                @if (!$sinDatos) disabled @endif
                                style=" height: 30px; ">
                            @error('nombresRepLegal')
                                <div class="text-danger">
                                    <b>El campo nombre es obligatorio</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="p_apellidoRepLegal"> AP. PATERNO:</label>
                            <input class="form-control @error('p_apellidoRepLegal') is-invalid @enderror"
                                id="p_apellidoRepLegal" @if (!$sinDatos) disabled @endif
                                wire:model.live="p_apellidoRepLegal"
                                style=" height: 30px; ">
                            @error('p_apellidoRepLegal')
                                <div class="text-danger">
                                    <b>El campo primer apellido es obligatorio</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="s_apellidoRepLegal"> AP. MATERNO:</label>
                            <input class="form-control @error('s_apellidoRepLegal') is-invalid @enderror"
                                id="s_apellidoRepLegal" @if (!$sinDatos) disabled @endif
                                wire:model.live="s_apellidoRepLegal"
                                style=" height: 30px; ">
                            @error('s_apellidoRepLegal')
                                <div class="text-danger">
                                    <b>El campo segundo apellido es obligatorio</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="ciRepLegal"> CARNET:</label>
                            <input class="form-control @error('ciRepLegal') is-invalid @enderror" id="ciRepLegal"
                                @if (!$sinDatos) disabled @endif wire:model.live=ciRepLegal
                                style=" height: 30px; ">
                            @error('ciRepLegal')
                                <div class="text-danger">
                                    <b>El campo carnet es obligatorio</b>
                                </div>
                            @enderror
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="complementoRepLegal"> COMPL.:</label>
                            <input class="form-control" id="complementoRepLegal"
                                @if (!$sinDatos) disabled @endif wire:model="complementoRepLegal"
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_nacimientoRepLegal"> FECHA NACIMIENTO:</label>
                            <input class="form-control @error('fecha_nacimientoRepLegal') is-invalid @enderror"
                                id="fecha_nacimientoRepLegal" @if (!$sinDatos) disabled @endif
                                wire:model="fecha_nacimientoRepLegal" type="date"
                                style=" height: 30px; ">
                            @error('fecha_nacimientoRepLegal')
                                <div class="text-danger">
                                    <b>El campo fecha de nacimiento es obligatorio</b>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <br>
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
                                <em class="ml-2"><b>Ingrese los campos para la búsqueda.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Aquí va el botón con icono de lupa -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; align-items: center;">

                            <button class="btn btn-primary" type="submit" wire:click="search_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg>
                            </button>
                            <input class="form-control" type="search" wire:model="ciBusqueda"
                                style="width: calc(100% - 20px); margin-left: 5px;"
                                placeholder="Ingresa el carnet de identidad">

                            <input class="form-control" type="search" wire:model="complementoBusqueda"
                                style="width: calc(100% - 20px); margin-left: 5px;"
                                placeholder="Ingresa el complemento">


                        </div>

                        <div wire:loading wire:target="search_persona">
                            <div class="spinner-grow text-warning" role="status">
                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>


                    </div>

                </div>


            </div>
            <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
                <x-cancel-button wire:click="cancelar">CANCELAR</x-cancel-button>
                <x-save-button onclick="guardarEdicion()" wire:loading.attr="disabled"
                    id="save-button">GUARDAR</x-save-button>
            </div>

        </div>

    </div>




    <script>
        // EDITAR TITULAR
        function guardarEdicion() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('actualizarSeguroInterior');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }

        document.addEventListener('seguroInteriorModificado', function() {
            Swal.fire({
                title: "Modificado!",
                text: "La institución se modificó correctamente.",
                icon: "success",
                showConfirmButton: false

            });
        });

        document.addEventListener('seguroInteriorNoModificado', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Se canceló el registro.",
                icon: "error",
                showConfirmButton: false

            });
        });
    </script>



</div>
