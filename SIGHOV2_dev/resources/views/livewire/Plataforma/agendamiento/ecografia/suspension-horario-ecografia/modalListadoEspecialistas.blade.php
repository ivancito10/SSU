<div>
    <x-ver-modal id="modalEspecialistasEcografia" labelledby="modalEspecialistasEcografiaLabel" title="LISTADO DE ESPECIALISTAS"
        saveAction="">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <livewire:Plataforma.agendamiento.ecografia.suspension-horario-ecografia.listado-especialistas-table/>
            </div>
        </div>

    </x-ver-modal>
</div>
<script>
        window.addEventListener('modalListadoEspecialidades', event => {
        $('#modalEspecialistasEcografia').modal('show');
    });
</script>