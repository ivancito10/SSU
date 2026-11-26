<div>
    {{-- BARRA DE TÍTULO SUPERIOR (SIN CONTENEDOR CARD) --}}
    <div class="input-group pr-4 mb-3" style="justify-content: space-between; align-items: center;">
        <h4 style="color: black; margin: 0; text-transform: uppercase;">
            ENFERMERÍA - <b>REGISTRO DE INYECCIONES</b>
        </h4>
        
        @can('menu.enfermeria')
            <div class="d-flex">
                <x-reportes-button class="mr-2" wire:click="abrirModalReporte" title="REPORTE EXCEL">
                    REPORTE EXCEL
                </x-reportes-button>
                <x-agregar-button wire:click="redirectToCreate" title="REGISTRAR INYECCIÓN">
                    REGISTRAR
                </x-agregar-button>
            </div>
        @endcan
    </div>

    {{-- CONTENEDOR DE LA TABLA POWERGRID (SIN CARD, PARA QUE LA LÍNEA AZUL DE LA TABLA DESTAQUE) --}}
    <div class="w-100">
        @livewire('enfermeria.inyectables.inyectables-table')
    </div>

    <!-- ========================================== -->
    <!-- VENTANA EMERGENTE (MODAL VER)              -->
    <!-- ========================================== -->
    @include('livewire.enfermeria.inyectables.modalVerInyeccion')

    <!-- ========================================== -->
    <!-- VENTANA EMERGENTE (MODAL REPORTE EXCEL)    -->
    <!-- ========================================== -->
    @can('menu.enfermeria')
        @include('livewire.enfermeria.inyectables.modalReporteExcel')
    @endcan

    <!-- Scripts de SweetAlert2 -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alertaError', (data) => {
                Swal.fire({
                    title: 'Alerta!',
                    text: data.mensaje || data[0].mensaje, 
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });

            Livewire.on('alertaExito', (data) => {
                Swal.fire({
                    title: '¡Modificación Exitosa!',
                    text: data.mensaje || data[0].mensaje,
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#6f42c1'
                });
            });

            // Compatibilidad con los eventos de estado actualizados
            document.addEventListener('estadoActualizado', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¡Actualizado!',
                        text: 'El estado de la atención se actualizó con éxito.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });

            document.addEventListener('errorActualizacion', function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo actualizar el estado del registro.',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
</div>