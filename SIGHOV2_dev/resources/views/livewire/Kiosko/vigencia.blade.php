<div>
    <div style="position: relative; ">
        <div style="position: absolute; top: -90px; bottom:0px; right: 200px;">
            <x-volver-button wire:click="volverAInicio">INICIO</x-volver-button>
            <x-salir-dos-button wire:click="de3VigenciaA2Busqueda">ATRÁS</x-salir-dos-button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-2">
            <div class="card-vista-comun ">
                <!-- FOTOGRAFIA -->
                <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                    class="img-thumbnail" width="100" height="100" alt="FTP.{{ $fotografia }}"
                    style="width:200px; height:200px; object-fit:cover; object-position: top;">
                <div class="card-body">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                        style="display:flex; justify-content: center; font-size: 30px">
                        @if ($estado_vigencia == 'VIGENTE')
                            <strong style="background-color:#196f3d; padding:5px; border-radius:10px">
                                <span style="color: white;"><b>VIGENTE</b></span>
                            </strong>
                        @elseif ($estado_vigencia == 'CESANTE')
                            <strong style="background-color:#ffbc06; padding:5px; border-radius:10px">
                                <span style="color: white;"><b>CESANTE</b></span>
                            </strong>
                        @else($estado_vigencia == 'NO VIGENTE')
                            <strong style="background-color:#d92a1a; padding:5px; border-radius:10px">
                                <span style="color: white;"><b>NO VIGENTE</b></span>
                            </strong>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card-vista-comun ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">NOMBRE COMPLETO: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $nombreCompleto }}</p>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">EDAD: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $edad }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">CARNET: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $carnet }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">MATRICULA: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $matricula_seguro }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">SEXO: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $sexoText }}</p>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">FECHA DE
                                NACIMIENTO: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $fechaNacimientoText }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">TIPO DE ASEGURADO: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $tipo_asegurado }}</p>
                        </div>
                        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                            <strong class="label-titulos">NOMBRE DE INSTITUCIÓN: </strong>
                            <p class="p-2 input-texto-vigencia"> {{ $nombre_institucion }}</p>
                        </div>
                    </div>
                </div>
                @if ($estado_vigencia == 'VIGENTE' || $estado_vigencia == 'CESANTE')
                    <div style="display: flex; justify-content: right; ">
                        <p
                            style="font-style: italic; font-size: 22px; color: #eded3a; text-align: right;padding-right:3px;margin-right:5px">
                            <b> Si esta seguro de sus datos, presione el botón verde para continuar.</b>
                        </p>
                        <x-siguiente-button wire:click="de3VigenciaA4SelectFecha" class="ml-3">
                            SIGUIENTE</x-siguiente-button>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>

</div>
