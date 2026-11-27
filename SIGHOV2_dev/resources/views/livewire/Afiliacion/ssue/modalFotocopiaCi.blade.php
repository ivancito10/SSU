<x-ver-modal id="verModalFotocopia" labelledby="verModalSangreLabel" title="  FOTOCOPIA DE CARNET DE IDENTIDAD ">

    <iframe src="data:application/pdf;base64,{{ $pdf_fotocopia }}#toolbar=0" width="100%" height="600px"></iframe>
    <!--s

#toolbar=0
si quito esta extension me sale las opciones del pdf...
    
-->

</x-ver-modal>
<script>
    window.addEventListener('modalFotocopiaCi', event => {
        $('#verModalFotocopia').modal('show');
    });
</script>
