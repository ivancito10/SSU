<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#273746;">PLATAFORMA - <b>LISTA DE  ESPECIALIDADES</b></h4>
        <!-- Aquí va el botón registrar -->
        @can('registrar.especialidades')
            <div class="d-flex">
                <div>
                    <!-- Aquí va el botón agregar -->
                    <x-agregar-button wire:click="abrirModal"
                        title="REGISTRAR ESPECIALIDAD">REGISTRAR</x-agregar-button>
                </div>
            </div>
        @endcan
    </div>
    <br>
    @can('lista.especialidades')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('plataforma.especialidades.especialidades-table')
            </div>
        </div>
    @endcan

    @can('registrar.especialidades')
        <!--modal para agregar nueva especialidad-->
        @include('livewire.Plataforma.especialidades.modalAgregarEspecialidad')
    @endcan

    @can('ver.especialidades')
        <!--    este es el modal para editar las especiallidades -->
        @include('livewire.Plataforma.especialidades.modalVerEspecialidad')
    @endcan

    @can('editar.especialidades')
        <!--    este es el modal para editar las especiallidades -->
        @include('livewire.Plataforma.especialidades.modalEditarEspecialidad')
    @endcan

    @can('asignar_servicio.especialidades')
        <!--    este es el modal para ver las especiallidades -->
        @include('livewire.Plataforma.especialidades.especialidad-servicio.modalVerServiciosEspecialidad')
    @endcan
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>

<script>
    document.addEventListener('especialidadGuardado', function() {
        Swal.fire({
            title: 'Guardado!',
            text: "La especialidad se registro con éxito.",
            icon: 'success'
        });
        $('#verAgregarModal').modal('hide');
    });

    document.addEventListener('noDatosIntroducidos', function() {
        Swal.fire({
            title: 'Complete los datos!',
            text: "Introduzca los datos solicitados",
            icon: 'error'
        });
    });

    document.addEventListener('especialidadYaExiste', function() {
        Swal.fire({
            title: '¡Especialidad ya registrada!',
            text: "La especialidad ingresada ya existe en el sistema.",
            icon: 'error'
        });
    });

    document.addEventListener('confirmarCambioEstado', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar especialidad?' : '¿Deshabilitar especialidad?',
            text: !estado ?
                "Esta especialidad será habilitado." : "Esta especialidad será deshabilitado.",
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
                    title: !estado ? "¡Habilitada!" : "¡Deshabilitada!",
                    text: !estado ?
                        "La especialidad ha sido habilitado correctamente." :
                        "La especialidad ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "info"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en la especialidad.",
                    icon: "info"
                });
            }
        });
    });
</script>
