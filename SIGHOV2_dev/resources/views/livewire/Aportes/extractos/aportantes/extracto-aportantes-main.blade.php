<div>
    @can('lista.extracto_persona')
        <!--CARD LISTA PRINCIPAL-->
        <div class="row">
            <div class="col-lg-10" style="text-align:left;">
                <h4 style=" color: #273746; ">APORTES - <b> LISTA DE APORTANTES </b> </H4>
            </div>
        </div><br>
        <div class="row">
            <div class="col-lg-12">

                @livewire('aportes.extractos.aportantes.extractos-aportantes-table')
                @include('livewire.Aportes.extractos.aportantes.modalVerExtracto')
            </div>
            {{-- Para el cargando --}}
            <div wire:loading>
                <x-cargando-dos />
            </div>
        </div>
    @endcan
</div>
<script>
    // ESTO ES PARA ESCUCHAR ver
    window.addEventListener('openModalVerExtracto', event => {
        $('#vistaModalExtracto').modal('show');
    });
    // FUNCION DEL BOTON GUARDAR para SWEETALERT2
    function saveEditInsti() {
        Swal.fire({
            title: '¿Generar Extracto?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ver',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('verExtractoPdf');
                $('#vistaModalExtracto').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                @this.set('modal', true);
                $('#vistaModalExtracto').modal('hide');
            }
        });
    }
    document.addEventListener('DescargaExitosaExtracto', function() {
        $('#vistaModalExtracto').modal('hide');
        Swal.fire({
            title: "Descargado!",
            text: "Se descargó el extracto con satisfación.",
            icon: "success"

        });
    });
    document.addEventListener('DescargaerroaExtracto', function() {
        $('#vistaModalExtracto').modal('hide');
        Swal.fire({
            title: "Error!",
            text: "Se produjo un error no se pudo descargar.",
            icon: "error"

        });
    });
    document.addEventListener('noExistenRegistros', function() {
        $('#vistaModalExtracto').modal('hide');
        Swal.fire({
            title: "No se encontraron registros!",
            text: "No existen registros de aportes para el rango de fechas seleccionados.",
            icon: "warning"

        });
    });
</script>
