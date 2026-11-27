<x-modal-sin-footer id="estudianteAntiguo" labelledby="estudianteAntiguoLabel"
    title=" MANUAL PARA REGISTRO DE ESTUDIANTE ANTIGUO ">

    <iframe src="{{ asset('docs/afiliacion/MANUAL USO-ESTUDIANTE ANTIGUO.pdf') }}#toolbar=0" width="100%"
        height="600px"></iframe>

</x-modal-sin-footer>
<script>
    window.addEventListener('mostrarEstudianteAntiguoManualPdf', event => {
        $('#estudianteAntiguo').modal('show');
    });
</script>
