<div>
    <x-modal-sin-footer id="bajaPlanilla" labelledby="bajaPlanillaLabel" title="REGISTRAR BAJA" saveAction="">
        @livewire('Aportes.bajas-medicas.bajas-register')
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('modalAbrirBajaPlanilla', function() {
        $('#bajaPlanilla').modal('show');
    });

    document.addEventListener('modalCerrarBajaPlanilla', function() {
        $('#bajaPlanilla').modal('hide');
    });
</script>
