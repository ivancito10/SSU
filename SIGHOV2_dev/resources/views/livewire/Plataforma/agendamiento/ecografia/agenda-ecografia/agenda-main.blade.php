<div>

    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">PLATAFORMA - <b> AGENDAMIENTO ECOGRAFÍA</b></h4>
        <div class="d-flex">

            <div>
                @can('agendar.agenda_ecografia')
                    <x-agregar-button wire:click="redirectAgendamiento">AGENDAR</x-agregar-button>
                @endcan

            </div>
        </div>


    </div>
    <br>

    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.agenda_ecografia')
            @livewire('plataforma.agendamiento.ecografia.agenda-ecografia.agenda-table')
        @endcan

        @can('ver.agenda_ecografia')
            @include('livewire.Plataforma.agendamiento.ecografia.agenda-ecografia.modalVerAgendaTabla')
        @endcan
    </div>


    <!--modal para ver bajas medicas-->
    {{-- @include('livewire.vigencia.bajas-medicas.modalVerBajaMedica') --}}
    <!--modal para entregar bajas medicas-->
    {{-- @include('livewire.vigencia.bajas-medicas.modalEntregarBajaMedica') --}}

    {{-- Para el cargando --}}
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>

<script>
    //VALIDAR
    document.addEventListener('alertaValidar', function() {
        Swal.fire({
            title: '¿Validar?',
            text: "Se validará el registro seleccioando.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, validar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarValidacion');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la validación",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('guardado', function() {

        Swal.fire({
            title: "<b>Guardado!</b>",
            text: "Se valído la información!",
            icon: "success"

        });
    });

    document.addEventListener('noValidado', function() {

        Swal.fire({
            title: "<b>Error!</b>",
            text: "Sucedio un error, verifique la información!",
            icon: "error"

        });
    });

    // ANULAR
    document.addEventListener('alertaAnular', function() {

        Swal.fire({
            title: `Anular baja médica?`,
            icon: 'question',
            text: 'Describa los detalles a continuación:',
            input: "text",
            //inputLabel: "OBSERVACIÓN: ",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Guardar",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca la observación porfavor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()


        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarAnulacion', [result.value]);
            }
        });
    });
</script>
