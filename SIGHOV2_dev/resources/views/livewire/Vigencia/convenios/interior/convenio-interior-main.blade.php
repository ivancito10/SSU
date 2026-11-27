<div>
    <div class="input-group pr-4 d-flex justify-content-between w-100">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> CONVENIOS DEL INTERIOR</b></h4>
        @can('registrar.convenios_interior')
            <div class="d-flex justify-content-end">
                <!-- Aqui el boton de reportes -->
                <x-reportes-button class="mr-1" data-toggle="modal" data-target="#modalPdfConvenioInterior"> REPORTE
                </x-reportes-button>
                <!-- Aquí va el botón registrar -->
                <x-agregar-button wire:click="redirectToForm">REGISTRAR</x-agregar-button>
            </div>
        @endcan
    </div>

    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.convenios_interior')
            @livewire('vigencia.convenios.interior.convenio-interior-table')
        @endcan

        @can('ver.convenios_interior')
            <!--modal para ver las autorizaciones-->
            @include('livewire.Vigencia.convenios.interior.modalVerAutorizacion')
        @endcan

        @can('carnet.convenios_interior')
            <!--modal para entregar bajas medicas-->
            @include('livewire.Vigencia.convenios.interior.modalCarnetizacionConv')
        @endcan

        <!-- Modal para generar reporte PDF convenio interior -->
        @include('livewire.Vigencia.convenios.interior.modalPdfConvenioInterior')

        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
</div>







<script>
    document.addEventListener('alertaValidar', function() {
        Swal.fire({
            title: 'Validar?',
            text: "Se validará el registro seleccionado.",
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
            text: "Se valído el Convenio!",
            icon: "success"

        });
    });

    document.addEventListener('noValidado', function() {

        Swal.fire({
            title: "<b>Error!</b>",
            text: "Se canceló la validación!",
            icon: "error"

        });
    });


    // ANULAR
    document.addEventListener('alertaAnular', function() {

        Swal.fire({
            title: `Anular Convenio?`,
            icon: 'question',
            text: 'Describa el motivo de la anulación:',
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
    window.addEventListener('RegistroCarnetCorrecto', event => {

        Swal.fire({
            title: "Guardado!",
            text: "Se generó correctamente el Carnet de Asegurado",
            icon: "success"

        });
        $('#carnetModalConv').modal('hide');
    });
    window.addEventListener('sinFotoAfiliado', event => {
        Swal.fire({
            title: "Error!",
            text: "El afiliado no tiene fotografia",
            icon: "error"
        });

    });
</script>
