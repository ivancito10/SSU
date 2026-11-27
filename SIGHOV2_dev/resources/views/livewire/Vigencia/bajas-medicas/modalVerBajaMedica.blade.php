<x-ver-dos-modal id="verBajaMedica" labelledby="verBajaMedicaLabel" title=" VER BAJA MÉDICA  ">
    <div class="row">

        <div class="col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    ASEGURADO</p>
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $foto }}">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12"
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <span class="h5 font-weight-bold">{{ $edadPersona }} años</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12  ">
                        <strong style="font-size:90%">MATRICULA :</strong>
                        <span class=" font-weight-bold h5 " style="color:#273746;  ">{{ $matricula }} </span>
                        <span class=" font-weight-bold h5" style="color: #236cb6; "> {{ $codigoInstitucional }} </span>
                    </div>
                </div>

                @if ($id_persona_baja)
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                            <strong style="font-size:90%">TIPO :</strong>
                            <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
                        </div>
                    </div>
                @endif



                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 ">
                        <span class=" font-weight-bold h5" style="color:#a50601">{{ $institucion }}
                        </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong> NOMBRE COMPLETO:</strong>
                        <p class="p-1" id="nombre"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $nombreCompleto }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong> CARNET:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $ci }}</p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                        <strong>SEXO:</strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ $sexo }} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <strong>
                            FECHA DE NACIMIENTO: </strong>
                        <p class="p-1"
                            style="background-color:#f2f4f4;padding-rigth:1; border-color:white; border-radius:10px;">
                            {{ \Carbon\Carbon::parse($fecha_nacimiento)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    DATOS DE LA BAJA MÉDICA</p>
                <div class="card-body">

                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <spam><b>SOLICITUD: </b> </spam>
                            @if ($this->estado_validado == 'VALIDADO')
                                <p class="h3" style="color: green">VALIDADO</p>
                            @elseif($this->estado_validado == 'PENDIENTE')
                                <p class="h3 parpadeo" style="color: orange">PENDIENTE</p>
                            @elseif($this->estado_validado == 'ANULADO')
                                <p class="h3 parpadeo" style="color: red ;">ANULADO</p>
                            @elseif($this->estado_validado == 'HOMOLOGADO')
                                <p class="h3 " style="color:  #2874a6 ;">HOMOLOGADO</p>
                            @endif

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-1 col-sm-1 col-12"></div>
                        <div class="col-lg-3 col-md-5 col-sm-5 col-12">
                            <label for="cite"> NRO. BAJA MÉDICA:</label>
                            <input id ="cite" class="form-control" type="text" wire:model='cite' disabled
                                style=" height: 30px;">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                            <label for="fecha_recepcion"> FECHA DE RECEPCIÓN:</label>
                            <input id ="fecha_recepcion" class="form-control" type="date"
                                wire:model='fecha_recepcion' disabled style=" height: 30px;">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-12"></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <p class='h6'><b>VALIDEZ DE LA BAJA MÉDICA:</b></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <label for="fecha_inicio"> DESDE:</label>
                            <input id ="fecha_inicio" class="form-control" type="date" wire:model='fecha_inicio'
                                disabled style=" height: 30px;">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <label for="fecha_fin"> HASTA:</label>
                            <input id ="fecha_fin" class="form-control" type="date" wire:model='fecha_fin'
                                disabled style=" height: 30px;">
                            <br>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-6">
                            <label for="diasValidez"> TOTAL DE DÍAS:</label>
                            <input id ="diasValidez" class="form-control" type="type" wire:model='diasValidez'
                                disabled style=" height: 30px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class='col-lg-5 col-md-4 col-sm-6 col-12'>
                            <label for="especialidad">ESPECIALIDAD A SOLICITAR:</label>
                            <p class="p-comun-disabled p-1"> {{ $especialidad }}</p>

                        </div>

                        <div class='col-lg-7 col-md-4 col-sm-6 col-12'>
                            <label for="especialista">ESPECIALISTA:</label>
                            <p class="p-comun-disabled p-1"> {{ $especialista }}</p>
                        </div>
                        @if ($switchHomologado)
                            <div class="col-lg-6 col-md-12 col-sm-12 col-12">

                                <label for="diagnostico" style="display: text-align: left;">DIAGNÓSTICO:</label>

                                <!--<textarea id ="diagnostico" class="form-control" type="text" disabled style="">{{ $diagnostico }}</textarea> -->
                                <!-- muestra tu diagnostico aqui xd -->
                                <div id="diagnostico-content"
                                    style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                    {!! $diagnostico !!}
                                </div>

                            </div>


                            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                                <label for="observacion" style='color:black;'> OBSERVACIÓN: </label>
                                <br>
                                <div id="observacion-content"
                                    style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                    {!! $observacion !!}
                                </div>

                            </div>
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">

                                <label for="diagnostico" style="display: text-align: left;">DIAGNÓSTICO:</label>

                                <!--<textarea id ="diagnostico" class="form-control" type="text" disabled style="">{{ $diagnostico }}</textarea> -->
                                <!-- muestra tu diagnostico aqui xd -->
                                <div id="diagnostico-content"
                                    style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                    {!! $diagnostico !!}
                                </div>

                            </div>
                        @endif

                    </div>

                </div>
                <br><br><br><br><br><br><br><br>
            </div>



        </div>
        <div class="col-sm-3">
            <!-- <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body"> -->
            <!-- Aqui la vista para mostrar los estados PENDIENTE/VALIDADO/ENTREGADO/ANULADO -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header" style="background-color:#E08108; color:white">
                        <p class='h6'>PENDIENTE:</p>
                    </div>
                    <div class="card-body">
                        @if ($estado_registro == false)
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="persona" style='color:black;'>REGISTRO POR SISTEMA</label>
                                <br>
                                <p><em>El registro fue realizado por el sistema.</em></p>

                            </div>
                        @else
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="persona" style='color:black;'> EMITIDO POR:</label>
                                <br>
                                <p>{{ $especialista }}</p>
                            </div>

                            {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <label for="persona" style='color:black;'> EN FECHA: </label>
                                <br>
                                <p>{{ $created_at }}</p>
                            </div> --}}
                        @endif
                    </div>
                </div>
            </div>

            @if ($user_validado == null && $user_anulado == null && $user_entregado == null)

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header" style="background-color:#268749; color:white">
                            <p class='h6'>VALIDADO: </p>
                        </div>
                        <div class="card-body">
                            <p><em>pendiente...</em></p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header" style="background-color:#C11F0F; color:white">
                            <p class='h6'>ANULADO: </p>
                        </div>
                        <div class="card-body">
                            <p><em>pendiente...</em></p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header" style="background-color:#1575D6 ; color:white">
                            <p class='h6'>ENTREGADO:</p>
                        </div>
                        <div class="card-body">
                            <div class='row'>
                                <p><em>pendiente...</em></p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if ($user_anulado != null)
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#C11F0F; color:white">
                                <p class='h6'>ANULADO:</p>
                            </div>
                            <div class="card-body">
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <label for="persona" style='color:black;'> POR:</label>
                                        <br>
                                        <p>{{ $user_anulado }}</p>

                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <label for="persona" style='color:black;'> EN FECHA: </label>
                                        <br>
                                        <p>{{ \Carbon\Carbon::parse($fecha_anulado)->format('d/m/Y H:i:s') }} </p>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <label for="observacion" style='color:black;'> MOTIVO DE LA ANULACIÓN:
                                        </label>
                                        <br>
                                        <div id="observacion-content"
                                            style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                            {!! $observacion !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @if ($user_entregado != null)
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#268749; color:white">
                                    <p class='h6'>VALIDADO:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> POR:</label>
                                            <br>
                                            <p>{{ $user_validado }}</p>

                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> EN FECHA: </label>
                                            <br>
                                            <p>{{ \Carbon\Carbon::parse($fecha_validacion)->format('d/m/Y H:i:s') }}
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#1575D6 ; color:white">
                                    <p class='h6'>ENTREGADO:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> ENTREGADO A:</label>
                                            <br>
                                            <p>{{ $id_persona_entregado }}</p>

                                        </div>

                                        {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> POR:</label>
                                            <br>
                                            <p>{{ $user_entregado }}</p>

                                        </div> --}}

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> EN FECHA: </label>
                                            <br>
                                            <p>{{ \Carbon\Carbon::parse($fecha_homologado)->format('d/m/Y H:i:s') }}
                                            </p>

                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#268749; color:white">
                                    <p class='h6'>VALIDADO:</p>
                                </div>
                                <div class="card-body">
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> POR:</label>
                                            <br>
                                            <p>{{ $user_validado }}</p>

                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <label for="persona" style='color:black;'> EN FECHA: </label>
                                            <br>
                                            <p>{{ $fecha_validacion }}</p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#1575D6 ; color:white">
                                    <p class='h6'>ENTREGADO:</p>
                                </div>
                                <div class="card-body">
                                    <p><em>pendiente...</em></p>

                                </div>
                            </div>

                        </div>
                    @endif

                @endif
            @endif

        </div>
        <!-- </div>
        </div> -->
    </div>

</x-ver-dos-modal>


<script>
    window.addEventListener('modalVerBajaMedica', event => {

        $('#verBajaMedica').modal('show');

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
