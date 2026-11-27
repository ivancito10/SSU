<div>

            <div class="input-group pr-4" style="justify-content: space-between; ">
                <H4 style="color:#212f3c ;">APORTES - <b> LISTA DE CERTIFICADOS INSTITUCIONES</b></H4>
                    <!-- Aqui va el boto agregar-->
                    <x-agregar-button style=" padding: 5px 10px;" wire:click="registrarCertificado"
                     title="REGISTRAR CERTIFICADO"><b>REGISTRAR</b> </x-agregar-button>
   
            </div>
            <br>

    
    

    <!-- Div para la lista de los certificados -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Aqui va lo de el Listado-->
            @livewire('aportes.institucion.certificado.certificado-table')
        </div>
        <!-- modal para ver listado de instituciones -->
        @include('livewire.Aportes.institucion.certificado.modalRegistrarCertificado')
        <!-- modal para registrar en nueva institucion -->
        @include('livewire.Aportes.institucion.certificado.modalRegistrarInstitucion')
        <!-- modal para registrar en institucion existente -->
        @include('livewire.Aportes.institucion.certificado.modalRegistrarInstiCertificado')
         
        <!-- modal ver certificado -->
        @include('livewire.Aportes.institucion.certificado.modalVerCertificado')
         

    </div>

</div>
<script>
    function guardarCertificadoNoAfi() {
        Swal.fire({
            title: '¿ Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardar_no_afi');
                $('#modalInstitucion').modal('hide');
                $('#modalNuevoInstitucion').modal('hide');
            } else {
                Swal.fire({
                    title: "Registro Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                
            }
        });
    }

    function guardarCertificadoAfi() {
        Swal.fire({
            title: '¿ Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardar_afi');
                $('#modalInstitucion').modal('hide');
                $('#modalNuevoInstitucion').modal('hide');
            } else {
                Swal.fire({
                    title: "Registro Cancelado!",
                    text: "Se canceló el registro",
                    icon: "error"
                });
                
            }
        });
    }
    /* document.addEventListener('guardarCertificado2', function() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                Livewire.dispatch('guardar');

            } else {
                Swal.fire({
                    title: "Cancelado!",
                    text: "Se canceló el registro.",
                    icon: "error"
                });

            }
        });
    }); */

    document.addEventListener('guardado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "El certificado se guardó correctamente.",
            icon: "success"

        });
        $('#modalInstitucion').modal('hide');
        $('#modalNuevoInstitucion').modal('hide');
        $('#registrarCertificado').modal('hide');
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Sucedio un error.",
            icon: "error"

        });
        $('#modalInstitucion').modal('hide');
        $('#modalNuevoInstitucion').modal('hide');
        $('#registrarCertificado').modal('hide');
    });

    document.addEventListener('noExisteUsuario', function() {
        Swal.fire({
            title: "Error usuario!",
            text: "No existe el usuario.",
            icon: "warning"

        });
    });
    document.addEventListener('completar_datos', function() {
        Swal.fire({
            title: "Debe completar los datos!",
            text: "No se realizo el registro.",
            icon: "warning"

        });
    });

</script>
