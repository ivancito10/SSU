<div> 
    <x-subir-archivo-button style=" padding: 5px 10px;" data-toggle="modal" data-target="#modalPlanilla"> 
        SUBIR PLANILLA 
    </x-subir-archivo-button>

    <!--  MODAL SUBIR ARCHIVO-->
    <div class="modal fade" id="modalPlanilla" tabindex="-1" aria-labelledby="planilla" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0 m-0 border shadow"
            style="background-color: #ffffff; border-radius: 1rem; border: 1px solid #dee2e6; box-shadow: 0 10px 25px rgba(0, 97, 136, 0.2);">
                <div class="modal-header"
                style="background-color: #eaeded; border-bottom: 1px solid white; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                    <div style="display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#ba4a00"
                            class="bi bi-pen-fill" viewBox="0 0 16 16" style="margin-right: 5px;">
                            <path
                                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001" />
                        </svg>
                        <h5 style="font-family: Arial, sans-serif; color:black" class="modal-title"
                            id="modalPlanilla"><b> AGREGAR PLANILLA EXCEL</b></h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <p style="color:#1a5276 ;" class="h5"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg> <em class="ml-2"> <b>Ingrese y complete los siguientes
                                                        campos.</b></em>
                                                <spam style="color:#cb4335;"><b>*</b></spam>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' wire:ignore>
                                            <label for="id_institucion">INSTITUCIÓN:</label>
                                            <select wire:model="id_institucion" id="id_institucion"
                                                class="select2 form-control" style="width: 100%; border-radius: 10px;"
                                                required>
                                                <option value="" selected>Seleccione la institución.</option>
                                                @foreach ($instituciones as $dato)
                                                    <option value="{{ $dato->id }}">{{ $dato->nombre }}</option>
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
                                    <div class="row">
                                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="id_tipo_aporte">TIPO DE APORTE:</label>
                                            <select wire:model="id_tipo_aporte" id="id_tipo_aporte"
                                                class="select2 form-control" style="border-radius: 10px;">
                                                <option selected>Seleccione un tipo</option>
                                                <option value="0">MENSUAL</option>
                                                <option value="1">REINTEGRO</option>
                                                <option value="2">BENEFICIO SOCIAL</option>
                                                <option value="3">PLANILLA ADICIONAL</option>
                                                <option value="4">RETROACTIVO</option>
                                            </select>
                                        </div>
                                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="id_mes">MES:</label>
                                            <select wire:model="id_mes" id="id_mes" name="id_mes"
                                                class="form-control" style="width: 100%; border-radius: 10px;"
                                                tabindex="-1" aria-hidden="true">
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
                                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                            <label for="gestion">GESTIÓN:</label>
                                            <input wire:model="gestion" name="gestion" id="gestion"
                                                class="form-control @error('gestion') is-invalid @enderror"
                                                type="number" required style="border-radius: 10px;" min="1900" max="{{ date('Y') }}">
                                            @error('gestion')
                                                @if ($message == 'validation.required')
                                                    <span class="form-text text-danger">Este campo es obligatorio.</span>
                                                @else
                                                    <span class="form-text text-danger">{{ $message }}</span>
                                                @endif
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:ignore>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <p class="h5" style="color:#1a5276 ;"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-pencil-square"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                    </svg> <em class="ml-2"><b> Suba el archivo en formato
                                                            Excel.</b></em>
                                                    <spam style="color:#cb4335;"><b>*</b></spam>
                                                </p>
                                                @if ($errors->has('error_message'))
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first('error_message') }}
                                                    </div>
                                                @endif
                                                <label for="planilla" class="form-label">SELECCIONAR ARCHIVO:
                                                </label>
                                                <input class="form-control" type="file" id="planilla"
                                                    name="archivo" required accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                                    wire:model="planilla" style="border-radius: 10px;">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading
                                        wire:target="planilla">
                                        <x-cargando-dos />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0"
                style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
                    <x-cancel-button data-dismiss="modal">CANCELAR</x-cancel-button>
                    <x-save-button onclick="savePlanillaAporteInstitucional()"
                        wire:loading.attr="disabled">GUARDAR</x-save-button>
                </div>
                <div class="col-lg-12" wire:loading>
                    <x-cargando-dos />
                </div>
            </div>
        </div>
    </div>
    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
            padding: 6px 12px;
            border-radius: 10px;
            border-color: #ced4da;
        }

        .select2-selection__rendered {
            line-height: 25px;
        }
    </style>
</div>



<script>
    function savePlanillaAporteInstitucional() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'info',
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
        $('#modalPlanilla').modal('hide');
        Swal.fire({
            title: "Planilla Duplicada!",
            text: "Se cancelo el registro, su planilla esta duplicada.",
            icon: "error"

        });
    });
    document.addEventListener('SubirPlanillaExitoso', function() {
        $('#modalPlanilla').modal('hide');
        Swal.fire({
            title: "Guardado!",
            text: "Se cargo la planilla y se validó la información correctamente.",
            icon: "success"

        });
    });

    document.addEventListener('errorImportacion', function(event) {
        Swal.fire({
            title: "ERROR!",
            text: event.detail.mensaje,
            icon: "warning"
        });
    });

    document.addEventListener('ErrorGeneralPlanillaDatos', function() {
        $('#modalPlanilla').modal('hide');
        Swal.fire({
            title: "Planilla con error!",
            text: "Se canceló el registro, verifique el formato de la planilla.",
            icon: "warning"

        });
    });
    document.addEventListener('ErrorGeneral', function() {
        $('#modalPlanilla').modal('hide');
        Swal.fire({
            title: "Planilla con error!",
            text: "Se canceló el registro, verifique el formato de su planilla y comuniquese con el administrador.",
            icon: "warning"

        });
    });
    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Sucedió un error, verifique el error.",
            icon: "error"
        });
    });

</script>
