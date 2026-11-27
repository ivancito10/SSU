<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> PRESTACIONES MÉDICAS</b> - USUARIO EXTERNO</h4>
        <!-- Aquí va el botón registrar -->
        {{--@livewire('Vigencia.solicitud-prestacion-medica.solicitud-prestacion-medica-register')--}}
    </div>
    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @livewire('Vigencia.Externos.SolicitudPrestacionMedica.solicitud-prestacion-medica-ext-table')
        <!-- Modal para ver la solicitud-->

        @include('livewire.Vigencia.externos.solicitud-prestacion-medica.modalVerSolicitudExt')

        <!-- Modal para verificar firma digital -->
        @include('livewire.Vigencia.solicitud-prestacion-medica.modalVerificarFirmaDigital')

        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>

    </div>
</div>




<script>
    document.addEventListener('alertaFirmarDigitalmente', function() {
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
            text: "Se valído la solicitud!",
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
            title: `Anular Solicitud?`,
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
</script>
