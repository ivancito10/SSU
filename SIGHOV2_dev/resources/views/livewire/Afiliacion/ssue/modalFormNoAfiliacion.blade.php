<x-ver-modal id="verModalformulario" labelledby="verModalFormularioLabel" title=" FORMULARIO DE NO AFILIACIÓN "> 
  
    <iframe src="data:application/pdf;base64,{{ $this->pdf_formulario }}#toolbar=0" width="100%" height="600px" ></iframe>



</x-ver-modal>
<script>
    window.addEventListener('modalVerformulario', event => {
        
        $('#verModalformulario').modal('show');
        


    });

</script>
