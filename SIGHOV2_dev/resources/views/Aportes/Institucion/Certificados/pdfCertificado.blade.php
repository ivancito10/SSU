<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CERTIFICADO INSTITUCIÓN</title>

        <style>
            body , html{

                margin: 15px 25px;
                padding: 0;
                background-color: #f4f4f4;
                color: #333;
                background: url("{{ asset('logo/logo2.png') }}") no-repeat center center fixed;
                display: flex;
                flex-direction: column;

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
            /* Pie de página */
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
                height: 70px; /* Ajusta el alto */
                box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            /* Estilo para impresión, asegurando que el pie de página esté en todas las páginas */
            @media print {
                .footer {
                    position: fixed;
                    bottom: 0;
                    width: 100%;
                    page-break-before: always; /* Asegura que el pie se repita al comienzo de cada nueva página */
                }
            }
        </style>

        
    </head>
    <body>
    <div class="document-container" style="text-align: justify; margin: 5px 5px;">
        <!-- Encabezado -->
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <img src="logo/ssu.png" class="logo-left" width="112" height="80" alt="Logo">
            <div class="document-title" style="text-align: center; flex-grow: 1;">
                <h2 style="text-decoration: underline; margin: 0; font-family: Arial, sans-serif; letter-spacing: 1px;">
                    CERTIFICADO</h2>
                @if($tipo_certificado == false)
                <h4 style=" font-family: Arial, sans-serif; letter-spacing: 1px;"> 
                    NO AFILIACIÓN EMPRESA</h4>
                @elseif($tipo_certificado == true)
                <h4 style=" font-family: Arial, sans-serif; letter-spacing: 1px;">
                    AFILIACIÓN EMPRESA</h4>
                @endif
            </div>
            <img src="logo/logo2.png" class="logo-right" width="130" height="70" alt="Logo">
        </div>

        <!-- Contenido del documento -->
        <div class="document-content">
             
            <div style="text-align:right;">
                <h3>{{$cod_certificado}}</h3>
            </div>
            <p>A Solicitud escrita del Sr(a). <b>{{$nombre_persona}} {{$ap_paterno}} {{$ap_materno}}</b>, con cédula de identidad <b>{{$carnet}} {{$complemento}} {{$sigla}},</b> Representante Legal de la
                empresa <b>{{$nombre_insti}}</b>, de fecha {{$fecha_sol}}, dando cumplimiento al Código de Seguridad Social y sus normas
                vigentes, además de la Resolución Administrativa de <b>ASUSS Nº 334-2022</b>, previa revisión de la bade de Datos,
                la <b>UNIDAD DE APORTES</b> del Seguro Social Universitario La Paz.
            </p>
            <h2><b>CERTIFICA:</b></h2>
            <p>Que, la empresa <b>{{$nombre_insti}}</b> con <b>NIT {{$nit}}</b>
                <b> 
                    @if($tipo_certificado == false)
                    <span style="text-decoration: underline;">
                         NO se encuentra afiliada
                    </span>
                    @elseif($tipo_certificado == true)
                    <span style="text-decoration: underline;">
                        Se encuentra afiliada
                    </span>
                    @endif
                </b> en el Seguro Social Universitario La Paz.
                <br>
                En cuanto se certifica para fines consiguientes.
                <br>
                <p><em>La Paz, {{$fecha}}</em></p>
            </p>
            <br><br>
            <div style="text-align:center;">
                <span style="vertical-align: middle; font-size: 16px; font-weight: bold;">Seguridad incorporada:</span>
                <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="130" height="130" style="vertical-align: middle; margin-left: 5px;">
            </div>

            
           
        </div>


        <div class="footer">
            
            <table width="100%">
                <tr>
                    <td style="font-size: 9px; vertical-align: top;" >
                        <b>Fecha: </b>{{$fecha_registro}}<br>
                        <b>Usuario: </b>{{ ucwords(mb_strtolower($nombre_usuario)) }}<br>
                    </td>
                    <td align="left" style="font-size: 9px; vertical-align: top;">
                        
                        <b>Información de Contacto:</b><br>
                        Dirección: Av. 6 de Agosto Nº 2630 , La Paz, Bolivia<br>
                        Teléfono: +591 2 2434267 <br>
                        Email: contactos@ssulapaz.org<br>
                        <em><b>
                        'Este documento puede ser verificado escaneando el Qr adjunto en este documento.'
                         </b></em>
                     </td>
                     <td align="right" >
                       
                        <img src="images/logo_fondo_blanco.png" width="150" height="90" alt="Logo" style="opacity: 0.7;">

                     </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>