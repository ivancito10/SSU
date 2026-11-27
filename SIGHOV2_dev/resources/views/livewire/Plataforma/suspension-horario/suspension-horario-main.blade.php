<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">PLATAFORMA - <b>LISTA DE SUSPENCIÓN DE HORARIOS</b></h4>
        <div class="d-flex">
            @can('registrar.suspension_horarios')
                <div class="mr-2">
                    <x-agregar-button wire:click="agregarSuspensionMasiva" title="REGISTRAR SUSPENCION MASIVA">REGISTRO MASIVO
                    </x-agregar-button>
                </div>
            @endcan
            @can('registrar.suspension_horarios')
                <x-agregar-button wire:click="agregarSuspencion" title="REGISTRAR SUSPENCION">REGISTRAR </x-agregar-button>
            @endcan
        </div>
    </div>
    <br>
    @can('lista.suspension_horarios')
        <div class="card-body pt-2 pr-0 pl-0">
            @livewire('plataforma.suspension-horario.suspension-horario-table')
        </div>
    @endcan

    @can('ver.suspension_horarios')
        <!--modal ESPECIALISTAS -->
        @include('livewire.Plataforma.suspension-horario.ModalListadoEspecialistas')
    @endcan

    @can('ver.suspension_horarios')
        <!--modal ESPECIALISTAS -->
        @include('livewire.Plataforma.suspension-horario.modalVerSuspension')
    @endcan

    @can('ver.suspension_horarios')
        <div wire:loading>
            <x-cargando-dos />
        </div>
    @endcan

</div>
<script>
    window.addEventListener('modalVerSuspension', event => {

        $('#verModalSuspensionHorario').modal('show');
    });

    document.addEventListener('preguntarAnular', function() {
        Swal.fire({
            title: '¿Desea anular la suspensión?',
            icon: 'question',
            text: 'Describa el motivo de la anulación:',
            input: 'text',
            inputPlaceholder: 'Escriba el motivo aquí...',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-success mr-2', 
                cancelButton: 'btn btn-danger'
            },
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage('Introduzca la observación por favor');
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarAnulacion', {
                    data: result.value
                });
            }
        });
    });


    document.addEventListener('anuladoCorrectamente', function() {
        Swal.fire({
            title: "Guardado",
            text: "La suspensión de horario se anuló exitosamente.",
            icon: "success"
        });
    });
</script>
