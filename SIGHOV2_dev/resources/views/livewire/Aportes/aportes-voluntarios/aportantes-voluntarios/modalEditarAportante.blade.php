<div>
    <!--MODAL PARA MODIFICAR-->
    <x-big-component id="editarContratoModal" labelledby="editarContratoModalLabel" title="MODIFICAR REGISTRO"
        saveAction="editarContrato()">

        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="background-color:white;">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class ="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276 ; color:white; border-radius:10px;"> APORTANTE
                                    VOLUNTARIO</p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>NOMBRE COMPLETO: </b>
                                    <br>{{ $nombre }} {{ $primerApellido }} {{ $segundoApellido }} </span>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <span><b>CARNET: </b>{{ $ci }} {{ $complemento }} </span>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>FECHA DE NACIMIENTO: </b>{{ $fechaNacimiento }} </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">

                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class ="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Para la dirección modificar los datos necesarios.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="direccion">DIRECCIÓN:</label>
                                <input wire:model="direccion" class="form-control" name="direccion" id="direccion"
                                    style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="numero">NÚMERO DE PUERTA:</label>
                                <input wire:model="numero" class="form-control" name="numero" id="numero"
                                    style="border-radius: 10px;" oninput="this.value = this.value.toUpperCase()"
                                    placeholder="Número de puerta">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="zonaId">ZONA:</label>
                                <select wire:model="zonaId" name="zonaId" id="zonaId" class="form-control"
                                    style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                    <option selected="selected"value="{{ $zonaId }}"> {{ $zona }}
                                    </option>
                                    @foreach ($zonasSelected as $zonaSelected)
                                        <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono1">1er. TELÉFONO:</label>
                                <input wire:model="telefono1" class="form-control" type='number' name="telefono1"
                                    id="telefono1" style="border-radius: 10px;" placeholder="Ingrese teléfono">
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <label for="telefono2">2do. TELÉFONO:</label>
                                <input wire:model="telefono2" class="form-control" type='number' name="telefono2"
                                    id="telefono2" style="border-radius: 10px;" placeholder="Ingrese teléfono">
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="correo">CORREO ELÉCTRONICO:</label>
                                <input class="form-control" name="correo" id="correo" wire:model='correo' required
                                    style="border-radius: 10px;" placeholder="Ingrese el correo eléctronico">
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <span> <b>FECHA  INICIO: </b> {{ $fecha_i_m }} </span>

                            </div>

                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <span><b>FECHA  FIN: </b>{{ $fecha_f_m }} </span>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <p style="color:#1a5276 ;" class="h5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        <em class="ml-2"><b>Actualizar el registro contrato.</b> </em>
                                        <spam style="color:#cb4335;"><b>*</b></spam>
                                    </p>
                                </div>
                                
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="fechaInicioContrato">FECHA DE INICIO:</label>
                                    <input wire:model="fechaInicioContrato" type="date" class="form-control"
                                        name="fechaInicioContrato" id="fechaInicioContrato"
                                        style="border-radius: 10px;">
                                </div>

                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="fechaFinContrato"> FECHA DE FIN:</label>
                                    <input wire:model='fechaFinContrato' type="date" class="form-control"
                                        name="fechaFinContrato" id="fechaFinContrato" 
                                        style=" border-radius: 10px;">
                                </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <p style="color: #1a5276 ;" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Modificar la descripción del contrato.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                            

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               
                                <label for="descripcionContrato">DESCRIPCIÓN:</label>
                                <textarea wire:model='descripcionContrato' class="form-control" name="descripcionContrato" id="descripcionContrato"
                                    rows='3' placeholder="Ingrese la descripción" style="border-radius: 10px;"
                                    oninput="this.value = this.value.toUpperCase()"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-big-component>
</div>
<script>
    window.addEventListener('modalEditarAportante', event => {
        $('#editarContratoModal').modal('show');
    });
</script>
