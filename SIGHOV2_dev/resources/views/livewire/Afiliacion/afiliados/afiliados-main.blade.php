<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">AFILIACIÓN - <b> LISTA DE AFILIADOS</b></h4>
    </div>
    
    @can('lista.afiliado_historial')
        @livewire('Afiliacion.Afiliados.afiliados-table')
    @endcan

    @can('ver.afiliado_historial')
        {{-- @livewire('Afiliacion.afiliados.historial-afiliados') --}}
        @include('livewire.Afiliacion.afiliados.modalVerAfiliados')
    @endcan

    @can('grupo_familiar.afiliado_historial')
        @include('livewire.Afiliacion.afiliados.modalGrupoFamiliarAfiliado')
    @endcan

</div>
