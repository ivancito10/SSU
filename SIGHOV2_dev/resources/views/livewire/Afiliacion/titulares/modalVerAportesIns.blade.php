<div>
    <x-ver-dos-modal id="verAportesTitular" labelledby="verAportesTitularLabel" title="APORTES DE ASEGURADO">
        @livewire('Afiliacion.Titulares.titular-con-aporte-institucional')

    </x-ver-dos-modal>


    <script>
        document.addEventListener('modalVerAportesIns', function() {
            $('#verAportesTitular').modal('show');
        });

        /*   window.addEventListener('modalVerAportesIns', event => {

              $('#verAportesTitular').modal('show');

              // Para scrooll del primer modal 
              $('#verModalTitular').css('overflow-y', 'auto');
          }); */
    </script>
</div>
