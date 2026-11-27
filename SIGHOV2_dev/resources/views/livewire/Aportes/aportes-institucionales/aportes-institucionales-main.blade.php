<div>
    @can('subir_planilla.aportes_institucionales')  
        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:black;">APORTES - <b> LISTA DE PLANILLAS INSTITUCIONALES</b></h4>
            <div class="d-flex">

                <div style="margin-left: 7px;">
                    @livewire('Aportes.aportes-institucionales.aportes-institucionales-register')
                </div>
                <div style="margin-left: 7px;">
                    <x-agregar-button style=" padding: 5px 10px;" wire:click="actualizar_planillas"><b>ACTUALIZAR
                        PLANILLAS</b> </x-agregar-button>
                </div>
            </div>
        </div>
        <br>
    @endcan

    <div class="scroll-container-epi"> 
        @livewire('aportes.comprobante.comprobante-temp-table')
    </div>


    <x-ver-dos-modal id="aportantesModal" labelledby="aportantesModalLabel" title="VISTA DE APORTES">
        <div>
            
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
                            <td>   {{ $salario_cotizable_m }} <b>Bs.</b> </td>
                            <td> {{ $importe }} <b>Bs.</b>  </td>
                            @if ($total_compensacion > 0)
                            <td>  {{ $total_compensacion }} <b>Bs.</b>  </td>
                            @endif
                        </tr>
                        
                    </tbody>

                </table>
            </div> 

 
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

                 
                    @livewire('Aportes.aportes-institucionales.aportantes-insti-table', ['compId' => $compId])
                 
                 

            </div>

        </div>
    </x-ver-dos-modal>

 
    <x-ver-dos-modal id="aportantesModalTemp" labelledby="aportantesModalTempLabel" title="VISTA DE APORTES">
        <div>

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
                            <td>   {{ $salario_cotizable_m }} <b>Bs.</b> </td>
                            <td> {{ $importe }} <b>Bs.</b>  </td>
                            @if ($total_compensacion > 0)
                            <td>  {{ $total_compensacion }} <b>Bs.</b>  </td>
                            @endif
                        </tr>
                        
                    </tbody>

                </table>
            </div> 
 
 
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
                @livewire('Aportes.aportes-institucionales.aportantes-insti-temp-table', ['compId' => $compId])
            </div>
        </div>
    </x-ver-dos-modal>


    @include('livewire.Aportes.aportes-institucionales.modalLiquidacion')
    @include('livewire.Aportes.aportes-institucionales.modalPdfFormaPago')

    @include('livewire.Aportes.aportes-institucionales.modalVerPdfBajaMedica')

 
    <div wire:loading>
        <x-cargando-dos/>
    </div>

    <style>
        .scroll-container-epi {
            max-height: 700px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
</div>

<script>
    // ESTO ES PARA ESCUCHAR MODAL PRE-LIQ
    window.addEventListener('openModalPreLiq', event => {
        $('#preliqModal').modal('show');
    });
    // ESTO ES PARA ESCUCHAR MODAL LIQUIDACION
    window.addEventListener('openModalLiquidacion', event => {
        $('#liquidacionModal').modal('show');
    });
    // ESTO ES PARA ESCUCHAR MODAL DE GRUPO DE APORTANTES
    window.addEventListener('openModalAportantes', event => {
        $('#aportantesModal').modal('show');
    });
    // ESTO ES PARA ESCUCHAR MODAL DE GRUPO DE APORTANTES  tempporal
    window.addEventListener('openModalAportantesTemp', event => {
        $('#aportantesModalTemp').modal('show');
    });

    // ESTO ES PARA ESCUCHAR MODAL DE FORMA DE PAGO
    window.addEventListener('openModalFormaPago', event => {
        $('#formaPagoModal').modal('show');
    });


    window.addEventListener('ConfirmarValidarPlanillas', event => {
        const id_comprobante = event.detail.id_comprobante;
        const nombre_insti = event.detail.nombre_institu;
        Swal.fire({
            title: '¿Validar Planilla?',
            text: "Usted validará la planilla de la institución " + nombre_insti,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Validar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                Livewire.dispatch('ValidarComprobanteSeleccionado', {
                    id_comprobante_validar: id_comprobante
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
    document.addEventListener('SeValidoLaPlanilla', function() {
        Swal.fire({
            title: "Validado!",
            text: "Se validó todos los aportantes para la liquidación!.",
            icon: "success"

        });
    });
    document.addEventListener('NoSeValidoLaPlanilla', function() {
        Swal.fire({
            title: "No se pudo validar la planilla!",
            text: "Se canceló la validación!!!",
            icon: "error"

        });
    });

    document.addEventListener('SeRegistroFormaDePago', function() {
        Swal.fire({
            title: "Guardado!",
            text: "La forma de pago se registró correctamente!",
            icon: "success"

        });
    });
    document.addEventListener('NoSeRegistroFormaDePago', function() {
        Swal.fire({
            title: "Registro cancelado!",
            text: "Se canceló el registro",
            icon: "error"

        });
    });

    document.addEventListener('AlertUFV', function() {
        // Cierra el modal con el id "exampleModal"
        Swal.fire({
            title: "Ufv no actualizado!",
            text: "Registre el valor de la UFV de las fechas seleccionadas.",
            icon: "error"
        });
        $('#preliqModal').modal('hide');
    });
    document.addEventListener('liqCreada', function() {
        // Cierra el modal con el id "exampleModal"
        Swal.fire({
            title: "Guardado!",
            text: "La liquidación se registró correctamente.",
            icon: "success"
        });
        $('#liquidacionModal').modal('hide');
    });

    /* document.addEventListener('AlertUFV', function() {
        Swal.fire({
            title: "Ufv no actualizado!",
            text: "registre el valor de la UFV de las fechas seleccionadas",
            icon: "error",
            onAfterClose: function() {
                // Cierra el modal con el id "preliqModal"
                $('#preliqModal').modal('hide');
            }
        });
    }); */



    document.addEventListener('MostrarPreliquidacion', function() {
        var div = document.getElementById('ver_preliquidacion');
        div.style.display = 'block';
    });

    // Función para mostrar la alerta de confirmación

    function RegistroFormaDePago() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán todos los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('registrar_forma_de_pago');
                $('#formaPagoModal').modal('hide');
            }
        });
    }



    document.addEventListener('ValidacionBajasMedicasError', function() {
        Swal.fire({
            title: "Registro cancelado!",
            text: "Se canceló el registro",
            icon: "error"
        });

    });

    window.addEventListener('ConfirmarNuevoFuncion', event => {
        const id_comprobante = event.detail.id_comprobante;
        Swal.fire({
            title: '¿Validar bajas médicas?',
            text: "Correspondiente al comprobante: " + id_comprobante,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Validar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

            }
        });
    });

    window.addEventListener('ConfirmarBajaMedicaSw', event => {
        const id_comprobante = event.detail.id_comprobante;
        Swal.fire({
            title: '¿Reembolsar Bajas Médicas?',
            text: "Se validarán y reembolsarán todas las bajas médicas registradas. ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Liquidar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('RegistroBajaMedica', [id_comprobante]);
            } else {
                Swal.fire({
                    title: "Reembolso Cancelado!",
                    text: "La solicitud de registro fue anulada.",
                    icon: "warning"
                });
            }
        });
    });
    document.addEventListener('DescargaExitosaExtracto', function() {
        Swal.fire({
            title: "Planilla de Bajas Médicas Liquidado!",
            text: "Se liquidaron exitosamente las bajas médicas.",
            icon: "success"
        });
    });

    // para lo de descarga del PDF
    document.addEventListener('alertaDescargarPdf', function(event) {
        const id_comprobante = event.detail.id_comprobante;
        Swal.fire({
            title: `¿Motivo de Descarga?`,
            icon: 'warning',
            text: 'Se descargará el comprobante en PDF, describa los motivos:',
            input: "text",
            showCancelButton: true,
            confirmButtonText: "GUARDAR",
            cancelButtonText: "CANCELAR",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca el motivo porfavor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('descargarPdf', [id_comprobante]);
            }
        });
    });
    
    window.addEventListener('ConfirmareliminarPlanillas', event => {
        const id_comprobante = event.detail.id_comprobante;
        const nombre_insti = event.detail.nombre_institu;
        Swal.fire({
            title: '¿Eliminar Planilla?',
            text: "Ingrese el motivo para eliminar la planilla de la institucion: " + nombre_insti,
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

                Livewire.dispatch('EliminarComprobanteSeleccionado', {
                    id_comprobante_eliminar: id_comprobante,
                    motivo: result.value
                });
            }
        });
    });

    // para lo de descarga de LA COPIA DEL PDF
    document.addEventListener('alertaDescargarCopiaPdf', function(event) {
        const id_comprobante = event.detail.id_comprobante;
        Swal.fire({
            title: `¿Motivo de Descarga?`,
            icon: 'warning',
            text: 'Se descargará el comprobante copia en PDF, describa los motivos:',
            input: "text",
            showCancelButton: true,
            confirmButtonText: "GUARDAR",
            cancelButtonText: "CANCELAR",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca el motivo porfavor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                //alert('hastaqui?');
                Livewire.dispatch('descargarCopiaPdf', [id_comprobante]);
            }
        });
    });
    document.addEventListener('IntroduzcaFechaLiq', function() {
        Swal.fire({
            title: "Inserte la Fecha!",
            text: "Se canceló la evaluación.",
            icon: "error"
        });
        $('#verPreLiqModal').modal('hide');
    });
    document.addEventListener('AlertUFV', function() {
        Swal.fire({
            title: "UFV no Actualizada!",
            text: "No se realizó el cálculo.",
            icon: "warning"
        });
        $('#verPreLiqModal').modal('hide');
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Sucedió un error, verifique el error.",
            icon: "warning"
        });
    });

    document.addEventListener('no_ufv', function() {
        Swal.fire({
            title: "UFV no Actualizada!",
            text: "No se ha podido realizar la liquidación debido a que la Unidad de Fomento de Vivienda (UFV) no está actualizada. Por favor, actualice la UFV e intente nuevamente.",
            icon: "warning"
        });
        $('#liquidacionModal').modal('hide');
    });

    document.addEventListener('falta_planilla_umsa', function() {
        Swal.fire({
            title: "Advertencia!",
            text: "No se puede validar el comprobante debido a que no se subieron las 2 planillas de la UMSA, Docentes o Administrativos.",
            icon: "warning"

        });
    });
</script>

