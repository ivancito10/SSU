<div>
    <x-modal-sin-footer id="agregarInstitucionModal" labelledby="agregarInstitucionModalLabel" title="AGREGAR INSTITUCIÓN"
        saveAction="">
        @livewire('afiliacion.titulares.agregar-institucion')
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('modalAgregarNuevaInst', function() {
        $('#agregarInstitucionModal').modal('show');
    });

    document.addEventListener('cerrarModalAgregarNuevaInst', function() {
        $('#agregarInstitucionModal').modal('hide');
    });
</script>
