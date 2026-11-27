<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#273746;">PLATAFORMA - <b> LISTA DE HORARIOS</b></h4>
        @can('registrar.horarios')
            <!-- Aquí va el botón registrar -->
            <div class="d-flex">
                <div>
                    <!-- Aquí va el botón y modal de registro horario -->
                    @livewire('Plataforma.horarios.horarios-register')
                </div>
            </div>
        @endcan
    </div>
    <br>
    @can('lista.horarios')
        <div class="row">
            <div class="col-lg-12">
                <!-- Se muestra la tabla de listado de horarios-->
                @livewire('Plataforma.horarios.horarios-table')
            </div>
        </div>
    @endcan

    @can('ver.horarios')
        <!-- Esto es para ver el ver el horario  -->
        @include('livewire.Plataforma.horarios.modalVerHorario')
    @endcan

    @can('editar.horarios')
        <!-- Esto es para editar el horario  -->
        @include('livewire.Plataforma.horarios.modalEditarHorario')
    @endcan
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>


<script>
    document.addEventListener('activo', function() {
        Swal.fire({
            title: 'Activo!',
            text: "Se habilitó el horario.",
            icon: 'success'
        });
    });

    document.addEventListener('inactivo', function() {
        Swal.fire({
            title: 'Inactivo!',
            text: "Se deshabilitó el horario.",
            icon: 'error'
        });
    });

    document.addEventListener('horarioEditadoDuplicado', function() {
        Swal.fire({
            title: 'El mismo horario e intervalo ya existen!',
            text: "Verifique los datos e intente nuevamente",
            icon: 'error'
        });
    });

    document.addEventListener('confirmarCambioEstado', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar horario?' : '¿Deshabilitar horario?',
            text: !estado ?
                "Este horario será habilitado." : "Este horario será deshabilitado.",
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
                        "El horario ha sido habilitado correctamente." :
                        "El horario ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "success"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el horario.",
                    icon: "warning"
                });
            }
        });
    });
</script>
