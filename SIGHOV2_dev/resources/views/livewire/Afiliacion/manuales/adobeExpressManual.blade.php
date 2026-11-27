<x-modal-sin-footer id="adobeExpressPdf" labelledby="adobeExpressPdfLabel" title=" MANUAL PARA EDICIÓN DE FOTOS ">

    <iframe src="{{ asset('docs/afiliacion/MANUAL_USO-ADOBE_EXPRESS.pdf') }}#toolbar=0" width="100%"
        height="600px"></iframe>

</x-modal-sin-footer>
<script>
    window.addEventListener('mostrarAdobeExpressManualPdf', event => {
        $('#adobeExpressPdf').modal('show');
    });
</script>
