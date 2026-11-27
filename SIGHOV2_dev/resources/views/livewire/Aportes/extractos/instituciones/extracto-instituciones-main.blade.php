<div>
@can('lista.extracto_institucion')
        <div class="row">
            <div class="col-lg-10" style="text-align:left;">
                <H4 style=" color: #273746; ">APORTES - <b> LISTA DE INSTITUCIONES </b> </H4>
            </div>
        </div><br>

    <div class="row">
        <div class="col-lg-12">
            <!-- Aqui va lo de el Listado-->
            @livewire('Aportes.Extractos.Instituciones.extracto-instituciones-table')
           <!--  Aqui se muestra el modal de extracto institucion -->
            @include('livewire.Aportes.extractos.instituciones.modalAbrirInstitucion')
        </div>
        {{--Para el cargando--}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
@endcan
</div>

<script>
    // ESTO ES PARA ESCUCHAR ver
    window.addEventListener('openModalVer', event => {
                    $('#vistaExtracto').modal('show');
                });

                // FUNCION DEL BOTON GUARDAR para SWEETALERT2
    function guardarExtracto() {
        Swal.fire({
            title: '¿Generar Extracto?',
            text: "Se registrarán los datos ingresados." {{$nombre_institucion}},
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ver',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('verExtractoPdfInstitucion');
                $('#vistaExtracto').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                @this.set('modal', true);
                    $('#vistaExtracto').modal('hide');
            }
        });
    }

    document.addEventListener('DescargaExitosaExtracto', function() {
        $('#vistaExtracto').modal('hide');
        Swal.fire({
            title: "Descargado!",
            text: "Se descargó el extracto con satisfación.",
            icon: "success"

        });
    });
    
    document.addEventListener('DescargaerroaExtracto', function() {
        $('#vistaExtracto').modal('hide');
        Swal.fire({
            title: "Error!",
            text: "Se produjo un error no se pudo descargar!!!",
            icon: "error"

        });
    });
    document.addEventListener('noExistenRegs', function() {
        $('#vistaModalExtracto').modal('hide');
        Swal.fire({
            title: "No se encontraron registros!",
            text: "No existen registros de aportes para el rango de fechas seleccionados.",
            icon: "warning"

        });
    });
</script>
