<x-ver-modal id="verModalFotocopia" labelledby="verModalSangreLabel" title="  FOTOCOPIA DE CARNET DE IDENTIDAD ">

    
        @if($pdf_fotocopia)
            <iframe src="data:application/pdf;base64,{{ $this->pdf_fotocopia }}#toolbar=0"
            width="100%" height="600px" ></iframe>
        @else
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                <h5> <em style="color: #c0392b;"> No se encontró archivo. </em></h5>
            </div>
        </div>
            
        @endif 

</x-ver-modal>
<script>
    window.addEventListener('modalFotocopiaCi', event => {

        $('#verModalFotocopia').modal('show');

        // Para scrooll del primer modal 
        $('#verModalEstudiante').css('overflow-y', 'auto');

    });
</script>
