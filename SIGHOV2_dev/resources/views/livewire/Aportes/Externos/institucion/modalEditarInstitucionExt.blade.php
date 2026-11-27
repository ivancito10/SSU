<div wire:ignore>
     <!--ESTO ES EL COMPONENTE PARA EDITAR LA INSTITUCION  -->
     <x-big-component id="editarModalExt" labelledby="editarModalExtLabel" title="MODIFICAR  INSTITUCIÓN" saveAction="saveEditInsti()"> 
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                        <b>Actualizar los datos de la institución.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                            
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre"> NOMBRE DE LA INSTITUCIÓN</label>
                                <input class="form-control" type="text" wire:model='nombre' style="border-radius:10px;" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nit"> NIT:</label>
                                <input class="form-control" type="text" wire:model='nit' style="border-radius:10px;">
                            </div>
                            <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nit">SUBIR EN PDF EL NIT:</label>
                                <input class="form-control form-control-sm @error('file_digital_nit') is-invalid @enderror"
                                    type="file" id="formFiledigital" name="file_digital_nit" required wire:model="file_digital_nit"
                                    accept=".pdf">
                                <div wire:loading wire:target="file_digital_nit">
                                    <x-cargando-dos />
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="num_empleador"> NÚMERO DE EMPLEADOR:</label>
                               
                                <input class="form-control" type="text" wire:model='num_empleador' style="border-radius:10px;" oninput="this.value = this.value.toUpperCase()">
                            </div>


                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="acronimo"> ACRÓNIMO:</label>
                                <input class="form-control" type="text" wire:model='acronimo' style="border-radius:10px;" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="actividad_empleador"> ACTIVIDAD EMPLEADOR:</label>
                                <input class="form-control" type="text" wire:model='actividad_empleador' style="border-radius:10px;" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipoclaseaportante">  TIPO DE APORTANTE:</label>
                                <input class="form-control" type="text" wire:model='tipoclaseaportante' style="border-radius:10px;">
                            </div>
                            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipo_comercio">  CÓDIGO DE COMERCIO:</label>
                                <input class="form-control" type="text" wire:model='tipo_comercio' style="border-radius:10px;">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_afiliacion">  FECHA DE AFILIACIÓN:</label>
                                <input class="form-control" type="date" wire:model='fecha_afiliacion' style="border-radius:10px;">
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
                                        <b>Seleccionar los siguientes estados.</b> </em>
                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>

                        </div>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="tgn">ES TGN:</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <label class="mr-2">NO</label>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input wire:model="tgn" name="tgn" type="checkbox" class="custom-control-input"
                                            id="customSwitch2">
                                        <label class="custom-control-label" for="customSwitch2"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <label class="ml-2">SI</label>
                                </div>
                            </div>

                            
                            <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                <label for="tipo_institucion">ES DE TIPO:</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <label class="mr-2">Empresa</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input wire:model="tipo_institucion" name="tipo_institucion"
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="customSwitch1">
                                        <label class="custom-control-label"
                                            for="customSwitch1"
                                            style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                    </div>
                                    <label class="ml-2">Institución</label>
                                </div>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="publica"> ES:</label>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <label class="mr-2">Privada</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input wire:model="publica" name="publica"
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="customSwitch2publica">
                                        <label class="custom-control-label"
                                            for="customSwitch2publica"
                                            style="margin-left: 0.5rem; margin-right: 0.5rem;"></label>
                                    </div>
                                    <label class="ml-2">Pública</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <br>
                                        <p style="color:#1a5276" class="h5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                            <em class="ml-2">
                                                <b>Ingresar los nuevos datos a modificar de dirección.</b> </em>
                                            <spam style="color:#cb4335;"><b>*</b></spam>
                                        </p>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="direccion"> DIRECCIÓN:</label>
                                        <input class="form-control @error('direccion') is-invalid @enderror" name="direccion" id="direccion" type="text"
                                            wire:model="direccion" oninput="this.value = this.value.toUpperCase()" style="border-radius:10px;">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="numero"> NÚMERO DE PUERTA:</label>
                                        <input class="form-control @error('numero') is-invalid @enderror" name="numero" 
                                        id="numero" type="text" wire:model="numero" oninput="this.value = this.value.toUpperCase()" style="border-radius:10px;">
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="zonaId">ZONA:</label>
                                        <select wire:model="zonaId" name="zonaId" id="zonas_id" class="form-control @error('zonaId') is-invalid @enderror select2 "
                                            style="width: 100% ;border-radius:10px;" tabindex="-1">
                                            <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                            </option>
                                            @foreach ($zonasSelect as $zonaSelected)
                                                <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="telefono"> TÉLEFONO:</label>
                                        <input class="form-control @error('telefono') is-invalid @enderror" 
                                        name="telefono" id="telefono" type="numeric" wire:model="telefono" style="border-radius:10px;">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="correo">CORREO ELÉCTRONICO:</label>
                                        <input class="form-control @error('correo') is-invalid @enderror" name="correo" id="correo" 
                                        type="email" wire:model="correo" style="border-radius:10px;">
                                        @error('correo')
                                            @if ($message == 'validation.required')
                                                <span class="form-text text-danger">Ingrese el correo porfavor.</span>
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
                                                <button class="btn btn-primary" type="button" data-toggle="collapse"
                                                    data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                                    <i class="fa fa-street-view" aria-hidden="true"></i> Agregar Ubicación
                                                </button>

                                                <input type="text" disabled wire:model="ubicacionTexto"  
                                                style=" background-color: white;
                                                    border-color: white;  border-style: solid;"> 


                                                <div  id="collapseExample" wire:ignore>
                                                        @include('livewire.Aportes.institucion.ubicacion_institucion')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </x-big-component>
</div>
    <script>
    window.addEventListener('modalEditarInsitucionExt', event => {

        $('#editarModalExt').modal('show');
    });



    </script>
