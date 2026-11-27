<div>
    <x-agregar-button data-toggle="modal" data-target="#modalTitular"> AGREGAR </x-agregar-button>
    <!--MODAL PARA VER EL LISTADO DE APORTANTES-->
  
    <x-ver-modal id="modalTitular" labelledby="modalTitularLabel" title=" AGREGAR EXAMEN MÉDICO " >
           
        @livewire('Afiliacion.examen-medico.lista-titular-table')
       
    </x-ver-modal>

</div>
