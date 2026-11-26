<div>
    <x-modal-sin-footer-grande id="modalRegistrarInyeccion" labelledby="modalRegistrarInyeccionLabel"
        title="ENFERMERÍA - REGISTRAR INYECCIÓN">
        @livewire('enfermeria.inyectables.inyectables-register')
    </x-modal-sin-footer-grande>
</div>
<script>
    window.addEventListener('modal_registrar_inyeccion', event => {
        $('#modalRegistrarInyeccion').modal('show');
    });

    window.addEventListener('cerrar_modal_registrar_inyeccion', event => {
        $('#modalRegistrarInyeccion').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '');
    });
</script>
