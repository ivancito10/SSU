<x-ver-modal id="verModalCertificado" labelledby="verModalCertificadoLabel" title=" CERTIFICADO DE CAJAS ">

    <iframe src="data:application/pdf;base64,{{ $this->pdf_certificado }}#toolbar=0" width="100%"
        height="600px"></iframe>

</x-ver-modal>
<script>
    window.addEventListener('modalVerCertificadoCajas', event => {

        $('#verModalCertificado').modal('show');

        // Para scrooll del primer modal 
        $('#verModalEstudiante').css('overflow-y', 'auto');

    });
</script> 