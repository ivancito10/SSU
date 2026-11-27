<div>

    <x-agregar-button data-toggle="modal" data-target="#afiliadosModal"> REGISTRAR</x-agregar-button>


    <x-ver-modal id="afiliadosModal" labelledby="afiliadosModalLabel" title="LISTADO DE AFILIADOS">
        @livewire('Vigencia.SolicitudPrestacionMedica.afiliados-table')
    </x-ver-modal>
    
</div>
