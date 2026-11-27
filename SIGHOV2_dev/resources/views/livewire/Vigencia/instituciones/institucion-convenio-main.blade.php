<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:black;">VIGENCIA DE DERECHOS - <b> INSTITUCIONES DE
                CONVENIO</b></h4>
        @can('registrar.institucion_convenio')
        <div class="mr-2">
            <x-agregar-button wire:click="redirectToRegistro"
                title="REGISTRAR INSTITUCIÓN">REGISTRAR</x-agregar-button>
        </div>
        @endcan
        <!-- Aquí va el botón registrar -->

    </div>
    <hr>
    <div class="card-body pt-2 pr-0 pl-0">
        @can('lista.institucion_convenio')
        {{--Datatable--}}
        @livewire('Vigencia.Instituciones.instituciones-convenio-table')
        @endcan
        @can('ver.institucion_convenio')
        {{--Ver Institucion--}}
        @include('livewire.Vigencia.instituciones.modalVerInstitucionConvenio')
        @endcan

        <div wire:loading>
            <x-cargando-dos />
        </div>
    </div>
</div>

<script>
    document.addEventListener('activo', function() {
        Swal.fire({
            title: 'Activo!',
            text: "Se actualizo con éxito.",
            icon: 'success'
        });
    });


    document.addEventListener('inactivo', function() {
        Swal.fire({
            title: 'Inactivo!',
            text: "Se actualizo con éxito.",
            icon: 'error'
        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Sucedió un error, verifique el error.",
            icon: "warning"            
        });
    });
</script>
