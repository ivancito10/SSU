<x-ver-modal id="verFormaPagoExt" labelledby="verFormaPagoExtLabel" title=" FORMA DE PAGO REGISTRADO ">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card h-100" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                    <p style="background-color:#1a5276 ; color:white; border-radius:10px;"> PLANILLA REGISTRADA</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span><b> INSTITUCIÓN:</b> {{$nombre_institucion}}</span><br>
                                    <span><b> NIT:</b> {{$nit}}</span>
                                </div>
                                
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class=" form-group row">
                                        <label for="staticEmail" class="col-sm-6 col-form-label">PLANILLA DE TIPO:
                                        </label>
                                        <div class="col-sm-4">
                                            @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                                                <input type="text" class="form-control-plaintext" id="staticEmail"
                                                    value="MENSUAL" disabled>
                                            @elseif ($reintegro == true)
                                                <input type="text" class="form-control-plaintext" id="staticEmail"
                                                    value="REINTEGRO" style="" disabled>
                                            @elseif($beneficio_social == true)
                                                <input type="text" class="form-control-plaintext" id="staticEmail"
                                                    value="BENEFICIO SOCIAL" disabled>
                                            @elseif($planilla_adicional == true)
                                                <input type="text" class="form-control-plaintext" id="staticEmail"
                                                    value="PLANILLA ADICIONAL" disabled>
                                            @elseif($retroactivo == true)
                                                <input type="text" class="form-control-plaintext" id="staticEmail"
                                                    value="RETROACTIVO" disabled>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for= "nombre_mes"> MES:</label>
                                    <input class="form-control" type="text" id="nombre_mes"wire:model='nombre_mes'
                                        disabled style=" height: 30px; border-radius: 10px;">
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for= "gestion"> GESTIÓN:</label>
                                    <input class="form-control" type="text" id="gestion" wire:model='gestion'
                                        disabled style=" height: 30px; border-radius: 10px;">

                                </div>

                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <label for="total_salario_cotizable_m"> TOT. SAL. COTIZABLE: </label>
                                    <input class="form-control" type="text" wire:model='total_salario_cotizable_m' disabled
                                        style=" height: 30px; border-radius: 10px; text-align:right;">
                                    <br>
                                </div>
                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                    <br>
                                    <label for="importe"> IMPORTE: </label>
                                    <input class="form-control" type="text" wire:model='importe' disabled
                                        style=" height: 30px; border-radius: 10px; text-align:right;">

                                </div>
                                @if($total_compensacion != 0)
                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                                </div>
                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="total_compensacion">COMPEN. DE SALARIO:</label>
                                    <input class="form-control" type="text" wire:model='total_compensacion' disabled
                                        style=" height: 30px; border-radius: 10px; text-align:right;">
                                </div>
                                @endif



                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>

                                </div>
                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="aporte_a_liquidar" style="color:#34495e;"> APORTE A PAGAR: </label>
                                    <input class="form-control" id="aporte_a_liquidar" type="text"
                                        wire:model='aporte_a_liquidar' disabled
                                        style=" height: 30px; border-radius: 10px; text-align:right;">

                                </div>

                            </div>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                    <p style="color:#1f618d; text-decoration:underline;" class="h5">
                                        <b><em>  Datos de Forma  de Pago.</em> </b></p>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong> FECHA DE PAGO:</strong>
                                    <p class="p-1"
                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                        {{$fecha_pago}} </p>

                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong> NRO. DOCUMENTO:</strong>
                                    <p class="p-1"
                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                        {{$nro_pago}} </p>

                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <strong> TIPO DEPÓSITO:</strong>
                                    <p class="p-1"
                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                        {{$tipo_pago}} </p>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label>  TOTAL PAGADO:</label>
                                    <p class="p-1"
                                        style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                                        {{$forma_pago_c}}  <b>Bs.</b></p>
                                </div>
                            </div>
                            
                            
                            <div class="row">
                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                    <div class="row">
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label> MONTO EXCEDENTE: </label>
                                            <p class="p-1"
                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                                                {{$monto_excedente}} <b> Bs.</b></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label> DESCRIPCIÓN: </label>
                                            <p class="p-1"
                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                                {{$demasia_descripcion}} </p>
                                        </div>
                                    </div>
                                </div>

                                <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                    <div class="row">
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label> MONTO DEDUCCIÓN: </label>
                                            <p class="p-1"
                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; text-align:right;">
                                            {{$monto_deduccion}} <b> Bs.</b></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                            <label> DESCRIPCIÓN: </label>
                                            <p class="p-1"
                                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                            {{$deduccion_descripcion}} </p>
                                        </div>
                                    </div>
                                </div>
                                


                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                
                                    <button class="btn btn-info" wire:click='abrir_file_pago_ext'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"/>
                                    </svg>
                                            <b>ABRIR PDF REGISTRADO </b></button>
                                </div>

                            </div>




                        </div>
                    </div>

                    
                </div>
            </div>

        </div>
    </div>
</x-ver-modal>


<script>
    window.addEventListener('modalVerFormaPago', event => {    
        $('#verFormaPagoExt').modal('show');     
    });

</script>
