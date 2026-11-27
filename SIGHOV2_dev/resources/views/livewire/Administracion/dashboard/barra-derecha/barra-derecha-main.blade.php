<div>
    @can('super-admin-only')
        <div class="card card-outline card-primary bg-transparent">
            <div class="card-body bg-transparent">
                <x-subir-archivo-button wire:click="actualizarCache" title="REGISTRAR INSTITUCION"
                    class="btn btn-primary btn-block">
                    RECARGAR CACHE
                </x-subir-archivo-button>
            </div>
        </div>
    @endcan

    @can('personalssu.normativas_asuss')
        <div class="border rounded shadow-sm p-3 bg-transparent">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0 text-white">Documentos técnicos normativos</h5>
            </div>
            <button class="btn btn-outline-light w-100"
                onclick="window.open('https://esalud.asuss.gob.bo/', '_blank', 'width=800,height=600')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-box-arrow-up-right me-2"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6.364 13.364a.5.5 0 0 1-.708 0l-4.95-4.95a.5.5 0 0 1 0-.708l4.95-4.95a.5.5 0 0 1 .708.708L2.707 7.5H13.5a.5.5 0 0 1 0 1H2.707l3.657 3.657a.5.5 0 0 1 0 .707z" />
                </svg>
                Ir al sitio
            </button>
        </div>
    @endcan
</div>
<script>
    document.addEventListener('mostrarSalida', function(data) {
        Swal.fire({
            title: 'El cache fue actualizado',
            text: data.detail.message,
            icon: 'success',
        });
    });
</script>
