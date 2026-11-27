<x-ver-modal id="verPersonas" labelledby="verPersonasLabel" title=" BAJA MÉDICA - LISTA DE PERSONAS  ">
   
    <!--LISTA PARA REGISTRAR BAJAS MEDICAS MANUALES-->
    @livewire('vigencia.bajas-medicas.baja-afiliados-table')
       
</x-ver-modal>
<script>
    window.addEventListener('modalListaPersonas', event => {

        $('#verPersonas').modal('show');

    });
</script>

