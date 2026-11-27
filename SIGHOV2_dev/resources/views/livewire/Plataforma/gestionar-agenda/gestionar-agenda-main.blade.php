<div>

    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">PLATAFORMA - <b> GESTIÓN DE AGENDA</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                <x-agregar-button wire:click="registrarGestionAgenda()" title="REGISTRAR GESTIÓN AGENDA">
                    REGISTRAR</x-agregar-button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            @livewire('Plataforma.gestionar-agenda.gestionar-agenda-table')
        </div>
        @include('livewire.Plataforma.gestionar-agenda.modalVerGestionAgenda')
        @include('livewire.Plataforma.gestionar-agenda.modalEditarGestionAgenda')
    </div>
</div>
<script>
    document.addEventListener('confirmarCambioEstadoGestionAgenda', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar servicio?' : '¿Deshabilitar servicio?',
            text: !estado ?
                "Este servicio será habilitado." : "Este servicio será deshabilitado.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: !estado ? 'Sí, habilitar' : 'Sí, deshabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('actualizarEstadoGestionAgenda', [true]);

                Swal.fire({
                    title: !estado ? "¡Habilitado!" : "¡Deshabilitado!",
                    text: !estado ?
                        "El servicio ha sido habilitado correctamente." :
                        "El servicio ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "success"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el servicio.",
                    icon: "warning"
                });
            }
        });
    });
</script>
