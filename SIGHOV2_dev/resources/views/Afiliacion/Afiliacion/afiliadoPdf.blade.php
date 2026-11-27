<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title> Afiliado Historial Pdf</title>


    <style>
        body {

            margin: 0px 0px;
            padding: 0px;
            background-color: #f4f4f4;
            color: #333;
            background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;

        }

        .logo-right {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            right: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 10px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */

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
        <img src="logo/ssu.png" class="logo-left" width="80" height="80" alt="Logo" style="margin: 0;">
        <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">
            <h4 style=" margin: 0;">HISTORIAL </h4>
            <h4 style=" margin: 0;">DE </h4>
            <h4 style=" margin: 0;">AFILIACIONES </h4>
        </div>
        <img src="data:image/png;base64,{{ $qrCode }}" class="logo-right" width="85" height="85" alt="Logo" style="margin: 0;">
    </div>
    <hr>
    <div class="document-content" style="text-align: left; margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse; margin: 0;">
            <tbody>
                <tr>
                    <!-- Primera celda: Contiene otra tabla para información detallada -->
                    <td style="width: 60%; padding: 0; vertical-align: top;">
                        <table style="width: 100%; margin: 0; border-collapse: collapse;">
                            <tr>
                                <td style="width: 30%; text-align: right; font-size: 80%; padding: 2px;"><b>NOMBRE: </b>
                                </td>
                                <td style="width: 70%; text-align: left; font-size: 90%; padding: 2px;">
                                    {{ $nombre_completo }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; text-align: right; font-size: 80%; padding: 2px;"><b>SEXO: </b>
                                </td>
                                <td style="width: 70%; text-align: left; font-size: 90%; padding: 2px;">
                                    {{ $sexo_textual }}</td>
                            </tr>
                            <tr>
                                <td style="width: 30%; font-size: 70%; text-align: right;padding: 2px;"><b>FECHA
                                        NACIMIENTO: </b></td>
                                <td style="width: 70%; text-align: left; font-size: 90%; padding: 2px;">
                                    {{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->Format('d /m/ Y') }}
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- Segunda celda: Información adicional -->

                    <td style="width: 40%; padding: 0; vertical-align: top;">
                        <table style="width: 100%; margin: 0; border-collapse: collapse;">
                            <tr>
                                <td style="width: 30%; text-align: right; font-size: 80%; padding: 2px;"><b>CARNET: </b>
                                </td>
                                <td style="width: 70%; text-align: left; font-size: 90%; padding: 2px;">
                                    {{ $persona->ci }} {{ $persona->complemento }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%; text-align: right; font-size: 80%; padding: 2px;"><b>MATRICULA:
                                    </b></td>
                                <td style="width: 70%; text-align: left; font-size: 90%; padding: 2px;">
                                    {{ $persona->matricula_seguro }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


    <hr>
    <div>
        @if ($varEstudiante)
            <div class="lg-12">
                <p style="color: #1b5989 "><b> HISTORIAL COMO ESTUDIANTE</b></p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="font-size: 90%; border: none;text-align:center">Nro
                            </th>
                            <th style="font-size: 90%; border: none;text-align:center">Tipo
                                asegurado</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                solicitud</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                de afiliación</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                de baja</th>
                            <th style="font-size: 90%; border: none;text-align:center">
                                Carrera</th>
                        </tr>

                    </thead>
                    <tbody style="text-align: center;">
                        @foreach ($fue_estudiante as $dato)
                            <tr>
                                <td style="font-size: 80%;">{{ $count = $count + 1 }}
                                </td>
                                <td style="font-size: 80%;">ESTUDIANTE</td>
                                <td style="font-size: 80%;">
                                    {{ \Carbon\Carbon::parse($dato->fecha_solicitud_afiliacion)->Format('d /m/ Y') ?? 'S/D' }}
                                </td>
                                <td style="font-size: 80%;">
                                    {{ \Carbon\Carbon::parse($dato->fecha_afiliacion)->Format('d /m/ Y') ?? 'S/D' }}
                                </td>

                                <td style="font-size: 80%;">
                                    @if ($dato['fecha_baja'])
                                        {{ \Carbon\Carbon::parse($dato['fecha_baja'])->format('d/m/Y') }}
                                    @else
                                        S/R
                                    @endif
                                </td>
                                <td style="font-size: 80%;">
                                    {{ $dato->carreraEstudiante->nombre ?? 'S/D' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div>
        @if ($varBeneficiario)
            <div class="lg-12">
                <p style="color: #1b5989 "><b> HISTORIAL COMO BENEFICIARIO</b></p>
                <table class="table table-striped" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="font-size: 90%; border: none;text-align:center">Nro
                            </th>
                            <th style="font-size: 90%; border: none;text-align:center">Tipo
                                asegurado</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                afiliacion</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                baja </th>
                            <th style="font-size: 90%; border: none;text-align:center">
                                Traspaso de aportes</th>
                            <th style="font-size: 90%; border: none;text-align:center">
                                Institucion</th>
                        </tr>
                    </thead>
                    <tbody style="text-align:center;">
                        @foreach ($fue_beneficiario as $beneficio)
                            <tr>
                                <td style="font-size: 80%;">{{ $count = $count + 1 }}
                                </td>
                                <td style="font-size: 80%;">
                                    {{ $beneficio['tipo_asegurado'] }}</td>
                                <td style="font-size: 80%;">
                                    {{ \Carbon\Carbon::parse($beneficio['fecha_afiliacion'])->format('d/m/Y') }}
                                </td>
                                <td style="font-size: 80%;">
                                    @if ($beneficio['fecha_baja'])
                                        {{ \Carbon\Carbon::parse($beneficio['fecha_baja'])->format('d/m/Y') }}
                                    @else
                                        S/R
                                    @endif
                                </td>
                                <td style="font-size: 80%;">
                                    {{ $beneficio['remuneracion'] }}</td>
                                <td style="font-size: 80%;">
                                    {{ $beneficio['institucion'] }}</td>
                            </tr>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <div>

        @if ($varTitular)
            <div class="lg-12">
                <p style="color: #1b5989 "><b> HISTORIAL COMO TITULAR</b></p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="font-size: 90%; border: none;text-align:center">Nro
                            </th>
                            <th style="font-size: 90%; border: none;text-align:center">Tipo
                                de Titular</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                de Afiliación</th>
                            <th style="font-size: 90%; border: none;text-align:center">Fecha
                                de Baja</th>
                            <th style="font-size: 90%; border: none;text-align:center">
                                Institución</th>
                            <th style="font-size: 90%; border: none;text-align:center">
                                Contrato Voluntario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fue_titular as $titular)
                            <tr>
                                <td style="font-size: 80%;">{{ $count = $count + 1 }}
                                </td>
                                <td style="font-size: 80%;">
                                    {{ $titular['tipo_titular'] }} </td>
                                <td style="font-size: 80%;">
                                    {{ \Carbon\Carbon::parse($titular['fecha_afiliacion'])->format('d/m/Y') }}
                                </td>
                                <td style="font-size: 80%;">
                                    @if ($titular['fecha_baja'])
                                        {{ \Carbon\Carbon::parse($titular['fecha_baja'])->format('d/m/Y') }}
                                    @else
                                        S/R
                                    @endif
                                </td>
                                <td style="font-size: 80%;">
                                    @if ($titular['tipo_titular'] === 'TITULAR INSTITUCIONAL')
                                        <div>
                                            {{ $titular['institucion'] }}
                                            <br>
                                            {{ $titular['sigla'] }}
                                        </div>
                                    @else
                                        <div>
                                            <p>No aplica</p>
                                        </div>
                                    @endif
                                </td>

                                <td style="font-size: 80%;">

                                    @if ($titular['tipo_titular'] === 'TITULAR VOLUNTARIO')
                                        <div>
                                            <b>Inicio:</b>
                                            {{ $titular['fecha_inicio'] }}
                                            <br>
                                            <b>Fin:</b>
                                            {{ $titular['fecha_fin'] }}
                                        </div>
                                    @else
                                        <div>
                                            <p>No aplica</p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="footer">
        <table width="100%">
            <tr>
                <td style="font-size: 9px; vertical-align: top;">
                    <b>Fecha: </b>
                    {{ \Carbon\Carbon::parse($fecha_registro)->Format('d /m/ Y') }}<br>
                    <b>Usuario: </b>{{ ucwords(mb_strtolower($nombre_usuario)) }}<br>
                </td>
                <td align="left" style="font-size: 9px; vertical-align: top;">
                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630 , La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en
                            este documento.'
                        </b></em>
                </td>
                <td align="right">
                    <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="Logo"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
