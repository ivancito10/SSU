<x-ver-dos-modal id="verSolicitud" labelledby="verSolicitudLabel" title=" SOLICITUD DE PRESTACION MÉDICA ">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">

                <div class="card-body">
                    <div class="card-body d-flex flex-column align-items-center">
                        <!--  Mostramos la foto del asegurado en base 64 -->
                        <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                            class="img-thumbnail" width="200" height="200" alt="FTP">

                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center;">
                            <span><b>EDAD: {{ $edadPersona }} AÑOS</b></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                            style="display: flex; justify-content: center; font-size: 35px">

                            <label for="estado_vigencia">
                                @if ($estado_vigencia == 'VIGENTE')
                                    <span style="color: green;"><b>VIGENTE</b></span>
                                @elseif($estado_vigencia == 'CESANTE')
                                    <span style="color: yellow;"><b>CESANTE</b></span>
                                @elseif($estado_vigencia == 'NO VIGENTE')
                                    <span style="color: red;"><b>NO VIGENTE</b></span>
                                @endif
                            </label>
                            <br>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <p style="background-color:#3E6CA7; color:white">
                                <b> ASEGURADO </b>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <spam style="color:#212f3c ;"><b>MATRICULA: </b> </spam>
                            <spam>{{ $matricula }} </spam>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <spam style="color:#21618c;"><b>TIPO: </b> </spam>
                            <spam>{{ $tipo_asegurado }} </spam>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre"> NOMBRE COMPLETO :</label>
                            <input class="form-control" id="nombre" disabled wire:model=nombreCompleto
                                style=" height: 30px;">
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="carnet"> CARNET :</label>
                            <input class="form-control" id="carnet" disabled wire:model=ci style=" height: 30px;">
                        </div>
                    </div>


                    <div class="row mb-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="institucion"> INSTITUCIÓN :</label>
                            <textarea class="form-control" id="institucion" disabled wire:model=institucion style=" resize:none;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>SOLICITUD DE PRESTACIÓN MEDICA</b></p>
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" style="text-align:center;">
                            <p style="background-color:#e5eff9  ; color:#1e5886; ">
                                <b> DATOS DEL CONVENIO DEL INTERIOR</b>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <spam><b>SOLICITUD: </b> </spam>
                            @if ($this->estado_validacion == 'VALIDADO')
                                <p class="h3" style="color: green">VALIDADO</p>
                            @elseif($this->estado_validacion == 'PENDIENTE')
                                <p class="h3 parpadeo" style="color: orange">PENDIENTE</p>
                            @elseif($this->estado_validacion == 'ANULADO')
                                <p class="h3 parpadeo" style="color: red ;">ANULADO</p>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-2">

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_hoja_de_ruta"> NRO DE HOJA DE RUTA:</label>
                            <input class="form-control" id="nro_hoja_de_ruta" wire:model=nro_hoja_de_ruta disabled
                                style=" height: 30px; ;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud_hr"> FECHA SOLICITUD:</label>
                            <input class="form-control" id="fecha_solicitud_hr" disabled wire:model=fecha_solicitud_hr
                                style=" height: 30px; ;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="cod_cite"> CODIGO CITE:</label>
                            <input class="form-control" id="cod_cite" disabled wire:model=cod_cite
                                style=" height: 30px; ;">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicio"> FECHA INICIO: </label>
                            <input class="form-control" id="fecha_inicio" type='date' disabled
                                wire:model=fecha_inicio style=" height: 30px; ;">
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> FECHA FIN: </label>
                            <input class="form-control" id="fecha_fin" type='date'disabled wire:model=fecha_fin
                                style=" height: 30px; ;">
                            <br>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="diasVigencia"> DÍAS DE VALIDEZ: </label>
                            <input class="form-control" id="diasVigencia" type='text'disabled
                                wire:model=diasVigencia style=" height: 30px; ">
                            <br>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_seguro"> SEGURO DE DESTINO: </label>
                            <input class="form-control" id="nombre_seguro" disabled wire:model=nombre_seguro
                                style=" height: 30px; ;">
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <div class="accordion" id="accordionEspecialidades">
                                <div class="card">
                                    <div class="card-header" id="headingEspecialidades"
                                        style="background-color: #3E6CA7;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-white" type="button"
                                                data-toggle="collapse" data-target="#collapseEspecialidades"
                                                aria-expanded="false" aria-controls="collapseEspecialidades">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                </svg>
                                                ESPECIALIDAD SOLICITADAS
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseEspecialidades" class="collapse"
                                        aria-labelledby="headingEspecialidades"
                                        data-parent="#accordionEspecialidades">

                                        <ul class="list-group list-group-flush">
                                            @if ($especialidades != null)
                                                @foreach ($especialidades as $especialidad)
                                                    <li class="list-group-item">{{ $especialidad->especialidad }}
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item"><b>NO SE REGISTRARON ESPECIALIDADES</b>
                                                </li>

                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='col-lg-6 col-md-4 col-sm-6 col-xs-12'>
                            <div class="accordion" id="accordionServicios">
                                <div class="card">
                                    <div class="card-header" id="headingServicios"
                                        style="background-color: #3E6CA7;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left text-white" type="button"
                                                data-toggle="collapse" data-target="#collapseServicios"
                                                aria-expanded="false" aria-controls="collapseServicios">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd"
                                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                </svg>
                                                SERVICIOS SOLICITADOS
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseServicios" class="collapse" aria-labelledby="headingServicios"
                                        data-parent="#accordionServicios">

                                        <ul class="list-group list-group-flush">
                                            @if ($servicios != null)
                                                @foreach ($servicios as $servicio)
                                                    <li class="list-group-item">{{ $servicio->servicio }}</li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item"><b>NO SE REGISTRARON SERVICIOS</b>
                                                </li>
                                            @endif

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4">
                            <spam><b>MEDICAMENTOS LINAME:</b>
                                @if ($medicamentos_liname == true)
                                    <b style="color:#1e8449 ;"> SI</b>
                                @else
                                    <b style="color:#cb4335;"> NO</b>
                                @endif
                            </spam>
                        </div>
                        <div class="col-lg-4">
                            <spam><b>MEDICAMENTOS EXTRALINAME:</b>
                                @if ($medicamentos_extraliname == true)
                                    <b style="color:#1e8449 ;"> SI</b>
                                @else
                                    <b style="color:#cb4335;"> NO</b>
                                @endif
                            </spam>

                        </div>

                    </div>

                    @if ($solicitantesAgregados != null)
                        <div class="row mb-2">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <!-- <div class="card">
                                    
                                    <div class="card-body"> -->
                                <div class="row">
                                    <div class="col-lg-12" style="text-align:center;">
                                        <p style="background-color:#e5eff9  ; color:#1e5886; ">
                                            <b> GRUPO FAMILIAR AGREGADO A LA SOLICITUD</b>
                                        </p>

                                    </div>

                                </div>
                                <div class='row'>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr align="center" style="background-color:#2471a3 ; color:white;">
                                                <th scope="col">NRO</th>
                                                <th scope="col">NOMBRE COMPLETO</th>
                                                <th scope="col">MATRICULA</th>
                                                <th scope="col">CARNET</th>
                                                <th scope="col">PARENTESCO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp
                                            @foreach ($this->solicitantesAgregados as $personaList)
                                                <tr style="padding: 15px;">
                                                    <td align="center" style="padding: 4px;">
                                                        {{ $counter++ }}</td>
                                                    <td align="center" style="padding: 4px;">
                                                        {{ $personaList->p_apellido . ' ' . $personaList->s_apellido . ' ' . $personaList->nombres }}
                                                    </td>
                                                    <td align="center" style="padding: 4px;">
                                                        {{ $personaList->matricula_seguro }}</td>
                                                    <td align="center" style="padding: 4px;">
                                                        {{ $personaList->complemento !== null ? $personaList->ci . '-' . $personaList->complemento : $personaList->ci }}
                                                    </td>
                                                    <td align="center" style="padding: 4px;">
                                                        {{ $personaList->tipo_asegurado }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="otras_limitaciones"> OTRAS LIMITACIONES: </label>
                            <!--<input class="form-control" id="otras_limitaciones" disabled wire:model="otras_limitaciones"
                                style=" height: 30px; ;"> -->
                            <div id="otraslimitaciones-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $otras_limitaciones !!}
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="observacion"> OBSERVACIONES: </label>
                            <!--<input class="form-control" id="observacion" disabled wire:model="observacion"
                                style=" height: 30px; background-color: #f8fcff;"> -->
                            <div id="observacion-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $observacion !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <!-- <div class="card" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <div class="card-body"> -->

            <div class="row mb-2">
                @if ($personaAnulado == null && $personaVal != null)

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#E08108; color:white">
                                <p class='h6'>REGISTRADO POR:</p>
                            </div>
                            <div class="card-body">
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p>{{ $usuarioNombre }}</p>

                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="persona" style='color:black;'> EN FECHA </label>
                                        <br>
                                        <p>{{ $fecha_creacion }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#268749; color:white">
                                <p class='h6'>VALIDACIÓN:</p>
                            </div>
                            <div class="card-body">
                                @if ($personaVal == null)
                                @else
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EMITIDO POR </label>
                                            <br>
                                            <p>{{ $personaVal }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EN FECHA </label>
                                            <br>
                                            <p>{{ $fecha_validacion }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> FIRMADO DIGITALMENTE POR </label>
                                            <br>
                                            <p>{{ $firmado_por }}</p>

                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="persona" style='color:black;'> EN FECHA Y HORA </label>
                                            <br>
                                            <p>{{ $fecha_firmado }}</p>

                                        </div>
                                    </div>
                                @endif




                            </div>
                        </div>
                    </div>
                @elseif($personaVal == null && $personaAnulado != null)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#E08108; color:white">
                                <p class='h6'>REGISTRADO POR:</p>
                            </div>
                            <div class="card-body">
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p>{{ $usuarioNombre }}</p>

                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="persona" style='color:black;'> EN FECHA </label>
                                        <br>
                                        <p>{{ $fecha_creacion }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#C11F0F; color:white">
                                <p class='h6'>ANULADO POR:</p>
                            </div>
                            <div class="card-body">
                                <div class='row'>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="persona" style='color:black;'> POR</label>
                                        <br>
                                        <p>{{ $personaAnulado }}</p>

                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="persona" style='color:black;'> EN FECHA </label>
                                        <br>
                                        <p>{{ $fecha_anulacion }}</p>

                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="observacion" style='color:black;'> MOTIVO DE ANULACION </label>
                                        <br>
                                        <p>{{ $motivo_anulacion }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($personaVal == null && $personaAnulado == null)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header" style="background-color:#E08108; color:white">
                                <p class='h6'><b>REGISTRADO POR</b></p>
                            </div>
                            <div class="card-body">
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <p>{{ $usuarioNombre }}</p>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label for="persona" style='color:black;'> EN FECHA </label>
                                        <br>
                                        <p>{{ $fecha_creacion }}</p>

                                    </div>
                                </div>
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
    window.addEventListener('modalVerSolicitud', event => {

        $('#verSolicitud').modal('show');

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
