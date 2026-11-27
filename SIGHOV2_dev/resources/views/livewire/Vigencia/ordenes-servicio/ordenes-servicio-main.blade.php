<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> ORDENES DE SERVICIO </b></h4>
        @can('registrar.ordenes_servicio_hologacion')
            <div class="d-flex justify-content-end">
                <!-- Boton para el reporte de orden de servicio -->
                <x-reportes-button class="mr-1" data-toggle="modal" data-target="#modalGenerarPdf"> REPORTE
                </x-reportes-button>
                <!-- Aquí va el botón registrar -->
                @livewire('Vigencia.OrdenesServicio.personas-list')
            </div>
        @endcan
    </div>
    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.ordenes_servicio_hologacion')
            @livewire('Vigencia.OrdenesServicio.ordenes-servicio-table')
        @endcan

        @can('ver.ordenes_servicio_hologacion')
            <!-- Modal para Ver datos de la orden de servicio -->
            @include('livewire.Vigencia.ordenes-servicio.modalVerOrdenServicio')
        @endcan

        @can('entregar.ordenes_servicio_hologacion')
            <!-- Modal para Entregar  la orden de servicio -->
            @include('livewire.Vigencia.ordenes-servicio.modalEntregarOrden')
        @endcan

        <!-- Modal para generar reporte PDF ordenes de servicio -->
        @include('livewire.Vigencia.ordenes-servicio.modalPDFOrdenServicio')

        <!--  Para la parte de guardar una solicitud -->
        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
</div>


<script>
    //VALIDAR
    document.addEventListener('alertaValidarOrden', function() {
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
                Livewire.dispatch('guardarValidacionOrden');
            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló la validación",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('guardarOrden', function() {

        Swal.fire({
            title: "<b>Guardado!</b>",
            text: "Se guardó la información!",
            icon: "success"

        });
    });

    document.addEventListener('noValidadoOrden', function() {

        Swal.fire({
            title: "<b>Error!</b>",
            text: "Sucedió un error, verifique la información!",
            icon: "error"

        });
    });
    // ANULAR
    document.addEventListener('alertaAnularOrden', function() {

        Swal.fire({
            title: `Anular orden de servicio?`,
            icon: 'question',
            text: 'Describa los detalles a continuación:',
            input: "text",
            //inputLabel: "OBSERVACIÓN: ",
            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Anular",
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
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
                Livewire.dispatch('guardarAnulacionOrden', [result.value]);
            }
        });
    });
</script>
