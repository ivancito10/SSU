<!-- MODAL EDITAR REPRESENTANTE LEGAL -->
<x-small-modal id="ModalEditRep" labelledby="ModalEditRepLabel" title="AGREGAR REPRESENTANTE LEGAL" saveAction="saveEditRep()">
    
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">

                    <div class="row" style="background-color:white;">
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span><b> INSTITUCIÓN: </b> {{$nombre}} </span>
                        </div>
                    </div> 
                    <br>  
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
                    <br>

               
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <p style="background-color:#21618c  ; color:white; border-radius:10px;"> ACTUAL REPRESENTANTE LEGAL</p>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg> <em><b>Ingrese el carnet del nuevo rep. legal.</b> </em><spam style="color:#cb4335;"><b>*</b></spam>  </p></p>
                        </div>

                        <!-- Aquí va el botón con icono de lupa -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">
                            
                            <button class="btn btn-primary" type="submit" wire:click="nuevoRep">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            </button>
                            <input class="form-control" type="search" wire:model="ci_b"
                                style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                placeholder="Ingresa el carnet ">

                            <input class="form-control" type="search" wire:model="complemento_b"
                                style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;" placeholder=" complemento">

                        </div>
                        <div wire:loading>
                            <div class="spinner-grow text-warning" role="status">

                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>
                        

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombres"> NOMBRES: </label>
                            <input id="nombres" class="form-control" type="text" wire:model='nombres'    style=" height: 30px; border-radius: 10px;">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="p_apellido"> AP. PATERNO:</label>
                            <input id="p_apellido" class="form-control" type="text" wire:model='p_apellido'    style=" height: 30px; border-radius: 10px;">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="s_apellido"> AP. MATERNO:</label>
                            <input id="s_apellido" class="form-control" type="text" wire:model='s_apellido'    style=" height: 30px; border-radius: 10px;">
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            
                            <label for="ci"> CARNET:</label>
                            <input id="ci" class="form-control" type="text" wire:model='ci'    style=" height: 30px; border-radius: 10px;">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            
                            <label for="complemento"> COPL.:</label>
                            <input id="complemento" class="form-control" type="text" wire:model='complemento'    style=" height: 30px; border-radius: 10px;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_nacimiento"> FECHA DE NACIMIENTO:</label>
                            <input id="fecha_nacimiento" class="form-control" type="date" wire:model='fecha_nacimiento'    style=" height: 30px; border-radius: 10px;">
                        
                        </div>

                    </div>
                    
                    

                </div>

            </div>
       

    
    

            
            

        
    
</x-small-modal>
<script>
    window.addEventListener('modalEditarRepresentante', event => {
        $('#ModalEditRep').modal('show');
    });

    
</script>