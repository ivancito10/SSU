<div wire:ignore>
    <!--MODAL para editar-->
    <x-big-component id="exampleModal" labelledby="exampleModalLabel" title=" MODIFICAR INSTITUCIÓN"
        saveAction="saveEditInsti()">

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7" style="background-color:white ;">
                        <div class="row">
                            <div class="col-lg-12" style="text-align:center; ">
                                <p style="background-color:#1a5276 ; color:white; "> INFORMACIÓN GENERAL.</p>
                            </div>
                            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre"> INSTITUCIÓN</label>
                                <input id="nombre" class="form-control" type="text" wire:model='nombre' disabled  style=" height: 30px; background-color: #f8fcff;">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="nit">NIT</label>
                                <input id="nit" class="form-control" type="text" wire:model='nit' disabled  style=" height: 30px; background-color: #f8fcff;">
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                <label for="acronimo"> ACRÓNIMO</label>
                                <input id="acronimo" class="form-control" type="text" wire:model='acronimo' disabled  style=" height: 30px; background-color: #f8fcff;">
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="num_empleador"> NUM. EMPLEADOR</label>
                               
                                <input id="num_empleador"class="form-control" type="text" wire:model='num_empleador' disabled  style=" height: 30px; background-color: #f8fcff;">
                            </div>


                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="tipoclaseaportante">  APORTANTE</label>
                                <input id="tipoclaseaportante"class="form-control" type="text" wire:model='tipoclaseaportante' disabled  style=" height: 30px; background-color: #f8fcff;">
                            </div>

                            <div class='col-lg-2 col-md-12 col-sm-12 col-xs-12'>
                                <strong>ENTIDAD</strong>
                                @if($estado ==true)
                                <p style="color: #145a32;"><b>ACTIVO</b></p>
                                @else
                                <p style="color: #c0392b;"><b>INACTIVO</b></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-12" style="text-align:center;">
                                <p style="background-color:#c0392b  ; color:white; ">
                                     ACTUALIZAR TGN.</p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg> <em><b>Cambiar el estado de tgn.</b> </em>  <spam style="color:#cb4335;"><b>*</b></spam> </p>
                            </div>
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                <strong>TGN:</strong>
                                <div class="col-sm-10 d-flex align-items-center">
                                    <label class="mr-2">Deshabilitar</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input wire:model="tgn" name="tgn" type="checkbox"
                                            class="custom-control-input" id="customSwitch2">
                                        <label class="custom-control-label" for="customSwitch2"
                                            style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                    </div>
                                    <label class="ml-2">Habilitar</label>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <br><br>
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="row">
                                    <div class="col-lg-12" style="text-align:center;">
                                        <p style="background-color:#d35400 ; color:white; ">
                                        
                                        ACTUALIZAR DIRECCIÓN.</p>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg> <em><b>Ingrese los nuevos datos a modificar.</b></em>  <spam style="color:#cb4335;"><b>*</b></spam> </p>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="direccion"> DIRECCIÓN</label>
                                        <input id="direccion" class="form-control"  type="text"
                                            wire:model="direccion" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="numero"> NÚMERO DE PUERTA</label>
                                        <input class="form-control"  id="numero" type="text"
                                            wire:model="numero" oninput="this.value = this.value.toUpperCase()">
                                    </div>

                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <label for="zonas_id">ZONA</label>
                                        <select wire:model="zonaId" id="zonas_id" 
                                            class="form-control select2 "
                                            style="width: 100%" tabindex="-1">
                                            <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                            </option>
                                            @foreach ($zonasSelect as $zonaSelected)
                                                <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <label for="telefono"> TÉLEFONO</label>
                                        <input class="form-control"  id="telefono" type="numeric"
                                            wire:model="telefono">
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="correo"> CORREO ELÉCTRONICO</label>
                                        <input class="form-control" name="correo" id="correo" type="email"
                                            wire:model="correo">
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
                            <div class="col-lg-7" style="background-color:white;">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" type="button" data-toggle="collapse"
                                            data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            <i class="fa fa-street-view" aria-hidden="true"></i> Agregar Ubicación
                                        </button>

                                        <input type="text" disabled wire:model="ubicacionTexto"  
                                        style=" background-color: white;
                                            border-color: white;  border-style: solid;  "> 
                                        <div class="collapse" id="collapseExample" wire:ignore >
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
    window.addEventListener('modalEditarInstitucion', event => {

        $('#exampleModal').modal('show');
    });
</script>
