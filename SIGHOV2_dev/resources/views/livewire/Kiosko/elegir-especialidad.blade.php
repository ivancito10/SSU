<div>
    <div style="position: relative; ">
        <div style="position: absolute; top: -90px; bottom:0px; right: 200px;">
            <x-volver-button wire:click="volverAInicio">INICIO</x-volver-button>
            <x-salir-dos-button wire:click="de5SelecEspecialidadA4SelectFecha">ATRÁS</x-salir-dos-button>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card-vista-comun">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <p style="color:#eded3a; font-size:30px; align-items:left"><b>SELECCIONA LA ESPECIALIDAD</b>
                            </p>
                        </div>
                        <p style="font-style: italic; font-size: 22px; color: #eded3a; margin-top: 0;text-align: left;">
                            <b> Seleccione la especialidad de su preferencia presionando el botón SELECCIONAR.</b>
                        </p>

                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 "></div>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 ">
                            <input class="form-control" id="especialidadBusqueda"
                                style="border-radius:20px;color:black;"
                                placeholder="Ingrese la especialidad para realizar su busqueda"
                                wire:model="especialidadBusqueda"  wire:input="buscarEspecialidades">
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 ">
                            <x-buscar-button wire:click="buscarEspecialidades" style="padding:5px"></x-buscar-button>
                        </div>
                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12 "></div>
                    </div>
                    <br>
                    <!-- foreach de especilaidades  -->
                    <div class="scroll-container">
                        @foreach ($especialidadesDisponibles as $key => $especialidadDisponible)
                            <div class="boton boton-css rounded">
                                {{ $especialidadDisponible['especialidad'] }}
                                <x-seleccionar-button
                                    wire:click="especialidadSeleccionada('{{ $especialidadDisponible['id_especialidad'] }}')">
                                    SELECCIONAR</x-seleccionar-button>
                            </div>
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>

    <style>
        .scroll-container {
            max-height: 410px;
            overflow-y: auto;
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

        .input-fecha {
            background: #e2e4e6;
            border-color: #e2e4e6;
            font-size: 30px;
            font-weight: 700;
        }

        .boton {
            margin: 5px auto;
            border: none;
            padding: 10px 44px;
            font-size: 30px;
            position: relative;
            width: 60%;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.5s ease;
            display: inline-block;
            background: rgba(208, 211, 212, 0.4);
            color: #585858;
            box-shadow: 0 4px #d0d3d4;
            display: flex;
            justify-content: space-between;
            align-items: center;

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
        }

        .input-group svg {
            margin-right: 10px;
            color: #2471a3;
        }
    </style>
</div>
