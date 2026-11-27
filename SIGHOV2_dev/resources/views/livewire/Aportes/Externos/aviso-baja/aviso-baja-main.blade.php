<div>
    <div class="input-group pr-4" style="justify-content: space-between; ">
        <h4 style="color:#2471a3; "><b>APORTES</b> - <b style="color:#cb4335 ;"> LISTA DE AVISO DE BAJAS
                INSTITUCIONALES</b></h4>

        <div class="d-flex">
            <div class="mr-2">
                <x-reportes-button wire:click="redirigirExcelReporteAvisoBaja"> REPORTES
            </div>
            </x-reportes-button>
            @can('registrar.aviso_baja')
                @livewire('aportes.externos.aviso-baja.aviso-baja-register')
            @endcan
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @can('lista.aviso_baja')
                @livewire('aportes.externos.aviso-baja.aviso-baja-table')
            @endcan
        </div>
        @include('livewire.Aportes.Externos.aviso-baja.modalRegistrarAvisoBajaInstitucional')
        @can('ver.aviso_baja')
            @include('livewire.Aportes.Externos.aviso-baja.modalVerAvisoBaja')
        @endcan
        @can('ver_pdf.aviso_baja')
            @include('livewire.Aportes.Externos.aviso-baja.modalPdfAvisoBaja')
            @include('livewire.Aportes.Externos.aviso-baja.modalReporteAvisoBaja')
        @endcan
    </div>

    <div wire:loading>
        <x-cargando-dos />
    </div>
</div>
<script>
    window.addEventListener('anularAvisoBaja', function() {
        Swal.fire({
            title: '¿Anular Registro?',
            text: "Se anulará el registro seleccionado ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('anularAvisoInstitucional');
            }
        });
    });

    document.addEventListener('guardarAnulado', function() {
        Swal.fire({
            title: "Anulado!",
            text: "Se anuló satisfactoriamente el registro seleccionado.",
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
