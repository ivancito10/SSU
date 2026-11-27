<div>
    <x-ver-modal id="verModalBajaInstitucional" labelledby="verModalBajaInstitucionalLabel" title="VER DATOS DE LA BAJA">

        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#c4dff3 ;text-align:center;"> <b> DATOS DEL ASEGURADO</b></p>

                    <div class="row d-flex flex-column align-items-center">
                        <!-- Paso 4. Mostramos la foto en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">

                            @if ($estado_vigencia == 'VIGENTE')
                                <span style="color: green;"><b>VIGENTE</b></span>
                            @elseif($estado_vigencia == 'CESANTE')
                                <span class="h3 parpadeo" style="color: #ed8a03;"><b>CESANTE</b></span>
                            @elseif($estado_vigencia == 'NO VIGENTE')
                                <span style="color: red;"><b>NO
                                        VIGENTE</b></span>
                            @endif


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <span class="h5 font-weight-bold">{{ $edad }} años</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                            <strong style="font-size:90%">MATRICULA :</strong>
                            <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                            <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <span class=" font-weight-bold h5" style="color:#a50601">{{ $nombreInsPatronal }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <span class=" font-weight-bold h5" style="color:#030e7a">{{ $tipoAsegurado }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong> NOMBRE COMPLETO:</strong>
                            <p class="p-1" id="nombre"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $nombre }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong> CARNET:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $carnet }}</p>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <strong>SEXO:</strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ $sexo == 'F' ? 'FEMENINO' : 'MASCULINO' }} </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE NACIMIENTO: </strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE AFILIACIÓN: </strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                {{ \Carbon\Carbon::parse($fechaReafiliacion)->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong>
                                FECHA DE VENCIMIENTO DE CARNET: </strong>
                            <p class="p-1"
                                style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                                @if ($vencimientoCarnet != null)
                                    @if ($vencimientoCarnet == 'INDEFINIDO')
                                        <strong>INDEFINIDO</strong>
                                    @else
                                        <strong>{{ \Carbon\Carbon::parse($vencimientoCarnet)->format('d/m/Y') }}</strong>
                                    @endif
                                @else
                                    SIN DATOS
                                @endif
                            </p>
                        </div>
                    </div>

                </div>
            </div>




            <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#c4dff3 ;text-align:center;"> <b> DATOS DE LA BAJA </b></p>

                    <div class="row mb-2">
                        <div class='col-lg-12 col-md-4 col-sm-6 col-xs-12'>
                            <label for="motivo_baja">MOTIVO DE BAJA INSTITUCIONAL:</label>
                            <input wire:model="motivo_baja" type="text" name="motivo_baja" id="motivo_baja"
                                class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fecha_baja_inst">FECHA DE INICIO DE CESANTIA:</label>
                            <input wire:model="fecha_baja_inst" type="date" name="fecha_baja_inst"
                                id="fecha_baja_inst" class="form-control" disabled>
                        </div>

                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fecha_baja_ssu">FECHA DE BAJA DEFINITIVA:</label>
                            <input wire:model="fecha_baja_ssu" type="date" disabled name="fecha_baja_ssu"
                                id="fecha_baja_ssu" class="form-control">
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
                            <label for="fechaRegistroBaja">FECHA DE REGISTRO DE LA BAJA:</label>
                            <input wire:model="fechaRegistroBaja" type="date" name="fechaRegistroBaja"
                                id="fechaRegistroBaja" class="form-control" disabled>
                        </div>
                    </div>


                    <div class="row mb-2">

                        <div class='col-lg-4 col-md-12 col-sm-12 col-xs-12'>
                            <label for="baja_definitiva">BAJA DEFINITIVA:</label>
                            <div id="baja_definitiva" class="h3 fw-bold text-center"
                                style="color: {{ $baja_definitiva ? '#1e8449' : '#cb4335' }}; font-weight: bold; width: 100%;">
                                {{ $baja_definitiva ? 'SI' : 'NO' }}
                            </div>
                        </div>

                        <div class='col-lg-8 col-md-12 col-sm-12 col-xs-12'>
                            <label for="nro_documento_referencia">NRO DOCUMENTO DE REFERENCIA:</label>
                            <input wire:model="nro_documento_referencia" type="text"
                                name="nro_documento_referencia" id="nro_documento_referencia" class="form-control"
                                disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                            <label for="descripcion_motivo">DESCRIPCIÓN DEL MOTIVO DE LA BAJA:</label>
                            <textarea wire:model="descripcion_motivo" name="descripcion_motivo" id="descripcion_motivo" class="form-control"
                                disabled>
                            </textarea>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </x-ver-modal>


    <script>
        window.addEventListener('verModalBajaInstitucional', event => {

            $('#verModalBajaInstitucional').modal('show');
        });
    </script>

    <style>
        @keyframes parpadeo {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .parpadeo {
            animation: parpadeo 1s infinite;
        }
    </style>

</div>
