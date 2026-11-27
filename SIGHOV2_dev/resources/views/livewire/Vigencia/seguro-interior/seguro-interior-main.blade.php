<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> SEGUROS DEL
                INTERIOR</b></h4>

    </div>
    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.seguros_interior')
        @livewire('Vigencia.SeguroInterior.seguro-interior-table')
        @endcan

        @can('ver.seguros_interior')
        @include('livewire.Vigencia.seguro-interior.ModalUbicacionSeguroInterior')
        @endcan

        @can('editar.seguros_interior')
        <!--modal para EDITAR seguro interior-->
        {{--
        @include('livewire.Vigencia.seguro-interior.modalEditarSeguroInterior')
        --}}
        @endcan

        @can('responsable.seguros_interior')
        <!--modal para ASIGNAR USUARIO seguro interior-->
        @include('livewire.Vigencia.seguro-interior.ModalAsignarUsuarioResponsable')
        @endcan

        @can('ver.seguros_interior')
        <!--modal para VER seguro interior-->
        @include('livewire.Vigencia.seguro-interior.ModalVerSeguroInterior')
        <!--modal para VER seguro interior-->
        @endcan

        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

</div>
<script>
    function guardarAsignacion() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán la asignación",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('asignarUsuarioSeguroInterior');
                $('#AsignarModalSeguro').modal('hide');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

                $('#AsignarModalSeguro').modal('hide');
            }
        });
    }
</script>
