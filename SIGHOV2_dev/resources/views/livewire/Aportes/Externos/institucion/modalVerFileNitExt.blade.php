<x-ver-modal id="verModalPdfNitExt" labelledby="verModalNitExtLabel" title="ARCHIVO FILE PDF - NIT">
    @if($pdf_vista_nit)
        <iframe src="data:application/pdf;base64,{{ $this->pdf_vista_nit }}#toolbar=0"
        width="100%" height="600px" ></iframe>
    @else
        <h5> <em style="color: #c0392b ;">
         Documento Pdf no encontrado </em></h5>
    @endif 
    
        <!--
        #toolbar=0
        si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('modal_ver_nit_ext', event => {
        
        $('#verModalPdfNitExt').modal('show');
        
    });

</script>