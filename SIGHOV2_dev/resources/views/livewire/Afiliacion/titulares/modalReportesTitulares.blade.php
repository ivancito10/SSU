<x-modal-small-sin-footer id="reportesTitulares" labelledby="reportesTitularesLabel" title="GENERAR REPORTES">
    @livewire('Afiliacion.titulares.titulares-reportes')
</x-modal-small-sin-footer>

<script>
    window.addEventListener('modalTitularesReportes', event => {
        $('#reportesTitulares').modal('show');
    });

    window.addEventListener('cerrarmodalTitularesReportes', event => {
        $('#reportesTitulares').modal('hide');
    });
</script>
