<!--MODAL PARA VER -->
<div>
    <x-ver-dos-modal id="vistaModalRecibos" labelledby="vistaModalRecibosLabel" title="LISTA DE RECIBOS REGISTRADOS ">
        {{-- resources/views/livewire/Aportes/aportes-voluntarios/aporte-voluntario-nuevo.blade.php --}}
        {{-- app/Livewire/Aportes/AportesVoluntarios/RecibosCajaVoluntarioTable.php --}}
        @livewire('aportes.aportes-voluntarios.recibos-caja-voluntario-table')

    </x-ver-dos-modal>
</div>
<script>
    window.addEventListener('modalListaRecibosVoluntario', event => {
        $('#vistaModalRecibos').modal('show');
    });
    window.addEventListener('modalListaRecibosVoluntarioQuitar', event => {
        Swal.fire({
            title: "¡Seleccionado!",
            text: "Se registró el total de pago",
            icon: "success"

        });
        $('#vistaModalRecibos').modal('hide');
    });
    window.addEventListener('reciboYaAgregadoVoluntario', event => {
        Swal.fire({
            title: "¡Recibo ya agregado!",
            text: "El recibo ya se encuentra registrado",
            icon: "warning"

        });
        $('#vistaModalRecibos').modal('hide');
    });
</script>
