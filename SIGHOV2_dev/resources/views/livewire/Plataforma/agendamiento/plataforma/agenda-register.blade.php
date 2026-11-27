<div>
    <div class="col-12 d-flex justify-content-center">
        <x-buscar-dos-button class="btn d-flex align-items-center justify-content-center p-1" data-toggle="modal"
            data-target="#aseguradosModal" title="BUSCAR AL ASEGURADO"> BUSCAR ASEGURADO
        </x-buscar-dos-button>
    </div>

    <x-ver-modal id="aseguradosModal" labelledby="aseguradosModalLabel" title="LISTADO DE ASEGURADOS">
        @livewire('Plataforma.Agendamiento.Plataforma.asegurados-table')
    </x-ver-modal>
</div>

<script>
    window.addEventListener('cerrarModalAsegurados', event => {
        Swal.fire({
            title: "Asegurado seleccionado",
            text: "Se seleccionó al asegurado correctamente.",
            icon: "success"
        });
        $('#aseguradosModal').modal('hide');
    });
</script>
