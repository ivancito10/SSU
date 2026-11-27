<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">PLATAFORMA - <b>LISTA DE ANULACIÓN DE FICHAS</b></h4>
        @can('registrar.anulacion_ecografia')
            <x-agregar-button wire:click="redirectoAnulacionRegister" title="REGISTRAR ANULACION">REGISTRAR</x-agregar-button>
        @endcan
    </div>
    <br>
    @can('lista.anulacion_ecografia')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Aqui va lo de el Listado-->
                @livewire('plataforma.agendamiento.ecografia.anulacion-ecografia.anulacion-ecografia-table')
            </div>
        </div>
    @endcan

    @can('ver.anulacion_ecografia')
        <!-- modal ver especialista -->
        @include('livewire.Plataforma.agendamiento.ecografia.anulacion-ecografia.modalVerAnulacion')
    @endcan
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
