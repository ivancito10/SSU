<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">PLATAFORMA - <b> ADMINISTRACIÓN DE FERIADOS</b></h4>
        <div class="d-flex">
            <div class="mr-2">
                @livewire('Plataforma.AdministracionFeriados.administracion-feriados-register')
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            @livewire('Plataforma.administracion-feriados.administracion-feriados-table')
        </div>
        @include('livewire.Plataforma.administracion-feriados.modalVerFeriado')
        @include('livewire.Plataforma.administracion-feriados.modalEditarFeriados')
    </div>
</div>
<script>
    // REGISTRO YA EXISTE
    document.addEventListener('feriadoYaExiste', function() {
        Swal.fire({
            title: "El feriado ya fue registrado",
            text: "Intente registrar otro feriado o actualice el existente.",
            icon: "error"
        });
        $('#modalRegisterFeriado').modal('hide');
    });

    document.addEventListener('preguntaHabilitarEstadoFeriado', function() {
        //alert("asd");
        Swal.fire({
            title: '¿Desea habilitar el registro de feriado?',
            text: `Se habilitará el feriado`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, habilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarCambioEstadoFeriado');
            }
        });
    });

    document.addEventListener('preguntaInhabilitarEstadoFeriado', function() {
        //alert("asd");
        Swal.fire({
            title: '¿Desea inhabilitar el registro de feriado?',
            text: `Se inhabilitará el feriado`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, inhabilitar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarCambioEstadoFeriado');
            }
        });
    });

    document.addEventListener('estadoCambiado', function() {
        Swal.fire({
            title: "¡Actualizado!",
            text: "Se cambió el estado del feriado correctamente",
            icon: "success",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });

    document.addEventListener('estadoNoCambiado', function() {
        Swal.fire({
            title: "Error al intentar actualizar feriado",
            text: "Verifique e intente nuevamente",
            icon: "error",
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    });
</script>
