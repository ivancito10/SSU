<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGENDA ESPECIALISTA</title>

    <style>
        header {
            margin: 20px 50px 0 50px;
            /* Márgenes específicos para el encabezado */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
        }

        html,
        body {
            margin: 0;
            padding-top: 110px;
            padding-bottom: 80px;
            /* margin-bottom: 80px; */
            padding-left: 50px;
            padding-right: 50px;
            font-family: Arial, sans-serif;
            background: url("{{ asset('Images/SSU2019.png') }}") no-repeat center center fixed;
            background-size: cover;
        }


        /* Pie de página */
        footer {
            margin: 0;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8f9fa;
            text-align: center;
            font-size: 14px;
            width: 100%;
            height: 75px;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>

</head>

<body>
    <header>
        <table style="width: 100%; border-collapse: collapse">
            <tr>
                <!-- Columna Izquierda -->
                <td style="text-align: center; vertical-align: middle;">
                    <img src="logo/ssu.png" width="80" height="80" alt="Logo">
                </td>

                <!-- Columna Central -->
                <td style="text-align: center;">
                    <h3 style="margin: 0; padding: 0;">
                        <ins style="color: #17A2B8;">SEGURO SOCIAL UNIVERSITARIO</ins>
                        <ins style="color: #DC3545;">LA PAZ</ins>
                    </h3>
                    <h4 style="margin: 0; padding: 0;">UNIDAD DE PLATAFORMA</h4>
                    <h4 style="margin: 0; padding: 0;">LOTE DE IMPRESIÓN DE EXPEDIENTES CLÍNICOS</h4>
                </td>

                <!-- Columna Derecha -->
                <td style="text-align: center; vertical-align: middle;">
                    {{-- <img src="logo/logo2.png" width="95" height="60" alt="Logo"> --}}
                    {{-- <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="100"
                        height="100" /> --}}
                </td>
            </tr>
        </table>
    </header>
    {{-- <header>
        <table style="width: 100%; border-collapse: collapse">
            <tr>
                <!-- Columna Izquierda -->
                <td style="text-align: center; vertical-align: middle;">
                    <img src="logo/ssu.png" width="80" height="80" alt="Logo">
                </td>

                <!-- Columna Central -->
                <td style="text-align: center;">
                    <h3 style="margin: 0; padding: 0;"><ins>SEGURO SOCIAL UNIVERSITARIO - LA PAZ</ins></h3>
                    <h4 style="margin: 0; padding: 0;">PLATAFORMA</h4>
                    <h4 style="margin: 0; padding: 0;">LOTE DE IMPRESIÓN DE EXPEDIENTES CLÍNICOS</h4>
                </td>

                <!-- Columna Derecha -->{{                     -- <img src="data:image/png;base64, $qrCode " alt="Código QR" width="100"
                        height="100" /> }}
                <td style="text-align: center; vertical-align: middle;">
                    <img src="logo/logo2.png" width="95" height="60" alt="Logo">
                </td>
            </tr>{{  }}
        </table>
    </header> --}}

    <table>
        <tr>
            <td style="padding-right: 60px;">
                <b>Especialista:</b>
            </td>
            <td>
                {{ $nombres }}
            </td>
        </tr>
        <tr>
            <td style="padding-right: 60px;">
                <b>Especilidad:</b>
            </td>
            <td>
                {{ $especialidad }}
            </td>
        </tr>
        <tr>
            <td style="padding-right: 60px;">
                <b>Turno:</b>
            </td>
            <td>
                {{ $turno_registrado }} [{{ $hinicio }} - {{ $hfin }}]
            </td>
        </tr>
        <tr>
            <td style="padding-right: 60px;">
                <b>Fecha:</b>
            </td>
            <td>
                {{ \Carbon\Carbon::parse($fecha_hoy)->format('d/m/Y') }}
            </td>
        </tr>
    </table>
    <br>


    <table style="border: 1px solid black; width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead style="background-color: #E0E0E0; color: black;">
            <tr>
                <th style="border: 1px solid black;">N°</th>
                <th style="border: 1px solid black;">HORA</th>
                <th style="border: 1px solid black;">MATRICULA</th>
                <th style="border: 1px solid black;">PACIENTE</th>
            </tr>
        </thead>
        <tbody>
            @if ($consulta == [])
                <tr>
                    <td colspan="8" style="border: 1px solid black; text-align:center;">Sin resultados</td>
                </tr>
            @else
                @foreach ($consulta as $index => $dato)
                    <tr>
                        <td style="border: 1px solid black;"> {{ $index + 1 }} </td>
                        <td style="border: 1px solid black;"> {{ $dato->hora_agenda ?? 'N/A' }}</td>
                        @if ($dato->personaAgenda == null)
                            <td style="border: 1px solid black;">Suspensión</td>
                            <td style="border: 1px solid black;">Suspensión</td>
                        @else
                            <td style="border: 1px solid black;">{{ $dato->personaAgenda->matricula_seguro }} </td>
                            <td style="border: 1px solid black;">
                            @if ($dato->personaAgenda->p_apellido == null)
                                {{ $dato->personaAgenda->s_apellido }} {{ $dato->personaAgenda->nombres }}
                            @elseif($dato->personaAgenda->s_apellido == null)
                                {{ $dato->personaAgenda->p_apellido }} {{ $dato->personaAgenda->nombres }}
                            @else
                                {{ $dato->personaAgenda->p_apellido }} {{ $dato->personaAgenda->s_apellido }}
                                {{ $dato->personaAgenda->nombres }}
                            @endif
                        </td>
                        @endif
                        
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <footer>
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>{{ $fecha_registro }} <br>
                    <b>Usuario: </b> {{ $nombre_usuario }}<br>
                </td>

                <td class="text-left" style="font-size: 9px; vertical-align: top;">

                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630, La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em>
                </td>
                <td>
                    <img src="images/logo_fondo_blanco.png" width="110" height="70" alt="Logo">
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
