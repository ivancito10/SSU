<!-- MODAL EDITAR REPRESENTANTE LEGAL -->
<x-big-component id="modalAsignarUsuarioBaja" labelledby="modalAsignarUsuarioBajaLabel" title=" AGREGAR USUARIO AVISO BAJA INSTITUCIONAL"
 saveAction="saveUsuarioAvisoBaja()">
    <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <span style="text-decoration:underline;"><em> Datos Institucionales.</em>
                                    </span>
                                </b>
                            </p>
                        </div>
                    </div>
                    <div class="row">     
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span style="font-size:19px;"><b><em> {{$nombre}}</em> </b></span>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span><b>NIT: </b>{{$nit}} </span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span><b> ACRÓNIMO: </b>{{$acronimo}} </span>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span><b>   APORTANTE: </b>{{$tipoclaseaportante}} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <p style="background-color:#21618c  ; color:white; border-radius:10px; "> ACTUALIZAR USUARIO </p>
                        </div>
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em>
                                    <b>Ingrese el carnet de la nueva persona.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                        <!-- Aquí va el botón con icono de lupa -->
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">
                            <br>
                            <button class="btn btn-primary" type="submit" wire:click="searchPersonaUsuarioBaja">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            </button>
                            <input class="form-control" type="search" wire:model="ciUsuarioBusqueda"
                                style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                placeholder="Ingresa el carnet ">

                            <input class="form-control" type="search" wire:model="complementoUsuarioBusqueda"
                                style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;" placeholder=" complemento">
                        </div>

                        <div wire:loading>
                            <div class="spinner-grow text-warning" role="status">

                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>
                        
                        
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <label for="nombre"> NOMBRE COMPLETO: </label>
                            <input id="nombre" class="form-control" type="text" wire:model='nombreCompletoAsignadoBaja'  disabled  style=" height: 25px; border-radius: 10px; background-color:#f2f4f4 ; border-color:white;">
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                            <br>
                            <label for="ci"> CARNET:</label>
                            <input id="ci" class="form-control" type="text" wire:model='carnetAsignadoBaja'  disabled  style=" height: 25px; border-radius: 10px; background-color:#f2f4f4 ; border-color:white;">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                            <label for="usuarioAsignadoBaja"> USUARIO:</label>
                            <input id="usuarioAsignadoBaja" class="form-control" type="text" wire:model='usuarioAsignadoBaja'  disabled  style=" height: 25px; border-radius: 10px; background-color:#f2f4f4 ; border-color:white;">
                        </div>

                    </div>
                </div>

            </div>
            
            

            
        </div>

    </div>
    
</x-big-component>
<script>
    window.addEventListener('modalAsignarUsuarioAvisoBaja', event => {
        $('#modalAsignarUsuarioBaja').modal('show');
    });
</script>