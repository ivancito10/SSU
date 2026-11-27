<div>
    <x-ver-button data-toggle="modal" style="padding:3px" data-target="#verAgendaCompleta" title="ABRIR AGENDAS REGISTRADAS"
        style="padding:3px"> AGENDAS
        REGISTRADAS</x-ver-button>

    <x-ver-dos-modal id="verAgendaCompleta" labelledby="verAgendaCompletaLabel" title="AGENDAS REGISTRADAS">
        <div class="row mb-2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 text-left">
                        <div class="cabecera">
                            <b>LISTADO DE HORARIOS ASIGNADOS AL MÉDICO</b>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12 text-right ">
                        <div class="cabecera-borde">
                            <div class="row">
                                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                    <b>Consultorio:</b> <em>{{ $verConsultorio }} {{ $verPiso }}</em><br>
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
                                    <b>Ubicación:</b> <em>{{ $verUbicacion }}</em> <br>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <b>Dirección:</b> <em>{{ $verDireccion }}</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="height: 0.5rem;"></div>

                <div style="height: 600px; overflow-y: auto;">
                    <table class="tabla-azul-oscuro">
                        <thead>
                            <tr style=" text-align:center; vertical-align: middle;">
                                <th scope="col" style="width: 1%;">#</th>
                                <th scope="col" style="width: 1%;">HORA</th>
                                <th scope="col" style="width: 8%;">MATRICULA</th>
                                <th scope="col" style="width: 25%;">ASEGURADO</th>
                                <th scope="col" style="width: 25%;">INSTITUCIÓN</th>
                                <th scope="col" style="width: 17%;">TIPO ASEGURADO</th>
                                <th scope="col" style="width: 8%;">SUSPENSIÓN</th>
                                <th scope="col" style="width: 8%;">SERVICIO</th>
                                {{-- <th scope="col">ESTADO</th>
                                        <th scope="col">OBSERVACIÓN</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($horasDisponibles as $key => $hora)
                                <tr>
                                    <th scope="row" style="text-align: center; vertical-align: middle;">
                                        {{ $key + 1 }}</th>
                                    <td style="text-align: center; vertical-align: middle;">
                                        @if ($hora['estado'] == 'LIBRE')
                                            <span class="badge badge-success"
                                                style="font-size: 15px;">{{ $hora['hora'] }}</span>
                                        @else
                                            <span class="badge badge-danger"
                                                style="font-size: 15px;">{{ $hora['hora'] }}</span>
                                        @endif

                                    </td>
                                    @if ($hora['persona_asociada'])
                                        <td style="text-align: center; vertical-align: middle;">
                                            {{ $hora['matricula'] }}</td>
                                        <td style="text-align: left; vertical-align: middle;">
                                            {{ $hora['asegurado'] }}</td>
                                        <td style="text-align: left; vertical-align: middle;">
                                            {{ $hora['institucion'] }}</td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            {{ $hora['tipo_asegurado'] }}</td>
                                    @else
                                        <td colspan="4" style="text-align: center; vertical-align: middle;">
                                            OBSERVACION: <b>{{ $hora['observacion'] }}</b>
                                        </td>
                                    @endif
                                    <td style="text-align: center; vertical-align: middle;">
                                        {{ $hora['motivo_suspension'] }}</td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        {{ $hora['servicio'] }}</td>
                                    {{-- <td style="text-align: center; vertical-align: middle;">

                                                @if ($hora['estado'] == 'LIBRE')
                                                    <span style="color: #1e8449;">DISPONIBLE</span>
                                                @else
                                                    <span style="color: #c0392b ;">OCUPADO</span>
                                                @endif

                                            </td>
                                            <td>{{ $hora['observacion'] }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

    </x-ver-dos-modal>
</div>
<script>
    /*window.addEventListener('cerrarModalAsegurados', event => {

      Swal.fire({
          title: "<b>Felicidades!</b>",
          text: "Se seleccionó al asegurado correctamente",
          icon: "success"
      });
      $('#aseguradosModal').modal('hide');
  });*/
</script>
