<div>
    <div style="position: relative; ">
        <div style="position: absolute; top: -90px; bottom:0px; right: 200px;">
            <x-volver-button wire:click="volverAInicio">INICIO</x-volver-button>
            <x-salir-dos-button wire:click="de7ElegirHorarioA6SelecDoctor">ATRÁS</x-salir-dos-button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-2">
            <div class="card-vista-comun">
                <!-- FOTOGRAFIA -->
                <div style="padding: 30px;">
                    <img src="{{ !empty($fotografia) ? 'data:image/jpg;base64,' . $fotografia : asset('images/default_afiliacion.jpg') }}"
                        class="img-thumbnail" width="100" height="100" alt="FTP.{{ $fotografia }}"
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                class="bi bi-calendar-week-fill" viewBox="0 0 16 16">
                                <path
                                    d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M9.5 7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5m3 0h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5M2 10.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5" />
                            </svg>
                            <strong style="color:white"> {{ \Carbon\Carbon::parse($fechaIngresada)->format('d/m/Y') }}
                            </strong>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                class="bi bi-capsule" viewBox="0 0 16 16">
                                <path
                                    d="M1.828 8.9 8.9 1.827a4 4 0 1 1 5.657 5.657l-7.07 7.071A4 4 0 1 1 1.827 8.9Zm9.128.771 2.893-2.893a3 3 0 1 0-4.243-4.242L6.713 5.429z" />
                            </svg>
                            <strong style="color:white"> {{ $especialidadLiteral }} </strong>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white"
                                class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            <strong style="color:white">{{ $nombreCompletoEspecialista }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card-vista-comun">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <p style="color:#eded3a; font-size:30px; align-items:left"><b>SELECCIONA AL HORARIO:</b>
                            </p>
                        </div>
                        <p style="font-style: italic; font-size: 22px; color: #eded3a; margin-top: 0;text-align: left;">
                            <b> Seleccione el horario de su preferencia presionando el botón
                                SELECCIONAR.</b>
                        </p>

                    </div>
                    <br>
                    <!-- foreach de ESPECIALISTAS  -->
                    <div class="scroll-container-horarios">
                        <div class="row">
                            @foreach ($listaHorarios as $key => $horaDisponible)
                                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">

                                    @if ($horaDisponible['estado'] == 'LIBRE')
                                        <div class="card-horario horario-css">
                                            <div class="card-body-horario">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:50px;  text-align: center;">
                                                            <b> {{ $horaDisponible['hora'] }} </b>
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <x-seleccionar-button
                                                            wire:click="seleccionarHorario('{{ $horaDisponible['hora'] }} ')">
                                                            SELECCIONAR</x-seleccionar-button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($horaDisponible['estado'] == 'SUSPENDIDO')
                                        <div class="card-horario-naranja horario-css">
                                            <div class="card-body-horario-naranja">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:50px;  text-align: center;">
                                                            <b> {{ $horaDisponible['hora'] }} </b>
                                                        </p>
                                                        <p style="font-size:16px; "><b>HORARIO SUSPENDIDO</b></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-horario-red horario-css">
                                            <div class="card-body-horario-red">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:50px;  text-align: center;">
                                                            <b> {{ $horaDisponible['hora'] }} </b>
                                                        </p>
                                                        <p style="font-size:20px; "><b>HORARIO OCUPADO</b></p>
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
    <script>
        document.addEventListener('horaYaPasada', function() {
            Swal.fire({
                title: "<b>Hora seleccionada fuera de rango</b>",
                text: "La hora seleccionada se encuentra fuera del rango",
                icon: "warning"
            });
        });
    </script>
    <style>
        /* --------para el horario  */
        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group svg {
            margin-right: 10px;
            color: #dc7633;
        }

        .scroll-container-horarios {
            max-height: 460px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /*  para la pardte del card  */
        .card-horario {
            margin: 10px auto;
            border: none;
            padding: 5px 5px;
            position: relative;
            width: 85%;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.8s ease;
            display: flex;
            justify-content: center;
            box-shadow: 0 4px #016913;
            background-color: #52be80;

        }

        .card-horario-red {
            margin: 10px auto;
            border: none;
            padding: 5px 5px;
            position: relative;
            width: 85%;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.8s ease;
            display: flex;
            justify-content: center;
            box-shadow: 0 4px #9b1503;
            background-color: #c0392b;

        }

        .card-horario-naranja {
            margin: 10px auto;
            border: none;
            padding: 5px 5px;
            position: relative;
            width: 85%;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.8s ease;
            display: flex;
            justify-content: center;
            box-shadow: 0 4px #C84C09;
            background-color: #F56F27;

        }

        .horario-css:active {
            transform: translateY(5px);
            box-shadow: 0 2px #d0d3d4, inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Opcional: Estilo para el contenido dentro de la tarjeta */
        .card-body-horario {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 5px;
            height: auto;

        }

        /* --------para datos seleccionados agenda */
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
            font-size: 22px;
            text-align: left;
        }
    </style>
</div>
