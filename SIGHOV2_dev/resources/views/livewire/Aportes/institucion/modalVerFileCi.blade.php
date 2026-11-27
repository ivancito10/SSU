<x-ver-modal id="verModalPdfCi" labelledby="verModalCiLabel" title="ARCHIVO FILE PDF - CARNET DE IDENTIDAD">
    @if($pdf_vista_ci)
        <iframe src="data:application/pdf;base64,{{ $this->pdf_vista_ci }}#toolbar=0"
        width="100%" height="600px" ></iframe>
    @else
        <h5> <em style="color: #c0392b ;">
         Nro. de documento no encontrado. </em></h5>
    @endif 
    
        <!--
        #toolbar=0
        si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('modal_ver_carnet', event => {
        
        $('#verModalPdfCi').modal('show');

    });

</script>