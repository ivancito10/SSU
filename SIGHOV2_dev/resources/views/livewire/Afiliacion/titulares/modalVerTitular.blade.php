<div>
    <x-modal-sin-footer-grande id="verTitularModal" labelledby="verTitularModalLabel" title="AGREGAR INSTITUCIÓN"
        saveAction="">

        @livewire('afiliacion.titulares.titulares-ver')

    </x-modal-sin-footer-grande>
</div>
<script>
    document.addEventListener('abrirModalVerTitular', function() {
        $('#verTitularModal').modal('show');
    });

    document.addEventListener('cerrarModalVerTitular', function() {
        $('#verTitularModal').modal('hide');
    });
</script>
