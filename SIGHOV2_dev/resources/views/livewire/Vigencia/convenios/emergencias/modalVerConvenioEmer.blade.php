<x-ver-dos-modal id="verModalConvenioEmer" labelledby="verModalConvenioEmerLabel" title="VISTA DE CONVENIO DE EMERGENCIA">
    <div class="row">

        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    ASEGURADO POR CONVENIO</p>
                <div class="row d-flex flex-column align-items-center">
                    <!-- Paso 4. Mostramos la foto en base 64 -->
                    <img src="{{ !empty($fotito) ? 'data:image/jpg;base64,' . $fotito : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="200" height="200" alt="FTP.{{ $fotito }}">
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
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <strong style="font-size:90%">TIPO :</strong>
                        <span class=" font-weight-bold" style="color:#1a4c72 ;">{{ $tipo_asegurado }}</span>
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


        <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    DATOS DE CONVENIO DE EMERGENCIA</p>
                <!-- <div class="card-header" style="background-color:#283747; color:white"> DATOS DE
                    CONVENIO DE EMERGENCIA
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <label for="cod_convenio"> CODIGO CONVENIO:
                            </label>
                            <input class="form-control" id="cod_convenio"
                                value="C/EM/{{ $cod_convenio }}/{{ $created_at }}" style=" height: 30px; " disabled>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_inicio"> FECHA INICIO: </label>
                            <input class="form-control" id="fecha_inicio" wire:model=fecha_inicio
                                style=" height: 30px; " disabled type="date">
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> FECHA FIN:
                            </label>
                            <input class="form-control" id="fecha_fin" wire:model=fecha_fin style=" height: 30px; "
                                disabled type="date">
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_fin"> DÍAS VALIDEZ:
                            </label>
                            <input class="form-control" id="fecha_fin" value="{{ $dias }} DÍAS"
                                style=" height: 30px; " disabled type="text">
                        </div>

                    </div>
                    <br>
                    <!-- <div class="row">
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <label for="medicamentos_liname">MEDICAMENTOS
                                LINAME:</label>
                            <div class="col-sm-5 d-flex align-items-center">
                                <label class="mr-2 ml-5">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="medicamentos_liname" type="checkbox"
                                        class="custom-control-input" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">SI</label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <label for="medicamentos_extraliname">MEDICAMENTOS
                                EXTRALINAME:</label>
                            <div class="col-sm-5 d-flex align-items-center">
                                <label class="mr-2 ml-5">NO</label>
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input wire:model="medicamentos_extraliname" type="checkbox"
                                        class="custom-control-input" id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3"
                                        style="margin-left: 1.5rem; margin-right: 1.5rem;"></label>
                                </div>
                                <label class="ml-2">SI</label>
                            </div>
                        </div>
                    </div> -->
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
                    <br>
                    <div class="row">
                        <!--  para las especialidades seleccionados -->
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="accordion" id="accordionConvenio">
                                <div class="card">
                                    <div class="card-header" id="headingConvenio"
                                        style="background-color:#3E6CA7 ;color:white;">
                                        <button class="btn btn-link btn-block text-left text-white " type="button"
                                            data-toggle="collapse" data-target="#collapseConvenio"
                                            aria-expanded="false" aria-controls="collapseConvenio">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                            </svg>
                                            ESPECIALIDADES SELECCIONADAS
                                        </button>
                                    </div>

                                    <div id="collapseConvenio" class="collapse" aria-labelledby="headingConvenio"
                                        data-parent="#accordionConvenio">

                                        <ul class="list-group list-group-flush">
                                            @if ($this->especialidades != null)
                                                @foreach ($this->especialidades as $especialidad)
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
                        <!--  para los servicios seleccionados -->
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="accordion" id="accordionServicio">
                                <div class="card">
                                    <div class="card-header" id="headingServicio"
                                        style="background-color:#3E6CA7;color:white;">
                                        <button class="btn btn-link btn-block text-left text-white " type="button"
                                            data-toggle="collapse" data-target="#collapseServicio"
                                            aria-expanded="false" aria-controls="collapseServicio">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                            </svg>
                                            ESPECIALIDADES SELECCIONADAS
                                        </button>
                                    </div>

                                    <div id="collapseServicio" class="collapse" aria-labelledby="headingServicio"
                                        data-parent="#accordionServicio">

                                        <ul class="list-group list-group-flush">
                                            @if ($this->servicios != null)
                                                @foreach ($this->servicios as $servicio)
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

                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="otras_limitaciones">OTRAS LIMITACIONES:
                            </label>
                            <!--<textarea class="form-control" id="otras_limitaciones" wire:model=otras_limitaciones style=" " disabled
                                row="2"> </textarea> -->
                            <div id="observacion-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $otras_limitaciones !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">

                            <label for="observacion"> OBSERVACIONES: </label>
                            <!--<textarea class="form-control" id="observacion" wire:model=observacion style=" " disabled row="2"> </textarea> -->
                            <div id="observacion-content"
                                style="border: 1px solid #ccc; padding: 10px; height: 150px; overflow-y: auto; background-color: #f8f9fa; border-radius: 5px;">
                                {!! $observacion !!}
                            </div>

                        </div>


                    </div>
                    <br>

                    <!--   Datos de persona que autoriza -->
                    <div class="row">

                        <div class="col-lg-12" style="text-align:center;">
                            <p style="background-color:#1a5276; color:white;text-align:center;">
                                DATOS DEL TITULAR DEPENDIENTE</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="matriculaAutoriza">MATRICULA:
                            </label>
                            <input class="form-control" id="matriculaAutoriza" wire:model=matriculaAutoriza
                                style=" height: 30px; " disabled>
                        </div>

                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="ciAutoriza">CARNET: </label>
                            <input class="form-control" id="ciAutoriza" wire:model=ciAutoriza style=" height: 30px; "
                                disabled>
                        </div>

                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombreAutoriza">NOMBRE COMPLETO: </label>
                            <input class="form-control" id="nombreAutoriza" wire:model=nombreAutoriza
                                style=" height: 30px; " disabled>
                        </div>

                        {{-- <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                            <label for="complAutoriza">COMPL.: </label>
                            <input class="form-control" id="complAutoriza" style=" height: 30px; " disabled>
                        </div> --}}
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombre_institucion">INSTITUCIÓN: </label>
                            <input class="form-control" id="nombre_institucion" wire:model=nombre_institucion
                                style=" height: 30px; " disabled>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-lg-3">


            <!--   Datos de persona que es personal SSU -->
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#1a5276; color:white;text-align:center;">
                    AUTORIZACIÓN DEL REGISTRO</p>
                <!-- <div class="card-header" style="background-color:#283747; color:white">
                    ADMINISTRATIVO QUE AUTORIZÓ EL REGISTRO </div> -->
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nro_documento_respaldo">NRO. DOCUMENTO RESPALDO:
                            </label>
                            <input class="form-control" id="nro_documento_respaldo"
                                wire:model="nro_documento_respaldo" style=" height: 30px; ;" disabled>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="fecha_solicitud_hr">FECHA SOLICITUD:
                            </label>
                            <input class="form-control" id="fecha_solicitud_hr" wire:model=fecha_solicitud_hr
                                style=" height: 30px; ;" disabled type="date">
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <label for="tipo_convenio">TIPO CONVENIO: </label>
                            <input class="form-control" id="tipo_convenio" wire:model=tipo_convenio
                                style=" height: 30px; ;" disabled>
                        </div>

                    </div>

                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <p> <b style="color:#2874a6;"> AUTORIZADO POR:</b></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="nombrePersonal">NOMBRE COMPLETO: </label>
                            <input class="form-control" id="nombrePersonal" wire:model=nombrePersonal
                                style=" height: 30px; ;" disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="ciPersonal">CARNET: </label>
                            <input class="form-control" id="ciPersonal" wire:model=ciPersonal
                                style=" height: 30px; ;" disabled>
                        </div>
                    </div>
                    <div class="row mt-2">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="cargo_personal">EN CALIDAD DE: </label>
                            <input class="form-control" id="cargo_personal" wire:model=cargo_personal
                                style=" height: 30px; ;" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <!--  // EN CASO DE DE ANULACION -->
            @if ($anuladoPor)
                <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                    <p style="background-color:#1a5276; color:white;text-align:center;">
                        AUTORIZACIÓN DEL REGISTRO</p>
                    <!-- <div class="card-header" style="background-color:#C11F0F; color:white">
                        <p class='h6'><b> DETALLES DE ANULACIÓN</b></p>
                    </div> -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>ANULADO POR: </strong>
                                <spam> {{ $nombreAnular }} </spam>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong> EN FECHA: </strong>
                                <spam> {{ $fecha_anulacion }} </spam>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong>MOTIVO ANULACIÓN:</strong>
                                <spam> {{ $motivo_anulacion }} </spam>

                            </div>

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-ver-dos-modal>
<script>
    window.addEventListener('modalVerConvenioEmer', event => {

        $('#verModalConvenioEmer').modal('show');
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
