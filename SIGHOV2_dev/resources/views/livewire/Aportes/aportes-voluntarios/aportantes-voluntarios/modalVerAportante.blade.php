<div>
    <!-- MODAL PARA VER -->
    <x-ver-modal id="verContratoModal" labelledby="verContratoModalLabel" title="VISTA DE APORTANTE REGISTRADO">

        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12" style="background-color:white;">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <div class ="card-body">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                                <p style="background-color:#1a5276 ; color:white; border-radius:10px;"> APORTANTE
                                    VOLUNTARIO</p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span><b>NOMBRE COMPLETO: </b>
                                    <br>
                                    {{ $nombre }} {{ $primerApellido }} {{ $segundoApellido }} </span>

                            </div>


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <br>
                                <span><b>CARNET: </b>{{ $ci }} {{ $complemento }} </span>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span> <b> FECHA DE NACIMIENTO: </b>{{ $fechaNacimiento }} </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
                <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                    <div class ="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="background-color:white; color:#1a5276;" class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em>Actuales Datos Referenciales.</em>
                                        </spam>
                                    </b>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td>
                                                <b>DIRECCIÓN: </b>
                                            </td>
                                            <td class="px-3">{{ $direccion }} </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>NÚMERO DE PUERTA: </b>
                                            </td>
                                            <td class="px-3">{{ $numero }} </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>ZONA: </b>
                                            </td>
                                            <td class="px-3">{{ $zona }} </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>1er. TELÉFONO: </b>
                                            </td>
                                            <td class="px-3">{{ $telefono1 }} </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>2do. TELÉFONO: </b>
                                            </td>
                                            <td class="px-3">{{ $telefono2 }} </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>CORREO ELÉCTRONICO: </b>
                                            </td>
                                            <td class="px-3">{{ $correo }} </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                                <p style="color:#1a5276;  " class="h5">
                                    <b>
                                        <spam style="text-decoration:underline;"><em>Contrato Registrado.</em> </spam>
                                    </b>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td>
                                                <b>FECHA INICIO: </b>
                                            </td>
                                            <td class="px-4">
                                                {{ $fecha_i_m }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>FECHA FIN: </b>
                                            </td>
                                            <td class="px-4">
                                                {{ $fecha_f_m }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b>DESCRIPCIÓN: </b>
                                            </td>
                                            <td class="px-4">
                                                {{ $descripcionContrato }}
                                            </td>
                                        </tr>
                                    </table>
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
    window.addEventListener('modalVerAportante', event => {
        $('#verContratoModal').modal('show');
    });
</script>
