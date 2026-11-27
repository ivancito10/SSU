<!--MODAL PARA VER -->
<div>
    <x-ver-dos-modal id="vistaModalRecibos" labelledby="vistaModalRecibosLabel" title="LISTA DE RECIBOS REGISTRADOS ">
        @livewire('aportes.aportes-institucionales.recibos-caja-table')
    </x-ver-dos-modal>
</div>
<script>
    window.addEventListener('modalListaRecibos', event => {
        $('#vistaModalRecibos').modal('show');
    });
    window.addEventListener('modalListaRecibosQuitar', event => {
        Swal.fire({
            title: "¡Seleccionado!",
            text: "Se registró el total de pago",
            icon: "success"

        });
        $('#vistaModalRecibos').modal('hide');
    });
    window.addEventListener('reciboYaAgregado', event => {
        Swal.fire({
            title: "¡Recibo ya agregado!",
            text: "El recibo ya se encuentra registrado",
            icon: "warning"

        });
        $('#vistaModalRecibos').modal('hide');
    });
    
</script>
