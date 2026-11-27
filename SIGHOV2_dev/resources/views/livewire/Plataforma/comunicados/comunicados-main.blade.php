<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">PLATAFORMA - <b> COMUNICADOS</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                @livewire('Plataforma.comunicados.comunicados-register')
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            @livewire('Plataforma.comunicados.comunicados-table')
        </div>
        @include('livewire.Plataforma.comunicados.modalVerComunicados')
    </div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

</div>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('cambiarEstadoComunicado', function() {
        let estado = @this.get('estado');
        Swal.fire({
            title: !estado ? '¿Habilitar comunicado?' : '¿Deshabilitar comunicado?',
            text: !estado ?
                "Este comunicado será habilitado." : "Este comunicado será deshabilitado.",
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
                        "El comunicado ha sido habilitado correctamente." :
                        "El comunicado ha sido deshabilitado correctamente.",
                    icon: !estado ? "success" : "success"
                });
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se realizaron cambios en el comunicado.",
                    icon: "warning"
                });
            }
        });
    });
</script>
