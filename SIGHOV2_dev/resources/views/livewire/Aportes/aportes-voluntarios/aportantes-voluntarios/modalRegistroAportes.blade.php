<div>
    <!-- MODAL PARA VER -->
    <x-ver-modal id="registrarAportes" labelledby="registrarAportesLabel" title="APORTES REALIZADOS">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class ="card-body">
                    
            
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td><b>NOMBRE COMPLETO: </b></td>
                                            <td class="pl-4 px-4">{{ $nombre }} {{ $primerApellido }}
                                                {{ $segundoApellido }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>CARNET: </b></td>
                                            <td class="pl-4 px-4">{{ $ci }} {{ $complemento }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>FECHA DE NACIMIENTO: </b></td>
                                            <td class="pl-4 px-4">{{ $fechaNacimiento }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                    <p style="color:#1f618d; text-decoration:underline;" class="h5"><b><em> Historial de Aportes
                                                Realizados</em> </b></p>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <div style="overflow-y: auto; max-height: 300px; border: 1px solid #ddd;">
                                        <table class="table table-bordered table-hover"
                                            style="width: 100%; border-collapse: collapse;">
                                            <thead
                                                style="background-color: #2471a3; color: white; text-align: center; position: sticky; top: 0; z-index: 1;">
                                                <tr>
                                                    <th>#</th>
                                                    <td scope="col">MES</td>
                                                    <td scope="col">GESTIÓN</td>
                                                    <td scope="col">FECHA DE LIQUIDACIÓN</td>
                                                    <td scope="col">TOTAL APORTE</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($this->lista as $index => $dato)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td style="text-align: center;">{{ $dato->mes }}</td>
                                                        <td style="text-align: center;">{{ $dato->gestion }}</td>
                                                        <td style="text-align: center;">
                                                            {{ \Carbon\Carbon::parse($dato->fecha_liquidacion)->format('d/m/Y') }}
                                                        </td>
                                                        <td style="text-align: right;">
                                                            {{ number_format($dato->total_comprobante, 2, ',', '.') }} <b><em>Bs.</em></b>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ver-modal>

</div>
<script>
    window.addEventListener('modalRegistroAportes', event => {
        $('#registrarAportes').modal('show');
    });
</script>
