<div>@can('registro.instituciones_externas') 
      
            <!--LISTA CON  MENU DE ACCIONES  PRINCIPAL-->

            <div class="input-group pr-4" style="justify-content: space-between; ">
                <h4 style="color:#2471a3; "><b>APORTES</b> - <b style="color:#cb4335 ;"> LISTA DE  INSTITUCIONES</b></h4>
                <!-- Aqui va el boto agregar-->
                 @can('admin.ind')
                <x-agregar-button style=" padding: 5px 10px;" wire:click="redirectToForm" title="REGISTRAR INSTITUCIÓN"><b>REGISTRAR</b> </x-agregar-button>
                 @endcan
            </div>
            <br>


@endcan
@can('lista.instituciones_externas')   
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           
            @livewire('aportes.externos.institucion.institucion-table-ext')
            
            @include('livewire.Aportes.Externos.institucion.modalVerInstitucionExt')
           
            @include('livewire.Aportes.Externos.institucion.modalEditarInstitucionExt')
            
            @include('livewire.Aportes.Externos.institucion.modalEditarRepresentanteExt')
           
            @include('livewire.Aportes.Externos.institucion.modalVerFileCiExt')
           
            @include('livewire.Aportes.Externos.institucion.modalVerFileNitExt')
            
            {{-- Para el cargando --}}
            <div wire:loading>
                <x-cargando-dos />
            </div>
            <script>
                // ESTO ES PARA ESCUCHAR ver
                window.addEventListener('openModalVerExt', event => {
                    $('#vistaModalExt').modal('show');
                });
                // ESTO ES PARA ESCUCHAR editar
                window.addEventListener('openModalEditExt', event => {
                    $('#editarModalExt').modal('show');
                });

                // ESTO ES PARA ESCUCHAR editar el represenrtantet legal
                window.addEventListener('openModalEditRepExt', event => {
                    $('#editarReprModalExt').modal('show');
                });
                function saveEditRep() {
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
                            Livewire.dispatch('GuardarPerRep');
                           
                        } else {
                            Swal.fire({
                                title: "Completa todos los datos!",
                                text: "Se canceló el registro",
                                icon: "error"
                            });
                            @this.set('modal', true);
                            
                        }
                    });
                }
                document.addEventListener('RepresenanteModificada', function() {
                    $('#editarReprModalExt').modal('hide');
                    Swal.fire({
                        title: "<b>Felicidades</b>",
                        text: "Se modificó el presentante legal!!!",
                        icon: "success"

                    });
                });
                document.addEventListener('RepresenanteModificadaError', function() {
                    $('#editarReprModalExt').modal('hide');
                    Swal.fire({
                        title: "Error!",
                        text: "Se produjo un error no se pudo editar.",
                        icon: "error"

                    });
                });
                // FUNCION DEL BOTON GUARDAR para SWEETALERT2
                function saveEditInsti() {
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
                            Livewire.dispatch('actualizar');
                            $('#editarModalExt').modal('hide');
                        } else {
                            Swal.fire({
                                title: "Completa todos los datos!",
                                text: "Se canceló el registro",
                                icon: "error"
                            });
                            @this.set('modal', true);
                             $('#editarModalExt').modal('hide');
                        }
                    });
                }
                document.addEventListener('institucionModificada', function() {
                    Swal.fire({
                        title: "Guardado!",
                        text: "Se registro correctamente",
                        icon: "success"

                    });
                });
                document.addEventListener('InstitucionNoActualizado', function() {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se cancelo el registro",
                        icon: "error"

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
        </div>
    </div>
@endcan

</div>
