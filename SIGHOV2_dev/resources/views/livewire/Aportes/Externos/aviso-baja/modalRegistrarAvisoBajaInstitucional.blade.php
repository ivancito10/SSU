<div>
    <x-big-component id="registrarAviso" labelledby="registrarAvisoLabel" title=" REGISTRAR AVISO DE BAJA ASEGURADO"
        saveAction="RegistrarBaja()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body"> 
                        
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Asegurado registrado.</em>
                                        </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                         
                         <div class="row">
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nombre_completo">NOMBRE COMPLETO: </label>
                                <input class="form-control" type="text" id="nombre_completo" wire:model='nombre_completo' disabled
                                    style="  border-radius: 10px; background-color:#f2f4f4;padding-rigth:1; border-color:white;">
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="ci">CARNET: </label>
                                <input class="form-control" type="text" id="ci" wire:model='ci' disabled
                                    style="  border-radius: 10px; background-color:#f2f4f4;padding-rigth:1; border-color:white;">
                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="matricula_seguro">MATRICULA: </label>
                                <input class="form-control" type="text" id="matricula_seguro" wire:model='matricula_seguro' disabled
                                    style="  border-radius: 10px; background-color:#f2f4f4;padding-rigth:1; border-color:white;">
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <p class="h6" 
                                style="border: 2px solid #d4ac0d; border-radius: 5px; background-color: #f7dc6f; padding-left: 15px; display: inline-block;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13.113 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                    </svg> <em><b> Los campos marcados con (<span style="color:#cb4335;">*</span>) son obligatorios para continuar.</b></em> 
                                </p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg> <em class="ml-2"> <b>Complete los siguientes campos para el registro.</b></em>
                                    
                                </p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <label for="nombre_insti" class="me-2 fw-bold">INSTITUCIÓN:</label>
                            <input class="form-control w-50" 
                                type="text" 
                                id="nombre_insti" 
                                wire:model="nombre_insti" 
                                disabled
                                style="border-radius:10px; background-color:#f2f4f4; border-color:white;">
                        </div>
                        
                        
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_baja">FECHA BAJA: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control" type="date" id="fecha_baja" wire:model='fecha_baja'  style="  border-radius: 10px;">
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="motivo_baja">MOTIVO BAJA INSTITUCIONAL: <span style="color:#cb4335;">*</span></label>
                                <select wire:model="motivo_baja" id="motivo_baja" name="motivo_baja"
                                    class="form-control select2 @error('motivo_baja') is-invalid @enderror"
                                    style="width: 100%; border-radius: 10px;" tabindex="-1" required>
                                    <option value="0" selected>Seleccione el tipo de baja</option>
                                    <option value="JUBILACIÓN">JUBILACIÓN</option>
                                    <option value="RENUNCIA">RENUNCIA</option>
                                    <option value="REESTRUCTURACIÓN">REESTRUCTURACIÓN</option>
                                    <option value="FINALIZACIÓN DE CONTRATO">FINALIZACIÓN DE CONTRATO</option>
                                    <option value="RETIRO">RETIRO</option>
                                    <option value="COMISIÓN">COMISIÓN</option>
                                    <option value="SUSPENSIÓN">SUSPENSIÓN</option>
                                    <option value="FALLECIMIENTO">FALLECIMIENTO</option>
                                    <option value="LICENCIA SIN GOCE DE HABERES">LICENCIA SIN GOCE DE HABERES</option>
                                    <option value="OTRO">OTRO</option>
                                </select>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="ultimo_salario">ÚLTIMO SALARIO: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control" type="number" id="ultimo_salario" wire:model='ultimo_salario'
                                  style="  border-radius: 10px;" placeholder='Ej: 1000.50'>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_solicitud">FECHA REGISTRO: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control" type="date" id="fecha_solicitud" wire:model='fecha_solicitud'  style="border-radius: 10px;" disabled>
                            </div>
                            <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                                <label for="descripcion">DESCRIPCIÓN: </label>
                                <textarea class="form-control" type="text" id="descripcion" wire:model='descripcion'  style="border-radius: 10px;" rows="2"
                                placeholder="Ingrese la descripción" oninput="this.value = this.value.toUpperCase()"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-big-component>

</div>

<script>
    window.addEventListener('modalAbrirRegistro', event => {
        $('#registrarAviso').modal('show');
        // Para scrooll del primer modal lista 
         $('#modalAvisoBaja').css('overflow-y', 'auto');
    });
    
</script>
