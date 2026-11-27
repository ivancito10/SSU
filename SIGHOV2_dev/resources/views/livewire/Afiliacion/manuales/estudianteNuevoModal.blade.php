<x-modal-sin-footer id="estudianteNuevo" labelledby="estudianteNuevoLabel"
    title=" MANUAL PARA REGISTRO DE ESTUDIANTE NUEVO ">

    <iframe src="{{ asset('docs/afiliacion/MANUAL USO-ESTUDIANTE NUEVO.pdf') }}#toolbar=0" width="100%"
        height="600px"></iframe>

</x-modal-sin-footer>
<script>
    window.addEventListener('mostrarEstudianteNuevoManualPdf', event => {
        $('#estudianteNuevo').modal('show');
    });
</script>
