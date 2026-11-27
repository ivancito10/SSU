<div>
    <x-big-component id="editarEspecialistaModal" labelledby="editarEspecialistaModalLabel"
        title=" MODIFICAR  ESPECIALISTA " saveAction="guardarEditar()">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276; color:white;text-align:center; border-radius:6px">
                                    ESPECIALISTA
                                </p>
                                <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail" alt="img"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                                
                                <br><br>
                                <span style="color:#2c3e50;" class="h4">
                                    <b>{{ $doc_especialidad }} </b>
                                </span><br>
                                @if ($permanente == true)
                                    <p style='color:#2874A6 ;' class="h4"><b>PERMANENTE</b></p>
                                @else
                                    <p style='color:#DC7633 ;' class="h4"><b>EVENTUAL</b></p>
                                @endif
                            </div>

                            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                 
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <strong>NOMBRE COMPLETO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ $nombres }} </p>
                                    </div>
                                    
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <strong>MATRÍCULA:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                            {{ $matricula }} </p>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <strong>CARNET:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                            {{ $carnet }} </p>
                                    </div>
                                    


                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <strong>EXPEDIDO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                            {{ $expedido }} </p>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <strong>TIPO DOC.:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8;padding-rigth:1; border-color:white; border-radius:8px">
                                            {{ $tipoDoc }} </p>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <strong>FECHA DE NACIMIENTO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ $fecha_nacimiento }} </p>
                                    </div>
                                    
                                </div>
                                <div class="row"> 
                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <strong>GRADO ACADÉMICO:</strong>
                                        <p class="p-1"
                                            style="margin: 2; background-color:#e5e8e8; padding-right:1px; border-color:white; border-radius:8px">
                                            {{ $grado }} </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p style="color:#2471a3" class="h5">
                                            <em style="text-decoration:underline;" ><b>Atención a Tipo de Población Asegurado.</b></em>
                                        </p>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="checkAfiliado"
                                                        id="checkAfiliado" wire:model="checkAfiliado"
                                                        style="transform: scale(1.5); margin-right: 10px;">
                                                    <label class="form-check-label" for="checkAfiliado">
                                                        AFILIADO
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="checkConvenio"
                                                        id="checkConvenio" wire:model="checkConvenio"
                                                        style="transform: scale(1.5); margin-right: 10px;">
                                                    <label class="form-check-label" for="checkConvenio">
                                                        CONVENIO
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="checkSsue" id="checkSsue"
                                                        wire:model="checkSsue" style="transform: scale(1.5); margin-right: 10px;">
                                                    <label class="form-check-label" for="checkSsue">
                                                        SSUE
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                 
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                          
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                <p style="color:#2471a3" class="h5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                    <em class="ml-2"><b>Ingresar y completar los siguientes datos.</b> </em> 
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <label for="permanente">TIPO DE CONTRATO: <b style="color:#cb4335;">*</b></label>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <label>Eventual</label>
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input wire:model="permanente" name="permanente" type="checkbox"
                                            class="custom-control-input" id="switchpermanente"
                                            onchange="toggleFechaCampos()">
                                        <label class="custom-control-label" for="switchpermanente"
                                            style="margin-left: 0.5rem;"></label>
                                    </div>
                                    <label>Permanente</label>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" id="fecha_inicio_div"
                                style="display: {{ $permanente ? 'none' : 'block' }};">
                                <label for="fecha_inicio">FECHA INICIO DE CONTRATO: <b style="color:#cb4335;">*</b></label>
                                <input id="fecha_inicio" class="form-control" type="date"
                                    wire:model='fecha_inicio' style="height: 30px; border-radius:8px"
                                    min="{{ now()->toDateString() }}">
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12" id="fecha_fin_div"
                                style="display: {{ $permanente ? 'none' : 'block' }};">
                                <label for="fecha_fin">FECHA FIN DE CONTRATO: <b style="color:#cb4335;">*</b></label>
                                <input id="fecha_fin" class="form-control" type="date" wire:model='fecha_fin'
                                    style="height: 30px; border-radius:8px" min="{{ now()->toDateString() }}">
                            </div>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </x-big-component>
</div>

<script>
    window.addEventListener('modalEditarEspecialista', event => {
        $('#editarEspecialistaModal').modal('show');
    });

    // GUARDAR EDITAR ESPECIALIDAD
    function guardarEditar() {
        Swal.fire({
            title: '¿Actualizar registro?',
            text: "Se actualizarán los datos ingresados.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizar');
                $('#editarEspecialistaModal').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                @this.set('modal', true);
                $('#editarEspecialistaModal').modal('hide');
            }
        });
    }

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: 'Actualizado!',
            text: "Se registro con éxito.",
            icon: 'success'
        });
        $('#editarEspecialistaModal').modal('hide');
    });
    //validacion para las fechas
    document.addEventListener('errorFechaFinMayor', function() {
        Swal.fire({
            title: '¡Fecha Invalida!',
            text: "Selecciona una fecha de inicio menor a la fecha de finalización",
            icon: 'error'
        });
        $('#editarEspecialistaModal').modal('hide');
    });

    document.addEventListener('sinFechas', function() {
        Swal.fire({
            title: '¡registro invalido!',
            text: "Porfavor ingresa las fechas para completar el registro",
            icon: 'error'
        });
        $('#editarEspecialistaModal').modal('hide');
    });
</script>
<script>
    // Función para ocultar/mostrar los campos de fecha basado en el estado del switch
    function toggleFechaCampos() {
        // Acceder al valor de 'permanente' y sincronizarlo con el switch
        var isPermanente = document.getElementById('switchpermanente').checked;

        // Obtener los contenedores de los campos de fecha
        var fechaInicioDiv = document.getElementById('fecha_inicio_div');
        var fechaFinDiv = document.getElementById('fecha_fin_div');

        // Mostrar/ocultar los campos dependiendo del estado del switch
        if (isPermanente) {
            // Si el switch está activado (Permanente), ocultar los campos
            fechaInicioDiv.style.display = 'none';
            fechaFinDiv.style.display = 'none';
        } else {
            // Si el switch está desactivado (Eventual), mostrar los campos
            fechaInicioDiv.style.display = 'block';
            fechaFinDiv.style.display = 'block';
        }
    }

    // Ejecutar la función al cargar la página para reflejar el estado inicial
    window.onload = function() {
        // Sincronizar el estado del switch con la variable 'permanente'
        var isPermanente = {{ $permanente ? 'true' : 'false' }};
        document.getElementById('switchpermanente').checked = isPermanente;

        // Llamar a toggleFechaCampos para ajustar la visibilidad de los campos
        toggleFechaCampos();
    }
</script>
