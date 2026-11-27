<div>
    <div class="input-group pr-4 d-flex justify-content-between w-100">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> BAJAS INSTITUCIONALES DE ASEGURADOS</b></h4>

        @can('registrar.bajas_asegurados')
            <div class="d-flex justify-content-end">
                <!-- Aqui el boton de reportes -->
                <x-reportes-button class="mr-1" data-toggle="modal" data-target="#modalPdfBajaInstitucional"> REPORTE
                </x-reportes-button>
                <!-- Aquí va el botón registrar -->
                <x-agregar-button wire:click="redirectToRegistro" title="REGISTRAR INSTITUCIÓN">REGISTRAR</x-agregar-button>
            </div>
        @endcan
    </div>

    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.bajas_asegurados')
            {{-- Datatable --}}
            @livewire('Vigencia.BajasInstitucionales.bajas-institucionales-table')
        @endcan

        @can('ver.bajas_asegurados')
            {{-- Ver Baja --}}
            @include('livewire.Vigencia.bajas-institucionales.modalVerBajaInstitucional')
        @endcan

        <!-- Modal para generar reporte PDF baja institucional -->
        @include('livewire.Vigencia.bajas-institucionales.modalPdfBajaInstitucional')

        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>

    <script>
        document.addEventListener('alertaBajaDefinitiva', function() {
            Swal.fire({
                title: '¿Efectuar baja definitiva?',
                text: 'Por favor, introduzca la descripción de la baja',
                icon: 'question',
                html: `
                <p>Introduzca alguna observación o el motivo de la baja definitiva de manera anticipada</p>                
                <label for="observacion-texto" style="display: block; text-align: left;">OBSERVACIÓN:</label>
                <textarea class="form-control" id="observacion-texto" class="swal2-input" rows='4'></textarea>
            
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",

                showLoaderOnConfirm: true,

                preConfirm: () => {
                    const motivo_texto = document.getElementById('observacion-texto').value;

                    if (!motivo_texto) {
                        Swal.showValidationMessage('Introduzca la observación por favor');
                        return false;
                    }

                    return {
                        motivo_texto,
                    };
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarBajaDefinitiva', [result.value.motivo_texto]);
                } else {
                    Swal.fire({
                        title: "No se guardaron los cambios",
                        text: "No se efectuó la baja definitiva",
                        icon: "warning"
                    });
                }
            });
        });

        document.addEventListener('noValidado', function() {
            Swal.fire({
                title: "No se efectuó la baja",
                text: "Verifique e intente nuevamente",
                icon: "warning",
            });
        });

        document.addEventListener('guardado', function() {
            Swal.fire({
                title: "Registro exitoso",
                text: "La baja definitiva fue efectuada exitosamente",
                icon: "success",
            });
        });
        document.addEventListener('aceptaBajaAsuss', function() {
            Swal.fire({
                title: "Baja registrada",
                text: "La baja ASUSS fue efectuada exitosamente",
                icon: "success",
            });
        });
        document.addEventListener('preguntaBajaAsuss', function() {
            const motivo = "";
            const fecha = new Date();
            Swal.fire({
                title: `¿Registrar Baja ASUSS?`,
                icon: 'question',
                html: `<strong>Se registrara la baja con fecha: ${fecha.getDate()}-${fecha.getMonth() + 1}-${fecha.getFullYear()}</strong>
                <br><p>Describa las observaciones para la baja ASUSS: (Opcional)</p>`,
                input: "text",
                showCancelButton: true,
                confirmButtonText: "SI, DAR DE BAJA",
                cancelButtonText: "CANCELAR",
                showLoaderOnConfirm: true,
                preConfirm: async (motivo_texto) => {
                    // if (!motivo_texto) {
                    //     Swal.showValidationMessage(`Introduzca la observación por favor`);
                    //     return false;
                    // }
                    return motivo_texto;
                },
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const motivo = result.value;
                    Livewire.dispatch('confirmarBajaAsuss', [motivo,fecha]);
                    document.getElementById('cargando-anulacion-web').style.display = 'block';
                } 
            });
        });
         document.addEventListener('noValidadoBajaAsuss', function() {
            Swal.fire({
                title: "No se efectuó la baja",
                text: "Verifique e intente nuevamente",
                icon: "warning",
            });
        });
    </script>

</div>
