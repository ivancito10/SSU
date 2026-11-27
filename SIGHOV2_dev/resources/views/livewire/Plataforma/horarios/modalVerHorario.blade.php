<div>
    <x-small-ver-modal id="verModalHorario" labelledby="verModalHorarioLabel" title=" HORARIO REGISTRADO">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <span><b>ESTADO: </b></span>
                @if ($estado == true)
                    <span style="color: #1e8449;font-size:22px"><b> ACTIVO</b></span>
                @else($estado == false)
                    <span style="color: #e74c3c;font-size:22px"><b> INACTIVO</b></span>
                @endif
            </div>
        </div>

        <div class="row">

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <strong>HORA INICIO:</strong>
                <p class="p-1 p-comun-disabled">
                    {{ $hora_inicio }}</p>

            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <strong>HORA FIN:</strong>
                <p class="p-1 p-comun-disabled">
                    {{ $hora_fin }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <strong>INTÉRVALO:</strong>
                <p class="p-1 p-comun-disabled">
                    {{ $intervalo }} <b>min.</b></p>

            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <strong>NRO. PACIENTES:</strong>
                <p class="p-1 p-comun-disabled">
                    {{ $numero_paciente }}</p>

            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <strong>TURNO:</strong>
                <p class="p-1 p-comun-disabled">
                    {{ $turno }}</p>
            </div>
        </div>
        </x-small-ver-modall>
</div>
<script>
    window.addEventListener('modalVerHorario', event => {

        $('#verModalHorario').modal('show');
    });
</script>
