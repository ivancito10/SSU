<div>
    <div
        style="display: flex; justify-content: space-between; align-items: center; background-color:#34495e;padding:5px;border-radius:5px; box-shadow: 0 4px 8px rgba(0, 2, 2, 0.3);">
        <div style="display: flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                class="bi bi-person-fill" viewBox="0 0 16 16" style="color:white">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
            <h6 style="font-family: Verdana, sans-serif; color: white; margin: 0; margin-left: 20px;">REGISTRAR
                ANULACIÓN</h6>
        </div>
        <div style="margin-left: auto; padding-right:10px; position: relative;">
            <x-atras-button wire:click="volver" wire:loading.attr="disabled" wire:target="volver">
                ATRÁS
            </x-atras-button>
            <div wire:loading wire:target="volver">
                <x-cargando-dos />
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-comun p-2">
                <div class="cabecera text-center">
                    <b>ASEGURADO</b>
                </div>
                <div style="height: 0.5rem;"></div>

                <div class="row">
                    <div class="col-lg-12 col-md-5 col-sm-5 col-xs-12">
                        <div class=row>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                <!-- Paso 4. Mostramos la foto en base 64 -->
                                <img src="{{ !empty($foto) ? 'data:image/jpg;base64,' . $foto : asset('images/default_afiliacion.jpg') }}"
                                    class="img-thumbnail" width="150" height="150" alt="FTP.{{ $foto }}">
                            </div>
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
                                @foreach ($nombreInsPatronal as $key => $institucion)
                                    <strong style="font-size:90%">INSTITUCIÓN {{ $key + 1 }}:</strong>

                                    <span class=" font-weight-bold h5"
                                        style="color:#a50601">{{ $institucion['nombre_institucion'] }}
                                    </span>
                                    <br>
                                @endforeach

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <strong style="font-size:90%">TIPO DE ASEGURADO:</strong>
                                <span class=" font-weight-bold h5" style="color:#030e7a">{{ $tipoAsegurado }}
                                </span>
                            </div>
                        </div>
                        <hr class="p-0 m-0">
                    </div>
                    <div class="col-lg-12 col-md-7 col-sm-7 col-xs-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <strong> NOMBRE COMPLETO:</strong>
                                <p class="p-1 p-comun-disabled">
                                    {{ $nombre }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 col-3">
                                <strong> CARNET:</strong>
                                <p class="p-1 p-comun-disabled">
                                    {{ $carnet }}</p>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 col-4">
                                <strong>SEXO:</strong>
                                <p class="p-1 p-comun-disabled">
                                    {{ $sexo }} </p>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-5">
                                <strong>
                                    FECHA DE NACIMIENTO: </strong>
                                <p class="p-1 p-comun-disabled">
                                    {{ $fecha_nacimiento }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card card-comun p-2">
                <div class="row">
                    <div class="col-lg-7 col-md-8 col-sm-8 col-xs-12">
                        <div class="cabecera text-center">
                            <b>DETALLE DE AGENDA</b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-3 col-sm-4 col-6">
                        <strong>FECHA DE AGENDA:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $fechaAgenda }}</p>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-4 col-6">
                        <strong>HORA DE AGENDA:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $horaAgenda }}</p>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-4 col-7">
                        <strong>SERVICIO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $servicioPlataforma }}</p>
                    </div>
                    <div class="col-lg-6 col-md-3 col-sm-4 col-5">
                        <strong>NRO CONSULTORIO:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $nroConsultorio }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-4 col-5">
                        <strong>PISO :</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $piso }}</p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-4 col-7">
                        <strong>UBICACIÓN:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $ubicacion }}</p>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                        <strong>ESPECIALISTA:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $especialista }}</p>
                    </div>
                    <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                        <strong>ESPECIALIDAD:</strong>
                        <p class="p-1 p-comun-disabled">
                            {{ $especialidad }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12"></div>
                    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12 text-right">
                        <x-eliminar-button onclick="anularAgenda()"> <strong class="h4"><b>ANULAR
                                    AGENDA</b></strong>
                        </x-eliminar-button>
                    </div>
                    <div id="cargando-anulacion" style="display: none;">
                        <x-cargando-dos />
                    </div>
                </div>
            </div>

            @can('super-admin-only')
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                        <div class="p-2 card card-borde mb-2 h-100">
                            <h6 class="mb-3 text-blue font-weight-bold border-bottom pb-1">
                                <i class="fas fa-check-circle mr-1"></i> REGISTRADO POR
                            </h6>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Nombre de usuario:</strong>
                                    {{ $usuarioCreador }}
                                </li>
                                <li>
                                    <strong>Fecha de creación:</strong>
                                    {{ $createdAtFecha }}
                                </li>
                                <li>
                                    <strong> Hora creación:</strong>
                                    {{ $createdAtHora }}
                                </li>
                                <li>
                                    <strong>IP de origen:</strong>
                                    {{ $ip }}
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            @endcan
        </div>
    </div>
    <br>

</div>

<script>
    async function anularAgenda() {
        const {
            value: motivoAnulacion
        } = await Swal.fire({
            title: "Seleccione el motivo de anulación de la ficha",
            text: "Para hacer efectiva la anulación, debe seleccionar uno de los motivos enlistados:",
            icon: 'warning',
            input: "select",
            inputOptions: {
                "ATRASO DEL PACIENTE": "ATRASO DEL PACIENTE",
                "REPROGRAMACIÓN DE CITA": "REPROGRAMACIÓN DE CITA",
                "A SOLICITUD DE PLATAFORMA": "A SOLICITUD DE PLATAFORMA",
                "ERROR DE ESPECIALISTA": "ERROR DE ESPECIALISTA",
                "ERROR DE HORARIO": "ERROR DE HORARIO",                
                "PARO ADMINISTRATIVO": "PARO ADMINISTRATIVO",
                "A SOLICITUD DEL PACIENTE": "A SOLICITUD DEL PACIENTE",
                "A SOLICITUD DEL MEDICO": "A SOLICITUD DEL MÉDICO"
            },
            inputPlaceholder: "Seleccione el motivo",
            showCancelButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, anular",
            cancelButtonText: "No, cancelar",
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value) {
                        resolve();
                    } else {
                        resolve("Debe seleccionar un motivo");
                    }
                });
            }

        });
        if (motivoAnulacion) {
            Livewire.dispatch('anulacionConfirmada', [motivoAnulacion]);
            document.getElementById('cargando-anulacion').style.display = 'block';
        }
    }

    document.addEventListener('anulacionExitosa', function() {
        Swal.fire({
            title: "<b>Anulación exitosa</b>",
            text: "Se anuló correctamente la ficha",
            icon: 'success',
            showConfirmButton: false
        });
    });

    document.addEventListener('errorAnulacion', function() {
        Swal.fire({
            title: "<b>Error en la anulación</b>",
            text: "Verifique e intente nuevamente",
            icon: 'success',
            showConfirmButton: false
        });
    });
</script>
