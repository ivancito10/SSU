<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">  ACTUALIZAR DATOS DEL REPRESENTANTE  LEGAL</h6>
        </div>
        <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
    </div>

    

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card " style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
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
                        <br>
                        <div class="row">
                            <div class='col-lg-9 col-md-12 col-sm-12 col-xs-12'>
                                <span><b>INSTITUCIÓN: </b> {{ $institucion }}</span>
                            </div>
                            <div class='col-lg-3 col-md-4 col-sm-6 col-xs-12'>
                                <span><b>NIT: </b> {{ $nit }}</span>
                                
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Actual representante legal registrado en el sistema.</em> </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <strong> NOMBRE COMPLETO:</span></strong>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                   {{$nombres_m}} {{$p_apellido_m}}  {{$s_apellido_m}}</p>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <strong> CARNET:</span></strong>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                   {{$ci_m}} @if($complemento_m != null) {{$complemento_m}} @endif </p>
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <strong> FECHA DE NACIMIENTO:</span></strong>
                                <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                   {{$fecha_nacimiento_m}} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <p style="color:#1a5276 ;" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b> Ingrese y búsque al nuevo representante legal.</b><span style="color:#cb4335;"><b>*</b></span> </em>
                                </p>
                            </div>

                            <!-- Aquí va el botón con icono de lupa -->
                            <div class="col-lg-5  col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">

                                <button class="btn btn-primary" wire:click="buscarRepresentante">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                        class="bi bi-search" viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                    </svg>
                                </button>
                                <input class="form-control" type="search" wire:model="ci_b"
                                    style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                    placeholder="Ingresa el carnet ">
                                <input class="form-control" type="search" wire:model="complemento_b"
                                    style="width: calc(100% - 20px); margin-left: 5px; border-radius:10px;"
                                    placeholder="complemento" oninput="this.value = this.value.toUpperCase()">

                            </div>
                            <div wire:loading>
                                <div class="spinner-grow text-warning" role="status">

                                </div>
                                <b><i>Cargando datos espere porfavor...</i></b>
                            </div>
                            <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                            @if ($estado_encontrado == '')
                            @else
                                <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12" role="alert">
                                    
                                    @if ($estado_encontrado == 1)
                                        <div class="alert alert-success"> Se validó correctamente!</div>
                                    @elseif($estado_encontrado == 0)
                                        <div class="alert alert-warning">No se validó, debe ingresar los datos  manualmente.
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre" style="margin-bottom: 0;"> NOMBRES: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control @error('nombres') is-invalid @enderror" type="text"
                                    wire:model.live='nombres' style=" height: 30px; border-radius:10px;   "
                                    placeholder="Ingrese el nombre" oninput="this.value = this.value.toUpperCase()">
                                @error('nombres')
                                    <div class="text-danger">
                                        <b>El campo es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre" style="margin-bottom: 0;"> AP. PATERNO: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control" type="text" wire:model='p_apellido'
                                    style=" height: 30px; border-radius:10px;"
                                    placeholder="Apellido paterno" oninput="this.value = this.value.toUpperCase()">

                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="nombre" style="margin-bottom: 0;"> AP. MATERNO: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control" type="text" wire:model='s_apellido'
                                    style=" height: 30px; border-radius:10px; "
                                    placeholder="Apellido materno" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="ci" style="margin-bottom: 0;"> CARNET: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control @error('ci') is-invalid @enderror" type="text" wire:model.live='ci'
                                    style=" height: 30px; border-radius:10px;  "
                                    placeholder="Carnet de identidad">
                                @error('ci')
                                    <div class="text-danger">
                                        <b>El campo a  es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="complemento" style="margin-bottom: 0;"> COMPLEMENTO: </label>
                                <input class="form-control" type="text" wire:model.live='complemento'
                                    style=" height: 30px; border-radius:10px; "
                                    placeholder="complemento" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                <label for="fecha_nacimiento" style="margin-bottom: 0;"> FECHA DE NACIMIENTO: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control @error('fecha_nacimiento') is-invalid @enderror" type="date"
                                    wire:model.live='fecha_nacimiento'
                                    style=" height: 30px; border-radius:10px;  ">
                                @error('fecha_nacimiento')
                                    <div class="text-danger">
                                        <b>El campo  es obligatorio.</b>
                                    </div>
                                @enderror

                            </div>

                        </div>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="tipo_doc">TIPO DOCUMENTO: <span style="color:#cb4335;">*</span></label>
                                <select wire:model="tipo_doc" id="tipo_doc"
                                    class="form-control select2 @error('tipo_doc') is-invalid @enderror" tabindex="-1" required
                                    style="border-radius: 10px; height: 35px;">
                                    <option selected="selected">Seleccione el tipo documento </option>
                                    <option value="0">NACIONAL</option>
                                    <option value="1">EXTRANJERO</option>
                                </select>

                                @error('tipo_doc')
                                    <div class="text-danger">
                                        <b>El campo es obligatorio.</b>
                                    </div>
                                @enderror
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="expedido_ci">EXPEDIDO: <span style="color:#cb4335;">*</span></label>
                                <select wire:model="expedido_ci" id="expedido_ci"
                                    class="form-control select2 @error('expedido_ci') is-invalid @enderror" tabindex="-1" required
                                    style="border-radius: 10px;  height: 35px;">
                                    <option value=""> NINGUNO</option>

                                    @foreach ($departamento as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->sigla }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('expedido_ci')
                                    @if ($message == 'validation.required')
                                        <span class="form-text text-danger">Este campo es obligatorio.</span>
                                    @else
                                        <span class="form-text text-danger">{{ $message }}</span>
                                    @endif
                                @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class='col-lg-5 col-md-12 col-sm-12 col-xs-12'>
                                <label for="file_digital_ci" style="color:#1a5276">SUBIR EN PDF EL CARNET: <span style="color:#cb4335;">*</span></label>
                                <input class="form-control form-control-sm @error('file_digital_ci') is-invalid @enderror"
                                    type="file" id="formFiledigital" name="file_digital_ci" required
                                    wire:model="file_digital_ci" accept=".pdf">
                                @error('file_digital_ci')
                                    <div class="text-danger">
                                        <b>El campo es obligatorio, de tipo PDF.</b>
                                    </div>
                                @enderror

                            </div>

                        </div>
                        <br>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <br><br>
                                <x-cancel-button wire:click="atras">CANCELAR</x-cancel-button>
                                <x-save-button onclick="saveEditInsti()">GUARDAR</x-save-button>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

        </div>
        
            
            
            
            
            


            
            
        

</div>
<script>
    function saveEditInsti() {
        Swal.fire({
            title: '¿Modificar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, modificar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarRepLegal');
            }
        });
    }
    document.addEventListener('RepresenanteModificada', function() {
        Swal.fire({
            title: "Modificado!",
            text: "Se registro correctamente.",
            icon: "success"

        });
    });
    document.addEventListener('error', function() {
        Swal.fire({
            title: "Verifique todos los datos!",
            text: "Se cancelo el registro.",
            icon: "warning"
        });
    });

    document.addEventListener('sin_file_ci', function() {
        Swal.fire({
            title: "Advertencia!",
            text: "Debe subir el file escaneado del carnet de identidad, si cambia de representante legal.",
            icon: "warning"
        });
    });
</script>
