<div>
    <div style="position: relative; ">
        <div style="position: absolute; top: -90px; bottom:0px; right: 200px;">
            <x-volver-button wire:click="volverAInicio">INICIO</x-volver-button>
            <x-salir-dos-button wire:click="de4SelectFechaA3Vigencia">ATRÁS</x-salir-dos-button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-2">
            <div class="card-vista-comun ">
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
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card-vista-comun ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <p style="color:#eded3a; font-size:30px; align-items:left"><b>SELECCIONA LA FECHA</b></p>
                        </div>
                    </div>
                    <p style="font-style: italic; font-size: 22px; color: #eded3a; margin-top: 0;text-align: left;">
                        <b> Seleccione la fecha para agendar su cita.</b>
                    </p>

                    @foreach ($fechasElegibles as $fechaElegible)
                        <div class="row">
                            @if (($fechaElegible['feriado'] && $fechaElegible['nro_dia'] != 0) || $fechaElegible['nro_dia'] == 0)
                                <div class="boton-red boton-css-red rounded ">
                                    <div class="input-group-red">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                            fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                        <span>
                                            {{ \Carbon\Carbon::parse($fechaElegible['fecha'])->format('d/m/Y') }}
                                        </span>
                                        @if ($fechaElegible['nro_dia'] === 0)
                                            <p><b>DOMINGO</b> </p>
                                        @elseif ($fechaElegible['feriado'])
                                            <p><b>FERIADO</b> </p>
                                        @endif

                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                                            <path
                                                d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                                        </svg>

                                    </div>
                                </div>
                            @else
                                <div class="boton boton-css rounded">
                                    <div class="input-group">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                            fill="currentColor" class="bi bi-calendar2-check-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5m9.954 3H2.545c-.3 0-.545.224-.545.5v1c0 .276.244.5.545.5h10.91c.3 0 .545-.224.545-.5v-1c0-.276-.244-.5-.546-.5m-2.6 5.854a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                                        </svg>
                                        <span>
                                            <b>
                                                {{ \Carbon\Carbon::parse($fechaElegible['fecha'])->format('d/m/Y') }}</b>
                                        </span>
                                        <div>
                                            <x-seleccionar-button
                                                wire:click="fechaSeleccionadaAgenda('{{ $fechaElegible['fecha'] }}')">
                                                SELECCIONAR
                                            </x-seleccionar-button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>

    <style>
        .input-fecha {
            background: #e2e4e6;
            border-color: #e2e4e6;
            font-size: 30px;
            font-weight: 700;
        }

        .boton {
            margin: 20px auto;
            border: none;
            padding: 10px 44px;
            font-size: 30px;
            position: relative;
            width: 50%;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.5s ease;
            display: inline-block;
            background: rgba(208, 211, 212, 0.4);
            color: #585858;
            box-shadow: 0 4px #d0d3d4;
        }

        .boton-css {
            background: #e5e7e9;
            color: #585858;
            box-shadow: 0 0 0 2px #d0d3d4, 0 0.35em 0 0 rgb(166, 168, 168);

        }

        .boton-css:active {
            transform: translateY(5px);
            box-shadow: 0 2px #424242, inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .input-group svg {
            margin-right: 10px;
            color: #2471a3;
        }

        .fecha-feriado {
            color: rgb(196, 16, 16);
            font-weight: bold;
            display: inline-flex;
            align-items: center;
        }

        .boton-red {
            margin: 20px auto;
            border: none;
            padding: 10px 44px;
            font-size: 30px;
            position: relative;
            width: 50%;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.5s ease;
            display: inline-block;

            color: white;
        }

        .boton-css-red {
            background: #EB4A42;
            color: white;
            box-shadow: 0 0 0 2px #EB4A42, 0 0.35em 0 0 rgb(192, 43, 32);

        }

        .input-group-red {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .input-group-red p {
            color: white;
            font-size: 18px;
        }

        .input-group-red svg {
            margin-right: 10px;
            color: white;
        }
    </style>
</div>
