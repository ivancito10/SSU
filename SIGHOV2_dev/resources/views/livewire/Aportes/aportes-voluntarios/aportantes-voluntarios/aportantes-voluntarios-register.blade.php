<div>
    <x-agregar-button style=" padding: 5px 10px;" data-toggle="modal"
        data-target="#aporteVoluntarioModal"><b>REGISTRAR</b></x-agregar-button>

    <!--  MODAL REGISTRAR NUEVO VOLUNTARIO -->
    <div class="modal fade" id="aporteVoluntarioModal" tabindex="-1" aria-labelledby="aporteVoluntarioModal"
        aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-xl ">
            <div class="modal-content p-0 m-0 border shadow  "
            style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
                <div class="modal-header" 
                    style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#ba4a00"
                        class="bi bi-pen-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                        <path
                            d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                    </svg>
                    <h5 style="font-family: Verdana, sans-serif;" class="modal-title"> AGREGAR VOLUNTARIO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:black">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <div class="card"
                                style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p style="color:#1a5276 ;" class="h5"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg> <em><b>Agregar datos personales para el registro.</b> </em>
                                                <spam style="color:#cb4335;"><b>*</b>
                                            </p>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <br>
                                            <div style="display: flex; align-items: center;">
                                                <!-- Aquí va el botón con icono de lupa -->
                                                <button class="btn btn-primary" type="submit" wire:click="buscarDatos">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" fill="currentColor" class="bi bi-search"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                                    </svg>
                                                </button>
                                                <input class="form-control" type="search" id="buscarCarnet"
                                                    wire:model='buscarCarnet'
                                                    style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                                    placeholder="Ingresa el carnet">
                                                <input class="form-control" type="search" id="buscarComplemento"
                                                    wire:model='buscarComplemento'
                                                    style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                                    placeholder="complemento"
                                                    oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div wire:loading>
                                            <div class="spinner-grow text-warning" role="status">

                                            </div>
                                            <b><i>Cargando datos espere porfavor...</i></b>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <br>
                                            <label for="nombres">NOMBRES:</label>
                                            <input class="form-control" name="nombres" id="nombres"
                                                wire:model="nombres" placeholder="Nombres" style="border-radius: 10px;"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="primerApellido"> AP. PATERNO:</label>
                                            <input class="form-control" name="primerApellido" id="primerApellido"
                                                wire:model="primerApellido" placeholder="Apellido paterno"
                                                style="border-radius: 10px;"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="segundoApellido"> AP. MATERNO:</label>
                                            <input class="form-control" name="segundoApellido" id="segundoApellido"
                                                wire:model='segundoApellido' placeholder="Apellido materno"
                                                style="border-radius: 10px;"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="ci">CARNET:</label>
                                            <input type="number" class="form-control" name="ci" id="ci"
                                                wire:model='ci' placeholder="Carnet de identidad"
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="complemento">COMPLEMENTO:</label>
                                            <input class="form-control" name="complemento" id="complemento"
                                                wire:model='complemento' placeholder="Complemento"
                                                style="border-radius: 10px;"
                                                oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="fechaNacimiento">FECHA DE NACIMIENTO:</label>
                                            <input type="date" class="form-control" name="fechaNacimiento"
                                                id="fechaNacimiento" wire:model='fechaNacimiento'
                                                style="border-radius: 10px;">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0"
                            style="background-color:white;">
                            <div class="card" style="; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                                 
                                <div class="card-body">
                                    <div class="row">
                                         
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p style="color:#1a5276 ;" class="h5"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg> <em> <b>Registrar los datos de dirección.</b></em>
                                                <spam style="color:#cb4335;"><b>*</b>
                                            </p>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="direccion">DIRECCIÓN:</label>
                                            <input wire:model="direccion" class="form-control" name="direccion"
                                                id="direccion" oninput="this.value = this.value.toUpperCase()"
                                                placeholder="Ingrese la dirección" style="border-radius: 10px;">
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="numero">NUMERO DE PUERTA:</label>
                                            <input wire:model="numero" class="form-control" name="numero"
                                                id="numero" oninput="this.value = this.value.toUpperCase()"
                                                placeholder="Ingrese el nro. de puerta" style="border-radius: 10px;">
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="zonaAv">ZONA:</label>
                                            <select wire:model="zona" name="zonaAv" id="zonaAv"
                                                class="form-control" style="width: 100%; border-radius: 10px;"
                                                tabindex="-1" required>
                                                <option selected="selected">Selecciona la zona </option>
                                                @foreach ($zonas as $zona)
                                                    <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="telefono1">1er. TELÉFONO: </label>
                                            <input wire:model="telefono1" class="form-control" type="number"
                                                name="telefono1" id="telefono1" placeholder="Ingrese teléfono"
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                            <label for="telefono2">2do. TELÉFONO: </label>
                                            <input wire:model="telefono2" class="form-control" type="number"
                                                name="telefono2" id="telefono2" placeholder="Ingrese el teléfono"
                                                style="border-radius: 10px;" min="0">
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="correo"> CORREO ELÉCTRONICO:</label>
                                            <input class="form-control" name="correo" id="correo"
                                                wire:model='correo' required
                                                placeholder="Ingrese el correo eléctronico"
                                                style="border-radius: 10px;">
                                        </div>
                                        <div class="invalid-feedback">
                                            You must agree before submitting.
                                        </div>


                                    </div>
                                    <br>
                                    <div class="row">
                                         
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p style="color:#1a5276 ;" class="h5"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg> <em><b>Registrar los datos del contrato.</b> </em><span
                                                    style="color:#cb4335;"><b>*</b></span>
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <label for="fechaInicioContrato">FECHA DE INICIO: </label>
                                            <input wire:model="fechaInicioContrato" type="date"
                                                class="form-control" name="fechaInicioContrato"
                                                id="fechaInicioContrato" style="border-radius: 10px;">
                                        </div>

                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                            <label for="fechaFinContrato">FECHA DE FIN:</label>
                                            <input wire:model='fechaFinContrato' type="date" class="form-control"
                                                name="fechaFinContrato" id="fechaFinContrato"
                                                style="border-radius: 10px;">
                                        </div>


                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="formFiledigital" class="form-label"
                                                style="color:#1a5276">SUBIR CONTRATO:<span
                                                    style="color:#cb4335;"><b>*</b></span> </label>

                                            <input class="form-control @error('file_digital') is-invalid @enderror"
                                                type="file" id="formFiledigital" name="file_digital" required
                                                wire:model.live="file_digital" accept="application/pdf"
                                                style="border-radius: 10px;">
                                            @error('file_digital')
                                                <div class="text-danger">
                                                    <b>{{ $message }}</b>
                                                </div>
                                            @enderror
                                        </div>
                                        <div wire:loading wire:target="file_digital"> <x-cargando-dos /> </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                            <label for="descripcionContrato">DESCRIPCIÓN:</label>
                                            <textarea wire:model='descripcionContrato' class="form-control" name="descripcionContrato" id="descripcionContrato"
                                                rows='3' oninput="this.value = this.value.toUpperCase()" required style="border-radius:10px"
                                                placeholder="Ingrese la descripción"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="modal-footer pt-0"
                    style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                    <x-cancel-button data-dismiss="modal" wire:click='cancelar'>CANCELAR</x-cancel-button>
                    <x-save-button onclick="registrarContrato()" wire:loading.attr="disabled">GUARDAR</x-save-button>
                </div>
                <div class="col-lg-12" wire:loading>
                    <x-cargando-dos />
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS Y COMPONENTES -->
    <script>
        //Funciones para Registrar Contrato

        function registrarContrato() {
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
                    Livewire.dispatch('guardarContrato');
                    $('#aporteVoluntarioModal').modal('hide');
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el registro.",
                        icon: "error"
                    });
                    Livewire.dispatch('cancelar');
                    @this.set('modal', true);
                    $('#aporteVoluntarioModal').modal('hide');
                }
            });
        }


        document.addEventListener('contratoCreado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "El contrato voluntario se registro correctamente.",
                icon: "success"
            });
        });
        document.addEventListener('contratoCancelado', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Usted canceló el registro",
                icon: "error"
            });
        });
        document.addEventListener('error', function() {
            Swal.fire({
                title: "Verifique los datos ingresados!",
                text: "Debe completar todos los datos.",
                icon: "warning"
            });
        });

        document.addEventListener('noExiste', function() {
            Swal.fire({
                title: "No existe la persona!",
                text: "Ingrese nuevamente los datos.",
                icon: "warning"

            });

        });
    </script>

</div>
