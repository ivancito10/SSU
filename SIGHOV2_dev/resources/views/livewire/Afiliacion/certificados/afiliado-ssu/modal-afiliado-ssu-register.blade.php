<!-- Modal para registrar un nuevo certificado -->
<div>
    <x-small-modal id="modalRegister" labelledby="modalRegisterLabel" title="REGISTRAR CERTIFICADO"
        saveAction="saveRegisterCertificado()">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="nombres"> NOMBRE COMPLETO: </label>
                        <input class="form-control" name="nombres" id="nombres"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                            disabled wire:model=nombres>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label for="ci">CARNET: </label>
                        <input class="form-control" name="ci" id="ci"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                            disabled wire:model=ci>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label for="matricula">MATRICULA: </label>
                        <input class="form-control" name="matricula" id="matricula"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                            disabled wire:model=matricula>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label for="sexo">SEXO:</label>
                        <input class="form-control" name="sexo" id="sexo"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                            disabled wire:model=sexoTexto>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <label for="fecha_nacimiento">FECHA DE NACIMIENTO: </label>
                        <input class="form-control" name="fecha_nacimiento" id="fecha_nacimiento"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                            disabled wire:model=fecha_nacimiento>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="tipo_asegurado"> TIPO DE ASEGURADO: </label>
                        <input class="form-control" id="tipo_asegurado"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;"
                            disabled wire:model=tipo_asegurado>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="motivo">MOTIVO</label>
                        <textarea class="form-control" name="motivo" id="motivo" style=" border-radius:10px;" wire:model=motivo
                            placeholder="Escribe el motivo..."></textarea>
                    </div>
                </div>
            </div>

        </div>

    </x-small-modal>
</div>
<script>
    window.addEventListener('abrirModalRegister', event => {
        $('#modalRegister').modal('show');
        // para scroll
        $('#modalCertificadoSsuRegister').css('overflow-y', 'auto');

    });

    function saveRegisterCertificado() {
        Swal.fire({
            title: '¿Guardar registro?',
            text: "Se registrarán los datos ingresados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('guardarCertificado');
                $('#modalRegister').modal('hide');
            }
        });
    }

    document.addEventListener('certificadoCreado', function() {
        Swal.fire({
            title: "Guardado!",
            text: "Se registro correctamente",
            icon: "success"
        });
        $('#modalRegister').modal('hide');
        $('#modalCertificadoSsuRegister').modal('hide');
    });
</script>
