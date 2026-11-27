<x-ver-modal id="verPdf" labelledby="verLabel" title=" ARCHIVO PDF LIQUIDACIÓN ASUSS ">
    
    <iframe src="data:application/pdf;base64,{{ $this->pdf_vista }}#toolbar=1"
    width="100%" height="600px" ></iframe>

    
<!--
#toolbar=0
si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('mostrarAsussPdf', event => {
        $('#verPdf').modal('show');
    });

</script>
