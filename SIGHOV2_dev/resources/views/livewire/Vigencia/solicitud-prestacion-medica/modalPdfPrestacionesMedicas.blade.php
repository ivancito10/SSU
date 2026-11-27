<x-modal-small-sin-footer id="modalPdfPrestacionMedica" labelledby="modalPdfPrestacionMedicaLabel" title="REPORTES">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg> <em class="ml-2 h5" style="color:#2471a3; "><b>Seleccionar rango de fechas:</b> </em>
                <spam style="color:#cb4335;"><b>*</b></spam>
            </p>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!--contenido del modal -->
            <div class="form-group">
                <label for="fechaInicio">FECHA INICIO:</label>
                <input class="form-control @error('fechaInicio') is-invalid @enderror" wire:model='fechaInicio'
                    type="date" name="fechaInicio" id="fechaInicio" required>
                @error('fechaInicio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!--contenido del modal -->
            <div class="form-group">
                <label for="fechaFin">FECHA FIN:</label>
                <input class="form-control @error('fechaFin') is-invalid @enderror" wire:model='fechaFin' type="date"
                    name="fechaFin" id="fechaFin" required>
                @error('fechaFin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-end">
            <x-cancel-button class="mr-2" data-dismiss="modal"> CANCELAR </x-cancel-button><br>
            <x-reportes-button wire:click="generarPDF"> GENERAR PDF </x-reportes-button>
        </div>
    </div>
</x-modal-small-sin-footer>


<script>
    document.addEventListener('completeDatos', function() {
        Swal.fire({
            title: "<b>¡Sin fechas!</b>",
            text: "Introduzca las fechas de inicio y fin para generar el reporte.",
            icon: "warning"
        });
    });
</script>
