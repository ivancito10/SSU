<!--ESTO ES EL COMPONENTE PARA VER LA INSTITUCION  -->
<x-ver-modal id="vistaModalExt" labelledby="vistaModalExtLabel" title="VISTA DE INSTITUCIÓN REGISTRADA">

    <div class="row">
        <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <spam style="text-decoration:underline;"><em> Datos Institucionales.</em> </spam>
                                </b>
                            </p>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE DE LA INSTITUCIÓN:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nombre }} </p>
                        </div>


                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <strong> NIT:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $nit }}
                            </p>
                        </div>

                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong> NÚMERO DE EMPLEADOR:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">

                                {{ $num_empleador }}
                            </p>
                        </div>

                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong> ACRÓNIMO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $acronimo }}

                            </p>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <strong> ACTIVIDAD EMPLEADOR:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $actividad_empleador }}

                            </p>
                        </div>



                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>TIPO DE APORTANTE:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $tipoclaseaportante }}

                            </p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>CÓDIGO DE COMERCIO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $tipo_comercio }}

                            </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>FECHA AFILIACIÓN:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px; user-select: none;">
                                {{ $fecha_afiliacion }}

                            </p>
                        </div>

                        <div class='col-lg-2 col-md-12 col-sm-12 col-xs-12'>
                            <span> <b>ES TGN: </b></span>
                            @if ($tgn == true)
                                <span style="color: #145a32;"><b>SI</b></span>
                            @else
                                <span style="color: #c0392b;"><b>NO</b></span>
                            @endif
                        </div>
                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <span> <b>ES DE TIPO: </b></span>
                            @if ($tipo_institucion == true)
                                <span>INSTITUCIÓN</span>
                            @else
                                <span>EMPRESA</span>
                            @endif
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <span> <b>ES: </b></span>
                            @if ($publica == true)
                                <span style="color:#154360;"><b>PÚBLICA</b></span>
                            @else
                                <span style="color:#cb4335;"><b>PRIVADA</b></span>
                            @endif

                        </div>

                        <div class='col-lg-3 col-md-12 col-sm-12 col-xs-12'>
                            <span> <b>ESTADO: </b></span>
                            @if ($estado == true)
                                <span style="color: #145a32;"><b>ACTIVO</b></span>
                            @else
                                <span style="color: #c0392b;"><b>INACTIVO</b></span>
                            @endif
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <button class="btn btn-warning" wire:click='abrir_nit'><svg
                                    xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                    <path
                                        d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
                                    <path
                                        d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
                                </svg> <b> ABRIR NIT</b></button>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:left;">
                            <p style="background-color:white; color:#1f618d;" class="h5">
                                <b>
                                    <spam style="text-decoration:underline;"><em> Datos referenciales.</em> </spam>
                                </b>
                            </p>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> DIRECCIÓN:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $direccion }} </p>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <strong>NÚMERO DE PUERTA:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $numero }} </p>
                        </div>
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <strong>ZONA:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $zona }} </p>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>REFERENCIA DE LA DIRECCIÓN:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $direccion_referencia }} </p>

                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>CORREO ELECTRÓNICO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $correo }} </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>1er. TÉLEFONO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $telefono }} </p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong>2do. TÉLEFONO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $telefono_dos }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5  col-md-12 col-sm-12 col-xs-12 mt-2 mt-lg-0">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3); height: 100%;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                            <p style="background-color:#21618c ; color:white; border-radius:10px;">ACTUAL REPRESENTANTE
                                LEGAL</p>
                        </div>
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <strong>NOMBRE COMPLETO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1;
                                    border-color:white; border-radius:10px;">
                                {{ $nombres }} {{ $p_apellido }} {{ $s_apellido }}</p>


                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <strong>CÉDULA DE IDENTIDAD:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1;
                                    border-color:white; border-radius:10px;">
                                {{ $ci }} {{ $complemento }} </p>

                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <strong>FECHA DE NACIMIENTO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1;
                                border-color:white; border-radius:10px;">
                                {{ $fecha_nacimiento_m }} </p>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:right;">
                            <button class="btn btn-info" wire:click='abrir_carnet'> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg>
                                <b> ABRIR CARNET</b></button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-ver-modal>
<script>
    window.addEventListener('modalAbrirInstitucionExt', event => {
        $('#vistaModalExt').modal('show');
    });
</script>
