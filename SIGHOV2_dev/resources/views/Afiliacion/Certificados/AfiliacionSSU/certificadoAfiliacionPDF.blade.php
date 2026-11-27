<!DOCTYPE html>

<style>
    body {

        margin: 0px 0px;

        background-color: #f4f4f4;

        color: #333;

        background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;

    }



    .document-container {

        background-color: white;

        padding: 20px;

        border-radius: 5px;

        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

        position: relative;

        /* Habilita posicionamiento relativo para el contenedor */

    }



    .document-title {

        font-size: 18px;

        font-weight: bold;

        text-align: center;

        margin-bottom: 20px;

        font-family: 'Times New Roman', Times, serif
    }



    .document-content {

        line-height: 1, 1;

        font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;

    }



    .highlight {

        font-weight: bold;

    }



    .recommendation {

        margin-top: 20px;

        font-style: italic;

        color: #555;

    }



    #header {

        position: fixed;

        left: 0px;

        top: -30px;

        right: 0px;

        height: 150px;

        background-color: white;

        text-align: center;

        margin-left: 35px;

    }



    #watermark {

        position: fixed;

        bottom: 0px;

        left: 50px;

        top: 120px;

        opacity: 0.5;

        width: 15cm;

        height: 15cm;

        z-index: -1000;

    }


    i.subrayado {
        text-decoration: overline;
    }



    .interlineado {
        line-height: 80%;
        text-align: center;
    }



    .logo-right {
        position: absolute;
        top: 10px;
        right: 10px;
    }



    .logo-left {
        position: absolute;
        top: 10px;
        left: 20px;
    }
</style>

<html>



<head>

    <title>CERTIFICADO</title>

</head>



<body>



    <div style="display: flex; align-items: center; justify-content: space-between; margin: 0;">

        <img src="logo/ssu.png" class="logo-left" width="112" height="80" alt="Logo" style="margin: 0;">

        <div class="document-title" style="text-align: center; flex-grow: 1; margin: 0;">

            <h1 style="text-decoration: underline; margin: 0;">CERTIFICADO</h1>

            <h2 style="text-align:center; margin: 0;">AFILIACIÓN</h2>

        </div>

        <img src="logo/logo2.png" class="logo-right" width="150" height="80" alt="Logo" style="margin: 0;">

    </div>

    <div class="document-content" style="text-align: justify; margin: 50px 50px;">



        <p align="right" style="font-weight: bold; font-size: 17px;">{{ $afiliado->cod_certificado }}</p>

        <p style="text-align: justify; font-size: 15px; font-style: italic;">La unidad de Afiliaciones del
            Seguro Social

            Universitario La Paz, en

            uso de sus facultades y a solicitud del interesado (a), certifica que, según el registro de

            afiliados de la base de datos, la persona detallada específicamente bajo los siguientes

            datos:</p>

        <table class="table" align="center"
            style="border-collapse: separate; border-spacing: 0 10px; border: 1px solid black; border-radius: 8px; padding-right: 15px; padding-left: 15px;">

            <tbody>

                <tr>

                    <td style="font-weight: bold; font-size: 15px; padding-right: 20px;">Nombre(s):
                    </td>

                    <td style="font-size: 15px;">{{ $afiliado->nombres }}</td>

                </tr>

                <tr>

                    <td style="font-weight: bold; font-size: 15px; padding-right: 20px;">Apellidos:
                    </td>

                    <td style="font-size: 15px;">{{ $afiliado->paterno . ' ' . $afiliado->materno }}
                    </td>

                </tr>

                <tr>

                    <td style="font-weight: bold; font-size: 15px; padding-right: 20px;">Fecha de
                        nacimiento:</td>

                    <td style="font-size: 15px;">
                        {{ \Carbon\Carbon::parse($afiliado->nacimiento)->Format('d /m/ Y') }}

                    </td>

                </tr>

                <tr>

                    <td style="font-weight: bold; font-size: 15px; padding-right: 20px;">Cédula de
                        identidad:</td>

                    <td style="font-size: 15px;">{{ $afiliado->ci . ' ' . $afiliado->complemento }}
                        {{ $afiliado->personaCertificadoAfiliacion->departamentoPersona?->sigla ?: '' }}
                    </td>

                </tr>

                <tr>

                    <td style="font-weight: bold; font-size: 15px; padding-right: 20px;">Matrícula:
                    </td>

                    <td style="font-size: 15px;">{{ $afiliado->matricula }}</td>

                </tr>

            </tbody>

        </table>

        @php
            $tipos_especiales = [2, 3, 4, 5, 6, 7, 8, 11, 13, 14];
            $es_especial = in_array($id_tipo_asegurado, $tipos_especiales);
            $es_voluntario = $nombre_institucion == 'VOLUNTARIO';
        @endphp

        <p style="text-align: justify; font-size: 15px; font-style: italic;">
            Se encuentra <b>AFILIADO(A)</b> a nuestro Ente Gestor de Salud,
            en calidad de <b>{{ $tipo_asegurado }}</b> desde el <b>{{ $fecha_afiliacion }}</b>,

            @if ($es_especial)
                del titular: Sr(a) <b>{{ $nombre_titular }}</b> trabajador(a)
                {{ $es_voluntario ? 'de la institución' : 'perteneciente a la institución' }}
                <b>{{ $nombre_institucion }}</b>.
            @else
                {{ $es_voluntario ? 'perteneciente como aportante' : 'perteneciente a la institución' }}
                <b>{{ $nombre_institucion }}</b>.
            @endif
        </p>

        <p style="text-align: justify; font-size: 15px; font-style: italic;">
            Es en cuanto se certifica, para los fines consiguientes.
        </p>



        <p style="font-size: 15px;">La Paz, {{ $fechaEmision }}</p>

        <table align="left">

            <tr>

                <td align="center" style="margin: 0;">

                    <p style="margin: 0;"><span style="text-decoration: underline;"><b>Seguridad
                                Incorporada

                                :</b></span>

                        <br>

                        <em style="font-size: 10px; text-align:center;"><b>
                                {{ $afiliado->codigo_seguridad }} </b></em>

                    </p>

                </td>

                <td align="right" style="margin: 0;">

                    <p style="margin: 0;"><img src="data:image/jpeg;base64,{{ $qrCode }}" alt="Código QR"
                            width="130" height="130"></p>

                </td>

            </tr>

        </table>





        <p style="font-size: 15px;"><b>Válido hasta:</b>

            {{ \Carbon\Carbon::parse($afiliado->fecha_caducidad)->Format('d /m/ Y') }}</p>



        <p style="text-align: justify;">

            <span style="font-weight: bold; font-size: 15px;">NOTA:</span>

            La validez del presente documento es de 15 días a partir de la fecha de emisión. Este

            documento puede ser verificado en Nuestra Página Web <a href="http://www.ssulapaz.org"
                style="color: blue; text-decoration: underline;">http://www.ssulapaz.org</a>.

        </p>





    </div>





</body>



</html>
