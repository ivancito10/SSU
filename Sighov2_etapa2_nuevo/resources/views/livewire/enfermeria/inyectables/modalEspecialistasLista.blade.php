<div>
    <x-modal-sin-footer id="verListaEspecialistas" labelledby="verListaEspecialistasLabel"
        title=" LISTADO DE MÉDICOS SOLICITANTES">

        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12" style="text-align:left;">
                <div class="card cabecera">
                    <b> LISTA DE ESPECIALISTAS</b>
                </div>
            </div>
        </div>
        @livewire('enfermeria.inyectables.select-especialista-table')
        <br>
        <div class="modal-footer pt-0"
            style="background-color: white; border-top: none !important; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
            <x-cancel-button onclick="cerrarModalEspecialistasLista()"> CANCELAR</x-cancel-button>
        </div>
    </x-modal-sin-footer>
</div>
<script>
    window.addEventListener('modalEspecialistasLista', event => {
        $('#verListaEspecialistas').modal('show');
    });

    window.addEventListener('cerrarModalEspecialistasLista', event => {
        cerrarModalEspecialistasLista();
    });

    function cerrarModalEspecialistasLista() {
        $('#verListaEspecialistas').modal('hide');
        $('#verListaEspecialistas').removeClass('show').css('display', 'none');
        setTimeout(() => {
            if ($('#modalRegistrarInyeccion').hasClass('show')) {
                while ($('.modal-backdrop').length > 1) {
                    $('.modal-backdrop').last().remove();
                }
                $('body').addClass('modal-open');
            } else if ($('.modal.show').length === 0) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            }
        }, 150);
    }

    $(document).on('hidden.bs.modal', '#verListaEspecialistas', function () {
        if ($('#modalRegistrarInyeccion').hasClass('show')) {
            while ($('.modal-backdrop').length > 1) {
                $('.modal-backdrop').last().remove();
            }
            $('body').addClass('modal-open');
        }
    });
</script>
