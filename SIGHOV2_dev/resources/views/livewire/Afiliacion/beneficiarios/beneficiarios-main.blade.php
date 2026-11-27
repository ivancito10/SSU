<div>

    <div class="input-group pr-4 pb-2" style="justify-content: space-between; ">
        <h4 style="color:black;">AFILIACIÓN - <b> LISTA DE ASEGURADOS BENEFICIARIOS</b></h4>

        <div class="d-flex">
            @can('registrar.beneficiarios')
                <div class="mr-2">
                    @livewire('Afiliacion.beneficiarios.beneficiarios-register')
                </div>
            @endcan
            @can('reporte.afiliado_beneficiario')
                <x-reportes-button wire:click="abrirModalReportesBeneficiarios"> REPORTES
                </x-reportes-button>
                @include('livewire.Afiliacion.beneficiarios.modalReportesBeneficiarios')
            @endcan
        </div>
    </div>
    <br>
    @can('lista.beneficiarios')
        @livewire('Afiliacion.beneficiarios.beneficiarios-table')
    @endcan
    @can('registrar_tipo_sangre.beneficiarios')
        <!-- Modal Tipo de Sangre -->
        @include('livewire.Afiliacion.beneficiarios.modalAgregarTipoSangreBen')
    @endcan
    {{--    @can('editar.beneficiarios')
        <!-- Modal para Editar Beeficiario -->
        @include('livewire.Afiliacion.beneficiarios.modalEditarBeneficiario')
        @endcan
        --}}
    @can('ver.beneficiarios')
        <!-- Modal para Ver datos del Beneficiario -->
        @include('livewire.Afiliacion.beneficiarios.modalVerBeneficiario')
    @endcan


    <!-- Modal para agregar institucion del Beneficiario -->
    @include('livewire.Afiliacion.beneficiarios.modalAgregarInstitucion')


    <!-- Modal para la carnetizaciones -->
    @include('livewire.Afiliacion.beneficiarios.modalCarnetizacionBen')
    @include('livewire.Afiliacion.beneficiarios.modalRegistrarArchivoGrupoBeneficiario')
    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    function alertaGuardar() {
        $('#verModalBeneficiario').modal('hide');
        Swal.fire({
            title: `Descargar file digital?`,
            text: "Se descargará el archivo seleccionado del asegurado.",
            icon: 'question',
            input: "text",
            inputLabel: "INGRESE MOTIVO DESCARGA: ",

            inputAttributes: {
                autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Descargar",
            showLoaderOnConfirm: true,
            preConfirm: async (motivo_texto) => {
                if (!motivo_texto) {
                    Swal.showValidationMessage(`Introduzca detalles para la descarga`);
                    return false;
                }
                return motivo_texto;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('descargarArchivo', [result.value]);
            }
        });
    }

    /* document.addEventListener('guardado', function() {
        Swal.fire({
        title:'Aviso de Confidencialidad!',
        text: "Usted ha descargado un documento digital del sistema. Este documento es confidencial y está destinado exclusivamente para uso interno. Por favor, maneje esta información con la máxima discreción y no la comparta con personas no autorizadas. Cualquier uso indebido o divulgación de este documento puede resultar en medidas disciplinarias y/o legales. Gracias por su comprensión y cooperación..",
        icon:'warning'
        });
    }); */

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: 'Aviso de Confidencialidad!',
            html: "<p class='justificado'>Usted ha descargado un documento digital del sistema. Este documento es confidencial y está destinado exclusivamente para uso interno. Por favor, maneje esta información con la máxima discreción y no la comparta con personas no autorizadas. Cualquier uso indebido o divulgación de este documento puede resultar en medidas disciplinarias y/o legales. Gracias por su comprensión y cooperación. <br><br> <b><em>Ley N° 164, Artículo 56. (Inviolabilidad y secreto de las comunicaciones).</em></b></p>",
            icon: 'warning',
            customClass: {
                htmlContainer: 'text-justify'
            }
        });
    });
    window.addEventListener('RegistroCarnetCorrecto', event => {

        Swal.fire({
            title: "Guardado!",
            text: "Se generó correctamente el Carnet de Asegurado",
            icon: "success"
        });
        $('#carnetModalBen').modal('hide');
    });

    /* ALERTAS PRA CARNETS */

    window.addEventListener('normalSinFechas', event => {
        Swal.fire({
            title: "Error!",
            text: "Debes agregar las fechas de emision y de vencimiento",
            icon: "error"
        });
        $('#carnetModalBen').modal('hide');
    });
    window.addEventListener('sinFotoAfiliado', event => {
        Swal.fire({
            title: "Error!",
            text: "El afiliado no tiene fotografia",
            icon: "error"
        });

    });

    window.addEventListener('completaCamposReimpresion', event => {

        Swal.fire({
            title: "Ingresa todos los campos correspondientes",
            text: "Recuerda ingresar un motivo y el número de recibo para la reimpresión del carnet",
            icon: "error"
        });
        $('#carnetModalBen').modal('hide');
    });

    window.addEventListener('actualizaReferenciaYDireccion', event => {
        Swal.fire({
            title: "Actualiza los datos Referenciales!",
            text: "Porfavor actualiza los datos necesarios",
            icon: "error"
        });
        $('#carnetModalBen').modal('hide');
    });
    window.addEventListener('actualizaReferenciales', event => {
        Swal.fire({
            title: "Actualiza los datos Referenciales!",
            text: "Porfavor actualiza el telefono, nombre de referencia y telefono de referencia",
            icon: "error"
        });
        $('#carnetModalBen').modal('hide');
    });
    window.addEventListener('actualizaDireccion', event => {
        Swal.fire({
            title: "Ingresa la dirección en el formato correcto",
            text: "Recuerda ingresar la dirección por separado, ingresa el número de vivienda , la dirección y la zona por separado",
            icon: "error"
        });
        $('#carnetModalBen').modal('hide');
    });
</script>
