<div>
    <x-ver-modal id="vistaModalAvisoBaja" labelledby="vistaModalAvisoBajaLabel" title="VISTA   AVISO DE BAJA "> 
         
            <div class="row">
                <div class="col-lg-5 col-md-12 col-sm-12 col-12">
                    <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Aportante registrado.</em>
                                        </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nombre_completo">NOMBRE COMPLETO: </label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nombre_completo }} </p>

                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="ci">CARNET: </label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $ci }} </p>

                            </div>
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="matricula_seguro">MATRICULA: </label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $matricula_seguro }} </p>

                            </div>

                        </div>
                    </div>
                    

                </div>

                <div class="col-lg-7 col-md-12 col-sm-12 col-12">
                    <div class="card p-3" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276; color:white; border-radius:10px; ">DETALLE DEL REGISTRO </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_baja">FECHA BAJA: </label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $fecha_baja }} </p>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="motivo_baja">MOTIVO BAJA:</label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $motivo_baja }} </p>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="ultimo_salario">ÚLTIMO SALARIO: </label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $ultimo_salario }} </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                <label for="descripcion">DESCRIPCIÓN:</label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $descripcion }} </p>
                            </div>
                            <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_solicitud">FECHA SOLICITUD (REGISTRO): </label>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $fecha_solicitud }} </p>
                            </div>
                            
                        </div>


                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <span class="h6"> <b>ESTADO: </b>
                                @if($estado_validado == true && $anulado == null)
                                    <span ><b style="color: #1e8449;">VALIDADO</b></span>
                                @elseif($estado_validado == false && $anulado == null)  
                                    <span ><b style="color: #e67e22 ;">PENDIENTE</b></span>
                                @elseif($anulado == true)
                                    <span ><b style="color: #c0392b ;">ANULADO</b></span>  
                                @endif</span>
                            </div>
                            
                        </div>
                        
                        @if($estado_validado)
                        <br>
                        <div class="row">
                            <div class='col-lg-12 col-md-12 col-sm-12 col-12'>
                                <p class="m-0"><b>VALIDADO POR:  </b> {{ $usuario_valida }} </p>
                                <p class="m-0"><b>FECHA VALIDACIÓN: </b> {{ $fecha_validacion }} </p>
                            </div>
                             
                        </div>
                        @endif
                    </div>
                </div>

            </div>

             
        
                 
         
    </x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerAvisoBaja', event => {
        $('#vistaModalAvisoBaja').modal('show');
    });
</script>