<div>

    <x-small-modal-no-close id="bloqueoModal" labelledby="bloqueoModalLabel" title=" Notificación Importante">
        <!-- Incluir Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <div class="content">
            <div class="modal-body px-4 py-3">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <p class="h3"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="95" height="95" fill="orange" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                                <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                            </svg>
                            <br><br>
                            Actualice la UFV!
                        </p>
                        <br>
                        <p class="h5" style="font-family: 'Roboto', sans-serif; font-size: 16px; font-weight: 500; color: #4a4a4a;">
                           <em>Usted debe actualizar la UFV con los valores del día para evaluar las planillas, de lo contrario no podra realizar la liquidacion de planillas.</em> 
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <!-- <x-subir-archivo-button style="padding: 5px 10px;" wire:click="enlace_ufv"> 
                            REGISTRAR  UFV
                        </x-subir-archivo-button> -->

                        <button class="btn btn-primary" wire:click="enlace_ufv">OK </button>
                    </div>
                </div>
            </div>
        </div>
    </x-small-modal-no-close>
</div>
<!-- Script para mostrar y ocultar el modal -->
<script>
    window.addEventListener('abrirModalBloqueo', event => {
        $('#bloqueoModal').modal('show');
    });
    window.addEventListener('cerrar_modal', event => {
        $('#bloqueoModal').modal('hide'); // Cierra el modal
    });
</script>
