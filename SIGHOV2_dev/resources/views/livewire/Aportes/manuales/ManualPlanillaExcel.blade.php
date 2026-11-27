<x-ver-modal id="manualExcelPdf" labelledby="manualExcelPdfLabel" title=" MANUAL DE LLENADO PLANILLA EXCEL ">

    <iframe src="{{ asset('docs/aportes/Oficial Llenado de Planillas en Excel para el SSU.pdf') }}#toolbar=1" width="100%"
        height="600px"></iframe>

</x-ver-modal>
<script>
    window.addEventListener('mostrarManualExcel', event => {
        $('#manualExcelPdf').modal('show');
    });
</script>