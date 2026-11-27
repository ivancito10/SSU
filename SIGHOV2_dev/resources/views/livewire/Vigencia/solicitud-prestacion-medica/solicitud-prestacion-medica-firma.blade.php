<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div
                style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
                <div style="display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg>
                    <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">
                        VERIFICACIÓN Y VALIDACIÓN DE LA SOLICITUD</h6>
                </div>
                <x-atras-button wire:click="atras" style="margin-left: auto; padding-right:10px">ATRÁS</x-atras-button>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> ASEGURADO </b></p>
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
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <!-- <div class="card-header" style="background-color:#3E6CA7; color:white">
                    <p class='h6'><b>SOLICITUD DE PRESTACIÓN MEDICA</b></p>
                </div> -->
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> ASEGURADO </b></p>
                <div class="card-body">

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
                            <input class="form-control" id="fecha_solicitud_hr" type="date" disabled
                                wire:model=fecha_solicitud_hr style=" height: 30px; ;">
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
                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
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

                        <div class='col-lg-6 col-md-12 col-sm-12 col-xs-12'>
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
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <spam><b>MEDICAMENTOS LINAME:</b>
                                @if ($medicamentos_liname == true)
                                    <b style="color:#1e8449 ;"> SI</b>
                                @else
                                    <b style="color:#cb4335;"> NO</b>
                                @endif
                            </spam>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
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
        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <div class="card p-2" style="border-radius:10px; box-shadow: 0 4px 8px rgba(0, 2, 0, 0.3);">
                <p style="background-color:#c4dff3 ;text-align:center;"> <b> ASEGURADO </b></p>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label for="formFile" class="form-label">SELECCIONAR ARCHIVO: </label>
                            <p style="color:#1a5276 ;"> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-pencil-square"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg><em><b> Suba el archivo en formato PDF.</b>
                                </em>
                                <spam style="color:#cb4335;"><b>*</b></spam>
                            </p>
                            @if ($errors->has('error_message'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('error_message') }}
                                </div>
                            @endif
                            <input wire:model="documentoFirmado" class="form-control" type="file" id="formFile"
                                name="archivo" required accept=".pdf">

                        </div>
                    </div>
                    <div class="row">



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target.except="render">
                            <x-cargando-dos />
                        </div>


                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <x-subir-archivo-button wire:click="verificarFirma">VALIDAR
                                DOCUMENTO</x-subir-archivo-button>
                        </div>
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12"></div>

                    </div>

                    @if (is_null($switchValidacion))
                    @else
                        <div class="row mt-4">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="formFile" class="form-label">RESULTADO DE LA VALIDACIÓN: </label>
                            </div>
                        </div>
                        @if ($switchValidacion)
                            <div class="row mt-2">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                                    <div class="alert alert-success"> SE VERIFICÓ LA FIRMA DIGITAL</div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @foreach ($datosValidacion as $datoValidado)
                                        <div class="row">
                                            <label for="nombreSignatario"> FIRMADO POR:</label>
                                            <input class="form-control" id="nombreSignatario" disabled
                                                value="{{ $datoValidado['certificado']['nombreSignatario'] }}"
                                                style="background-color:#f2f4f4; border-color:white; border-radius:10px; text-align:center">
                                        </div>
                                        <div class="row">
                                            <label for="fechaFirmado"> EN FECHA:</label>
                                            <input class="form-control" id="fechaFirmado" disabled
                                                value="{{ \Carbon\Carbon::parse($datoValidado['fechaFirma'])->format('d/m/Y H:i:s') }}"
                                                style="background-color:#f2f4f4; border-color:white; border-radius:10px; text-align:center">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <x-save-button onclick="guardarFirmado()">GUARDAR DOCUMENTO Y VALIDAR
                                        SOLICITUD</x-save-button>
                                </div>
                            </div>
                        @else
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" role="alert">
                                    <div class="alert alert-danger">EL DOCUMENTO NO ESTA FIRMADO DIGITALMENTE</div>
                                </div>
                            </div>
                        @endif
                    @endif




                </div>
            </div>
        </div>
    </div>





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

    <script>
        document.addEventListener('sinDocumento', function() {

            Swal.fire({
                title: "<b>SIN ARCHIVO SELECCIONADO</b>",
                text: "Debe subir la solicitud firmada digitalmente",
                icon: "error"

            });
        });

        document.addEventListener('firmado', function() {

            Swal.fire({
                title: "<b>FIRMA DIGITAL VERIFICADA</b>",
                text: "Se verificó la firma digital del archivo",
                icon: "success"

            });
        });

        document.addEventListener('noFirmado', function() {

            Swal.fire({
                title: "<b>EL ARCHIVO NO FUE FIRMADO DIGITALMENTE</b>",
                text: "Debe seleccionar el archivo correspondiente a la solicitud firmado digitalmente",
                icon: "warning"

            });
        });

        document.addEventListener('guardado', function() {

            Swal.fire({
                title: "<b>SOLICITUD VALIDADA CORRECTAMENTE</b>",
                text: "Se almacenó el archivo firmado digitalmente",
                icon: "success",
                showConfirmButton: false
            });
        });

        document.addEventListener('noGuardado', function() {

            Swal.fire({
                title: "<b>ERROR AL VALIDAR LA SOLICITUD</b>",
                text: "Verifique e intente nuevamente",
                icon: "error",
            });
        });

        function guardarFirmado() {
            Swal.fire({
                title: '¿Desea guardar la firma digital?',
                text: "Se guaradará el archivo ingresados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'No, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarPdfFirmado');
                } else {
                    Swal.fire({
                        title: "Registro cancelado",
                        text: "Se canceló el registro",
                        icon: "error"
                    });
                }
            });
        }
    </script>

</div>
