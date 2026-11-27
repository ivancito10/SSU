<div>
    <x-agregar-button data-toggle="modal" data-target="#modalTitular"> <b>REGISTRAR</b></x-agregar-button>
    <!--MODAL PARA VER EL LISTADO DE APORTANTES-->
  
    <x-ver-modal-sin-titulo id="modalTitular" labelledby="modalTitularLabel" title=" APORTANTES LISTOS PARA AFILIARSE " >
      
        <div class="input-group pr-4"  style="justify-content: space-between; ">
            <h4 style="color:black;">AFILIACIÓN - <b> APORTANTES LISTOS PARA AFILIARSE</b></h4>
            <x-agregar-button wire:click="agregarNuevo()"><b>REGISTRO MANUAL</b> </x-agregar-button> 
        </div>
       <br>        
        @livewire('Afiliacion.titulares.listos-afiliarse-table')

    </x-ver-modal-sin-titulo>
</div>
