<div>
    <!--BOTON SUBIR PLANILLA FORMATO EXCEL -->
    <x-subir-archivo-button style=" padding: 5px 10px;" data-toggle="modal" data-target="#modalPlanillaExt"><b>SUBIR PLANILLA</b>   </x-subir-archivo-button>
    
        
    <!--  MODAL SUBIR ARCHIVO-->
    <div class="modal fade" id="modalPlanillaExt" tabindex="-1" aria-labelledby="planilla" aria-hidden="true" data-backdrop="static" wire:ignore>
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0 m-0 border shadow"
            style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
                <div class="modal-header"
                style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <h5 style="font-family: Arial, sans-serif;  color: #283747  " class="modal-title" id="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill=" #ca6f1e" class="bi bi-pen-fill" viewBox="0 0 16 16">
                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                    </svg> <b> AGREGAR PLANILLA  EXCEL </b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0" >
                    <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        
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
                                                    <b>Ingresar y completar todos los campos.</b> </em>
                                                
                                            </p>
                                        </div>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' >
                                            <label for="id_institucion">INSTITUCIÓN: <span style="color:#cb4335;">*</span></label>
                                            <select wire:model="id_institucion" id="id_institucion" name="id_institucion" class="form-control"
                                                style="width: 100%; border-radius:10px;" tabindex="-1" aria-hidden="true" required>
                                                <option selected="selected">Seleccione la institucion a registrar.</option>
                                                @foreach ($instituciones as $dato)
                                                <option value="{{ $dato->id }}" {{ !$dato->estado_validado ? 'disabled' : '' }}>
                                                    {{ $dato->nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('institucion_id')
                                                @if ($message == 'validation.required')
                                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                                @else
                                                    <span class="form-text text-danger">{{ $message }}</span>
                                                @endif
                                            @enderror
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                    <div class="row">
                                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12' >
                                            <label for="id_tipo_aporte" >TIPO DE APORTE: <span style="color:#cb4335;">*</span></label>
                                            <select wire:model="id_tipo_aporte" class="select2 form-control" style="border-radius: 10px;">
                                                <option selected>Seleccione un tipo</option>
                                                <option value="0">MENSUAL</option>
                                                <option value="1">REINTEGRO</option>
                                                <option value="2">BENEFICIO SOCIAL</option>
                                                <option value="3">PLANILLA ADICIONAL</option>
                                                <option value="4">RETROACTIVO</option>
                                            </select>
                                        </div>
                                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12' >
                                            <label for="mes">MES: <span style="color:#cb4335;">*</span></label>
                                            <select wire:model="id_mes" id="id_mes" name="id_mes" class="form-control"
                                                style="width: 100%; border-radius:10px;" tabindex="-1" aria-hidden="true" required>
                                                <option selected="selected">Seleccione el mes</option>
                                                @foreach ($mes as $dato)
                                                    <option value="{{ $dato->id }}">{{ $dato->mes }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_mes')
                                                @if ($message == 'validation.required')
                                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                                @else
                                                    <span class="form-text text-danger">{{ $message }}</span>
                                                @endif
                                            @enderror
                                        </div>
                                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12' >
                                            <label for="gestion">GESTIÓN: <span style="color:#cb4335;">*</span></label>
                                            <input wire:model="gestion" name="gestion" id="gestion"
                                             class="form-control" type="number" required style="border-radius: 10px;">
                                            @error('gestion')
                                                @if ($message == 'validation.required')
                                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                                @else
                                                    <span class="form-text text-danger">{{ $message }}</span>
                                                @endif
                                            @enderror
                                        </div>
                                        
                                    </div>
                                </div>   
                                            
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  wire:ignore>
                                <br>
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
                                                <b>Suba el archivo en formato Excel.</b> </em>
                                            
                                        </p>
                                    </div>

                                </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                            <label for="formFile" class="form-label" >SELECCIONAR ARCHIVO: <span style="color:#cb4335;">*</span></label>
                                            
                                            @if ($errors->has('error_message'))
                                                <div class="alert alert-danger">
                                                    {{ $errors->first('error_message') }}
                                                </div>
                                            @endif
                                        <input  class="form-control" type="file" id="formFile"
                                         name="archivo" required  wire:model.ignore="planilla" style="border-radius: 10px;" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                        </div>
                                        
                                        <div class="col-lg-4">
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12" wire:loading >
                                    <x-cargando-dos/> 
                                </div>
                                    
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer pt-0"
                style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                    <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
                    <x-save-button onclick="savePlanillaAporteInstitucional()"   wire:loading.attr="disabled">GUARDAR</x-save-button>   
                </div>
            </div>
        </div>
    </div>
</div>

<script>

     function savePlanillaAporteInstitucional() {
            Swal.fire({
                title: '¿Guardar registro?',
                text: "Se registrarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('SubirPlanilla');
                }
            });
        }

        document.addEventListener('ErrorYaExistePlanilla', function() {
            $('#modalPlanillaExt').modal('hide');
            Swal.fire({
                title: "Planilla duplicada!",
                text: "Se cancelo el registro, su planilla esta duplicada.",
                icon: "error"

            });
        });
        
        document.addEventListener('SubirPlanillaExitoso', function() {
            $('#modalPlanillaExt').modal('hide');
            Swal.fire({
                title: "Guardado!",
                text: "Se cargo la planilla y se validó la información correctamente.",
                icon: "success"

            });
        });
        document.addEventListener('mensaje_preliquidacion', function() {
            $('#modalPlanillaExt').modal('hide');
            Swal.fire({
                title: "Recuerde!",
                text: "Debe realizar la pre-liquidación antes de cancelar el depósito.",
                icon: "info"

            });
        });
        document.addEventListener('ErrorGeneral', function() {
            $('#modalPlanillaExt').modal('hide');
            Swal.fire({
                title: "Planilla con error!",
                text: "Se canceló el registro, verifique el formato de su planilla y comuniquese con el administrador.",
                icon: "warning"

            });
        });
        document.addEventListener('ErrorGeneralPlanillaDatos', function() {
            $('#modalPlanillaExt').modal('hide');
            Swal.fire({
                title: "Planilla duplicada!",
                text: "Se canceló el registro, error en el formato.",
                icon: "error"

            });
        });
        document.addEventListener('falta_datos', function() {
            
            Swal.fire({
                title: "Debe completar todos los campos!",
                text: "Su registro ha sido cancelado.",
                icon: "warning"

            });
            setTimeout(() => {
            $('#modalPlanillaExt').modal('show');
            }, 300);
        });

        document.addEventListener('errorImportacion', function(event) {
        Swal.fire({
            title: "Datos incorrectos",
            text: event.detail.mensaje,
            icon: "warning"
        });
    });

</script>
