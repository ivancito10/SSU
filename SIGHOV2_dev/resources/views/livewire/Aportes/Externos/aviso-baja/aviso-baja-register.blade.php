<div>
    <x-agregar-button data-toggle="modal" data-target="#modalAvisoBaja">
        REGISTRAR</x-agregar-button>
    <x-ver-modal id="modalAvisoBaja" labelledby="ListaPersonasLabel" title="LISTA DE APORTANTES REGISTRADOS">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @livewire('Aportes.externos.aviso-baja.lista-personas')
            </div>
        </div>
    </x-ver-modal>
    <div wire:loading>
        <x-cargando-dos />
    </div>
    @include('livewire.Aportes.Externos.aviso-baja.modalRegistrarAvisoBajaInstitucional')

</div>

<script>
    function RegistrarBaja() {
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
                Livewire.dispatch('guardarAvisoBaja');
            }
        });
    }
    document.addEventListener('guardadoBaja', function() {
        Swal.fire({
            title: "Guardado!",
            text: "El registro se realizó correctamente.",
            icon: "success"
        });
        $('#registrarAviso').modal('hide');
        $('#modalAvisoBaja').modal('hide');
    });



    document.addEventListener('faltaDatos', function() {
        Swal.fire({
            title: "Complete todos los campos!",
            text: "Debe completar todos los campos, el registro no se realizó.",
            icon: "warning"
        });
        $('#registrarAviso').modal('show');
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Se produjo un error.",
            icon: "error"
        });
    });
</script>
