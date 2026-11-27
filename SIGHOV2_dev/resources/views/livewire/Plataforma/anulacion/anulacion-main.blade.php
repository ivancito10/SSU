<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">PLATAFORMA - <b>LISTA DE ANULACIÓN DE FICHAS</b></h4>
        @can('registrar.anulacion_fichas')
            <x-agregar-button wire:click="redirectoAnulacionRegister" title="REGISTRAR ANULACION">REGISTRAR</x-agregar-button>
        @endcan
    </div>
    <br>
    @can('lista.anulacion_fichas')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('plataforma.anulacion.anulacion-table')
            </div>
        </div>
    @endcan

    @can('ver.anulacion_fichas')
        <!-- modal ver especialista -->
        @include('livewire.Plataforma.anulacion.modalVerAnulacion')
    @endcan
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
