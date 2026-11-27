<div>

    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">PLATAFORMA - <b> AGENDA DE ESPECIALISTA</b></h4>
    </div>
    <br>

    @can('lista.agenda_especialista')
        <div class="card-body pt-2 pr-0 pl-0">
            @livewire('plataforma.agenda-especialista.agenda-especialista-table')
        </div>
    @endcan
    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
