<x-modal-small-sin-footer id="reportesAgendasplataforma" labelledby="reportesAgendasplataformaLabel"
    title="GENERAR REPORTES">
    @livewire('Plataforma.agendamiento.plataforma.reportes-agenda')
</x-modal-small-sin-footer>

<script>
    window.addEventListener('modalReportesAgendas', event => {
        $('#reportesAgendasplataforma').modal('show');
    });

    window.addEventListener('cerrarmodalReportesAgendas', event => {
        $('#reportesAgendasplataforma').modal('hide');
    });
</script>
