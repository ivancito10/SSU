<div>
    @can('lista.codigo_institucional')
        <!--CARD LISTA PRINCIPAL-->


        <div class="input-group pr-4" style="justify-content: space-between; ">
            <h4 style="color:black;">AFILIACIÓN - <b>CÓDIGOS INSTITUCIONALES
                    SSU</b></h4>
        </div>


        <div class="card-body pt-2 pr-0 pl-0">

            @livewire('afiliacion.asignacion-codigo.codigo-institucion-ssu-table')
            <!--ACCIONES DE LA LISTA PRINCIPAL-->
        </div>
    @endcan

    @can('asignar.codigo_institucional')
        @include('livewire.Afiliacion.asignacion-codigo.AsignacionCodigoModal')
    @endcan

    @can('ver.codigo_institucional')
        @include('livewire.Afiliacion.asignacion-codigo.VerInstitucionModal')
    @endcan

    <div wire:loading>
        <x-cargando-dos />
    </div>

</div>
