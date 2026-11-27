<div>
    <div class="input-group pr-4 d-flex justify-content-between w-100">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> BAJAS MÉDICAS</b></h4>

        @can('registrar.baja_medica_hologacion')
            <div class="d-flex justify-content-end">
                <x-reportes-button class="mr-1" data-toggle="modal" data-target="#modalPdfBajasMedicas"> REPORTE
                </x-reportes-button>
                <x-agregar-button wire:click='abrirLista'> REGISTRAR</x-agregar-button>
            </div>
        @endcan
    </div>

    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.baja_medica_hologacion')
            @livewire('vigencia.bajas-medicas.bajas-medicas-table')
        @endcan

        @can('ver.baja_medica_hologacion')
            <!--modal para ver bajas medicas-->
            @include('livewire.Vigencia.bajas-medicas.modalVerBajaMedica')
        @endcan

        @can('entregar.baja_medica_hologacion')
            <!--modal para entregar bajas medicas-->
            @include('livewire.Vigencia.bajas-medicas.modalEntregarBajaMedica')
        @endcan

        @can('registrar.baja_medica_hologacion')
            <!--modal para entregar bajas medicas-->
            @include('livewire.Vigencia.bajas-medicas.modalListaPersonas')
        @endcan

        <!-- Modal para generar reporte PDF bajas medicas -->
        @include('livewire.Vigencia.bajas-medicas.modalPdfBajasMedicas')

        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>
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

    document.addEventListener('noAfiliadoTitBen', function() {

        Swal.fire({
            title: "<b>Datos Incorrectos</b>",
            text: "Los datos del asegurado no corresponden a un Titular o Beneficiario",
            icon: "warning"
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
