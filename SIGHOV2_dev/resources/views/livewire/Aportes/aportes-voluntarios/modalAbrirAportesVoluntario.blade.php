<div>
    <x-ver-modal id="aportantesModal" labelledby="aportantesModalLabel" title="VISTA DE APORTE REGISTRADO">
                
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" >
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center; ">
                                <p style="background-color:#1a5276; color:white; border-radius:10px;">APORTANTE VOLUNTARIO</p>
                            </div>
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong> NOMBRE COMPLETO:</strong>
                                <p>{{$nombres}} {{$pApellido}} {{$sApellido}} </p>
                                    
                            </div>
                             
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>CARNET: </b>{{$ci}} {{$complemento}} </span>
                                
                            </div>


                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>FECHA DE NACIMIENTO:</b> {{$fechaNacimiento}} </span>
                               
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                <b> <spam style="text-decoration:underline;"><em>Anterior Comprobante Liquidado.</em> </spam></b>
                                </p>
                            </div>
                        </div>
                        <div class ="row">
                            
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                <span><b>NRO. COMPROBANTE: </b> {{$nroComproAnterior}} </span>
                                
                            </div>
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                 <span><b> APORTE PAGADO: </b> <em> {{$totalComproAnterior}}</em> Bs. </span>
                            </div>
                            <!-- <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                 <span><b> LUGAR DE PAGO: </b> LA PAZ </span>
                            
                            </div> -->

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                 <span><b>NRO. COMPROBANTE: </b> {{$nroComprobante}} </span>
                            
                            </div>

                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <span><b>MES: </b> {{$mesNombre}} </span>
                            </div>


                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                 <span><b> GESTIÓN: </b> {{$gestion}} </span>
                            
                            </div>
                            <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                                 <span><b>   FECHA LIQUIDACIÓN:</b>  {{ \Carbon\Carbon::parse($fechaLiquidacion)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <span><b> TOTAL SALARIO COTIZABLE: </b><em> {{$totalGanado}} </em> <b>Bs.</b></span>
                            </div>
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                  <span><b> IMPORTE: </b><em> {{$totalAporte}}</em> <b>Bs.</b> </span>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                                        <div class="row">
                                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                  <span><b> MONTO EXCEDENTE: </b><em>{{$montoDemasia}}</em>  </span>
                                            </div>
                                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                  <span><b>DESCRIPCIÓN: </b> {{$demasiaDescripcion}} </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                                 <span><b>MONTO DEDUCCIÓN:  </b> <em>{{$montoDeduccion}}</em> </span>
                                            </div>
                                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                                 <span><b>DESCRIPCIÓN: </b> {{$deduccionDescripcion}} </span>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class= "col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                 <span><b>TOTAL LIQUIDADO: </b> <em>{{$montoTotalPago}}</em> <b>Bs.</b> </span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                <b> <spam style="text-decoration:underline;"><em>Actual Depósito Registrado.</em> </spam></b>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <span><b> FECHA DE PAGO:</b>  {{ \Carbon\Carbon::parse($fechaPago)->format('d/m/Y') }} </span>
                            </div>

                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'> 
                                 <span><b> TIPO DEPÓSITO: </b> {{$tipoPago}} </span>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'> 
                                <span><b>  NRO DEPÓSITO: </b> {{$nroFormaPago}} </span>
                            </div>
                            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'> 
                                     <span><b>   TOTAL DEPOSITADO: </b> <em> {{$montoTotalPago}}</em> <b>Bs.</b></span>
                                <br>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
                    
    </x-ver-modal>

</div>
<script>
    window.addEventListener('modalAporteVoluntario', event => {
        $('#aportantesModal').modal('show');
    });
</script>