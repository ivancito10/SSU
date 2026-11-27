<x-ver-dos-modal id="verModalOrden" labelledby="verModalOrdenLabel" title=" ORDEN DE SERVICIO">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    ASEGURADO</p>
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
                        <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }} </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        @if ($esConvenioInteriorSwitch)
                            <strong style="font-size:90%"> SEGURO DE ORIGEN:</strong><br>
                        @endif

                        @if ($esConvenioEmergenciaSwitch)
                            <strong style="font-size:90%"> TITULAR RESPONSABLE:</strong><br>
                        @endif
                        <span class=" font-weight-bold h5" style="color:#a50601">{{ $institucion }}
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" id="nombre"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $nombreCompleto }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong> CARNET:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $ci }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong>SEXO:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $sexo }} </p>
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
            </div>
        </div>


        <div class="col-sm-6">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    DATOS DE ORDEN DE SERVICIO</p>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <spam><b>SOLICITUD: </b> </spam>
                            @if ($estado == 'VALIDADO')
                                <p class="h3" style="color: green">VALIDADO</p>
                            @elseif($estado == 'HOMOLOGADO')
                                <p class="h3" style="color: #1b4f72;"> HOMOLOGADO</p>
                            @elseif($estado == 'PENDIENTE')
                                <p class="h3 parpadeo" style="color: orange">PENDIENTE</p>
                            @elseif($estado == 'ANULADO')
                                <p class="h3 parpadeo" style="color: red ;">ANULADO</p>
                            @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_orden_servicio">NRO. ORDEN SERVICIO: </label>
                            <input class="form-control" id="nro_orden_servicio" wire:model=nro_orden_servicio disabled
                                style=" height: 30px; ">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_recepcion"> FECHA DE RECEPCIÓN:
                            </label>
                            <input class="form-control" id="fecha_recepcion" wire:model="fecha_recepcion"
                                style=" height: 30px; " disabled type="date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="institucion_convenio"> CENTRO MÉDICO:
                            </label>
                            <input class="form-control" id="institucion_convenio" wire:model=institucion_convenio
                                style=" height: 30px; " disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="especialidad"> ESPECIALIDAD: </label>
                            <textarea class="form-control" id="especialidad" wire:model=especialidad style=" resize: none; " disabled></textarea>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="especialista"> ESPECIALISTA: </label>
                            <textarea class="form-control" id="especialista" wire:model=especialista style=" resize: none; " disabled></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="estudio"> DIAGNÓSTICO: </label>
                            <!--<textarea class="form-control" id="estudio" wire:model='estudio' class="swal2-input" style=" resize:none; "
                                disabled></textarea> -->
                            <div id="diagnostico-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $estudio !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($estado == 'HOMOLOGADO')
                {{-- <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <p style="background-color:#e5eff9  ; color:#1e5886; ">
                            <b> REGISTRO DE ENTREGA</b> 
                            </p>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="nombresExt"> NOMBRES:</label>
                                    <input class="form-control" id="nombresExt" wire:model=nombresExt
                                        style=" height: 30px; " disabled>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="p_apellidoExt"> AP. PATERNO:</label>
                                    <input class="form-control" id="p_apellidoExt" wire:model=p_apellidoExt
                                        style=" height: 30px; " disabled>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="s_apellidoExt"> AP. MATERNO:</label>
                                    <input class="form-control" id="s_apellidoExt" wire:model=s_apellidoExt
                                        style=" height: 30px; " disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <label for="ciExt"> CARNET:</label>
                                    <input class="form-control" id="ciExt" wire:model=ciExt 
                                    style=" height: 30px; " disabled>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                    <label for="fecha_entregado"> FECHA ENTREGA:</label>
                                    <input class="form-control" id="fecha_entregado" wire:model=fecha_entregado
                                        style=" height: 30px;" disabled type="date">
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="observacion"> OBSERVACIONES: </label>
                                    <textarea class="form-control" id="observacion" class="swal2-input"
                                    style=" resize: none; " disabled>{{$observacion}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div> --}}
            @elseif($estado == 'ANULADO')
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <spam> ESTADO DE ENTREGA:</spam>
                        <p><em><b style=" color:#c0392b ">LA ORDEN DE SERVICIO FUE ANULADA</b></em> </p>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <spam> ESTADO DE ENTREGA:</spam>
                        <p><em><b style=" color:#3E6CA7">AÚN NO SE REALIZÓ LA ENTREGA</b></em> </p>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-sm-3">
            <!-- <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body"> -->
            <!--  Parte de los usuarios -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="card-header" style="background-color:#E08108; color:white">
                            <p class='h6'>REGISTRADO POR:</p>
                        </div>
                        <div class="card-body">
                            <p><b>NOMBRE: </b></p>
                            <p>{{ $nombreCreador }}</p>
                            <p class='h6'><b>EN FECHA: </b></p>
                            <p>{{ \Carbon\Carbon::parse($created_at)->format('d/m/Y H:i:s') }} </p>
                        </div>
                    </div>
                </div>

                @if ($validadoPor)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#268749; color:white">
                                <p class='h6'>VALIDADO POR:</p>
                            </div>
                            <div class="card-body">
                                <p><b>NOMBRE: </b></p>
                                <p>{{ $nombreValidar }} </p>
                                <p class='h6'><b>EN FECHA: </b></p>
                                <p>{{ \Carbon\Carbon::parse($fecha_validacion)->format('d/m/Y H:i:s') }} </p>

                            </div>
                        </div>
                    </div>
                @endif

                @if ($actualizadoPor)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#1575D6; color:white">
                                <p class='h6'>ENTREGADO A:</p>
                            </div>
                            <div class="card-body">
                                <p><b>NOMBRE: </b></p>
                                <p>{{ $nombresExt }}</p>
                                <p><b>CARNET: </b></p>
                                <p>{{ $ciExt }}</p>
                                <p class='h6'><b>EN FECHA: </b></p>
                                <p>{{ \Carbon\Carbon::parse($fecha_entregado)->format('d/m/Y H:i:s') }} </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($anuladoPor)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#C11F0F; color:white">
                                <p class='h6'>ANULADO POR:</p>
                            </div>
                            <div class="card-body">
                                <p><b>NOMBRE: </b></p>
                                <p>{{ $nombreAnular }}</p>
                                <p class='h6'><b>EN FECHA: </b></p>
                                <p>{{ \Carbon\Carbon::parse($fecha_anulacion)->format('d/m/Y H:i:s') }} </p>
                                <p><b>MOTIVO DE LA ANULACIÓN: </b></p>
                                <p>{{ $observacion }}</p>

                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <!-- </div>
            </div> -->
        </div>

    </div>




</x-ver-dos-modal>
<script>
    window.addEventListener('modalVerOrdenServicio', event => {

        $('#verModalOrden').modal('show');
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
