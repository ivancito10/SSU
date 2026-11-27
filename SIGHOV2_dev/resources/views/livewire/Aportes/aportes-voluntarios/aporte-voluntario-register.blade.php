<div>
    <!-- Este es el modal para registras aportes de la lista de contratos con estado activo -->
    <x-agregar-button style=" padding: 5px 10px;" 
     data-toggle="modal" data-target="#aportantesListosModal"> <b>REGISTRAR</b></x-agregar-button>
        <x-ver-modal id="aportantesListosModal" labelledby="aportantesListosModalLabel" title="APORTANTES VOLUNTARIOS">
            @livewire('Aportes.AportesVoluntarios.aportantes-listos-table')
        </x-ver-modal>
</div>
