<div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <div class="card cabecera text-center">
                    <b>INSTITUCIONES REGISTRADAS</b>
                </div>
                <div style="max-height: 650px; overflow-y: auto;">
                    @forelse ($instituciones_registradas as $key => $institucionRegistrada)
                        <div class="row">
                            <div class="col-lg-12col-md-12 col-sm-12 col-12">
                                <table class="tabla-ploma" width=100%>
                                    <tr>
                                        <td width=40% style="background-color: #1E6BA2; border-radius:6px">
                                            <strong>
                                                @if ($loop->first)
                                                    <b style="color:white">INSTITUCIÓN PRINCIPAL:</b>
                                                @else
                                                    <b style="color:white"> INSTITUCIÓN {{ $loop->iteration }} :</b>
                                                @endif
                                            </strong>
                                        </td>
                                        <td width=60% colspan="2">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                <label for="switchPrincipal{{ $key }}"> ¿ES INSTITUCIÓN
                                                    PRINCIPAL?</label>
                                                <div class="col-sm-6 d-flex align-items-center">
                                                    <strong style="color:#e45252">NO</strong>
                                                    <div
                                                        class="custom-control custom-switch custom-switch-off-red custom-switch-on-green">
                                                        <input type="checkbox" class="custom-control-input"
                                                            id="switchPrincipal{{ $key }}"
                                                            @if ($institucionRegistrada['primera_institucion']) checked @endif
                                                            {{ $institucionRegistrada['primera_institucion'] ? 'disabled' : '' }}
                                                            onclick="confirmarCambiarEstado(event,{{ $institucionRegistrada['id'] }},'{{ $institucionRegistrada['nombre_institucion'] }}' )">
                                                        <label class="custom-control-label"
                                                            for="switchPrincipal{{ $key }}"
                                                            style="margin-left: 1.5rem; margin-right: 1.5rem;">
                                                        </label>
                                                    </div>
                                                    <strong style="color:#128521">SI</strong>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12col-md-12 col-sm-12 col-12">
                                <strong> NOMBRE DE INSTITUCIÓN:</strong>
                                <p class="p-comun-disabled p-1">{{ $institucionRegistrada['nombre_institucion'] }} </p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                <strong>COD.TIT.:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['codigo_titular'] }} </p>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                <strong>COD.BEN.:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['codigo_beneficiario'] }}
                                </p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <strong> TIPO INSTITUCIÓN:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['tipo_institucion'] }} </p>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <strong> CARGO:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['cargo_actual'] }} </p>

                            </div>

                            <div class="col-lg-12 col-md-5 col-sm-5 col-5">
                                <strong> TITULARIDAD:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['titularidad'] }}
                                </p>
                            </div>

                            <div class="col-lg-6 col-md-3 col-sm-3 col-3">
                                <strong> SALARIO:</strong>
                                <p class="p-comun-disabled p-1"> Bs.
                                    {{ $institucionRegistrada['salario'] }} </p>


                            </div>
                            <div class="col-lg-6 col-md-4 col-sm-4 col-4">
                                <strong> FECHA INGRESO::</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ \Carbon\Carbon::parse($institucionRegistrada['fecha_ingreso'])->translatedFormat('d /m/ Y ') }}

                                </p>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <strong> ITEM:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['item'] }}
                                </p>

                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <strong> CARGA HORARIA:</strong>
                                <p class="p-comun-disabled p-1">
                                    {{ $institucionRegistrada['carga_horaria'] }} </p>
                            </div>

                        </div>
                    @empty
                        <div class="text-center p-3">
                            <p>No existen instituciones registradas para este titular.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg> <em class="ml-2 h5" style="color:#2471a3 ; "><b>Ingrese
                            los datos institucionales :</b> </em>
                    <spam style="color:#cb4335;"><b>*</b></spam>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label for="institucionNuevaId">INSTITUCIONES:</label>
                        <select wire:model="institucionNuevaId" wire:change="datosInstitucionNueva"
                            name="institucionNuevaId" id="institucionNuevaId" class="form-control"
                            style="width: 100%; border-radius:10px;" tabindex="-1" required>
                            <option selected="selected">Selecciona la nueva Institución </option>
                            @foreach ($instituciones_sin_registrar as $institucionNueva)
                                <option value="{{ $institucionNueva->id }}">{{ $institucionNueva->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label for="nombreNuevaInstitucion"> INSTITUCIÓN SELECCIONADA:</label>
                        <textarea class="form-control @error('nombreNuevaInstitucion') is-invalid @enderror" id="nombreNuevaInstitucion"
                            wire:model=nombreNuevaInstitucion disabled style="padding-rigth:1; border-color:white; border-radius:10px;" row=2></textarea>
                        @error('nombreNuevaInstitucion')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <label for="nombreNuevoCodigoTitular"> COD. TIT.:</label>
                        <input class="form-control" id="nombreNuevoCodigoTitular" wire:model=nombreNuevoCodigoTitular
                            disabled style="padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 col-12">
                        <label for="nombreNuevoCodBen"> COD. BEN.: </label>
                        <input class="form-control" id="nombreNuevoCodBen" wire:model=nombreNuevoCodBen disabled
                            style="padding-rigth:1; border-color:white; border-radius:10px;">
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <label for="nuevaFechaIngreso"> FECHA INGRESO:</label>
                        <input type="date" class="form-control @error('nuevaFechaIngreso') is-invalid @enderror"
                            id="nuevaFechaIngreso" style="border-radius:10px;" wire:model=nuevaFechaIngreso>
                        @error('nuevaFechaIngreso')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <label for="nuevoCargoActual"> CARGO: </label>
                        <input class="form-control @error('nuevoCargoActual') is-invalid @enderror"
                            id="nuevoCargoActual" placeholder="Ingresa el cargo"
                            oninput="this.value = this.value.toUpperCase()" style="border-radius:10px;"
                            wire:model=nuevoCargoActual>
                        @error('nuevoCargoActual')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                        <label for="nuevoSalario"> SALARIO: </label>
                        <input type="number" class="form-control @error('nuevoSalario') is-invalid @enderror"
                            placeholder="Ingresa el salario" style="border-radius:10px;" id="nuevoSalario"
                            wire:model=nuevoSalario>
                        @error('nuevoSalario')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                
                    <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                        <label for="nuevaTitularidad">TITULARIDAD:</label>
                        <select wire:model="nuevaTitularidad" name="nuevaTitularidad" id="nuevaTitularidad"
                            class="form-control select2 @error('nuevaTitularidad') is-invalid @enderror"
                            style=" border-radius:10px;" tabindex="-1" required>
                            <option selected="selected">Seleccione titularidad</option>
                            <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                            <option value="CONTRATADO">CONTRATADO</option>
                            <option value="EVENTUAL">EVENTUAL</option>
                            <option value="INTERINO">INTERINO</option>
                            <option value="TITULAR A">TITULAR A</option>
                            <option value="TITULAR B">TITULAR B</option>
                            <option value="TITULAR C">TITULAR C</option>
                            <option value="TITULAR EMERITO">TITULAR EMERITO</option>
                            <option value="SECTOR SALUD">SECTOR SALUD</option>
                            <option value="NINGUNO">NINGUNO</option>
                        </select>
                        @error('nuevaTitularidad')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                        <label for="nueva_carga_horaria">CARGA HORARIA:</label>
                        <select wire:model="nueva_carga_horaria" name="nueva_carga_horaria" id="nueva_carga_horaria"
                            class="form-control select2 @error('nueva_carga_horaria') is-invalid @enderror"
                            style=" border-radius:10px;" tabindex="-1" required>
                            <option selected>Seleccione carga horaria</option>
                            <option value="160 Hrs.">160 Hrs.</option>
                            <option value="128 Hrs.">128 Hrs.</option>
                            <option value="114 Hrs.">114 Hrs.</option>
                            <option value="112 Hrs.">112 Hrs.</option>
                            <option value="96 Hrs.">96 Hrs.</option>
                            <option value="80 Hrs.">80 Hrs.</option>
                            <option value="64 Hrs.">64 Hrs.</option>
                            <option value="48 Hrs.">48 Hrs.</option>
                            <option value="32 Hrs.">32 Hrs.</option>
                            <option value="16 Hrs.">16 Hrs.</option>
                            <option value="POR ACTUALIZAR">NINGUNO</option>
                            <option value="TC">TC</option>
                        </select>
                        @error('nueva_carga_horaria')
                            <div class="text-danger">
                                <b>{{ $message }}</b>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                        <label for="nuevoItem"> ITEM: </label>
                        <input type="text" class="form-control " placeholder="Ingresa el item"
                            style="border-radius:10px;" id="nuevoItem" wire:model=nuevoItem>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">
        <div id="cargando" style="display: none;">
            <x-cargando-dos />
        </div>
        <x-cancel-button data-dismiss="modal">
            CANCELAR</x-cancel-button>
        <x-save-button onclick="preguntarGuardarInstitucion()">GUARDAR</x-save-button>
    </div>

</div>
<script>
    function preguntarGuardarInstitucion() {
        Swal.fire({
            title: 'Guardar registro?',
            text: "El cierre de gestión será registrado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarInstitucionNueva');

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Usted canceló el registro.",
                    icon: "warning"
                });
                // $('#agregarInstitucionModal').modal('hide');
            }
        });
    }

    function confirmarCambiarEstado(event, id, nombre_institucion) {
        // event.preventDefault();
        const checkbox = event.target;
        // const estadoOriginal = !checkbox.checked;

        //console.log(checkbox, estadoOriginal);
        Swal.fire({
            title: '¿Cambiar Institución Principal?',
            html: `Vas a marcar a <b>${nombre_institucion}</b> como la <b>INSTITUCIÓN PRINCIPAL</b>. <br> Esto intercambiará los datos con la actual.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                //Livewire.dispatch('guardarInstitucionNueva');
                @this.cambiarAPrincipal(id);
                checkbox.checked = false;

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Usted canceló el registro.",
                    icon: "warning"
                });
                checkbox.checked = false;
                // $('#agregarInstitucionModal').modal('hide');
            }
        });
    }




    document.addEventListener('nuevaInstitucionAgregada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente",
            icon: "success",
            showConfirmButton: false,
            timer: 2000,
        });

        //$('#agregarInstitucionModal').modal('hide');
    });

    document.addEventListener('nuevaInstitucionNoAgregada', function() {
        Swal.fire({
            title: "Completa todos los datos!",
            text: "Se cancelo el registro",
            icon: "error",
            showConfirmButton: false,
            //timer: 2000,
        });
    });

    document.addEventListener('institucionCambiada', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se modificó correctamente",
            icon: "success",
            showConfirmButton: false,
            timer: 2000,
        });

        //$('#agregarInstitucionModal').modal('hide');
    });
</script>
