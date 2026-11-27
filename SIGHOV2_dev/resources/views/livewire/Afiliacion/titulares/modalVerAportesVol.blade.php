<x-ver-modal id="verAportesVoluntario" labelledby="verAportesVoluntarioLabel" title=" APORTES DE ASEGURADO ">

        @livewire('Afiliacion.titulares.titular-con-aporte-voluntario ')


</x-ver-modal>
<script>
    
    window.addEventListener('modalVerAportesVol', event => {
        
        $('#verAportesVoluntario').modal('show');
       // Para scrooll del primer modal 
       $('#verModalTitular').css('overflow-y', 'auto');

    });
</script>