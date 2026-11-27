<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">AGREGAR NUEVA
                ANULACIÓN</h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="volver" wire:loading.attr="disabled" wire:target="volver">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="volver">
                <x-cargando-dos />
            </div>
        </div>
    </div>

    <br>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">  <b>LISTA DE AGENDAS REGISTRADAS</b></h4>
    </div>
    @can('lista.anulacion_fichas')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('plataforma.anulacion.seleccionar-agenda-table')
            </div>
        </div>
    @endcan

</div>
