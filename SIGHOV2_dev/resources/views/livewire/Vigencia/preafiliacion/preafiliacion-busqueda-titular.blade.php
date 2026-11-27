<div>
    <div class="col-12 d-flex justify-content-center">
        <x-buscar-dos-button class="btn d-flex align-items-center justify-content-center p-1" data-toggle="modal"
            data-target="#buscarTitularModal" title="BUSCAR AL ASEGURADO"> BUSCAR ASEGURADO
        </x-buscar-dos-button>
    </div>

    <x-ver-modal id="buscarTitularModal" labelledby="buscarTitularModalLabel" title="BÚSQUEDA DEL TITULAR">
        @livewire('Vigencia.Preafiliacion.busqueda-titular-table')
    </x-ver-modal>
</div>

<script>
    window.addEventListener('titularAgregado', event => {
        Swal.fire({
            title: "<b>Titular seleccionado</b>",
            text: "Se seleccionó al titular correctamente.",
            icon: "success"
        });
        $('#buscarTitularModal').modal('hide');
    });
</script>
