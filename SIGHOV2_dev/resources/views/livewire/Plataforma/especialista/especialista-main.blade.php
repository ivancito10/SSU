<div>
    <div class="input-group pr-4" style="justify-content: space-between">
        <h4 style="color: black">PLATAFORMA - <b>LISTA DE  ESPECIALISTAS</b></h4>
        @can('registrar.especialistas')
            <!--BOTON PARA AGREGAR NUEVO ESPECIALISTA-->
            <x-agregar-button wire:click="registrarEspecialista()" title="REGISTRAR ESPECIALISTA">
                REGISTRAR</x-agregar-button>
        @endcan
    </div>
    <br />
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.especialistas')
            <!--TABLA DE ESPECIALISTAS-->
            @livewire('plataforma.especialista.especialista-table')
        @endcan
        <div>
            @can('ver.especialistas')
                @include('livewire.Plataforma.especialista.modalVerEspecialista')
                @endcan @can('editar.especialistas')
                @include('livewire.Plataforma.especialista.modalEditarEspecialista')
                @endcan @can('ver_horario.especialistas')
                @include('livewire.Plataforma.especialista.modalActualizarAgenda')
                @endcan @can('asignar_hosrario.especialistas')
                @include('livewire.Plataforma.especialista.modalRegistroActualizar')
            @endcan
            {{-- Para el cargando --}}
            <div wire:loading>
                <x-cargando-dos />
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("activo", function() {
        Swal.fire({
            title: "Activo!",
            text: "Se actualizo con éxito.",
            icon: "success",
        });
    });

    document.addEventListener("inactivo", function() {
        Swal.fire({
            title: "Inactivo!",
            text: "Se actualizo con éxito.",
            icon: "error",
        });
    });

    /*     window.addEventListener('modalEditarEspecialista', event => {
            $('#editarModal').modal('show');
        }); */

    document.addEventListener('confirmarCambioEstado', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar especialista?' : '¿Deshabilitar especialista?',
            text: !estado ?
                "Este especialista será habilitado." : "Este especialista será deshabilitado.",
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
                        "El especialista ha sido habilitado correctamente." :
                        "El especialista ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "info"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el especialista.",
                    icon: "info"
                });
            }
        });
    });
</script>
