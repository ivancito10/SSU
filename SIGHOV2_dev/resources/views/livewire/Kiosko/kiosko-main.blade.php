<div>
    <div class="contenido">
        <div class="row transition-text {{ $var == 1 ? 'posicion-inicial' : 'posicion-final' }}">
            <div>
                <br> <br> <br> <br>
                <p class="texto-h1">AGENDAMIENTO POR KIOSCO</p>
                <p class="texto-subtitle">¡BIENVENIDO!</p>
            </div>
            <div id="hora-digital" style="display:flex;justify-content:center;align-items:center;height:100px;"
                wire:ignore>
                <span id="clock-time" class="hora-digital-stilos">00:00:00</span>
            </div>
        </div>

        @if ($var == 1)
            <!--  Este es el componente para ingresar -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.ingreso')
            </div>
        @elseif($var == 2)
            <!--  Este es el componente para realizar la busqueda -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.busqueda')
            </div>
        @elseif($var == 3)
            <!--  Este es el componente para verificar la vigeencia -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.vigencia')
            </div>
        @elseif($var == 4)
            <!--  Este es el componente para seleccionar fechas  -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.select-fecha')
                @include('livewire.Kiosko.modalVerAgendados')
            </div>
        @elseif($var == 5)
            <!--  Este es el componente para elegir la especialidad -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.elegir-especialidad')
            </div>
        @elseif($var == 6)
            <!--  Este es el componente para elegir el especialista -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.elegir-especialista')
            </div>
        @elseif($var == 7)
            <!--  Este es el componente para elegir el especialista -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.elegir-horario')
            </div>
        @elseif($var == 8)
            <!--  Este es el componente para elegir el especialista -->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                @include('livewire.Kiosko.final')
            </div>
        @endif

        <div wire:loading
            wire:target="volverAInicio, de1IngresoA2Busqueda, de2BusquedaA1Ingreso, seleccionarPersona, de3VigenciaA2Busqueda,
            obtenerFechasDisponibles, de3VigenciaA4SelectFecha, de4SelectFechaA3Vigencia, obtenerEspecialidades, fechaSeleccionadaAgenda, de5SelecEspecialidadA4SelectFecha,
            obtenerEspecialistas, especialidadSeleccionada, de6SelecDoctorA5SelectEspecilidad, obtenerHorariosEspecialista. listaHorasAgendaKiosko,
            especialistaSeleccionado, seleccionarHorario, agendaKioskoConfirmada, de7ElegirHorarioA6SelecDoctor, existeGrupoFamiliarAgendado, imprimirFichaKiosco">
            <x-cargando-dos />
        </div>

        <div id="cargando-agenda-realizada-ciosko" style="display: none;">
            <x-cargando-dos />
        </div>

        <div id="cargando-agenda-confirmada-ciosko" style="display: none;">
            <x-cargando-dos />
        </div>

    </div>

    <script>
        // para recargar la pagina cada minut

        // funcion registrar horario
        document.addEventListener('registrar', function() {
            //alert("asd");
            Swal.fire({
                title: '¿SELECCIONAR EL HORARIO?',
                text: `Se agendará una cita`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, agendar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('registrarAgenda');
                    document.getElementById('cargando-agenda-realizada-ciosko').style.display = 'block';
                } else {
                    Swal.fire({
                        title: "Cancelado!",
                        text: "Se canceló el agendamiento",
                        icon: "error"
                    });
                }
            });
        });

        document.addEventListener('agendaCreada', function() {
            Swal.fire({
                title: "<b>Felicidades!</b>",
                text: "Se agendó la cita!",
                icon: "success",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('alertaAgendaExistente', function() {
            Swal.fire({
                title: "<b>Error!</b>",
                text: "Ya existe un miembro de su grupo familiar agendado en la fecha especificada",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        // ASEGURADO NO AFILIADO
        document.addEventListener('alertaNoAsegurado', function() {
            Swal.fire({
                title: "<b>Acceso denegado</b>",
                html: "No se encontró un registro como asegurado en la institución.<br>Por favor, comuníquese con la <b>Unidad de Vigencia de Derechos </b> para más información.",
                icon: "error",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('horaYaPasada', function() {
            Swal.fire({
                title: "<b>Hora seleccionada fuera de rango</b>",
                text: "La hora seleccionada se encuentra fuera del rango",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('preguntarGuardarAgenda', event => {
            //alert('entra aqui');
            const hora_mostrar = event.detail.hora;
            Swal.fire({
                title: "¿Agendar cita a Hrs. " + hora_mostrar + "?",
                text: `Se agendará una cita con los datos especificados`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3A9862',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, agendar',
                cancelButtonText: 'No, cancelar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    title: 'swal-title-lg',
                    htmlContainer: 'swal-text-lg',
                    confirmButton: 'swal-btn-lg',
                    cancelButton: 'swal-btn-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('agendaKioskoConfirmada');
                    document.getElementById('cargando-agenda-confirmada-ciosko').style.display = 'block';
                } else {
                    Swal.fire({
                        title: "<b>Cancelado</b>",
                        text: "Se canceló el agendamiento",
                        icon: "error"
                    });
                }
            });
        });

        document.addEventListener('errorRegistro', function() {
            Swal.fire({
                title: "<b>Ocurrió un error</b>",
                text: "No se realizó el agendamiento, por favor intente nuevamente",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('registroExistoso', function() {
            let timeout = setTimeout(function() {
                Livewire.dispatch("volverAInicio");
                document.getElementById('cargando-agenda-confirmada-ciosko').style.display = 'block';
                Swal.close();
            }, 5000);

            Swal.fire({
                title: "<b>Agenda registrada exitosamente</b>",
                text: "Se registro la agenda correctamente",
                icon: "success",
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                clearTimeout(timeout);
                if (result.isConfirmed) {
                    Livewire.dispatch("volverAInicio");
                    document.getElementById('cargando-agenda-confirmada-ciosko').style.display = 'block';
                }
            });
        });

        document.addEventListener('existeAgendadoGrupoFamiliar', function() {
            Swal.fire({
                title: "<b>Familiar Agendado</b>",
                text: "Un miembro de su grupo familiar agendó una cita para la fecha seleccionada.",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('ustedEstaAgendado', function() {
            Swal.fire({
                title: "<b>Usted ya tiene una cita agendada</b>",
                text: "Usted ya tiene una agenda registrada para el día seleccionado",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('carnetCaduco', function(event) {
            const fechaVencimiento = event.detail.fechaVencimiento;
            Swal.fire({
                title: "<b>Su carnet de asegurado caducó</b>",
                html: `Su carnet de asegurado venció en fecha: <b>${fechaVencimiento}</b>. <br> Pase por la Unidad de Afiliación del Seguro Universitario para la regularización el proceso.`,
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        });

        document.addEventListener('sinResultados', function() {
            Swal.fire({
                title: "<b>Sin resultados</b>",
                text: "No se encontraron resultados para la búsqueda. Verifique los datos e inténtelo nuevamente",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false,
            });
        });


        document.addEventListener('sinEspecialistas', function() {
            Swal.fire({
                title: "<b>¡Sin especialistas!</b>",
                text: "No se encuentran especialistas registrados",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('noPuedeAccederConvenio', function() {
            Swal.fire({
                title: "<b>¡Advertencia!</b>",
                text: "El asegurado no se encuentra autorizado para el agendamiento por KIOSCO",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        document.addEventListener('estudianteRegularizar', function() {
            Swal.fire({
                title: "<b>Regularice su afiliación</b>",
                text: "Debe apersonarse a las oficinas de la Unidad de Afiliaciones para regularizar su trámite de afiliación correspondiente a la presente gestión",
                icon: "warning",
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        });

        // función para INACTIVIDAD jiji
        let inactividadTimer;

        function resetInactividad() {
            clearTimeout(inactividadTimer);
            inactividadTimer = setTimeout(() => {
                Livewire.dispatch('volverAInicio');
            }, 20000); // 20 segundos
        }
        ['mousemove', 'keydown', 'click', 'touchstart'].forEach(evento => {
            document.addEventListener(evento, resetInactividad);
        });
        resetInactividad();

        /*document.addEventListener('imprimirAgenda', event => {
            const idAgenda = event.detail.idAgenda;
            var ruta = "{{ url('kiosco-print/agenda') }}" + '/' + idAgenda;
            var w = window.open(ruta, "_blank", "width=100, height=100");
            w.close();
        });*/

        /*Livewire.on('imprimirAgenda', async (escposData) => {
            try {
                const response = await fetch('http://192.168.4.125:5000/imprimir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        escpos_data: escposData
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    console.log('✅ Ficha enviada a impresión:', result.message);
                } else {
                    console.error('❌ Error en impresión:', result.message);
                    alert('Error al imprimir: ' + result.message);
                }
            } catch (error) {
                console.error('⚠️ No se pudo conectar con el servicio de impresión local:', error);
                alert(
                    'No se pudo conectar con el servicio de impresión local. Asegúrate que esté ejecutándose.');
            }
        });*/
    </script>
    <style>
        .swal-title-lg {
            font-size: 40px !important;
        }

        .swal-text-lg {
            font-size: 30px !important;
        }

        .swal-btn-lg {
            font-size: 30px !important;
            padding: 10px 20px !important;
        }
    </style>
</div>
