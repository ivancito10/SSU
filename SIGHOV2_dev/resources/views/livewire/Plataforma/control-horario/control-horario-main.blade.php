<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">PLATAFORMA - <b> CONTROL DE HORARIOS</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                @livewire('Plataforma.control-horario.control-horario-register')
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            @livewire('Plataforma.control-horario.control-horario-table')
        </div>
        @include('livewire.Plataforma.control-horario.modalVerControlHorario')
        @include('livewire.Plataforma.control-horario.modalEditarControlHorario')
    </div>
</div>
<script>
    document.addEventListener('cambiarEstadoControlHorarios', function() {
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
