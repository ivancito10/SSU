<x-big-component id="editarModalSeguro" labelledby="editarModalSeguroLabel" title="MODIFICAR SEGURO DEL INTERIOR"
    saveAction="guardarEdicion()">

    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>INSTITUCIÓN SEGURO DEL INTERIOR</b></p>
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" style="text-align:left;">
                            <p style="background-color:#e5eff9  ; color:#1e5886; ">
                            <b> INSITUCIÓN - SEGURO DEL INTERIOR</b> 
                            </p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_insti">  INSTITUCIÓN:</label>
                            <input id="nombre_insti" class="form-control" disabled wire:model=nombre_seguro
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="acronimo"> ACRÓNIMO:</label>
                            <input id="acronimo" class="form-control" disabled wire:model="sigla_seguro"
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="num_empleador"> NUM.  EMPLEADOR:</label>
                            <input id="num_empleador" class="form-control" name="num_empleador"  disabled
                                wire:model="num_empleador"
                                style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="nit"> NIT:</label>
                            <input id="nit" class="form-control" name="nit"  disabled wire:model="nit"
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>UBICACIÓN - DATOS REFERENCIALES</b></p>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                                <em   class="ml-2" ><b>Actualizar datos referenciales.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="direccion"> DIRECCIÓN:</label>
                            <input id="direccion" class="form-control" wire:model="direccion">
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="zonas_id">ZONA:</label>
                            <select wire:model="zonaId" name="zonaId" id="zonas_id" class="form-control select2 "
                                style="width: 100%" tabindex="-1">
                                <option selected="selected" value="{{ $zonaId }}"> {{ $zona }}
                                </option>
                                @foreach ($zonasSelect as $zonaSelected)
                                <option value="{{ $zonaSelected->id }}">{{ $zonaSelected->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_viv"> NRO VIVIENDA:</label>
                            <input id="nro_viv" class="form-control" wire:model="nro_vivienda">
                        </div>

                    </div>
                    <div class="row">
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="telefono"> TÉLEFONO REFERENCIA:</label>
                            <input id="telefono" class="form-control" type="numeric" wire:model="telefono">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fax"> FAX:</label>
                            <input id="fax" class="form-control" type="numeric" wire:model="fax">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="correo"> CORREO ELÉCTRONICO:</label>
                            <input class="form-control" name="correo" id="correo" type="email" wire:model="correo">

                        </div>

                    </div>

                </div>
                <br>

            </div>

        </div>
        <div class="col-lg-6">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>USUARIO ENCARGADO</b></p>
                </div>
                <div class="card-body">
                    

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_rep"> NOMBRES:</label>
                            <input class="form-control"  id="nombre_rep" disabled wire:model=nombres
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="paterno"> AP. PATERNO:</label>
                            <input class="form-control"  id="paterno" disabled wire:model="p_apellido"
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="materno"> AP. MATERNO:</label>
                            <input class="form-control"  id="materno" disabled wire:model="s_apellido"
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="carnet"> CARNET:</label>
                            <input class="form-control"  id="carnet" disabled wire:model=ci
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="complemento"> COMPL.:</label>
                            <input class="form-control"  id="complemento" disabled wire:model="complemento"
                            style=" height: 30px; background-color: #f8fcff;">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="num_empleador"> FECHA NACIMIENTO:</label>
                            <input class="form-control"  id="num_empleador" disabled
                                wire:model="fecha_nacimiento"
                                style=" height: 30px; background-color: #f8fcff;">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        
                            <p style="color:#2471a3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                                <em   class="ml-2" ><b>Ingrese los campos para la búsqueda.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Aquí va el botón con icono de lupa -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">
                            
                            <button class="btn btn-primary" type="submit" wire:click="search_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </button>
                            <input class="form-control" type="search" wire:model="ci_b"
                                style="width: calc(100% - 20px); margin-left: 5px;"
                                placeholder="Ingresa el carnet de identidad">

                            <input class="form-control" type="search" wire:model="complemento_b"
                                style="width: calc(100% - 20px); margin-left: 5px;"
                                placeholder="Ingresa el complemento">


                        </div>
                        
                        <div wire:loading>
                            <div class="spinner-grow text-warning" role="status">
                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>

                        
                    </div>

                </div>
                <br><br>

            </div>

        </div>

    </div>





</x-big-component>
<script>
    window.addEventListener('modalEditarSeguro', event => {

        $('#editarModalSeguro').modal('show');
    });
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
                Livewire.dispatch('actualizarConveniosInterior');
                $('#editarModalSeguro').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

                $('#editarModalSeguro').modal('hide');
            }
        });
    }

    document.addEventListener('SeguroInteriorModificado', function () {
        $('#editarModalSeguro').modal('hide');
        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "Se los datos del Seguro del Interior!!!",
            icon: "success"

        });
    });
    document.addEventListener('SeguroInteriorError', function () {
        $('#editarModalSeguro').modal('hide');
        Swal.fire({
            title: "<b>Error</b>",
            text: "Se produjo un error no se pudo validar!!!",
            icon: "error"

        });
    });
</script>


