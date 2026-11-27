<x-ver-modal id="verModalMatricula" labelledby="verModalMatriculaLabel" title="  MATRICULA ">
 
    <iframe src="data:application/pdf;base64,{{ $pdf_matricula }}#toolbar=0" width="100%" height="600px" ></iframe>

</x-ver-modal>
<script>
    window.addEventListener('modalVerMatricula', event => {
        
        $('#verModalMatricula').modal('show');
        
        // Para scrooll del primer modal 
        $('#verModalEstudiante').css('overflow-y', 'auto');

    });

</script>
