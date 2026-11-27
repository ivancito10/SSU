<x-ver-modal id="preliqModal" labelledby="preliqModalLabel" title=" EVALUAR PRE - LIQUIDACIÓN">
    <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            <strong>INSTITUCIÓN O RAZÓN SOCIAL DEL EMPLEADOR:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nombre_institucion }} </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>NIT/C.I.:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nit }} </p>
                        </div>

                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center; ">
                            <p style="background-color:1a5276   ; color:white; border-radius:10px;">ACTUAL
                                PLANILLA</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>MES:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nombre_mes }} </p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> AÑO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $gestion }} </p>
                        </div>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> TOTAL SALARIO COTIZABLE:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $total_salario_cotizable_m }} </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="h6"> <b> PLANILLA: </b></span>
                            @if ($estado_comprobante == 'SUBIDO')
                                <span style="color:#b21d0b;" class="h6"><b>{{ $estado_comprobante }}</b>
                                </span>
                            @elseif($estado_comprobante == 'VALIDADO')
                                <span style="color:#e67e22  ;" class="h6"><b>{{ $estado_comprobante }}</b> </span>
                            @elseif($estado_comprobante == 'REGISTRADO')
                                <span style="color:#2874a6   ;" class="h6">
                                    <b>{{ $estado_comprobante }}</b></span>
                            @elseif($estado_comprobante == 'LIQUIDADO')
                                <span style="color:#1a5819  ;" class="h6"><b>{{ $estado_comprobante }}</b> </span>
                            @endif
                        </div>
                    </div>

                </div>

                <!--FORMULARIO PARA EL BOTON-->
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <p style="color:#1a5276" class="h5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                                <em class="ml-2">
                                    <b>Ingresar los siguientes campos para evaluar la liquidación.</b> </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                        </div>

                    </div>
                    <div class="row">
                        <div class='col-lg-9 col-md-12 col-sm-12 col-xs-12'>
                            <label for="demasia_descripcion">DESCRIPCIÓN DEMASIA:</label>
                            <input wire:model="demasia_descripcion" name="demasia_descripcion" id="demasia_descripcion"
                                type="text" class="form-control" placeholder=" Introduzca la descripción"
                                style="border-radius: 10px;">
                        </div>
                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <label for="monto_demasia">MONTO DEMASIA:</label>
                            <input wire:model="monto_demasia" name="monto_demasia" id="monto_demasia" type="number"
                                step="0.01" class="form-control" placeholder="0.00" style="border-radius: 10px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class='col-lg-9 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="deduccion_descripcion">DESCRIPCIÓN DEDUCCIÓN:</label>
                                    <input wire:model="deduccion_descripcion" name="deduccion_descripcion"
                                        id="deduccion_descripcion" type="text" class="form-control"
                                        placeholder=" Introduzca la descripción" style="border-radius: 10px;">
                                </div>
                                <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                    <label for="monto_deduccion">MONTO DEDUCCIÓN:</label>
                                    <input wire:model="monto_deduccion" name="monto_deduccion"
                                        id="monto_deduccion" type="number" step="0.01" class="form-control"
                                        placeholder="0.00" style="border-radius: 10px;">
                                </div>

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <label for="presenta_planilla">¿PRESENTÓ PLANILLA?</label>
                            <div class="col-sm-10 d-flex align-items-center" style=" justify-content: center;">
                                <label class="mr-2">NO</label>
                                <div class="custom-control custom-switch ">
                                    <input wire:model="presenta_planilla" name="presenta_planilla" type="checkbox"
                                        class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">SI</label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for= "fecha_pago">FECHA PAGO:</label>
                            <input type="date" class="form-control" name="fecha_pago" id="fecha_pago"
                                wire:model='fecha_pago' style="border-radius: 10px;">

                            @error('fecha_pago')
                                @if ($message == 'validation.required')
                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                @else
                                    <span class="form-text text-danger">{{ $message }}</span>
                                @endif
                            @enderror
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <x-agregar-button wire:click="pre_liquidacion">EVALUAR</x-agregar-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--Acá se muestra la pre liquidacion-->
    <div id="ver_preliquidacion" style="display:none;">
        <!--COMPROBANTE -->
        <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                        <p style="background-color:white; color:#1f618d;" class="h5">
                            <b>
                                <spam style="text-decoration:underline;"><em>Datos Evaluados.</em> </spam>
                            </b>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                        <label for="m_fecha_pago"> PARA LA FECHA: </label>
                        <input class="form-control" id="m_fecha_pago" type="date" wire:model='m_fecha_pago'
                            disabled style="border-radius: 10px; height:30px;">
                    </div>
                </div>
                <br>
                <h6 style="color:#34495e ;">A.<b>LIQUIDACIÓN DE APORTES</b> </h6>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff6fb ;">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered bordered-black">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 200px;">APORTE</th>
                                        <th scope="col" style="width: 200px;">TASA</th>
                                        <th scope="col" style="width: 200px;">FACTOR ACTUAL</th>
                                        <th scope="col" style="width: 200px;">T. SALARIO COTIZABLE</th>
                                        <th scope="col" style="width: 200px;">IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">
                                            <textarea type="text" class="form-control" disabled wire:model='m_tipo_clase'
                                                style="resize:none; background-color:white;"></textarea>
                                        </th>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model='m_tasa_clase'
                                                style="height:30px; background-color:white;">
                                        </td>
                                        <td style="width: 200px;"></td>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model='m_total_salario_cotizable' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model='m_importe_aporte_patronal'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100px;"><b>EXCEDENTES</b></td>
                                        <td colspan="3" class="text-right" style="width: 800px;"><input
                                                type="text" class="form-control" disabled
                                                wire:model = 'demasia_descripcion'
                                                style="height:30px; background-color:white;"></td>
                                        <td style="width: 220px;"><input type="text" class="form-control" disabled
                                                wire:model = 'm_monto_demasia'
                                                style="height:30px; background-color:white;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 200px;">ACTUALIZACIÓN APORTE</th>
                                        <td style="width: 200px;"></td>
                                        <td style="width: 200px;"><input type="number" class="form-control"
                                                step="0.000001" disabled wire:model = 'factor_activo'
                                                placeholder = '-' style="height:30px; background-color:white;"></td>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model = 'm_actualizacion_aporte' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model = 'm_importe_actualizacion_aporte' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right" style="width: 200px;"
                                            style="background-color: #d0d3d4;"><b>SUB
                                                TOTAL (a)</b></td>
                                        <td style="width: 200px;" style="background-color: #d0d3d4;"><input
                                                type="text" class="form-control" disabled
                                                wire:model = 'm_subtotal_liquidacion' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <h6 style="color:#34495e  ;">B.<b>RECARGOS DE LEY</b> </h6>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff6fb ;">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">TASA</th>
                                        <th scope="col">DIAS DE MORA</th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>INTERESES</th>
                                        <td><input type="text" class="form-control" disabled wire:model = 'm_tasa'
                                                placeholder = '-' style="height:30px; background-color:white;"></td>
                                        <td><input type="number" class="form-control" disabled
                                                wire:model = 'dias_mora' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model = 'm_subtotal_liquidacion' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model = 'm_intereses' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    <tr>
                                        <th>MULTAS S/C INTERESES</th>
                                        <td>10 %</td>
                                        <td></td>
                                        <td></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model = 'm_multas_sin_intereses' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    <tr>
                                        <th>MULTAS NO PRES. PLANILLA</th>
                                        <td>1 %</td>
                                        <td></td>
                                        <td></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model = 'm_multas_no_planilla' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-right"><b>SUB TOTAL (b)</b></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model = 'm_subtotal_recargos'placeholder='-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <h6 style="color:#34495e  ;">C.<b>DEDUCCIONES</b> </h6>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:#eff6fb ;">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><b>DEDUCCIONES</b></td>
                                        <td colspan="2" style="width: 700px;"> <input type="text"
                                                class="form-control" disabled wire:model = 'deduccion_descripcion'
                                                style="height:30px; background-color:white;">
                                        </td>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model ='m_monto_deduccion'
                                                style="height:30px; background-color:white;">
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td><b>MINISTERIO DE SALUD</b></td>
                                        <td colspan="2" style="width: 700px;"><input type="text"
                                                class="form-control" disabled wire:model = 'descripcion'
                                                style="height:30px; background-color:white;"></td>
                                        <td style="width: 200px;"><input type="text" class="form-control" disabled
                                                wire:model ='m_ministerio' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    

                                    <tr>
                                        <td colspan="3" class="text-right"><b>SUB TOTAL (c)</b></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model = 'm_subtotal_deducciones' placeholder = '-'
                                                style="height:30px; background-color:white;"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right"><b>TOTAL:</b></td>
                                        <td><input type="text" class="form-control" disabled
                                                wire:model ='m_total_a_b_c'
                                                style="height:30px; background-color:#fdfef2;"></td>
                                    </tr>
                                    <tr>
                                        <td><b>TOTAL(Literal):</b></td>
                                        <td colspan="3"><input type="text" class="form-control" disabled
                                                wire:model ='totalLiteral'
                                                style="height:30px; background-color:#fdfef2;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-ver-modal>
