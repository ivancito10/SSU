<div>
    <!--MODAL PARA ABRIR INSTITUCIÓN -->
        <x-big-component id="vistaExtracto" labelledby="vistaModalLabel" title=" DESCARGAR REPORTE INSTITUCIÓN" saveAction="guardarExtracto()">
                
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                   
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Afiliado registrado.</em>
                                        </spam>
                                    </b>
                                </p>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                
                                <span><b>INSTITUCIÓN: </b> {{$nombre_institucion}} </span>
                                
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <span><b>NIT: </b>{{$nit}} </span>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <span><b>APORTANTE: </b>{{$tipoclaseaportante}} </span>
                            <br>
                            </div>
                        </div>
                        <br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                
                                <p style="color:#1a5276 ;" class="h5"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg> <em><b>Seleccione el rango de fechas  para obtener el extracto de la institución.</b>
                                </em> <spam style="color:#cb4335;"><b>*</b></p>
                            </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_inicial"> FECHA INICIO:</label>
                                <input id="fecha_inicial" class="form-control" type="date"  wire:model="fecha_inicial" style="border-radius: 10px;">
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" >
                                <label for="fecha_final"> FECHA FIN:</label>
                                <input id="fecha_final" class="form-control" type="date" wire:model="fecha_final" style="border-radius: 10px;">
                            </div>

                        </div>
                    </div>
                </div>

                
        </x-big-component>
</div>
<script>
    window.addEventListener('modalAbrirInstitucion', event => {
        $('#vistaExtracto').modal('show');
        
    });

</script>