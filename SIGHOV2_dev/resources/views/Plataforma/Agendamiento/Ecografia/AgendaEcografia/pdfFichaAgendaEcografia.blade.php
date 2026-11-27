<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha de Atención Médica - Ecografía</title>
    <style>
        /* 1. Unificamos a letter y controlamos los márgenes globales aquí */
        @page {
            size: letter portrait;
            margin: 0; 
        }

        html, body {
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
            font-family: "Times New Roman", Times, serif;
            width: 100%;
        }

        /* 2. Blindamos el contenedor para evitar saltos de página */
        .container {
            page-break-inside: avoid;
            break-inside: avoid;
            border: 2px solid #000;
            border-radius: 10px;
            line-height: 1.1;
            padding: 20px;
            max-width: 650px;
            margin: 15px auto;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 25px; /* Espacio óptimo entre la cabecera y los datos */
        }

        /* Eliminamos el posicionamiento absoluto confuso para Dompdf */
        .fondo-logo {
            display: inline-block;
            margin-top: -100px;
            width: 420px; /* Ajusta este valor si lo quieres aún más grande o pequeño */
            height: auto;  /* Mantiene la proporción original para que no se deforme */
            margin-bottom: -100px; /* Espacio directo entre el logo y la fecha */
        }

        .header .date {
            font-size: 30px;
            font-style: italic;
            font-weight: bold;
        }

        .info {
            margin-bottom: 6px;
        }

        .info strong {
            display: block;
            text-align: center;
            font-size: 34px;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        .info span {
            display: block;
            text-align: left;
            font-size: 35px;
            margin: 0;
            padding: 0;
            line-height: 1;
            letter-spacing: 0.1px;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 31px;
            border-top: 1px solid #000;
            padding-top: 8px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo_fondo_blanco.png'))) }}" class="fondo-logo" alt="Logo">
            
            <div class="date">Fecha de emisión: {{ $fechaActual }}</div>
        </div>

        <div class="info">
            <span>Médico:</span>
            <strong>{{ $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->persona->p_apellido . ' ' . $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->persona->s_apellido . ' ' . $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->persona->nombres }}</strong>
        </div>
        
        <div class="info">
            <span>Afiliado:</span>
            <strong>{{ $datosPersona['p_apellido'] . ' ' . $datosPersona['s_apellido'] . ' ' . $datosPersona['nombres'] }}</strong>
        </div>
        
        <div class="info">
            <span>Especialidad:</span>
            <strong>{{ $datosAgenda->asignacionHorarioAgenda->especialistaAsignacionHorario->especialidadEspecialista->especialidad }}</strong>
        </div>
        
        <div class="info">
            <span>Matrícula:</span>
            <strong>{{ $datosPersona['matricula_seguro'] }}</strong>
        </div>
        
        <div class="info">
            <span>Fecha:</span>
            <strong>{{ \Carbon\Carbon::parse($datosAgenda->fecha_agenda)->format('d/m/Y') }}</strong>
        </div>
        
        <div class="info">
            <span>Hora:</span>
            <strong>{{ \Carbon\Carbon::parse($datosAgenda->hora_agenda)->format('H:i') }}</strong>
        </div>

        <div class="info">
            <span>Consultorio:</span>
            <strong>{{ $datosAgenda->asignacionHorarioAgenda->consultorioAsignacionHorario->numero_consultorio }} - {{ $datosAgenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->piso }}</strong>
        </div>

        <div class="info">
            <span>Ubicación:</span>
            <strong>{{ $datosAgenda->asignacionHorarioAgenda->consultorioAsignacionHorario->consultorioSedes->ubicacion }}</strong>
        </div>

        <div class="footer">
            <p>© {{ \Carbon\Carbon::now()->format('Y') }} SIGHO V2 - Seguro Social Universitario LA PAZ</p>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function() {
            if (window.print) {
                window.print();
            }
        };
    </script>
</body>
</html>