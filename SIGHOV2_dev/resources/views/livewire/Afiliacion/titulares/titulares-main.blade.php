<div>
    <div class=" row d-flex justify-content-between align-items-center pr-4">
        <h4 class="mb-0" style="color:black;">AFILIACIÓN - <b> LISTA DE ASEGURADOS TITULARES</b></h4>
        <div class="d-flex">
            @can('registrar.titulares')
                <div class="mr-2">
                    @livewire('Afiliacion.titulares.titulares-register')
                </div>
            @endcan
            @can('reporte.afiliado_titular')
                <x-reportes-button wire:click="abrirModalReportes"> REPORTES
                </x-reportes-button>
                @include('livewire.Afiliacion.titulares.modalReportesTitulares')
            @endcan
        </div>
    </div>
    <br>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('ver.titulares')
            <!--Modal para ver los datos de asegurado -->
            @include('livewire.Afiliacion.titulares.modalVerTitular')
        @endcan
        @can('lista.titulares')
            @livewire('Afiliacion.titulares.titulares-table')
        @endcan

        @can('agregar_institucion.titulares')
            <!--Modal para agregar Institucion asegurado -->
            @include('livewire.Afiliacion.titulares.modalAgregarInstitucion')
        @endcan

        @can('registrar_tipo_sangre.titulares')
            <!--Modal para el tipo de sangre  -->
            @include('livewire.Afiliacion.titulares.modalAgregarTipoSangre')
        @endcan
        @can('ver_grupo_familiar.titulares')
            <!--Modal para ver el Grupo Familiar  -->
            @include('livewire.Afiliacion.titulares.modalVerGrupoFamiliar')
        @endcan
        @can('ver_registro_asuss.titulares')
            <!--Modal para verificar con el Asuss  -->
            @include('livewire.Afiliacion.titulares.modalValidacionAsuss')
        @endcan

        @can('aportes.titulares')
            <!--modal para VER APORTES insitutcionalex-->
            @include('livewire.Afiliacion.titulares.modalVerAportesIns')
        @endcan
        @can('aportes.titulares')
            <!--modal para VER APORTES voluntarios-->
            @include('livewire.Afiliacion.titulares.modalVerAportesVol')
        @endcan

        <!-- Modal para la carnetizaciones -->
        @include('livewire.Afiliacion.titulares.modalCarnetizacion')
        @include('livewire.Afiliacion.titulares.modalRegistrarArchivoGrupo')


        {{-- Para el cargando --}}
        <div wire:loading>
            <x-cargando-dos />
        </div>

    </div>
</div>
<script>
    window.addEventListener('RegistroCarnetCorrecto', event => {

        Swal.fire({
            title: "Guardado!",
            text: "Se generó correctamente el Carnet de Asegurado",
            icon: "success"

        });
        $('#carnetModal').modal('hide');
    });

    window.addEventListener('RegistroCarnetInCorrecto', event => {

        Swal.fire({
            title: "Se canceló el registro del carnet",
            text: "Intenta nuevamente, si el problame persiste comunicate con la unidad de sistemas ",
            icon: "error"

        });
        $('#carnetModal').modal('hide');
    });

    /*  VALIDACIONES PARA EL CARNET */

    window.addEventListener('indefinidoSinEmision', event => {
        Swal.fire({
            title: "Error!",
            text: "Si seleccionaste Indefinido , debes agregar una fecha de emisión",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
    window.addEventListener('indefinidoConEmision', event => {

        Swal.fire({
            title: "Error!",
            text: "Si seleccionaste Indefinido , no debes agregar una fecha de vencimiento",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
    window.addEventListener('normalSinFechas', event => {
        Swal.fire({
            title: "Error!",
            text: "Debes agregar los campos correspondientes",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
    window.addEventListener('emisionMayorVencimiento', event => {

        Swal.fire({
            title: "Error!",
            text: "La fecha de vencimiento debe ser mayor a la fecha de emision",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
    window.addEventListener('sinFotoAfiliado', event => {
        Swal.fire({
            title: "Error!",
            text: "El afiliado no tiene fotografia",
            icon: "error"
        });

    });



    window.addEventListener('faltaMotivoCarnet', event => {

        Swal.fire({
            title: "Ingresa todos los campos correspondientes",
            text: "Recuerda ingresar un motivo y el nro de recibo para la reimpresión del carnet",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });

    window.addEventListener('actualizaReferenciaYDireccion', event => {
        Swal.fire({
            title: "Actualiza los datos Referenciales!",
            text: "Porfavor actualiza los datos necesarios",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
    window.addEventListener('actualizaReferenciales', event => {
        Swal.fire({
            title: "Actualiza los datos Referenciales!",
            text: "Porfavor actualiza el telefono, nombre de referencia y telefono de referencia",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
    window.addEventListener('actualizaDireccion', event => {
        Swal.fire({
            title: "Ingresa la dirección en el formato correcto",
            text: "Recuerda ingresar la dirección por separado, ingresa el número de vivienda , la dirección y la zona por separado",
            icon: "error"
        });
        $('#carnetModal').modal('hide');
    });
</script>
