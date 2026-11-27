<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agenda Especialista - Básico</title>
    <style>
        body {
            position: relative;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px dashed black;
            padding: 2px;

        }

        .no-border td {
            border: none;
            padding: 2px;
        }

        hr {
            border: none;
            border-top: 1px dashed black;
            margin: 5px 0;
        }

        footer {

            margin-top: 10px;
            text-align: left;
        }

        .fondo-logo {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 0;
            width: 250px;
            height: auto;
        }
    </style>

</head>

<body style="font-size: 35px;">
    <img src="images/logo_fondo_blanco.png" alt="Logo" class="fondo-logo">
    <table class="no-border">
        <tr>
            <td><b>Especialista:</b></td>
            <td>{{ $datos_especialista->personaEspecialista->p_apellido }}
                {{ $datos_especialista->personaEspecialista->s_apellido }}
                {{ $datos_especialista->personaEspecialista->nombres }}</td>
        </tr>
        <tr>
            <td><b>Especialidad:</b></td>
            <td>{{ $datos_especialista->especialidadEspecialista->especialidad }}</td>
        </tr>
        <tr>
            <td><b>Consultorio:</b></td>
            <td>
                {{ $datos_especialista->asignacionHorarioEspecialista[0]->consultorio->numero_consultorio }} -
                {{ $datos_especialista->asignacionHorarioEspecialista[0]->consultorio->consultorioSedes->piso }}</td>
        </tr>
        <tr>
            <td><b>Fecha:</b></td>
            <td>{{ $fecha_boucher }}</td>
        </tr>
        <tr>
            <td><b>Usu. Imp:</b></td>
            <td>{{ $usuario }}</td>
        </tr>
        <tr>
            <td><b>Impreso.:</b></td>
            <td>{{ Carbon\Carbon::now()->format('d-m-Y H:i:s') }}</td>
        </tr>
    </table>

    <hr>

    <table>

        <tr>
            <th>HORA</th>
            <th>PACIENTE</th>
            <th>MAT.</th>
            <th>TIP.</th>
        </tr>
        <tbody>
            @foreach ($slots as $slot)
                <tr>
                    <td align="center"><b>{{ $slot['hora'] }}</b></td>

                    @if ($slot['estado'] === 'suspension')
                        <td colspan="3" align="center">
                            <b>Suspensión</b> {{ $slot['suspension'] }}
                        </td>
                    @elseif ($slot['estado'] === 'ocupado')
                        <td>{{ $slot['paciente'] }}</td>
                        <td align="center"><b>{{ $slot['matricula'] }}</b></td>
                        <td align="center"><b>{{ $slot['tipo_codigo'] }}</b></td>
                    @else
                        <td colspan="3" align="center"></td>
                    @endif
                </tr>
            @endforeach

        </tbody>
    </table>

    <hr>

    <footer style="font-size: 25px;">
        <b>Derechos Reservados {{ Carbon\Carbon::now()->format('Y') }} - Sistema SIGHO V2 </b>
    </footer>
</body>

</html>
