<x-ver-modal id="verModalPdfNit" labelledby="verModalNitLabel" title="ARCHIVO FILE PDF - NIT ">
    @if($pdf_vista)
        <iframe src="data:application/pdf;base64,{{ $this->pdf_vista }}#toolbar=0"
        width="100%" height="600px" ></iframe>
    @else
        <h5> <em style="color: #c0392b ;">
          NIT no encontrado. </em></h5>
    @endif 
    
        <!--
        #toolbar=0
        si quito esta extension me sale las opciones del pdf...-->
</x-ver-modal>
<script>
    window.addEventListener('modal_ver_nit', event => {
        
        $('#verModalPdfNit').modal('show');
        // Para scrooll del primer modal lista de instituciones
        $('#vistaModal').css('overflow-y', 'auto');
        $('#vistaModal').modal('show');
        

    });

</script>