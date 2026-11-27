<!-- MODAL EDITAR REPRESENTANTE LEGAL -->
<x-modal-sin-footer id="modalReporteAfiliados" labelledby="modalReporteAfiliadosLabel"
    title="REPORTE DE ASEGURADOS POR INSTITUCIÓN">


    @livewire('aportes.institucion.institucion-aportantes')

</x-modal-sin-footer>
<script>
    window.addEventListener('abrirModalReportesAfiliados', event => {
        $('#modalReporteAfiliados').modal('show');
    });


    /*     document.addEventListener('sinDAtosReportesInjst', function() {
            Swal.fire({
                title: "Completa todos los datos",
                text: "Porfavor ingresa todos los campos del fomulario",
                icon: "error"
            });
        }); */
</script>
