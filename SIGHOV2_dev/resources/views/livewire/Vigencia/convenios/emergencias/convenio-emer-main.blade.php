<div>
    <div class="input-group pr-4 d-flex justify-content-between w-100">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> CONVENIO EMERGENCIA</b></h4>

        <div class="d-flex justify-content-end">
            <!-- Aqui el boton de reportes -->
            <x-reportes-button class="mr-1" data-toggle="modal" data-target="#modalPdfConvenioEmergencia"> REPORTE </x-reportes-button>
            <!-- Aquí va el botón registrar -->
            <x-agregar-button wire:click='convenioRegister'>REGISTRAR</x-agregar-button>
        </div>
    </div>
    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        <!-- tabla de convenios -->
        @livewire('vigencia.convenios.emergencias.convecio-emer-table')
        <!-- ver convenio de emergencias  -->
        @include('livewire.Vigencia.convenios.emergencias.modalVerConvenioEmer')

        <!-- Modal para generar reporte PDF convenio emergencia -->
        @include('livewire.Vigencia.convenios.emergencias.modalPdfConvenioEmer')

        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
</div>



<script>
    // ANULAR
    document.addEventListener('alertaAnularConvenioEmer', function() {

        Swal.fire({
            title: `Anular Convenio de emergencia?`,
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
                    Swal.showValidationMessage(`Introduzca el motivo, porfavor`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()


        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarAnulacionConvenioEmer', [result.value]);
            }
        });
    });
</script>
