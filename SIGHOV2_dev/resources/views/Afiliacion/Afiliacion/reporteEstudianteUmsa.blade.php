<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title> Reporte de estudiante</title>

    <style>
        body {
            margin: 10px 10px;
            padding: 10px;
            color: #333;
        }

        .logo-right {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 15px;
            width: 100px;
            right: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 15px;
            width: 70px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .texto {
            font-size: 8px;
        }

        .tabla-cabecera {
            background-color: #922b21;
            color: white;
            padding: 2px;
            margin: 2px;
            text-align: center;
            font-size: 11px;
            border-radius: 8px;
        }

        .footer {
            background-color: #f8f9fa;
            padding-top: 3px;
            text-align: center;
            font-size: 14px;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70px;
            /* Ajusta el alto */
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0;">
        <img src="{{ public_path('logo/ssu.png') }}" class="logo-left" alt="Logo">
        <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">
            <h2 style="margin: 0; padding: 0; font-size: 20px; color: #2c3e50;">Reporte de Afiliación</h2>
            <h4 style="margin: 0; font-weight: normal; color: #7f8c8d;">Reporte mensual UMSA</h4>
            <h5 style="margin: 0; font-weight: normal; color: #2c3e50;">{{ $mesNombre }} - {{ $año }}</h5>
        </div>
        <img src="{{ public_path('logo/logo2.png') }}" class="logo-right" alt="Logo">
    </div>
    <br>
    <!-- RESULTADOS -->
    <table style="width: 100%; border-spacing: 3px; border-collapse: separate;" border="1">
        <tr>
            <th class="tabla-cabecera">
                NRO
            </th>
            <th class="tabla-cabecera">
                CI
            </th>
            <th class="tabla-cabecera">
                DATOS
                PERSONALES </th>

            <th class="tabla-cabecera">
                FACULTAD
            </th>
            <th class="tabla-cabecera">
                CARRERA
            </th>
        </tr>
        @if ($estudiantes_registrados)
            @foreach ($estudiantes_registrados as $reportes)
                <tr>
                    <td class="texto" style="text-align:center">{{ $count = $count + 1 }}</td>
                    <td class="texto"style="text-align:left">
                        {{ $reportes->personaEstudiante->ci . '' . $reportes->personaEstudiante->complemento }}</td>
                    <td class="texto" style="text-align:left"> {{ $reportes->personaEstudiante->p_apellido }}
                        {{ $reportes->personaEstudiante->s_apellido }} {{ $reportes->personaEstudiante->nombres }}</td>
                    <td class="texto"style="text-align:left">
                        {{ $reportes->carreraEstudiante->facultadCarrera->nombre ?? 'SIN ACTUALIZAR' }}</td>
                    <td class="texto" style="text-align:left">
                        {{ $reportes->carreraEstudiante->nombre ?? 'SIN ACTUALIZAR' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4"
                    style="text-align: center; padding: 1rem; border: 2px dashed #ccc; border-radius: 12px; background-color: #f9fafb; font-family: Arial, sans-serif;">
                    <p style="margin: 0; font-size: 1.2rem; color: #6b7280;">
                        No hay reportes por esas fechas.
                    </p>
                </td>
            </tr>
        @endif
    </table>
</body>

</html>

{{--  EXCEL 
<style>
    .cabecera {
        background-color: #922b21;
        color: white;
        text-align: center;
        font-size: 12px;
    }

    .celda {
        font-size: 11px;
        padding: 4px;
    }
</style>

<h3 style="text-align: center;">REPORTE DE ESTUDIANTES - {{ $mesNombre }} {{ $año }}</h3>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th class="cabecera">NRO</th>
            <th class="cabecera">MATRÍCULA</th>
            <th class="cabecera">CI</th>
            <th class="cabecera">NOMBRE COMPLETO</th>
            <th class="cabecera">R.U.</th>
            <th class="cabecera">FACULTAD</th>
            <th class="cabecera">CARRERA</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 0; @endphp
        @foreach ($estudiantes_registrados as $estudiante)
            <tr>
                <td class="celda">{{ ++$count }}</td>
                <td class="celda">{{ $estudiante->personaEstudiante->matricula_seguro }}</td>
                <td class="celda">
                    {{ $estudiante->personaEstudiante->ci . $estudiante->personaEstudiante->complemento }}</td>
                <td class="celda">
                    {{ $estudiante->personaEstudiante->nombres }}
                    {{ $estudiante->personaEstudiante->p_apellido }}
                    {{ $estudiante->personaEstudiante->s_apellido }}
                </td>
                <td class="celda">{{ $estudiante->registro_universitario }}</td>
                <td class="celda">{{ $estudiante->carreraEstudiante->facultadCarrera->nombre ?? 'SIN ACTUALIZAR' }}
                </td>
                <td class="celda">{{ $estudiante->carreraEstudiante->nombre ?? 'SIN ACTUALIZAR' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
--}}
