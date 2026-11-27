<div>@can('registrar.aportantes_voluntarios')
        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:#212f3c ;">APORTES - <b> LISTA DE VOLUNTARIOS AFILIADOS</b></h4>
            <!-- Aqui va el boto agregar-->
            @livewire('Aportes.aportes-voluntarios.aportantes-voluntarios.aportantes-voluntarios-register')

        </div>
        <br>
    @endcan
    @can('menu.aportantes_voluntarios')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <!-- Aqui va lo de el Listado-->
                @livewire('Aportes.AportesVoluntarios.aportantes-voluntarios.aportantes-voluntarios-table')
                @can('ver.aportantes_voluntarios')
                    <!-- modal ver aportante -->
                    @include('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.modalVerAportante')
                @endcan
                @can('editar.aportantes_voluntarios')
                    <!-- modal editar aportante -->
                    @include('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.modalEditarAportante')
                @endcan
                @can('ver.aportantes_voluntarios')
                    <!-- modal registrao de aportes realizados por el aportante -->
                    @include('livewire.Aportes.aportes-voluntarios.aportantes-voluntarios.modalRegistroAportes')
                @endcan
            </div>
        </div>
        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
    @endcan
    <script>
        window.addEventListener('openModal', event => {
            $('#editarContratoModal').modal('show');
        });

        // esto para el modal VER
        window.addEventListener('openModalVer', event => {
            $('#verContratoModal').modal('show');
        });

        //FUNCION DEL BOTON GUARADR 
        function editarContrato() {
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
                    Livewire.dispatch('renovarContrato');
                    $('#editarContratoModal').modal('hide');
                } else {
                    Swal.fire({
                        title: "Completa todos los datos!",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                    @this.set('modal', true);
                    $('#editarContratoModal').modal('hide');
                }
            });
        }

        function contratoGuardar() {
            Swal.fire({
                title: "Guardado!",
                text: "Se registro correctamente",
                icon: "success"

            });
        }

        function contratoCancelar() {
            Swal.fire({
                title: "No se guardaron los datos",
                text: "Se cancelo el registro",
                icon: "error"

            });
        }

        document.addEventListener('contratoRenovado', function() {
            contratoGuardar();
        });
        document.addEventListener('contratoCancelado', function() {
            contratoCancelar();
        });


        document.addEventListener('contratoDeshabilitado', function() {
            Swal.fire({
                title: ' Se deshabilito el contrato!',
                text: 'El registro del contrato ahora esta inactivo.',
                icon: 'success'
            });
        });

        document.addEventListener('contratoHabilitado', function() {
            Swal.fire({
                title: ' Contrato habilitado!',
                text: 'El contrato ahora esta habilitado.',
                icon: 'error'
            });
        });
        document.addEventListener('error', function() {
            Swal.fire({
                title: "Error!",
                text: "Sucedió un error, verifique el error.",
                icon: "warning"
            });
        });

        document.addEventListener('menorEdad', function() {
            Swal.fire({
                title: "Error de edad!",
                text: "Verifique la fecha de nacimiento.",
                icon: "warning"
            });
            Livewire.dispatch('cancelar');
        });
        document.addEventListener('error', function() {
            Swal.fire({
                title: "Falta de datos!",
                text: "Debe completar todos los datos.",
                icon: "warning"
            });
            Livewire.dispatch('cancelar');
        });
    </script>
</div>
