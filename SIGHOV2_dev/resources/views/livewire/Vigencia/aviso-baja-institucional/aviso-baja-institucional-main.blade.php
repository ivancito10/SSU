<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#2471a3; "><b>VIGENCIA DE DERECHOS</b> - <b style="color:#cb4335 ;">LISTA DE AVISOS DE BAJAS
                INSTITUCIONALES</b></h4>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            @livewire('Vigencia.AvisoBajaInstitucional.aviso-baja-institucional-table')

        </div>
        @include('livewire.Vigencia.aviso-baja-institucional.modalVerAvisoBajaInstitucional')
        {{-- @include('livewire.Aportes.Externos.aviso-baja.modalPdfAvisoBaja') --}}
    </div>

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    window.addEventListener('validarAvisoBaja', function() {
        Swal.fire({
            title: 'Validar Registro?',
            text: "Se validará el registro seleccionado ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, validar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('validarAvisoInstitucional');
            }
        });
    });


    document.addEventListener('guardarValidado', function() {
        Swal.fire({
            title: "Registro validado",
            text: "Se validó satisfactoriamente el registro seleccionado.",
            icon: "success"
        });
    });

    document.addEventListener('error', function() {
        Swal.fire({
            title: "Error!",
            text: "Se produjo un error.",
            icon: "error"
        });
    });
</script>
