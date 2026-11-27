<div>
    <div class="d-flex justify-content-between align-items-center pr-3 pb-2 flex-wrap">
        <h4 class="mb-0" style="color:#192733;">
            AFILIACIÓN SSUE - <b>LISTADO DE ESTUDIANTES</b>
        </h4>

        <div class="d-flex align-items-center" style="gap: 12px;">
            @can('reporte.afiliado_estudiante')
                <x-reportes-button wire:click="abrirModalReportesEstudiantes"> REPORTES
                </x-reportes-button>
                @include('livewire.Afiliacion.estudiantes-ssue.modalReportesEstudiantes')
            @endcan

            @can('registro_manual.afiliacion_estudiantes_ssue')
                <x-agregar-button wire:click="agregarNuevoEstudiante">
                    <b>REGISTRO MANUAL</b>
                </x-agregar-button>
            @endcan
        </div>
    </div>


    <br>
    @can('lista.afiliacion_estudiantes_ssue')
        @livewire('Afiliacion.estudiantes-ssue.estudiantes-table')
    @endcan


    @can('ver.afiliacion_estudiantes_ssue')
        <!--Modal para editar datos de asegurado -->
        @include('livewire.Afiliacion.estudiantes-ssue.modalVerEstudiante')
        <!--Modal para ver Fotocopia Ci -->
        @include('livewire.Afiliacion.estudiantes-ssue.modalFotocopiaCi')
        <!--Modal para ver Matricula -->
        @include('livewire.Afiliacion.estudiantes-ssue.modalMatricula')
        <!--Modal para ver Boleta de Inscripcion -->
        @include('livewire.Afiliacion.estudiantes-ssue.modalBoletaInscripcion')
        <!--Modal para ver Form no afiliacion  - GESTORA -->
        @include('livewire.Afiliacion.estudiantes-ssue.modalFormNoAfiliacion')
        <!--Modal para ver Certificado de cajas - FPRMULARIO 101-->
        @include('livewire.Afiliacion.estudiantes-ssue.modalCertificadoCajas')
        <!--Modal para ver Tipo Sangre -->
        @include('livewire.Afiliacion.estudiantes-ssue.modaltipoSangre')
        <!--Modal para ver Tipo Sangre -->
        @include('livewire.Afiliacion.estudiantes-ssue.modalVerAfiliadoEstudiante')
        @include('livewire.Afiliacion.estudiantes-ssue.modalCarnetizacionEst')
    @endcan

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    document.addEventListener('ConfirmacionHabilitarEstudiante', function() {
        Swal.fire({
            title: '¿Validar datos y documento del estudiante?',
            text: "Los datos y documentos del estudiante serán validados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Validar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('ValidarRegistroEstudiante');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "Validación cancelada",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('validado', function() {
        Swal.fire({
            title: "<b>¡Felicidades!</b>",
            text: "Los documentos y archivos del estudiante fueron validados.",
            icon: "success"

        });
    });
    document.addEventListener('noValidado', function() {
        Swal.fire({
            title: "<b>¡Error!</b>",
            text: "No se validaron los datos del estudiante.",
            icon: "error"

        });
    });
    {{-- - para dar vigencia  --}}
    document.addEventListener('ConfirmacionVigenciaEstudiante', function() {
        Swal.fire({
            title: '¿Dar vigencia al estudiante?',
            text: "Se afiliará al estudiante seleccionado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, dar vigencia',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarVigencia');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "No se dio vigencia al estudiante",
                    icon: "error"
                });
            }
        });
    });
    {{-- -- fin vigencia --}}

    document.addEventListener('alertaObservacion12', function() {

        Swal.fire({
            title: `Agregar observación?`,
            icon: 'question',
            text: 'Describa los detalles a continuación:',
            input: "text",
            //inputLabel: "OBSERVACIÓN: ",
            inputAttributes: {
                autocapitalize: "on"
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
                Livewire.dispatch('guardarObservacion', [result.value]);
            }
        });
    });

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: 'Guardado!',
            text: "Se ha registrado su observación",
            icon: 'success'
        });
    });

    const nombres_m = '{{ $nombres }}';
    document.addEventListener('alertaObservacion', function() {
        Swal.fire({
            title: `Agregar observación? ${nombres_m}`,
            text: 'Aquí puedes poner tu texto adicional',
            icon: 'question',
            html: `
                <br>
                <P>Seleccione las casillas correspondientes y describa la observacón.</P>
                <br>
                <div style="text-align: left;">
                    <label for="option1" style=" margin-bottom: 15px; margin-left: 15px; ">
                        <input type="checkbox" id="option1" name="options" value="Option 1">
                        <em> Fotocopia de C.I</em>
                    </label>
                    <label for="option2" style=" margin-bottom: 15px; margin-left: 53px;">
                        <input type="checkbox" id="option2" name="options" value="Option 2">
                        <em>Matricula</em>
                    </label><br>

                    <label for="option3" style=" margin-bottom: 15px; margin-left: 15px;">
                        <input type="checkbox" id="option3" name="options" value="Option 3">
                        <em>Boleta de Inscripción</em>
                    </label>
                    <label for="option4" style=" margin-bottom: 15px; margin-left: 15px;">
                        <input type="checkbox" id="option4" name="options" value="Option 4">
                        <em>Certificado Gestora</em>
                    </label><br>

                    <label for="option5" style=" margin-bottom: 15px; margin-left: 15px;">
                        <input type="checkbox" id="option5" name="options" value="Option 5">
                        <em>Formulario 101 </em>
                    </label>
                    <label for="option6" style=" margin-bottom: 15px; margin-left: 55px;">
                        <input type="checkbox" id="option6" name="options" value="Option 6">
                        <em>Tipo de Sangre</em>
                    </label>

                </div>
                <br>
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
                const option1 = document.getElementById('option1').checked;
                const option2 = document.getElementById('option2').checked;
                const option3 = document.getElementById('option3').checked;
                const option4 = document.getElementById('option4').checked;
                const option5 = document.getElementById('option5').checked;
                const option6 = document.getElementById('option6').checked;

                if (!motivo_texto) {
                    Swal.showValidationMessage('Introduzca la observación por favor');
                    return false;
                }

                {{-- -- if (!option1 && !option2 && !option3 && !option4 && !option5 && !option6) {
                    Swal.showValidationMessage('Debe seleccionar al menos una opción');
                    return false;
                } --}}

                return {
                    motivo_texto,
                    options: {
                        option1,
                        option2,
                        option3,
                        option4,
                        option5,
                        option6
                    }
                };
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarObservacion', [result.value.motivo_texto, {
                    options: result.value.options
                }]);
            }
        });
    });

    window.addEventListener('modalVerAfiliadoEstudiante', event => {

        $('#verEstudiante').modal('show');
    });
    window.addEventListener('RegistroCarnetCorrecto', event => {

        Swal.fire({
            title: "Guardado!",
            text: "Se generó correctamente el Carnet de Asegurado",
            icon: "success"

        });
        $('#carnetModalEst').modal('hide');
    });
    window.addEventListener('errorCarnet', event => {

        Swal.fire({
            title: "Error!",
            text: "No se generó correctamente el Carnet de Asegurado",
            icon: "error"

        });
        $('#carnetModalEst').modal('hide');
    });
    /* ALERTAS PRA CARNETS */

    window.addEventListener('normalSinFechas', event => {
        Swal.fire({
            title: "Error!",
            text: "Debes agregar las fechas de emision y de vencimiento",
            icon: "error"
        });
        $('#carnetModalEst').modal('hide');

    });
    window.addEventListener('sinFotoAfiliado', event => {
        Swal.fire({
            title: "Error!",
            text: "El afiliado no tiene fotografia",
            icon: "error"
        });

    });

    document.addEventListener('regCancelado', function() {
        Swal.fire({
            title: "<b>Cancelado</b>",
            text: "Se canceló la operación.",
            icon: "error"

        });
    });

    window.addEventListener('vigente', event => {
        Swal.fire({
            title: "Vigente",
            text: "El estudiante se encuentra vigente",
            icon: "success"
        });
    });


    // REAFILIAR
    document.addEventListener('reafiliarEstudianteAlert', function() {
        Swal.fire({
            title: '¿Quiere reafiliar al estudiante?',
            text: "Se reafiliará al estudiante seleccionado.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, reafiliar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarReafiliacionEstudiante');
            } else {
                Swal.fire({
                    title: "¡Cancelado!",
                    text: "No se reafilió al estudiante",
                    icon: "error"
                });
            }
        });
    });

    document.addEventListener('estudianteListoReafiliar', function() {
        Swal.fire({
            title: "<b>Listo para reafiliarse</b>",
            text: "El estudiante ya puede realizar su afiliacion nuevamente.",
            icon: "success"

        });
    });
</script>
