<div>
    <!-- MODAL PARA REGISTRAR TODOS LOS DATOS-->
    <x-big-component id="modalInstitucion" labelledby="modalInstitucionLabel" title="AGREGAR CERTIFICADO" saveAction="guardarCertificadoAfi()">

        <div class="row">
             
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class="card-body">
                         
                        <div class="row">
                            <div class= "col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <label for="institucion">NOMBRE INSTITUCIÓN: </label>
                                <input class="form-control" type="text" wire:model='institucion'disabled  style=" border-radius: 10px;">
                            </div>
                            <div class= "col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nit"> NIT:</label>
                                <input class="form-control" type="text" wire:model='nit' disabled  style="  border-radius: 10px;">
                            </div> 
                        </div> 
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ; text-decoration:underline;" class="h5">
                                    <em  class="ml-2" ><b>Actual Representante Legal.</b>  </em>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre"> NOMBRES:</label>
                                <input class="form-control" type="text" id="nombre" wire:model='nombre' disabled  style="  border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="ap_paterno"> AP. PATERNO:</label>
                                <input class="form-control" type="text" id="ap_paterno" wire:model='ap_paterno' disabled  style="  border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="ap_materno"> AP. MATERNO:</label>
                                <input class="form-control" type="text" id="ap_materno" wire:model='ap_materno' disabled   style="  border-radius: 10px;">
                            </div>
                        </div>
 
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci"> CARNET:</label>
                                <input class="form-control" type="text" id="ci" wire:model='ci' disabled  style="  border-radius: 10px;">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="complemento">COMPLEMENTO:</label>
                                <input class="form-control" type="text" id="complemento" wire:model='complemento'  disabled  style="  border-radius: 10px;">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_nacimiento"> FECHA NACIMIENTO:</label>
                                <input class="form-control" type="date" id="fecha_nacimiento" wire:model='fecha_nacimiento' placeholder=" Fecha Nac." disabled  style="  border-radius: 10px;">
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
                                    <em   class="ml-2" ><b>Ingresar y completar los siguientes datos.</b>  </em><spam style="color:#cb4335;"><b>*</b></spam>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nro_cite_m_a" style="color:#283747  ;"> NRO. CITE:</label>
                                <input class="form-control" type="text" id="nro_cite_m_a" wire:model='nro_cite_m_a'  disabled style=" border-radius: 10px;">
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_solicitud"> FECHA EMISIÓN:</label>
                                <input class="form-control" type="date" id="fecha_solicitud" wire:model='fecha_solicitud' wire:change="funcion_fecha" style="border-radius: 10px;">
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_validez"> FECHA VALIDEZ:</label>
                                <input class="form-control"  id="fecha_validez" wire:model='fecha_validez' style=" border-radius: 10px;" disabled>
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <strong> TIPO:   <strong>
                                    <p style="color: #196f3d;"><b> AFILIACIÓN </b></p>
                                     
                            </div>
                           
                        </div> 
                    </div>
                </div>
            </div>

        </div>


    </x-big-component>

</div>
<script>
    window.addEventListener('modalRegistrarCertificado', event => {
        $('#modalInstitucion').modal('show');
         // Para scrooll del primer modal lista de instituciones
         $('#registrarCertificado').css('overflow-y', 'auto');
    });

</script>