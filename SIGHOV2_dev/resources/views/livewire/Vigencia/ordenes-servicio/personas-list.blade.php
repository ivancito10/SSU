<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <x-agregar-button data-toggle="modal" data-target="#modalSeleccionarPersona"> AGREGAR </x-agregar-button>
        </div>
    </div>

    <x-ver-modal id="modalSeleccionarPersona" labelledby="modalSeleccionarPersonaLabel"
        title=" ORDENES DE SERVICIO - LISTA DE PERSONAS ">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!--tabla para buscar a la persona -->
                @livewire('Vigencia.OrdenesServicio.persona-select-table')
            </div>
        </div>
    </x-ver-modal>

</div>
