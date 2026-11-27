<div>

    <!--LISTA CON  MENU DE ACCIONES  PRINCIPAL-->

    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#2471a3 ;"><b>APORTES</b> - <b style="color:#cb4335 ;"> LISTA DE PLANILLAS Y COMPROBANTES</b>
        </h4>
        <!-- Aqui va el boto agregar-->
        @livewire('Aportes.externos.aporte.aporte-institucion-register-ext')
    </div>
    <br>
 
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- Aqui va lo de el Listado-->
            @livewire('aportes.externos.aporte.aporte-institucion-table-ext')
            @include('livewire.Aportes.Externos.aporte.modalPreLiquidacion')
            @include('livewire.Aportes.Externos.aporte.modalVerPdfCopia')
            @include('livewire.Aportes.Externos.aporte.modalVerFormaPago')
            @include('livewire.Aportes.Externos.aporte.modalPdfFormaPagoExt')
            {{-- @include('livewire.Aportes.Externos.aporte.modalComunicadoAportes') --}}

            
            <x-ver-dos-modal id="aportantesModalExt" labelledby="aportantesModalExtLabel"
                title="VISTA DE APORTES ">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="5" 
                                    style="font-size: 22px;   
                                        padding-top: 0; padding-bottom: 0;">
                                         
                                    {{ $nombre_institucion }}
                                </th>
                            </tr>
                            
                            <tr style="font-size: 16px; background-color: #1a5276 ; color:white; text-align:center; 
                                        height: 20px; line-height: 20px; padding-top: 0; padding-bottom: 0;">
                                <th colspan="1"> Gestión </th>
                                <th colspan="1">   Planilla</th>
                                <th colspan="1">Total Salario Cotizable</th>
                                <th colspan="1"> Importe</th> 
                                @if ($total_compensacion > 0)
                                <th colspan="1">Comp. Salario Mínimo Nacional</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody style="font-size:19px; text-align:center;height: 20px; line-height: 20px; padding-top: 0; padding-bottom: 0;">
                            <tr>
                                <td> {{ $nombre_mes }},  {{ $gestion }}  </td>
                                <td>  
                                    @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                                    <span style="font-size:16px;">De tipo: <b><em>MENSUAL</em> </b> </span>
                                    @elseif ($reintegro == true)
                                        <span style="font-size:16px;">De tipo: <b><em>REINTEGRO</em> </b>
                                        </span>
                                    @elseif($beneficio_social == true)
                                        <span style="font-size:16px;">De tipo: <b><em>BENEFICIO SOCIAL</em>
                                            </b> </span>
                                    @elseif($planilla_adicional == true)
                                        <span style="font-size:16px;">De tipo: <b><em>PLANILLA ADICIONAL</em>
                                            </b> </span>
                                    @elseif($retroactivo == true)
                                        <span style="font-size:16px;">De tipo: <b><em>RETROACTIVO</em> </b>
                                        </span>
                                    @endif  
                                </td>
                                <td>   {{ $total_salario_cotizable_m }} <b>Bs.</b> </td>
                                <td> {{ $aporte_a_liquidar }} <b>Bs.</b>  </td>
                                @if ($total_compensacion > 0)
                                <td>  {{ $total_compensacion }} <b>Bs.</b>  </td>
                                @endif
                            </tr>
                            
                        </tbody>

                    </table>
                </div> 
 
                 
                <div>
                    <div class="card p-1" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        <div>
                            <span style="margin-left:15px;">
                                <span style="background-color:#D6EAF8; color:#2471A3; border-radius:9px; padding:2px 6px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#2471A3" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                        </svg>   
                                </span>
                                
                            </span>
                            <span><b style="margin-left:5px; font-size:18px;"> DETALLE DE APORTANTES</b></span> 
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td colspan="2" >
                                            <b style="font-size: 15px;">SUBIDO POR: </b>
                                            <span style="font-size:14px; margin-left: 6px; "> {{ $user_created_name }}
                                            <b>En fecha:</b> {{ \Carbon\Carbon::parse($fecha_user_created)?->format('d/m/Y') }} 
                                            {{ \Carbon\Carbon::parse($fecha_user_created)?->format('H:i') }} </span>
                                        </td>

                                        <td colspan="3" > 
            
                                            @if ($estado_comprobante == 'VALIDADO')
                                                
                                                    <b style="font-size: 15px;">VALIDADO POR: </b>
                                                    <span style="font-size:14px; margin-left: 6px;"> {{ $user_updated_name }} <b>En fecha:</b>
                                                        {{ \Carbon\Carbon::parse($fecha_user_updated)?->format('d/m/Y') }} 
                                                        {{ \Carbon\Carbon::parse($fecha_user_updated)?->format('H:i') }}  </span>
                                                
                                            @elseif($estado_comprobante == 'REGISTRADO')
                                                <b style="font-size: 15px;">REGISTRADO POR: </b>
                                                    <span style="font-size:14px; margin-left: 6px;"> {{ $user_updated_name }} <b>En fecha:</b>
                                                        {{ \Carbon\Carbon::parse($fecha_user_updated)?->format('d/m/Y') }} {{ \Carbon\Carbon::parse($fecha_user_updated)?->format('H:i') }} </span>
                                                
                                            @elseif($estado_comprobante == 'LIQUIDADO')
                                                <b style="font-size: 15px;">LIQUIDADO POR: </b>
                                                    <span style="font-size:14px; margin-left: 6px;"> {{ $user_updated_name }} <b>En fecha:</b>
                                                        {{ \Carbon\Carbon::parse($fecha_user_updated)?->format('d/m/Y') }}  {{ \Carbon\Carbon::parse($fecha_user_updated)?->format('H:i') }} </span>
                                            
                                            @endif
                                        </td>

                                    </tr>
                                </thead>
                            </table>
                        </div>
                                   
                         @livewire('Aportes.externos.aporte.aportantes-insti-table-ext', ['compId' => $compId])
                                
                    </div>
                </div>
            </x-ver-dos-modal>


            
            <x-ver-dos-modal id="aportantesModalTempExt" labelledby="aportantesModalTempExtLabel"
                title="VISTA DE APORTES  ">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="5" 
                                    style="font-size: 22px;   
                                        padding-top: 0; padding-bottom: 0;">
                                         
                                    {{ $nombre_institucion }}
                                </th>
                            </tr>
                            
                            <tr style="font-size: 16px; background-color: #1a5276 ; color:white; text-align:center; 
                                        height: 20px; line-height: 20px; padding-top: 0; padding-bottom: 0;">
                                <th colspan="1"> Gestión </th>
                                <th colspan="1">   Planilla</th>
                                <th colspan="1">Total Salario Cotizable</th>
                                <th colspan="1"> Importe</th> 
                                @if ($total_compensacion > 0)
                                <th colspan="1">Comp. Salario Mínimo Nacional</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody style="font-size:19px; text-align:center;height: 20px; line-height: 20px; padding-top: 0; padding-bottom: 0;">
                            <tr>
                                <td> {{ $nombre_mes }},  {{ $gestion }}  </td>
                                <td>  
                                    @if ($reintegro == false && $beneficio_social == false && $planilla_adicional == false && $retroactivo == false)
                                    <span style="font-size:16px;">De tipo: <b><em>MENSUAL</em> </b> </span>
                                    @elseif ($reintegro == true)
                                        <span style="font-size:16px;">De tipo: <b><em>REINTEGRO</em> </b>
                                        </span>
                                    @elseif($beneficio_social == true)
                                        <span style="font-size:16px;">De tipo: <b><em>BENEFICIO SOCIAL</em>
                                            </b> </span>
                                    @elseif($planilla_adicional == true)
                                        <span style="font-size:16px;">De tipo: <b><em>PLANILLA ADICIONAL</em>
                                            </b> </span>
                                    @elseif($retroactivo == true)
                                        <span style="font-size:16px;">De tipo: <b><em>RETROACTIVO</em> </b>
                                        </span>
                                    @endif  
                                </td>
                                <td>   {{ $total_salario_cotizable_m }} <b>Bs.</b> </td>
                                <td> {{ $aporte_a_liquidar }} <b>Bs.</b>  </td>
                                @if ($total_compensacion > 0)
                                <td>  {{ $total_compensacion }} <b>Bs.</b>  </td>
                                @endif
                            </tr>
                            
                        </tbody>

                    </table>
                </div> 
                 
                
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        
                    <div>
                        <span style="margin-left:15px;">
                            <span style="background-color:#D6EAF8; color:#2471A3; border-radius:9px; padding:2px 6px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#2471A3" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                    </svg>   
                            </span>
                            
                        </span>
                        <span><b style="margin-left:5px; font-size:18px;"> DETALLE DE APORTANTES</b></span> 
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="5" >
                                        <b style="font-size: 15px;">SUBIDO POR: </b>
                                        <span style="font-size:14px; margin-left: 6px; "> {{ $user_created_name }}
                                        <b>En fecha:</b> {{ \Carbon\Carbon::parse($fecha_user_created)?->format('d/m/Y') }} 
                                        {{ \Carbon\Carbon::parse($fecha_user_created)?->format('H:i') }} </span>
                                    </td>

                                    

                                </tr>
                            </thead>
                        </table>
                    </div>

                    @livewire('Aportes.externos.aporte.aportantes-insti-temp-table-ext', ['compId' => $compId])
                                
                </div>
                 
            </x-ver-dos-modal>


            <div wire:loading>
                <x-cargando-dos />
            </div>
        </div>
    </div>

</div>

<script>
    // ESTO ES PARA ESCUCHAR MODAL PRE-LIQ
    window.addEventListener('openModalPreLiq', event => {
        $('#preliqModal').modal('show');
    });

    // ESTO ES PARA ESCUCHAR MODAL DE GRUPO DE APORTANTES
    window.addEventListener('openModalAportantesExt', event => {
        $('#aportantesModalExt').modal('show');
    });
    // ESTO ES PARA ESCUCHAR MODAL DE GRUPO DE APORTANTES  tempporal
    window.addEventListener('openModalAportantesTempExt', event => {
        $('#aportantesModalTempExt').modal('show');
    });

    document.addEventListener('MostrarPreliquidacion', function() {
        var div = document.getElementById('ver_preliquidacion');
        div.style.display = 'block';
    });

    document.addEventListener('IntroduzcaFecha', function() {
        Swal.fire({
            title: "Inserte Fecha",
            text: "Se cancelo la evaluación.",
            icon: "error"
        });
        $('#preliqModal').modal('hide');
    });
    document.addEventListener('AlertUFVExt', function() {
        Swal.fire({
            title: "UFV no Actualizada!",
            text: "No se realizó el cálculo.",
            icon: "warning"
        });
        $('#preliqModal').modal('hide');
    });

        window.addEventListener('confirmarEliminarPlanilla', event => {
            const dato_mes = event.detail.dato_mes;
            const dato_tipo_planilla = event.detail.dato_tipo_planilla;
            Swal.fire({
                title: '¿Eliminar Planilla?',
                text: "Desea eliminar la planilla de tipo: " + dato_tipo_planilla +", del mes de: "+ dato_mes,
                input: "text",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'No, cancelar',
                showLoaderOnConfirm: true,
                preConfirm: async (motivo_texto) => {
                    if (!motivo_texto) {
                        Swal.showValidationMessage(`Debe introducir el motivo.`);
                        return false;
                    }
                    return motivo_texto;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.dispatch('eliminarPlanillaGuardar', {
                        motivo: result.value
                    });
                }
            });
        });

        document.addEventListener('PlanillaEliminada', function() {
            Swal.fire({
                title: "Eliminado!",
                text: "Se eliminó la planilla y todos los registros seleccionados.",
                icon: "success"

            });
        });
</script>
