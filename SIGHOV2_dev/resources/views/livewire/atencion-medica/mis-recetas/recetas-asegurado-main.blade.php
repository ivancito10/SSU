<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">ATENCION MEDICA - <b> LISTA DE RECETAS</b></h4>
    </div>
    <br>
    <livewire:atencion-medica.mis-recetas.recetas-table />
    @include('livewire.atencion-medica.mis-recetas.modalDetalleReceta')
</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('mostrarSweetAlert', (event) => {
            // Livewire v3 envía los parámetros dentro de un array en event[0]
            const data = event[0];
            Swal.fire({
                title: data.titulo,
                text: data.mensaje,
                icon: data.tipo, // 'error', 'warning', 'info', etc.
                // showConfirmButton: false,
                // timer: 1500
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6',
            });
        });
    });
</script>
