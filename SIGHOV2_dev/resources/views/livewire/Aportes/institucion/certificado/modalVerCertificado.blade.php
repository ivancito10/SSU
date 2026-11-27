<x-ver-modal id="verModalPdf" labelledby="verModalLabel" title=" ARCHIVO PDF CERTIFICADO ">
    <iframe src="data:application/pdf;base64,{{ $this->pdf_vista }}#toolbar=1" width="100%" height="600px" ></iframe>

    
<!--
#toolbar=0
si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('modalVerCertificado', event => {
        $('#verModalPdf').modal('show');
    });

</script>
