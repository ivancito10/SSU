<div>
    @can('registro.instituciones')
        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:#212f3c ;">APORTES - <b> LISTA DE INSTITUCIONES</b></h4>
            <!-- Aqui va el boto agregar-->
            <x-agregar-button style=" padding: 5px 10px;" wire:click="redirectToForm" title="REGISTRAR INSTITUCIÓN">
                <b>REGISTRAR</b></x-agregar-button>

        </div>
        <br>
    @endcan

    <div class="row">
        @can('lista.instituciones')
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('aportes.institucion.institucion-table')
            </div>


            @include('livewire.Aportes.institucion.modalVerInstitucion')

            @include('livewire.Aportes.institucion.modalEditarInstitucion')
            @include('livewire.Aportes.institucion.modalEditarRepresentante')
            @include('livewire.Aportes.institucion.modalEditarUsuarioEncargado')
            @include('livewire.Aportes.institucion.modalAsignarUsuarioBaja')

            @include('livewire.Aportes.institucion.modalVerFileNit')
            @include('livewire.Aportes.institucion.modalVerFileCi')
            @include('livewire.Aportes.institucion.modalReporteAfiliados')
        @endcan
    </div>


    <div wire:loading>
        <x-cargando-dos />
    </div>

    <script>
        // esto es lo de las zonas
        document.addEventListener('DOMContentLoaded2', function() {
            var select = document.querySelector('#zonas_id');
            $(select).select2();
        });


        // FUNCION DEL BOTON GUARDAR para SWEETALERT2
        function saveEditInsti() {
            Swal.fire({
                title: '¿Modificar registro?',
                text: "Se modificarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('actualizar');
                    $('#exampleModal').modal('hide');
                } else {
                    Swal.fire({
                        title: "No se modifico!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                    $('#exampleModal').modal('hide');
                }
            });
        }
        window.addEventListener('abri-modal-rep', event => {
            $('#ModalEditRep').modal('show');
        });

        function saveEditRep() {
            Swal.fire({
                title: '¿ Modificar registro?',
                text: "Se modificarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('editar_rep');
                    $('#ModalEditRep').modal('hide');
                } else {
                    Swal.fire({
                        title: "No se modifico!",
                        text: "Se canceló el registro",
                        icon: "warning"
                    });
                    $('#ModalEditRep').modal('hide');
                }
            });
        }

        function saveUsuario() {
            Swal.fire({
                title: '¿Asignar usuario de planillas externas?',
                text: "Se asignará los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, asignar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('asignarUsuario');
                    $('#ModalEditUsuario').modal('hide');
                } else {
                    Swal.fire({
                        title: "No se modifico!",
                        text: "Se canceló el registro",
                        icon: "warning"
                    });
                    $('#ModalEditUsuario').modal('hide');
                }
            });
        }

        function saveUsuarioAvisoBaja() {
            Swal.fire({
                title: '¿Asignar usuario a bajas institucionales?',
                text: "Se asignarán los datos ingresados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, asignar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarUsuarioAvisoBaja');
                    $('#modalAsignarUsuarioBaja').modal('hide');
                } else {
                    Swal.fire({
                        title: "No se asigno!",
                        text: "Se canceló el registro",
                        icon: "warning"
                    });
                    $('#modalAsignarUsuarioBaja').modal('hide');
                }
            });
        }


        document.addEventListener('institucionModificada', function() {
            Swal.fire({
                title: "Modificado!",
                text: "La instittución se modificó correctamente",
                icon: "success"

            });
        });
        document.addEventListener('InstitucionNoActualizado', function() {
            Swal.fire({
                title: "No se modifico!",
                text: "Se canceló el registro",
                icon: "error"

            });
        });

        document.addEventListener('InstitucionDeshabilitada', function() {
            Swal.fire({
                title: 'ACTIVO!',
                text: 'Cambio de estado exitoso.',
                icon: 'success'
            });
        });
        document.addEventListener('InstitucionHabilitada', function() {
            Swal.fire({
                title: ' INACTIVO!',
                text: 'Cambio de estado  exitoso.',
                icon: 'success'
            });
        });


        document.addEventListener('RepresenanteModificada', function() {
            $('#ModalEditRep').modal('hide');
            Swal.fire({
                title: "Modificado!",
                text: "El representante legal se modificó correctamente.",
                icon: "success"

            });
        });

        document.addEventListener('ConfirmacionValidacionInstitucion', function() {
            Swal.fire({
                title: '¿Validar Información?',
                text: "Se validarán los datos ingresados por el usuario.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, validar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    Livewire.dispatch('ValidarRegistroInstitucion');

                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló la validación.",
                        icon: "warning"
                    });


                }
            });
        });

        document.addEventListener('InstitucionValidada', function() {

            Swal.fire({
                title: "Validado!",
                text: "La información de la institución se validó correctamente.",
                icon: "success"

            });
        });
        document.addEventListener('NoInstitucionValidada', function() {

            Swal.fire({
                title: "Error!",
                text: "Se produjo un error no se pudo validar.",
                icon: "error"

            });
        });
        document.addEventListener('error', function() {
            Swal.fire({
                title: "Error!",
                text: "Sucedió un error, verifique el error.",
                icon: "warning"
            });
        });
        document.addEventListener('institucionCreada', function() {
            Swal.fire({
                title: "Guardado!",
                text: "La institución se registró correctamente.",
                icon: "success"

            });
        });
        document.addEventListener('institucionCancelado', function() {
            Swal.fire({
                title: "Registro cancelado!",
                text: "Se canceló el registro.",
                icon: "error"

            });
        });
        document.addEventListener('asignacionGuardado', function() {
            Swal.fire({
                title: "Guardado!",
                text: "Se guardo correctamente al usuario solicitado.",
                icon: "success"

            });
        });
        document.addEventListener('noExisteUsuario', function() {
            Swal.fire({
                title: "Error usuario!",
                text: "No existe el usuario.",
                icon: "warning"

            });
        });
        document.addEventListener('Error', function() {
            Swal.fire({
                title: "Error!",
                text: "Ocurrio un error, se canceló el registro.",
                icon: "error"

            });
        });
        document.addEventListener('noHayUsuario', function() {
            Swal.fire({
                title: "Advertencia!",
                text: "La insitución no tiene usuario encargado, pero se realizará con éxito la acción.",
                icon: "success"
            });
        });

        document.addEventListener('menorEdad', function() {
            Swal.fire({
                title: "No es mayor de edad!",
                text: "Vuelva a ingresar los datos por favor.",
                icon: "warning"
            });
        });

   
    </script>
</div>
