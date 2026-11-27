<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title> Ficha Afiliado</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        @page {
            margin-bottom: 60px;
        }

        .document-title {
            text-align: center;
            margin-bottom: 10px;
            margin-top: 0;
            font-family: 'Times New Roman', Times, serif
        }

        .document-content {
            /*  line-height: 1, 1; */

        }

        .logo-right {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 2px;
            right: 10px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }

        .logo-left {
            position: absolute;
            /* Posiciona el logo absolutamente respecto al contenedor */
            top: 2px;
            left: 20px;
            /* Ajusta este valor para mover el logo más a la derecha o a la izquierda */
        }



        table,
        tr,
        td {
            page-break-inside: avoid;
        }

        * {
            box-sizing: border-box;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            font-size: 10px;
            background-color: white;
        }


        .footer-spacer {
            height: 100px;
        }
    </style>
</head>

<body>
    <div class="footer">
        <table width="100%" style="margin-top:10px; font-size:10px;">
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
                    {{-- - <em><b>
                            'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                        </b></em> --}}
                </td>
                <td align="right">
                    <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="Logo"
                        style="opacity: 0.7;">
                </td>
            </tr>
        </table>
    </div>

    <div>
        <div style="page-break-inside: avoid; margin-bottom:10px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin: 0; ">
                <img src="logo/ssu.png" class="logo-left" width="75" height="75" alt="Logo"
                    style="margin: 0;">
                <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">

                    <h6 style=" margin: 0; color:#0f396f">SEGURO SOCIAL UNIVERSITARIO</h6>
                    <h6 style=" margin: 0; color:#0f396f">LA PAZ - BOLIVIA </h6>
                    <h5 style=" margin: 0; color:#772005">FICHA DE AFILIACIÓN </h5>
                </div>
                <img src="data:image/png;base64,{{ $qrCode }}" class="logo-right" width="70" height="70"
                    alt="Logo" style="margin: 0;">
            </div>
            <br>
            <div style="width:20%; float:left;text-align:center;">
                <br>
                <div style="border-radius:8px;">
                    <img src="data:image/jpg;base64,{{ $foto }}" width="150" height="150">
                </div>
            </div>

            <div style="width:78%; float:left; padding-left: 10px;">
                <table style="width: 100%; margin: 5px 0; padding: 0; border-spacing: 0;">
                    <tbody>
                        <tr style="text-align:center;   color: #333; font-size:90%">
                            <td style="width: 33%; font-weight: bold;">
                                {{ $titular->titularPersona->nombres }}
                            </td>
                            <td style="width: 2%;"> </td>
                            <td style="width: 33%; font-weight: bold;">
                                {{ $titular->titularPersona->p_apellido }}</td>
                            <td style="width: 2%;"> </td>
                            <td style="width: 33%; font-weight: bold;">
                                {{ $titular->titularPersona->s_apellido }}</td>
                        </tr>
                        <tr style="text-align:center; font-size:12px;  color: #555;">
                            <td style="width: 33%; border-top: 1px solid #ccc;">Nombres</td>
                            <td style="width: 2%;"> </td>
                            <td style="width: 33%; border-top: 1px solid #ccc;">Primer Apellido</td>
                            <td style="width: 2%;"> </td>
                            <td style="width: 33%; border-top: 1px solid #ccc;">Segundo Apellido</td>
                        </tr>
                    </tbody>
                </table>
                <table style="width: 100%; margin: 5px 0; padding: 0;">
                    <tbody>
                        <tr style="font-size:13px;  color: #333;">
                            <td style="width: 60%;">
                                <strong>Matricula:</strong>
                                {{ $titular->titularPersona->matricula_seguro }}<br>
                            </td>
                            <td style="width: 40%;">
                                <strong>Carnet:</strong> {{ $titular->titularPersona->ci }}
                                {{ $titular->titularPersona->departamentoPersona->sigla ?? '' }}<br>
                            </td>
                        </tr>
                        <tr style="font-size:13px;  color: #333;">
                            <td style="width: 60%;"><strong>Institución:</strong>
                                {{ $titularInstitucional->titularInstitucionConInstitucion->nombre ?? 'APORTANTE VOLUNTARIO' }}
                                {{ $titularInstitucional->titularInstitucionConInstitucion->codigosInstitucion->codigo ?? ' ' }}{{ $titularInstitucional->titularInstitucionConInstitucion->codigosInstitucion->tipo_titular ?? '' }}
                            </td>
                            <td style="width: 40%;">
                                <strong>Fecha Afiliación:</strong>
                                {{ $fechaAfiliacion }}
                            </td>
                        </tr>
                        <tr style="font-size:13px;  color: #333;">
                            <td style="width: 60%;">
                                <strong>Fecha de Nacimiento:</strong>
                                {{ mb_strtoupper(\Carbon\Carbon::parse($titular->titularPersona->fecha_nacimiento)->Format('d/m/Y ')) }}
                            </td>
                            <td style="width: 40%;">
                                <strong>Telefono:</strong> {{ $titular->telefono }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width: 100%; margin: 5px 0; padding: 0;">
                    <tbody>
                        <tr style="font-size:13px;  color: #094893;">
                            <td><b>Datos Referenciales:</b></td>
                        </tr>
                        <tr style="font-size:13px;  color: #333;">
                            <td style="width:40%; padding-right: 15px;">
                                <strong>Grupo Sanguíneo:</strong>
                                {{ $titular->titularPersona->grupo_sanguineo }}
                            </td>
                            <td style="width:60%; padding-left: 15px;">
                                <strong>Alergias: </strong>
                                @if (!empty($titular->titularPersona->alergias))
                                    {{ $titular->titularPersona->alergias }}
                                @else
                                    NINGUNO
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Dirección -->
                <table style="width: 100%; margin: 5px 0; padding: 0;">
                    <tbody>
                        <tr style="font-size:13px; color: #333;">
                            <td><strong>Dirección:</strong>
                                {{ $titular->titularResidencia->direccion }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Nro Vivienda y Zona -->
                <table style="width: 100%; margin: 5px 0; padding: 0;">
                    <tbody>
                        <tr style="font-size:13px; color: #333;">
                            <td style="width:40%; padding-right: 15px;">
                                <strong>Nro Vivienda:</strong>
                                {{ $titular->titularResidencia->nro_vivienda }}
                            </td>
                            <td style="width:60%; padding-left: 15px;">
                                <strong>Zona:</strong>
                                {{ $titular->titularResidencia->zonaResidencia->nombre }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="clear:both;"></div>
        </div>

        @if ($grupoFamiliarDatos == [])
            <div style="height: 50vh;">
                <h2
                    style=" text-align: center;
                    color: #c0392b; 
                    font-family: Arial, sans-serif;
                    font-size: 18px;
                    background-color: #f5c6cb;
                    padding: 20px;
                    border-radius: 5px;">
                    EL AFILIADO NO CUENTA CON BENEFICIARIOS
                </h2>
            </div>
            <br>
            <br>
            <br> <br><br> <br><br><br>
            <table style=" width: 100%; background-color:white;font-size:60%; margin-top:200px;">
                <tr>
                    <td style=" width: 30%; border-top: 1px solid black;">FIRMA TITULAR</td>
                    <td style=" width: 5%;"> </td>
                    <td style=" width: 30%; border-top: 1px solid black;">FIRMA ENCARGADO AFILIACIÓN</td>
                    <td style=" width: 5%;"> </td>
                    <td style=" width: 30%; border-top: 1px solid black;">FIRMA Vo.Bo. JEFE DE SEGUROS
                        S.S.U.</td>
                </tr>

            </table>
        @else
            <p style="text-align:center;color: #1b5989"><b>BENEFICIARIOS A CARGO DEL TITULAR</b></p>
            @foreach ($grupoFamiliarDatos as $key => $datosFamiliar)
                @if ($loop->last)
                    <div style="page-break-inside: avoid;">
                        <p style="color:#792500 ">
                            <b>{{ $datosFamiliar->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado }}</b>
                        </p>
                        <table style="width:100%; table-layout: fixed; border-collapse: collapse; font-size:70%;">
                            <tbody>
                                <tr>
                                    <!-- Celda de la foto -->
                                    <td width="20%"
                                        style="vertical-align: top; border-right:1px solid black; padding:5px;">
                                        <img src="{{ !empty($datosFamiliar->foto_nombre) ? 'data:image/jpg;base64,' . $datosFamiliar->foto_nombre : public_path('images/default_afiliacion.jpg') }}"
                                            width="90" height="90" alt="Foto del usuario"
                                            style="border: 1px solid black;">
                                    </td>
                                    <!-- Celda de los datos -->
                                    <td width="80%" style="vertical-align: top; padding:5px;">
                                        <!-- Fila única para el NOMBRE -->
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td colspan="2">
                                                    <b>NOMBRE:</b> {{ $datosFamiliar->beneficiarioPersona->nombres }}
                                                    {{ $datosFamiliar->beneficiarioPersona->p_apellido }}
                                                    {{ $datosFamiliar->beneficiarioPersona->s_apellido }}

                                                </td>

                                            </tr>
                                            <tr>
                                                <!-- Primera columna -->
                                                <td
                                                    style="width: 50%; vertical-align: top; text-align: left;  padding-right: 10px;">
                                                    <b>CARNET:</b> {{ $datosFamiliar->beneficiarioPersona->ci }}
                                                    {{ $datosFamiliar->beneficiarioPersona->complemento }}<br>
                                                    <b>MATRÍCULA:</b>
                                                    {{ $datosFamiliar->beneficiarioPersona->matricula_seguro }}
                                                </td>
                                                <!-- Segunda columna -->
                                                <td
                                                    style="width: 50%; vertical-align: top; text-align: left;  padding-left: 10px;">
                                                    <b>GRUPO SANGUÍNEO:</b>
                                                    {{ $datosFamiliar->beneficiarioPersona->grupo_sanguineo }}<br>
                                                    <b>FECHA DE NACIMIENTO:</b>
                                                    {{ \Carbon\Carbon::parse($datosFamiliar->beneficiarioPersona->fecha_nacimiento)->Format('d /m/ Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="width: 50%; vertical-align: top; text-align: left;  padding-right: 10px;">
                                                    <b>FECHA AFILIACIÓN:</b>
                                                    @if ($datosFamiliar->fecha_reafiliacion)
                                                        {{ \Carbon\Carbon::parse($datosFamiliar->fecha_reafiliacion ?? 'Sin dato')->Format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($datosFamiliar->fecha_afiliacion ?? 'Sin dato')->Format('d/m/Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <table style=" width: 100%; background-color:white;font-size:60%; margin-top:70px;">
                            <tr>
                                <td style=" width: 30%; border-top: 1px solid black;">FIRMA TITULAR</td>
                                <td style=" width: 5%;"> </td>
                                <td style=" width: 30%; border-top: 1px solid black;">FIRMA ENCARGADO AFILIACIÓN</td>
                                <td style=" width: 5%;"> </td>
                                <td style=" width: 30%; border-top: 1px solid black;">FIRMA Vo.Bo. JEFE DE SEGUROS
                                    S.S.U.</td>
                            </tr>

                        </table>
                    </div>
                @else
                    <div style="page-break-inside: avoid;">
                        <p style="color:#792500 ">
                            <b>{{ $datosFamiliar->beneficiarioPersona->tipoAseguradoPersona->tipo_asegurado }}</b>
                        </p>
                        <table style="width:100%; table-layout: fixed; border-collapse: collapse; font-size:70%;">
                            <tbody>
                                <tr>
                                    <!-- Celda de la foto -->
                                    <td width="20%"
                                        style="vertical-align: top; border-right:1px solid black; padding:5px;">
                                        <img src="{{ !empty($datosFamiliar->foto_nombre) ? 'data:image/jpg;base64,' . $datosFamiliar->foto_nombre : public_path('images/default_afiliacion.jpg') }}"
                                            width="90" height="90" alt="Foto del usuario"
                                            style="border: 1px solid black;">
                                    </td>
                                    <!-- Celda de los datos -->
                                    <td width="80%" style="vertical-align: top; padding:5px;">
                                        <!-- Fila única para el NOMBRE -->
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td colspan="2">
                                                    <b>NOMBRE:</b> {{ $datosFamiliar->beneficiarioPersona->nombres }}
                                                    {{ $datosFamiliar->beneficiarioPersona->p_apellido }}
                                                    {{ $datosFamiliar->beneficiarioPersona->s_apellido }}

                                                </td>

                                            </tr>
                                            <tr>
                                                <!-- Primera columna -->
                                                <td
                                                    style="width: 50%; vertical-align: top; text-align: left;  padding-right: 10px;">
                                                    <b>CARNET:</b> {{ $datosFamiliar->beneficiarioPersona->ci }}
                                                    {{ $datosFamiliar->beneficiarioPersona->complemento }}<br>
                                                    <b>MATRÍCULA:</b>
                                                    {{ $datosFamiliar->beneficiarioPersona->matricula_seguro }}
                                                </td>
                                                <!-- Segunda columna -->
                                                <td
                                                    style="width: 50%; vertical-align: top; text-align: left;  padding-left: 10px;">
                                                    <b>GRUPO SANGUÍNEO:</b>
                                                    {{ $datosFamiliar->beneficiarioPersona->grupo_sanguineo }}<br>
                                                    <b>FECHA DE NACIMIENTO:</b>
                                                    {{ \Carbon\Carbon::parse($datosFamiliar->beneficiarioPersona->fecha_nacimiento)->Format('d /m/ Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="width: 50%; vertical-align: top; text-align: left;  padding-right: 10px;">
                                                    <b>FECHA AFILIACIÓN:</b>
                                                    @if ($datosFamiliar->fecha_reafiliacion)
                                                        {{ \Carbon\Carbon::parse($datosFamiliar->fecha_reafiliacion ?? 'Sin dato')->Format('d/m/Y') }}
                                                    @else
                                                        {{ \Carbon\Carbon::parse($datosFamiliar->fecha_afiliacion ?? 'Sin dato')->Format('d/m/Y') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                @endif
            @endforeach



        @endif
        <div class="footer-spacer"></div>
    </div>



</body>

</html>
