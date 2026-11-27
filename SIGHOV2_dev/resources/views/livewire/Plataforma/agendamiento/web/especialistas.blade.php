<style>
    .position-relative {
        position: relative;
    }

    .image-container {
        position: relative;
        width: 85px;
        height: 85px;
    }

    .ocupado {
        border-color: red;
        /* Borde rojo si no hay disponibilidad */
    }

    .disponible {
        border-color: green;
        /* Borde verde si hay disponibilidad */
    }

    button.position-relative {
        border-width: 2px;
        /* Ajusta el grosor del borde si es necesario */
        border-style: solid;
        padding: 0;
        /* Asegura que el padding no afecte al tamaño de la imagen */
        background-color: transparent;
        /* Mantener el fondo transparente */
    }


    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: red;
        font-weight: bold;
        font-size: 14px;
        background-color: rgba(255, 255, 255, 0.7);
        /* Fondo blanco semi-transparente */
        padding: 2px 5px;
        border-radius: 3px;
        text-align: center;
        pointer-events: none;
        animation: blink 1s infinite;
        /* Aplica la animación de parpadeo */
    }

    .demanda-btn {
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: bold;
        cursor: pointer;
    }
</style>
<!-- EN ESTA SECCIÓN SE REGISTRAN LOS DATOS -->

<div>
    <div class="row">
        <div div class="col-lg-10 col-md-10 col-sm-9 col-8">
            <div class="card cabecera">
                <b> <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor"
                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd"
                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    3. <em> Elija al especialista:</em> </b>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3 col-4" style="text-align:right;">
            <x-salir-button wire:click="retroceder">ATRÁS</x-salir-button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2" style="font-size:15px;">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill=" #145a32"
                    class="bi bi-circle-fill" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8" />
                </svg>
                <b style="color: #145a32;">Disponible</b>
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="orange"
                    class="bi bi-circle-fill" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8" />
                </svg>

                <b style="color: orange ;"> Suspendido </b>

                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill=" #1577e5 "
                    class="bi bi-circle-fill" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="8" />
                </svg>

                <b style="color: #1577e5 ;"> Total Fichas </b>

            </p>
        </div>


    </div>


    <div class="row" style="font-size:16px;">
        @foreach ($horariosDisponibles as $especialistaDisponible)
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 mt-1">
                <div class="especialista-card-button {{ $especialistaDisponible['nro_fichas_disponibles'] <= 0 ? 'ocupado' : '' }}"
                    wire:click="{{ $especialistaDisponible['nro_fichas_disponibles'] > 0
                        ? ' especialistaSeleccionado(' .
                            $especialistaDisponible['id_especialista_asignacion'] .
                            ', ' .
                            $especialistaDisponible['id_asignacion_horario'] .
                            ')'
                        : '' }}"
                    title="ELEGIR ESPECIALISTA" style="padding: 0;margin-bottom: 0;">

                    <div class="col-auto">
                        <div class="image-container">

                            <!-- <img src="{{ !empty($especialistaDisponible['foto']) ? 'data:image/jpg;base64,' . $especialistaDisponible['foto'] : asset('images/default_afiliacion.jpg') }}"
                                class="img-thumbnail" width="80" height="80" alt="img"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 0%;"> -->

                            @php
                                if (!empty($especialistaDisponible['foto'])) {
                                    $foto = 'data:image/jpg;base64,' . $especialistaDisponible['foto'];
                                } else {
                                    $sexo = strtoupper(trim($especialistaDisponible['sexo'] ?? ''));
                                    $path = public_path($sexo === 'M' ? 'images/doctor.png' : 'images/doctora.png');
                                    $foto = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                                }
                            @endphp

                            <img src="{{ $foto }}" class="img-thumbnail" width="80" height="80"
                                alt="img" style="width: 80px; height: 80px; object-fit: cover; border-radius: 0%;">


                            @if ($especialistaDisponible['nro_fichas_disponibles'] <= 0)
                                <div class="overlay-text">No
                                    disponible
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col p-1" style="font-size:15px;">

                        <span>{{ $especialistaDisponible['grado_academico'] }}<b>
                                {{ $especialistaDisponible['nombres'] }}</b></span>
                        <span>Turno: <b style="font-size:16px;">{{ $especialistaDisponible['turno'] }}</b></span>
                        <span>Horario:<b style="font-size:18px;">
                                {{ \Carbon\Carbon::parse($especialistaDisponible['hora_inicio'])->format('H:i') }}</b>
                            a
                            <b
                                style="font-size:18px;">{{ \Carbon\Carbon::parse($especialistaDisponible['hora_fin'])->format('H:i') }}</b></span>
                        <span>Ubicación: <b style="font-size:16px;">{{ $especialistaDisponible['ubicacion'] }}</b></span>
                        <span style="font-size:17px; text-align:right;">
                            <b style="color: #145a32;">D = {{ $especialistaDisponible['nro_fichas_disponibles'] }}
                            </b>,
                            <b style="color: orange;"> S = {{ $especialistaDisponible['nro_fichas_suspendidas'] }}
                            </b>,
                            <b style="color: #1577e5 ;"> TF = {{ $especialistaDisponible['numero_paciente'] }} </b>



                            @if ($especialistaDisponible['nro_fichas_disponibles'] <= 0)
                                <button
                                    wire:click="demandaInsatisfecha({{ $especialistaDisponible['id_especialidad_especialista'] }}, {{ $especialistaDisponible['id_especialista_asignacion'] }},
                                        {{ $especialistaDisponible['id_asignacion_horario'] }})"
                                    type="button" class="btn btn-warning btn-sm demanda-btn"
                                    style="vertical-align: top; margin-left:20px;">
                                    DEMANDA INSATISFECHA
                                </button>
                            @endif
                        </span>

                    </div>
                </div>

            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="especialistaSeleccionado">
            <x-cargando-dos />
        </div>
    </div>

    <br>


    <div class="row"></div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" wire:loading wire:target="retroceder">
        <x-cargando-dos />
    </div>

</div>


<style>
    /* Estilos para el contenedor que actuará como botón */
    /* Estilos para el contenedor que actuará como botón */
    .especialista-card-button {
        background-color: #ebf5fb;
        /* Color de fondo base que ya tienes */
        border: 1px solid #ccc;
        /* Un borde sutil para que parezca clickeable */
        border-radius: 8px;
        /* Bordes ligeramente redondeados */
        padding: 10px;
        /* Espaciado interno */
        margin-bottom: 15px;
        /* Espacio entre cada "tarjeta" de especialista */
        cursor: pointer;
        /* Cambiar el cursor al pasar por encima */
        text-align: left;
        /* Asegura que el texto se alinee a la izquierda dentro del botón */
        display: flex;
        /* Para mantener la estructura de imagen a la izquierda y texto a la derecha */
        align-items: flex-start;
        /* Alinea los elementos al inicio (arriba) */
        width: 100%;
        /* Asegura que ocupe todo el ancho de su columna */
        border-radius: 8px;

        /* Transición suave para el efecto hover */
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
    }

    /* Estilo al pasar el cursor por encima */
    .especialista-card-button:hover {
        background-color: #d1e7f2;
        /* Un tono más claro para el hover */
        border-color: #007bff;
        /* Un borde más distintivo al hover */
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        /* Sutil sombra para dar profundidad */
    }

    @keyframes parpadeo-borde {
        0% {
            border-color: red;
        }

        50% {
            border-color: transparent;
        }

        100% {
            border-color: red;
        }
    }

    /* Estilos para cuando el especialista está 'ocupado' o no disponible */
    .especialista-card-button.ocupado {
        opacity: 0.7;
        /* Hazlo un poco transparente */
        cursor: not-allowed;
        /* Cambia el cursor para indicar que no es clickeable */
        border: 2.5px solid red;
        animation: parpadeo-borde 1s infinite;
    }

    .especialista-card-button.ocupado:hover {
        background-color: #ebf5fb;
        /* No cambia el fondo al hover si está ocupado */
        border-color: #ccc;
        box-shadow: none;
    }

    /* Ajustes para el texto dentro del botón para que no haya márgenes extra */
    .especialista-card-button span {
        display: block;
        /* Asegura que cada línea de texto ocupe su propia línea */
        margin-bottom: 2px;
        /* Pequeño margen entre líneas, ajusta si es necesario */
    }

    .especialista-card-button span:last-child {
        margin-bottom: 0;
        /* Elimina el margen inferior del último span */
    }

    /* Ajuste para el contenedor de la imagen si es necesario */
    .image-container {
        margin-right: 15px;
        /* Espacio entre la imagen y el texto */
        position: relative;
        /* Necesario para el overlay de "No disponible" */
    }

    .overlay-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(255, 0, 0, 0.7);
        /* Fondo rojo semitransparente */
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
        text-align: center;
        white-space: nowrap;
        opacity: 0;
        /* Por defecto invisible */
        transition: opacity 0.3s ease;
    }

    /* Solo muestra el overlay si el especialista está 'ocupado' */
    .especialista-card-button.ocupado .overlay-text {
        opacity: 1;
        /* Hace visible el overlay */
    }

    .especialista-card-button.ocupado .btn-warning {
        opacity: 10 !important;
        cursor: pointer !important;
    }
</style>
</div>
