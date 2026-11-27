<div>
    <x-ver-modal id="ModalEspecialistas" labelledby="ModalEspecialistasLabel" title="LISTADO DE ESPECIALISTAS"
        saveAction="">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <livewire:plataforma.SuspensionHorario.listado-especialistas-table/>
            </div>
        </div>

    </x-ver-modal>
</div>
<script>
        window.addEventListener('ModalListadoEspecialidades', event => {
        $('#ModalEspecialistas').modal('show');
    });
</script>