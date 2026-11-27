<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#273746;">PLATAFORMA - <b>LISTA DE CONSULTORIOS</b></h4>
        @can('registrar.consultorios')
            <!-- Aquí va el botón registrar -->
            <div class="d-flex">
                <div>
                    <!-- Aquí va el botón agregar -->
                    <x-agregar-button style=" padding: 5px 10px;" wire:click="formularioRegistro"
                        title="REGISTRAR CONSULTORIO">REGISTRAR</x-agregar-button>
                </div>
            </div>
        @endcan
    </div>
    <br>
    @can('lista.consultorios')
        <div class="row">
            <div class="col-lg-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('plataforma.consultorios.consultorios-table')
            </div>
        </div>
    @endcan
    @can('registrar.consultorios')
        <!--modal para REGISTRO consultorio-->
        @include('livewire.Plataforma.consultorios.ModalFormularioRegistro')
    @endcan

    @can('ver.consultorios')
        <!--modal para REGISTRO consultorio-->
        @include('livewire.Plataforma.consultorios.ModalVerConsultorio')
    @endcan

    @can('editar.consultorios')
        <!--modal para EDITAR consultorio-->
        @include('livewire.Plataforma.consultorios.ModalEditarConsultorio')
    @endcan
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>



<script>
    document.addEventListener('confirmarCambioEstado', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar consultorio?' : '¿Deshabilitar consultorio?',
            text: !estado ?
                "Este consultorio será habilitado." : "Este consultorio será deshabilitado.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: !estado ? 'Sí, habilitar' : 'Sí, deshabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarEstado', [true]);

                Swal.fire({
                    title: !estado ? "¡Habilitado!" : "¡Deshabilitado!",
                    text: !estado ?
                        "El consultorio ha sido habilitado correctamente." :
                        "El consultorio ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "info"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el consultorio.",
                    icon: "info"
                });
            }
        });
    });


    window.addEventListener('ModalRegisterConsultorio', event => {
        $('#ModalFormularioRegistro').modal('show');
    });

    window.addEventListener('ModalRegisterConsultorioCerrar', event => {
        $('#ModalFormularioRegistro').modal('hide');
    });


    document.addEventListener('RegistroIncorrecto', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "Error",
            text: "Se cancelo el registro, verifique los datos!!!",
            icon: "error"

        });
    });
    document.addEventListener('RegistroCorrecto', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "¡Consultorio registrado!",
            text: "El consultorio ingresado ha sido creado exitosamente.",
            icon: "success"

        });
    });

    document.addEventListener('ConsultorioInhabilitado', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "¡Deshabilitado!",
            text: "Se deshabilitó el consultorio!!!",
            icon: "error"

        });
    });

    document.addEventListener('ConsultorioHabilitado', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "¡Habilitado!",
            text: "Se habilitó el consultorio!!!",
            icon: "success"

        });
    });

    document.addEventListener('EdicionIncorrectamente', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "Error",
            text: "No se pudo modificar el consultorio!!!",
            icon: "error"

        });
    });

    document.addEventListener('consultorioYaExiste', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "¡Consultorio ya registrado!",
            text: "El consultorio ingresado ya existe en el sistema.",
            icon: "error"
        });
    });


    document.addEventListener('EdicionCorrectamente', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "¡Consultorio modificado!",
            text: "El consultorio ingresado ha sido actualizado exitosamente.",
            icon: "success"

        });
    });

    document.addEventListener('datosIncompletos', function() {
        $('#ModalFormularioRegistro').modal('hide');
        Swal.fire({
            title: "Complete los datos!",
            text: "Introduzca todos los datos solicitados",
            icon: "error"

        });
    });
</script>
