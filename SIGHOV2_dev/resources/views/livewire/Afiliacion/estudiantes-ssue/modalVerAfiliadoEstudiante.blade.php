<div>
    <x-ver-modal id="verEstudiante" labelledby="verEstudianteLabel" title=" AFILIADO ESTUDIANTE  ">
        <livewire:Afiliacion.EstudiantesSsue.historial-matriculas />
    </x-ver-modal>

    <script>
        window.addEventListener('modalVerAfiliadoEstudiante', event => {

            $('#verEstudiante').modal('show');
        });
    </script>
</div>
