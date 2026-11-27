<x-ver-modal id="verModalBoleta" labelledby="verModalBoletaLabel" title=" BOLETA DE INSCRIPCIÓN ">
    <iframe src="data:application/pdf;base64,{{ $this->pdf_boleta }}#toolbar=0" width="100%" height="600px"></iframe>

</x-ver-modal>
<script>
    window.addEventListener('modalVerBoleta', event => {

        $('#verModalBoleta').modal('show');

        // Para scrooll del primer modal 
        $('#verModalEstudiante').css('overflow-y', 'auto');

    });
</script>
