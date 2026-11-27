<div>
    <div style="position: relative; ">
        <div style="position: absolute; top: -90px; bottom:0px; right: 200px;">
            <x-volver-button wire:click="volverAInicio">INICIO</x-volver-button>
            <x-salir-dos-button wire:click="de6SelecDoctorA5SelectEspecilidad">ATRÁS</x-salir-dos-button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-2">
            <div class="card-vista-comun">
                <!-- FOTOGRAFIA -->
                <div style="padding: 30px;">
                    <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="80" height="70" alt="FTP.{{ $fotografia }}"
                        style="width:200px; height:200px; object-fit:cover; object-position: top;">
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                        style="display:flex; justify-content: center; font-size: 25px">
                        @if ($estado_vigencia == 'VIGENTE')
                            <label for="estado_vigencia"
                                style="background-color:#196f3d; padding:5px; border-radius:10px">
                                <span style="color: white;"><b>VIGENTE</b></span>
                            </label>
                        @elseif ($estado_vigencia == 'CESANTE')
                            <label for="estado_vigencia"
                                style="background-color:#ffbc06; padding:5px; border-radius:10px">
                                <span style="color: white;"><b>CESANTE</b></span>
                            </label>
                        @else($estado_vigencia == 'NO VIGENTE')
                            <label for="estado_vigencia"
                                style="background-color:#d92a1a; padding:5px; border-radius:10px">
                                <span style="color: white;"><b>NO VIGENTE</b></span>
                            </label>
                        @endif
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="p-1 input-texto">
                            {{ $p_apellido }} {{ $s_apellido }} {{ $nombres }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong class="titulos-datos">C.I.:</strong>
                        <p class="p-1 input-texto">
                            {{ $carnet }} </p>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <strong class="titulos-datos">MATRICULA:</strong>
                        <p class="p-1 input-texto">
                            {{ $matricula_seguro }} </p>
                    </div>
                </div>
                <hr class="p-0 m-0" style="color:white">
                <strong class="datos-seleccionados"> DATOS DE AGENDA:</strong>
                <div style="padding: 2px;">
                    <div class="card-datos">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                <path
                                    d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                            </svg>
                            <strong style="color:white">
                                {{ \Carbon\Carbon::parse($fechaIngresada)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                class="bi bi-capsule" viewBox="0 0 16 16">
                                <path
                                    d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                            </svg>
                            <strong style="color:white">{{ $especialidadLiteral }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card-vista-comun">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <p style="color:#eded3a; font-size:30px;text-align: left;"><b>SELECCIONA AL ESPECIALISTA</b>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <p
                                style="font-style: italic; font-size: 22px; color: #eded3a; margin-top: 0;text-align: left;">
                                <b> Seleccione al especialista de su preferencia presionando el botón
                                    SELECCIONAR.</b>
                            </p>
                        </div>
                        <div class="col-lg-6 mt-2" style="font-size:15px;">
                            <p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill=" #1ad008"
                                    class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                                <b style="color: #1ad008;">Fichas Disponibles (FD)</b>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="orange"
                                    class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                                <b style="color: orange ;"> Fichas Suspendidas (FS)</b>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill=" #3498db "
                                    class="bi bi-circle-fill" viewBox="0 0 16 16">
                                    <circle cx="8" cy="8" r="8" />
                                </svg>
                                <b style="color: #3498db ;"> Total Fichas (TF) </b>
                            </p>
                        </div>
                    </div>

                    <br>
                    <!-- foreach de ESPECIALISTAS  -->
                    <div class="scroll-container">
                        <div class="row">
                            @foreach ($horariosDisponibles as $key => $horarioDisponible)
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    @if ($horarioDisponible['nro_fichas_disponibles'] === 0)
                                        <div class="card-especialista card-css-undisponible">
                                            <div class="card-body-especialista">
                                                <div class="row">
                                                    <div class="lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="img-section">
                                                                    <!--CAMBIAR POR FOTO DE ESPECIALISTA-->
                                                                    @php
                                                                        if (!empty($horarioDisponible['foto'])) {
                                                                            $foto =
                                                                                'data:image/jpg;base64,' .
                                                                                $horarioDisponible['foto'];
                                                                        } else {
                                                                            $sexo = strtoupper(
                                                                                trim($horarioDisponible['sexo'] ?? ''),
                                                                            );
                                                                            $path = public_path(
                                                                                $sexo === 'M'
                                                                                    ? 'images/doctor.png'
                                                                                    : 'images/doctora.png',
                                                                            );
                                                                            $foto =
                                                                                'data:image/png;base64,' .
                                                                                base64_encode(file_get_contents($path));
                                                                        }
                                                                    @endphp

                                                                    <!-- <img src="{{ !empty($horarioDisponible['foto']) ? 'data:image/jpg;base64,' . $horarioDisponible['foto'] : asset('images/default_afiliacion.jpg') }}"
                                                            class="img-thumbnail" width="100" height="100"
                                                            alt="FTP.{{ $horarioDisponible['nombres'] }}"
                                                            style="border-radius: 10px;  width: 90%; height: auto;"> -->

                                                                    <img src="{{ $foto }}"
                                                                        class="img-thumbnail" width="100"
                                                                        height="100" alt="img"
                                                                        style="width: 90%; height: 100px; object-fit: cover; border-radius: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="info-especialista">
                                                                    <div class="row">
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <p
                                                                                style="font-size:90%; text-align:center;">
                                                                                <strong>
                                                                                    @if ($sexo == 'F')
                                                                                        Dra.
                                                                                    @else
                                                                                        Dr.
                                                                                    @endif
                                                                                    {{ $horarioDisponible['nombres'] }}
                                                                                </strong>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="lg-12">
                                                        <div class="info-especialista">
                                                            <div class="row" style="text-align:left;">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <p style="font-size:80%"><b>HORARIOS:</b>
                                                                        {{ $horarioDisponible['hora_inicio'] }} -
                                                                        {{ $horarioDisponible['hora_fin'] }}
                                                                        <b>{{ $horarioDisponible['turno'] }}</b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="text-align:left;">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <p style="font-size:80%; "><b>CONSULTORIO:</b>
                                                                        CONSULTORIO
                                                                        {{ $horarioDisponible['numero_consultorio'] }}

                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="text-align:left;">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <p style="font-size:80%; "><b>UBICACIÓN:</b>
                                                                        {{ $horarioDisponible['piso'] }},
                                                                        <b>{{ $horarioDisponible['ubicacion'] }}</b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div
                                                                    style="display: flex; align-items: left; gap: 5px;">

                                                                    <p
                                                                        style="display: flex; align-items: center; color: #1ad008; margin: 0;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="#1ad008" class="bi bi-circle-fill"
                                                                            viewBox="0 0 16 16"
                                                                            style="margin-right: 4px;">
                                                                            <circle cx="8" cy="8"
                                                                                r="8" />
                                                                        </svg>
                                                                        <b>FD =
                                                                            {{ $horarioDisponible['nro_fichas_disponibles'] }}</b>
                                                                    </p>

                                                                    <p
                                                                        style="display: flex; align-items: center; color: orange; margin: 0;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="orange" class="bi bi-circle-fill"
                                                                            viewBox="0 0 16 16"
                                                                            style="margin-right: 4px;">
                                                                            <circle cx="8" cy="8"
                                                                                r="8" />
                                                                        </svg>
                                                                        <b>
                                                                            FS =
                                                                            {{ $horarioDisponible['nro_fichas_suspendidas'] }}</b>
                                                                    </p>

                                                                    <p
                                                                        style="display: flex; align-items: center; color: #3498db; margin: 0;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="#3498db" class="bi bi-circle-fill"
                                                                            viewBox="0 0 16 16"
                                                                            style="margin-right: 4px;">
                                                                            <circle cx="8" cy="8"
                                                                                r="8" />
                                                                        </svg>
                                                                        <b>TF =
                                                                            {{ $horarioDisponible['numero_paciente'] }}</b>
                                                                    </p>
                                                                </div>
                                                                @if ($horarioDisponible['nro_fichas_disponibles'] === 0)
                                                                    <div class="col-lg-12">
                                                                        <div style="height: 0.6rem;"></div>
                                                                        <p class="parpadeo-cupo"> ¡SIN FICHAS
                                                                            DISPONIBLES! </p>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @else
                                        <div class="card-especialista card-css">
                                            <div class="card-body-especialista">
                                                <div class="row">
                                                    <div class="lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="img-section">
                                                                    <!--CAMBIAR POR FOTO DE ESPECIALISTA-->
                                                                    @php
                                                                        if (!empty($horarioDisponible['foto'])) {
                                                                            $foto =
                                                                                'data:image/jpg;base64,' .
                                                                                $horarioDisponible['foto'];
                                                                        } else {
                                                                            $sexo = strtoupper(
                                                                                trim($horarioDisponible['sexo'] ?? ''),
                                                                            );
                                                                            $path = public_path(
                                                                                $sexo === 'M'
                                                                                    ? 'images/doctor.png'
                                                                                    : 'images/doctora.png',
                                                                            );
                                                                            $foto =
                                                                                'data:image/png;base64,' .
                                                                                base64_encode(file_get_contents($path));
                                                                        }
                                                                    @endphp

                                                                    <!-- <img src="{{ !empty($horarioDisponible['foto']) ? 'data:image/jpg;base64,' . $horarioDisponible['foto'] : asset('images/default_afiliacion.jpg') }}"
                                                            class="img-thumbnail" width="100" height="100"
                                                            alt="FTP.{{ $horarioDisponible['nombres'] }}"
                                                            style="border-radius: 10px;  width: 90%; height: auto;"> -->

                                                                    <img src="{{ $foto }}"
                                                                        class="img-thumbnail" width="100"
                                                                        height="100" alt="img"
                                                                        style="width: 90%; height: 100px; object-fit: cover; border-radius: 0%;">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="info-especialista">
                                                                    <div class="row">
                                                                        <div
                                                                            class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <p
                                                                                style="font-size:90%; text-align:center;">
                                                                                <strong>
                                                                                    @if ($sexo == 'M')
                                                                                        Dr.
                                                                                    @else
                                                                                        Dra.
                                                                                    @endif
                                                                                    {{ $horarioDisponible['nombres'] }}
                                                                                </strong>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="lg-12">
                                                        <div class="info-especialista">
                                                            <div class="row" style="text-align:left;">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <p style="font-size:80%"><b>HORARIOS:</b>
                                                                        {{ $horarioDisponible['hora_inicio'] }} -
                                                                        {{ $horarioDisponible['hora_fin'] }}
                                                                        <b>{{ $horarioDisponible['turno'] }}</b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="text-align:left;">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <p style="font-size:80%; "><b>CONSULTORIO:</b>
                                                                        CONSULTORIO
                                                                        {{ $horarioDisponible['numero_consultorio'] }}

                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row" style="text-align:left;">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <p style="font-size:80%; "><b>UBICACIÓN:</b>
                                                                        {{ $horarioDisponible['piso'] }},
                                                                        <b>{{ $horarioDisponible['ubicacion'] }}</b>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div
                                                                    style="display: flex; align-items: left; gap: 5px;">

                                                                    <p
                                                                        style="display: flex; align-items: center; color: #1ad008; margin: 0;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="#1ad008" class="bi bi-circle-fill"
                                                                            viewBox="0 0 16 16"
                                                                            style="margin-right: 4px;">
                                                                            <circle cx="8" cy="8"
                                                                                r="8" />
                                                                        </svg>
                                                                        <b>FD =
                                                                            {{ $horarioDisponible['nro_fichas_disponibles'] }}</b>
                                                                    </p>

                                                                    <p
                                                                        style="display: flex; align-items: center; color: orange; margin: 0;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="orange" class="bi bi-circle-fill"
                                                                            viewBox="0 0 16 16"
                                                                            style="margin-right: 4px;">
                                                                            <circle cx="8" cy="8"
                                                                                r="8" />
                                                                        </svg>
                                                                        <b>
                                                                            FS =
                                                                            {{ $horarioDisponible['nro_fichas_suspendidas'] }}</b>
                                                                    </p>

                                                                    <p
                                                                        style="display: flex; align-items: center; color: #3498db; margin: 0;">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="10" height="10"
                                                                            fill="#3498db" class="bi bi-circle-fill"
                                                                            viewBox="0 0 16 16"
                                                                            style="margin-right: 4px;">
                                                                            <circle cx="8" cy="8"
                                                                                r="8" />
                                                                        </svg>
                                                                        <b>TF =
                                                                            {{ $horarioDisponible['numero_paciente'] }}</b>
                                                                    </p>
                                                                </div>
                                                                @if ($horarioDisponible['nro_fichas_disponibles'] === 0)
                                                                    <div
                                                                        class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                                                        <p class="parpadeo-cupo"> SIN CUPO! </p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <x-seleccionar-button
                                                                        wire:click="especialistaSeleccionado('{{ $horarioDisponible['id_asignacion_horario'] }}','{{ $horarioDisponible['id_especialista_especialista'] }}')">
                                                                        SELECCIONAR
                                                                    </x-seleccionar-button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>

    <style>
        @keyframes parpadeo-cupo {
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

        .parpadeo-cupo {
            animation: parpadeo-cupo 1s infinite;
            color: red;
            font-size: 30px;
            font-weight: 700;
        }

        /* --------para el esecilistas  */
        .card-especialista {
            margin: 8px 5px;
            border: none;
            padding: 10px 20px;
            font-size: 20px;
            position: relative;
            width: 100%;
            border-radius: 10px;
            /*  cursor: pointer; */
            transition: all 0.8s ease;
            display: inline-block;
            background: #d0d3d4;
            color: #585858;
            box-shadow: 0 4px #d0d3d4;
            opacity: 0;
            animation: slideInRight 0.8s ease forwards;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translatex(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .card-especialista:hover {
            transform: scale(1.02);
            transition: transform 0.4s ease;
        }

        .card-css {
            background: rgb(224, 224, 224);
            color: #424242;
            box-shadow: 0 0 0 2px rgb(150, 150, 150), 0 0.35em 0 0 rgb(168, 168, 168);

        }

        .card-css-undisponible {
            background: rgb(224, 224, 224);
            color: #424242;
            box-shadow: 0 0 0 2px rgb(236, 41, 41), 0 0.35em 0 0 rgb(202, 38, 38);

        }

        .card-css:active {
            transform: translateY(5px);
            box-shadow: 0 2px #d0d3d4, inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-body-especialista {
            display: flex;
        }

        .card-img-especialista {
            margin-right: 0px;
            height: auto;
        }



        .info-especialista p {
            margin: 0;
            font-size: 90%;
        }

        .img-section {
            text-align: left;
        }

        .img-section img {
            display: block;
            margin: 0 auto;
        }

        .img-section p {
            margin-top: 1px;
        }

        .scroll-container {
            max-height: 490px;
            overflow-x: auto;
            padding-right: 2px;
            overflow-x: hidden;
        }

        .card-datos {
            background-color: hsla(216, 2.30%, 42.90%, 0.50);
            padding: 10px;
            border-radius: 10px;
            border: 1px solid rgb(55, 56, 56);
            text-align: left;
            font-size: 18px;
        }

        .datos-seleccionados {
            /*color: #eded3a;*/
            color: white;
            font-size: 20px;
            text-align: left;
        }
    </style>
</div>
