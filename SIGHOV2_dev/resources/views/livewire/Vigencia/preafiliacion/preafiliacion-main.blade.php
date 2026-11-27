<div>
    <div class="input-group pr-4 d-flex justify-content-between w-100">
        <h4 style="color:black;"> AFILIACIÓN - <b> LISTA DE PREAFILIACIONES </b></h4>
        @can('registro.preafilaicion')
            <div class="d-flex justify-content-end">
                <!-- Aquí va el botón registrar -->
                <x-agregar-button wire:click='registrarPreafiliacion'>REGISTRAR</x-agregar-button>
            </div>
        @endcan
    </div>
    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.preafilaicion')
            <!-- tabla de convenios -->
            @livewire('Vigencia.Preafiliacion.preafiliacion-table')
        @endcan
        @can('ver.preafilaicion')
            <!-- ver preafiliacion  -->
            @include('livewire.Vigencia.preafiliacion.modalVerPreafiliacion')
        @endcan
        @can('pdf.preafilaicion')
            <!-- Modal para generar reporte PDF convenio emergencia -->
            {{-- @include('livewire.Vigencia.convenios.emergencias.modalPdfConvenioEmer') --}}
        @endcan

        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

    <script>
        document.addEventListener('alertaAnularPreafiliacion', function() {

            Swal.fire({
                title: '<b>¿Desea anular la preafiliación?</b>',
                icon: 'question',
                text: 'Describa los detalles a continuación:',
                /*input: "text",
                //inputLabel: "OBSERVACIÓN: ",
                inputAttributes: {
                    autocapitalize: "off"
                },*/
                showCancelButton: true,
                cancelButtonText: "No, cancelar",
                confirmButtonText: "Si, anular",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                /*showLoaderOnConfirm: true,*/
                /*preConfirm: async (motivo_texto) => {
                    if (!motivo_texto) {
                        Swal.showValidationMessage(`Introduzca el motivo, porfavor`);
                        return false;
                    }
                    return motivo_texto;
                },
                allowOutsideClick: () => !Swal.isLoading()*/


            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarAnulacionPreafiliacion');
                }
            });
        });

        document.addEventListener('errorEnAnulacion', function() {
            Swal.fire({
                title: '<b>Error durante la anulación</b>',
                text: "Verifique e intente nuevamente",
                icon: 'error'
            });
        });

        document.addEventListener('anuladoCorrectamente', function() {
            Swal.fire({
                title: "<b>Preafiliación anulada correctamente</b>",
                text: "Se guardaron los cambios",
                icon: "success"
            });
        });
    </script>

</div>
