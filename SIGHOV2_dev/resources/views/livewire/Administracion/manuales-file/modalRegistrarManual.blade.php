<div>
    <x-modal-sin-footer id="registrarManualModal" labelledby="registrarManualModalLabel" title="SUBIR NUEVO MANUAL PDF">
        @livewire('administracion.manuales-file.manuales-register')
    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modalRegistrarManual', () => {
        $('#registrarManualModal').modal('show');
    });

    window.addEventListener('cerrarModalRegistrarManual', () => {
        $('#registrarManualModal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');
    });
</script>
