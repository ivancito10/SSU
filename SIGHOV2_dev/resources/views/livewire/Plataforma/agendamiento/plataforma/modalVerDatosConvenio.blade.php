<div>
    <x-ver-button data-toggle="modal" style="padding:3px" data-target="#verDatosConvenio"
        title="ABRIR AGENDAS REGISTRADAS"> VER DATOS
        CONVENIO </x-ver-button>

    <x-ver-dos-modal id="verDatosConvenio" labelledby="verDatosConvenioLabel" title="DATOS DE CONVENIO">
        <div class="table-responsive">
            <table class="tabla-azul-oscuro">
                <thead>
                    <tr style=" text-align:center; vertical-align: middle;">
                        <th scope="col" style="width: 1%;">#</th>
                        <th scope="col" style="width: 1%;">COD. CONVENIO</th>
                        <th scope="col" style="width: 40%;">LIMITACIONES</th>
                        <th scope="col" style="width: 40%;">OTRAS LIMITACIONES</th>
                        <th scope="col" style="width: 18%;">OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($limitacionesConvenio as $key => $limitacion)
                        <tr>
                            <th scope="row" style="text-align: center; vertical-align: middle;">
                                {{ $key + 1 }}</th>
                            <td style="text-align: center; vertical-align: middle;">
                                {{ $limitacion['cod_interior'] }}
                            </td>
                            <td style="text-align: left; vertical-align: middle;">
                                @if ($limitacion['limitaciones'] === null || trim($limitacion['limitaciones']) === '')
                                    <b class="text-danger">SIN DATO</b>
                                @else
                                    {{ $limitacion['limitaciones'] }}
                                @endif
                            </td>
                            <td style="text-align: left; vertical-align: middle;">
                                @if ($limitacion['otras_limitaciones'] === null)
                                    <b><i>SIN DATO</i></b>
                                @else
                                    {!! $limitacion['otras_limitaciones'] !!}
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                @if ($limitacion['observaciones'] === null)
                                    <b><i>SIN DATO</i></b>
                                @else
                                    {!! $limitacion['observaciones'] !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>




    </x-ver-dos-modal>
</div>
<script></script>
