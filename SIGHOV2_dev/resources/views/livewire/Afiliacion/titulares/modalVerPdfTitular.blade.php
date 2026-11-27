<div>
    <x-modal-sin-footer id="verModalPdf" labelledby="verModalPdfLabel" title="ARCHIVO PDF DEL TITULAR">

        @if (!is_null($rowId))
            <iframe src="{{ route('ver.File', ['ruta' => $ruta, 'row' => $rowId]) }}#toolbar=0" width="100%"
                height="600px" style="border: none;">
            </iframe>
        @else
            <div class="text-center p-4">
                <span class="text-muted">Cargando el visor del documento...</span>
            </div>
        @endif
        <div class="modal-footer pt-0 pb-0" style="border: none; background-color: white;">

            <x-cancel-button wire:click="cerrarModalVerTitular">
                CANCELAR</x-cancel-button>
        </div>
    </x-modal-sin-footer>
</div>
<script>
    document.addEventListener('modalVerPdfTitular', function() {
        $('#verModalPdf').modal('show');
    });


    document.addEventListener('modalVerPdfTitularCerrar', function() {
        $('#verModalPdf').modal('hide');
    });
</script>
