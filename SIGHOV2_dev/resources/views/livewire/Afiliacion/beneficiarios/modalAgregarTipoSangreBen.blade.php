<div wire:ignore>
    <x-small-modal id="modalTipoSangreBen" labelledby="modalTipoSangreBenLabel" title="AGREGAR TIPO DE SANGRE"
        saveAction="SaveTipoSangreBen()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="grupo_sanguineo"> TIPO DE SANGRE: </label>
                <div class="col-sm-12">
                    <select wire:model="grupo_sanguineo" id="grupo_sanguineo" class="select2 form-control" style="border-radius:10px">
                        <option value="RH A(-)">RH A(-)</option>
                        <option value="RH A(+)">RH A(+)</option>
                        <option value="RH AB(-)">RH AB(-)</option>
                        <option value="RH AB(+)">RH AB(+)</option>
                        <option value="RH B(-)">RH B(-)</option>
                        <option value="RH B(+)">RH B(+)</option>
                        <option value="RH O(-)">RH O(-)</option>
                        <option value="RH O(+)">RH O(+)</option>
                    </select>
                </div>
            </div>
            <br>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <br>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#2471a3"
                    class="bi bi-upload" viewBox="0 0 16 16">
                    <path
                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                    <path
                        d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z" />
                </svg>
                <label for="file_digital_sangre" style="color:#2471a3">Subir Tipo de Sangre:</label>
                <input class="form-control @error('file_digital_sangre') is-invalid @enderror" type="file"
                    id="file_digital_sangre" name="file_digital_sangre" required wire:model="file_digital_sangre"
                    accept=".pdf">
            </div>
        </div>

    </x-small-modal>
</div>

<script>
    window.addEventListener('tipoSangreBenModal', event => {
        $('#modalTipoSangreBen').modal('show');
    });

    function SaveTipoSangreBen() {
        Swal.fire({
            title: '¿Guardar Tipo de Sangre?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('AgregarTipoSangreBen');
                $('#modalTipoSangreBen').modal('hide');
            } else {
                Swal.fire({
                    title: "Completa todos los datos!",
                    text: "Se canceló el registro",
                    icon: "error"
                });

                $('#modalTipoSangreBen').modal('hide');
            }
        });
    }

    document.addEventListener('TipoSangreBenNoGuardado', function() {
        $('#modalTipoSangreBen').modal('hide');
        Swal.fire({
            title: "Error",
            text: "Se canceló el registro, verifique que todos los datos fueron ingresados.",
            icon: "error"

        });
    });

    document.addEventListener('TipoSangreBenGuardado', function() {
        $('#modalTipoSangreBen').modal('hide');
        Swal.fire({
            title: "<b>Felicidades</b>",
            text: "Se registró correctamente el tipo de sangre!!!",
            icon: "success"

        });
    });
</script>
