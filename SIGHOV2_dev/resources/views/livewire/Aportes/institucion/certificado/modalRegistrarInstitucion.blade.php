<div>
    <!-- MODAL PARA REGISTRAR TODOS LOS DATOS-->
    <x-big-component id="modalNuevoInstitucion" labelledby="modalNuevoInstitucionLabel" title="AGREGAR CERTIFICADO" saveAction="guardarCertificadoNoAfi()">
        <div class="row">
             
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                                    <em   class="ml-2" ><b>Ingresar datos de la institución.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class= "col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="institucion">NOMBRE INSTITUCIÓN:</label>
                                <input class="form-control" type="text" id="institución" wire:model='institucion' placeholder="Ingrese el nombre de la institución"
                                oninput="this.value = this.value.toUpperCase()" required style="border-radius: 10px;">
                            </div>
                            <div class= "col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nit"> NIT:</label>
                                <input class="form-control" type="number" id="nit" wire:model='nit' 
                                placeholder="Ingrese el nit" style="border-radius: 10px;" min="0">
                            </div>
                        </div>
                         
                        <br>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                                    <em   class="ml-2" ><b>A continuación busque al representante legal.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">
                            
                                <button class="btn btn-primary" type="submit" wire:click="search_persona">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                                </button>
                                <input class="form-control" type="search" wire:model="ci"
                                    style="width: calc(100% - 20px); margin-left: 5px; border-radius: 10px;"
                                    placeholder="Ingresa el carnet " >

                                <input class="form-control" type="search" wire:model="complemento"
                                    style="width: calc(100% - 20px); margin-left: 5px; border-radius: 10px;" placeholder=" complemento" >
                                
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div wire:loading class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="spinner-grow text-warning" role="status">
                                </div>
                                <b><i>Cargando datos espere porfavor...</i></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                
                                <label for="nombre"> NOMBRES:</label>
                                <input class="form-control" type="text" id ="nombre" wire:model='nombre' placeholder=" Nombre" 
                                oninput="this.value = this.value.toUpperCase()"   style=" border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            
                                <label for="ap_paterno"> AP. PATERNO:</label>
                                <input class="form-control" type="text" id="ap_paterno" wire:model='ap_paterno'
                                 placeholder=" Ap. Paterno" oninput="this.value = this.value.toUpperCase()"   style=" border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            
                                <label for="ap_materno"> AP. MATERNO:</label>
                                <input class="form-control" type="text" id="ap_materno" wire:model='ap_materno' 
                                placeholder=" Ap. Materno" oninput="this.value = this.value.toUpperCase()"   style=" border-radius: 10px;">
                            </div>

                        </div> 
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                
                                <label for="ci"> CARNET:</label>
                                <input class="form-control" type="text" id="ci" wire:model='ci' placeholder=" Carnet"   style=" border-radius: 10px;">
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                
                                <label for="complemento">COMPLEMENTO:</label>
                                <input class="form-control" type="text" wire:model='complemento' 
                                placeholder=" Complemento" oninput="this.value = this.value.toUpperCase()"   style=" border-radius: 10px;">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_nacimiento"> FECHA NAC.:</label>
                                <input class="form-control" type="date" id="fecha_nacimiento" wire:model='fecha_nacimiento' placeholder=" Fecha Nac."   style=" border-radius: 10px;">
                            </div>
                            <div class='col-lg-2 col-md-12 col-sm-12 col-xs-12'  wire:ignore>
                                <label for="tipo_doc">TIPO DOC.:</label>
                                <select wire:model="tipo_doc" id="tipo_doc"
                                    class="form-control select2 @error('tipo_doc') is-invalid @enderror"
                                    tabindex="-1" required style="border-radius: 10px;">
                                    <option selected="selected">Seleccione..  </option>
                                    <option value="0">NACIONAL</option>
                                    <option value="1">EXTRANJERO</option>
                                </select>

                                @error('tipo_doc')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                            <div class='col-lg-2 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                                <label for="expedido_ci">EXPEDIDO:</label>
                                <select wire:model="expedido_ci" id="expedido_ci"
                                    class="form-control select2 @error('expedido_ci') is-invalid @enderror"
                                    tabindex="-1" required style="border-radius: 10px;">
                                    <option selected="selected">Seleccione..
                                    </option>
                                    @foreach ($this->departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('expedido_ci')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                                    <em   class="ml-2" ><b>Completar los datos del certificado.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nro_cite_m_na" style="color:#2e4053  ;"> NRO. CITE: </label>
                                <input class="form-control" id="nro_cite_m_na" type="text" wire:model='nro_cite_m_na'  disabled style=" border-radius: 10px;" >
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_solicitud"> FECHA EMISIÓN:</label>
                                <input class="form-control" id="fecha_solicitud" type="date" wire:model='fecha_solicitud' wire:change="funcion_fecha" style="border-radius: 10px;">
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_validez"> FECHA VALIDEZ:</label>
                                <input class="form-control"  id="fecha_validez" wire:model='fecha_validez' disabled style="border-radius: 10px;">
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <strong> TIPO:  <strong>
                                    <p><b style="color:#cb4335;"> NO AFILIACIÓN</b></p> 
                            </div>

                        </div> 

                    </div>
                </div>

            </div>

        </div>
        
    </x-big-component>

</div>
<script>
    window.addEventListener('modalRegistrarInstitucion', event => {
        $('#modalNuevoInstitucion').modal('show');
         // Para scrooll del primer modal lista de instituciones
         $('#registrarCertificado').css('overflow-y', 'auto');
    });
</script>