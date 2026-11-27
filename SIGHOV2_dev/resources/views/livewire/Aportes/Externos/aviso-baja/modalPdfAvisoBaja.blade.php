<x-ver-modal id="verModalPdfAviso" labelledby="verModalAvisoLabel" title=" AVISO BAJA ASEGURADO PDF ">
    
<iframe src="data:application/pdf;base64,{{ $this->pdf_vista }}#toolbar=1" width="100%" height="600px" ></iframe>

    
<!--#toolbar=0
si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('modalVerAvisoPdf', event => {
        $('#verModalPdfAviso').modal('show');
    });

</script>