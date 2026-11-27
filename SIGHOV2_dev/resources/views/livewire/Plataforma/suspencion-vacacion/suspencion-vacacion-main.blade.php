<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">PLATAFORMA - <b>LISTA DE VACACIÓN A ESPECIALISTAS</b></h4>
    </div>
    <br>
    @can('lista.suspension_vacaciones')
        <div class="card-body pt-2 pr-0 pl-0">
            <!--   Esto es para el table -->
            @livewire('Plataforma.suspencion-vacacion.especialistas-suspencion-table')
        </div>
    @endcan
    @can('registrar.suspension_vacaciones')
        <!-- Para suspencion de vacaciones -->
        @include('livewire.Plataforma.suspencion-vacacion.modalRegistrarVacacion')
    @endcan

    @can('ver.suspension_vacaciones')
        <!-- Para ver la de vacaciones -->
        @include('livewire.Plataforma.suspencion-vacacion.modalVerSuspencion')
    @endcan

    @can('desabilitar.suspension_vacaciones')
        <!-- Para anular de vacaciones -->
        @include('livewire.Plataforma.suspencion-vacacion.modalAnularVacacion')
        @include('livewire.Plataforma.suspencion-vacacion.modalEditarVacacion')
    @endcan
    <!-- Modal para ver los agendados -->
    @include('livewire.Plataforma.suspencion-vacacion.modalVerAgendados')

    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>

    <script>
        document.addEventListener('epecialistaNoHabilitado', function() {
            Swal.fire({
                title: "Especislita inhabilitado",
                text: "El especialista no se encuentra habilitado, no se puede registrar una vacación",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });
    </script>

</div>
