<div>
    <div class="row">
        <div class="col-sm-4">
        <div class="card">
        <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>AGREGAR NUEVO EXÁMEN MÉDICO</b></div>
               
                <div class="card-body">
                    <!--DATOS DE LA PERSONA-->
                    <!--ENCABEZADO DE LA BÚSQUEDA Y MENSAJE DE VALIDACION-->
                    
                    <h6><em>Seleccione e ingrese los datos correspondientes.</em></h6>

                    <div class="row">
                        
                        

                        <div class="col-lg-12" style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->

                            <button class="btn btn-primary" type="submit  " wire:click="search_persona">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 10 -1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                            </button>

                            <input class="form-control " type="search" id="search"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder="Ingrese el C.I"
                                wire:model="ci">
                            <input class="form-control" type="search" id="complemento"
                                style="width: calc(100% - 20px); margin-left: 5px;" placeholder=" Ingrese complemento"
                                wire:model="complemento"> 
                        </div>

                        <!--FUNCION MOSTRAR VALIDACIONES DEL ASUSS-->
                        @if ($estado_api == '')
                        @else
                        <div class="col-lg-12 " role="alert">
                            @if ($estado_api == 1)
                            <div class="alert alert-success"> Se validó
                                correctamente!</div>
                            @else
                            <div class="alert alert-danger">No se validó!</div>
                            @endif
                        </div>
                        @endif
                        <div wire:loading wire:target="search_persona">
                            <div class="spinner-grow text-warning" role="status">

                            </div>
                            <b><i>Cargando datos espere porfavor...</i></b>
                        </div>
                    </div>
                    <br>
                    <!--AQUI SE MUESTRAN LOS DATOS RECUPERADOS DEL ASUSS-->

                    <div class="row">

                        <div class='col-lg-8 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ci">CARNET </label>
                            <input type="number" class="form-control" disabled  wire:model.live="ci"
                                name="ci" id="ci" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                            <label for="complemento_m">COMPLEMENTO </label>
                            <input type="number" class="form-control" disabled 
                                wire:model.live="complemento_m" name="complemento_m" id="complemento_m"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="nombre">NOMBRE </label>
                            <input type="text" class="form-control" disabled 
                                wire:model.live="nombre" name="nombre" id="nombre"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_paterno">PRIMER APELLIDO </label>
                            <input type="text" class="form-control" disabled
                                wire:model.live="p_apellido" name="ap_paterno" id="ap_paterno"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="ap_materno">SEGUNDO APELLIDO </label>
                            <input type="text" class="form-control" disabled 
                                wire:model.live="s_apellido" name="ap_materno" id="ap_materno"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="fecha_nacimiento">FECHA DE NACIMIENTO </label>
                            <input type="text" class="form-control" disabled 
                                wire:model.live="fecha_nacimiento" name="fecha_nacimiento" id="fecha_nacimiento"
                                oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula"> MATRICULA</label>
                            <input class="form-control" name="matricula" id="matricula" wire:model.live="matricula_seguro"
                                disabled>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="sexo"> SEXO</label>
                            <div class="col-sm-6 d-flex align-items-center">
                                <label class="mr-2">M</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="sexo" name="sexo" type="checkbox" class="custom-control-input"
                                        id="customSwitch2" wire:change='GenerarMatricula()'>
                                    <label class="custom-control-label" for="customSwitch2"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">F</label>
                            </div>
                        </div>

                    </div>
                   
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>TITULAR PRINCIPAL</b></div>
               
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">
                            <label for="estado_vigencia">
                                @if ($estado_vigencia == 'VIGENTE')
                                    <span style="color: green;"><b>VIGENTE</b></span>
                                @elseif($estado_vigencia == 'CESANTE')
                                    <span style="color: yellow;"><b>CESANTE</b></span>
                                @elseif($estado_vigencia == 'NO VIGENTE')
                                    <span style="color: red;"><b>NO VIGENTE</b></span>
                                @endif
                            </label>
                            <br>
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="matricula_titular" > MATRICULA</label>
                            <input class="form-control" name="matricula_titular" id="matricula_titular" disabled wire:model=matricula_titular>
                        <br>
                        </div>


                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="titular"> NOMBRE COMPLETO</label>
                            <input class="form-control" name="titular" id="titular" disabled wire:model=titular>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> CARNET</label>
                            <input class="form-control" name="ci" id="ci" disabled wire:model=ci_titular>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> COMPLEMENTO</label>
                            <input class="form-control" name="ci" id="ci" disabled wire:model=complemento_titular>
                        
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> TELEFONO REF.</label>
                            <input class="form-control" name="ci" id="ci" disabled wire:model=telefono_titular>
                        </div>
                        
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> G. SANGUINEO</label>
                            <input class="form-control" name="ci" id="ci" disabled wire:model=sangre_titular>
                        </div>
                        
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> DIRECCION</label>
                            <input class="form-control" name="ci" id="ci" disabled wire:model=direccion_titular>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="ci"> ZONA</label>
                            <input class="form-control" name="ci" id="ci" disabled wire:model=zona_titular>
                        </div>

                    </div>    
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>HOJA DE RUTA</b></div>
                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_tramite" > NRO. TRAMITE</label>
                            <input class="form-control" name="nro_tramite" id="nro_tramite" wire:model=nro_tramite>
                        
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_tramite" > FERCHA TRAMITE</label>
                            <input class="form-control" name="fecha_tramite" id="fecha_tramite" type='date' wire:model=fecha_tramite>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="procedencia" > PROCEDENCIA</label>
                            <textarea class="form-control" name="procedencia" id="procedencia" wire:model=procedencia rows="2"></textarea>
                        </div>  
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background-color:#3f3e3e ; color:white"><b>REGISTRO DE DATOS DEL SOLICITANTE</b></div>
                <div class="card-body">

                    <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud" > FECHA SOLICITUD</label>
                            <input class="form-control" name="fecha_solicitud" id="fecha_solicitud" type='date' wire:model=fecha_solicitud wire:change='emitirFechaFin'>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> FECHA FIN</label>
                            <input class="form-control" name="fecha_fin" id="fecha_fin" type='date' disabled wire:model=fecha_fin>
                        <br>
                        </div>

                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12' wire:ignore>
                            <label for="id_asegurado">TIPO BENEFICIARIO</label>
                            <select wire:model="id_asegurado" name="id_asegurado" id="asegurado_select"
                                class="form-control " style="width: 100%;"
                                tabindex="-1" required>
                                <option selected="selected" value="{{ $id_asegurado }}"> {{ $tipo_asegurado }}
                                </option>
                                @foreach ($aseguradoSelect as $asegurado)
                                    <option value="{{ $asegurado->id }}">{{ $asegurado->tipo_asegurado }}
                                    </option>
                                @endforeach
                                
                            </select>
                            <br>
                        </div>

                        
                       
                        

                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="modal-footer">
        <x-cancel-button> CANCELAR</x-cancel-button>
        <x-save-button onclick="guardar()">GUARDAR</x-save-button>
    </div>
</div>
<script>

    // Aqui se busca el C.I
    function buscarDatos() {
        var carnet = document.getElementById('search').value;
        var complemento = document.getElementById('complemento').value;
        console.log('Valor del carnet:', carnet, complemento);
        Livewire.dispatch('search_persona', [carnet, complemento]);
    }

    // esto es lo de las zonas
    document.addEventListener('DOMContentLoaded2', function() {
        var select = document.querySelector('#asegurado_select');
        $(select).select2();
    });

    function guardar() {
        Swal.fire({
            title: '¿Guardar titular?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('GuardarExamen');
                Swal.fire({
                    title: "Guardado!",
                    text: "Se registro correctamente.",
                    icon: "success"
                });

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });


            }
        });
    }
</script>
