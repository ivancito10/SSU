<x-modal-small-sin-footer id="reportesBeneficiarios" labelledby="reportesBeneficiariosLabel" title="GENERAR REPORTES">
    @livewire('Afiliacion.beneficiarios.beneficiarios-reportes')
</x-modal-small-sin-footer>

<script>
    window.addEventListener('modalBeneficiariosReportes', event => {
        $('#reportesBeneficiarios').modal('show');
    });

    window.addEventListener('cerrarmodalBeneficiariosReportes', event => {
        $('#reportesBeneficiarios').modal('hide');
    });
</script>
