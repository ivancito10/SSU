<x-modal-small-sin-footer id="reportesEstudiantes" labelledby="reportesEstudiantesLabel" title="GENERAR REPORTES">
    @livewire('Afiliacion.estudiantes-ssue.estudiantes-reportes')
</x-modal-small-sin-footer>

<script>
    window.addEventListener('modalReportesEstudiantes', event => {
        $('#reportesEstudiantes').modal('show');
    });

    window.addEventListener('cerrarmodalReportesEstudiantes', event => {
        $('#reportesEstudiantes').modal('hide');
    });
</script>
