<div>
    <x-ver-modal id="vistaModalAvisoBaja" labelledby="vistaModalAvisoBajaLabel"
        title="AVISO DE  BAJA ASEGURADO REGISTRADO">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                                <span class="h6">
                                    @if ($estado_validado == true && $anulado == null)
                                        <span class="H4"><b style="color: #1e8449;">VALIDADO</b></span>
                                    @elseif($estado_validado == false && $anulado == null)
                                        <span class="H4"><b style="color: #e67e22 ;">PENDIENTE</b></span>
                                    @elseif($anulado == true)
                                        <span class="H4"><b style="color: #c0392b ;">ANULADO</b></span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Asegurado registrado.</em>
                                        </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                                <label for="nombre_completo">NOMBRE COMPLETO: </label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $nombre_completo }} </p>

                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="ci">CARNET: </label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $ci }} </p>

                            </div>
                            <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                                <label for="matricula_seguro">MATRICULA: </label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $matricula_seguro }} </p>

                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1f618d;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em> Detalles del registro.</em>
                                        </spam>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_baja">FECHA BAJA: </label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $fecha_baja }} </p>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="motivo_baja">MOTIVO BAJA:</label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $motivo_baja }} </p>
                            </div>
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="ultimo_salario">ÚLTIMO SALARIO: </label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $ultimo_salario }} </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                                <label for="fecha_solicitud">FECHA SOLICITUD (REGISTRO): </label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $fecha_solicitud }} </p>
                            </div>
                            <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                                <label for="descripcion">DESCRIPCIÓN:</label>
                                <p class="p-1"
                                    style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                    {{ $descripcion }} </p>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#E08108; color:white">
                                        <p class='h6'>REGISTRADO POR:</p>
                                    </div>
                                    <div class="card-body">
                                        <p><b>NOMBRE: </b></p>
                                        <p>{{ $nombreRegistra }}</p>
                                        <p><b>PERTENECIENTE A: </b></p>
                                        <p>{{ $institucionRegistra }}</p>
                                        <p class='h6'><b>EN FECHA: </b></p>
                                        <p>{{ \Carbon\Carbon::parse($fechaRegistra)->format('d/m/Y') }} </p>
                                    </div>
                                </div>
                            </div>

                            @if ($validadoSwitch)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-header" style="background-color:#268749; color:white">
                                            <p class='h6'>VALIDADO POR:</p>
                                        </div>
                                        <div class="card-body">
                                            <p><b>NOMBRE: </b></p>
                                            <p>{{ $nombreValida }} </p>
                                            <p class='h6'><b>EN FECHA: </b></p>
                                            <p>{{ \Carbon\Carbon::parse($fechaValida)->format('d/m/Y') }}
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($anuladoSwitch)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="card-header" style="background-color:#C11F0F; color:white">
                                            <p class='h6'>ANULADO POR:</p>
                                        </div>
                                        <div class="card-body">
                                            <p><b>NOMBRE: </b></p>
                                            <p>{{ $nombreAnula }}</p>
                                            <p><b>PERTENECIENTE A: </b></p>
                                            <p>{{ $institucionAnula }}</p>
                                            <p class='h6'><b>EN FECHA: </b></p>
                                            <p>{{ \Carbon\Carbon::parse($fechaAnula)->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-ver-modal>
</div>

<script>
    window.addEventListener('modalVerAvisoBaja', event => {
        $('#vistaModalAvisoBaja').modal('show');
    });
</script>
