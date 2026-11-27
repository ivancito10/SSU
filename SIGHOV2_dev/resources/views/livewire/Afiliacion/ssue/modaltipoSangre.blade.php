<x-ver-modal id="verModalSangre" labelledby="verModalSangreLabel" title="  TIPO DE SANGRE ">
 
    <iframe src="data:application/pdf;base64,{{ $this->pdf_tipo_sangre }}#toolbar=0" width="100%" height="600px" loading="lazy"></iframe>

</x-ver-modal>
<script>
    window.addEventListener('modalTipoSangre', event => {
        
        $('#verModalSangre').modal('show');

    });

</script>
