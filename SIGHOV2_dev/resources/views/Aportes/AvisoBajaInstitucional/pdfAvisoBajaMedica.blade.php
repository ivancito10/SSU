<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AVISO BAJA INSTITUCIONAL</title>

        <style>
            html {
            margin: 2;
            }
            body {
            font-family: Arial, sans-serif;
            margin: 5mm 5mm 5mm 5mm;
                background: url("{{asset('Images/SSU2019.png')}}") no-repeat center center fixed;
                    -webkit-background-size: cover;
                    -moz-background-size: cover;
                    -o-background-size: cover;
                    background-size: cover;
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
            <div class="document-title" style="text-align: center; flex-grow: 1;">
                <h1 style="text-decoration: underline; margin: 0; font-family: Arial, sans-serif; letter-spacing: 1px;">
                    AVISO DE BAJA DEL<br>  ASEGURADO</h1>
                
            </div>
        </div>
        <div>

            <table border="1" style="font-size:13px;" width="100%">
                <thead>
                    <tr>
                        <th> APELLIDO PATERNO</th>
                        <th> APELLIDO MATERNO</th>
                        <th> NOMBRES</th>
                        <th> Nº DE IDENTIDAD</th>
                        <th> Nº  MATRICULA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">{{$p_apellido}}</td>
                        <td align="center">{{$s_apellido}}</td>
                        <td align="center">{{$nombres}}</td>
                        <td align="center">{{$ci}}</td>
                        <td align="center">{{$matricula_seguro}}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table border="1" style="font-size:13px;" width="70%">
                <thead>
                    <tr>
                        <th> FECHA DE BAJA</th>
                        <th> MOTIVO DE BAJA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">{{$fecha_baja}}</td>
                        <td align="center">{{$motivo_baja}}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table border="1" style="font-size:13px;" width="100%">
                <thead>
                    <tr>
                        <th> ÚLTIMO SALARIO</th>
                        <th> NOMBRE O RAZÓN SOCIAL DEL EMPLEADOR</th>
                        <th> Nº DEL EMPLEADOR</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="right">{{$ultimo_salario}} <b><em>Bs.</em></b></td>
                        <td align="center">{{$nombre_insti}}</td>
                        <td align="center">{{$nro_empleador}}</td>
                    </tr>
                </tbody>
            </table>

        </div>
        
        <div>
            <table border="1" style="font-size:13px;" width="100%">
                <tbody>
                    <tr height="150px">
                        <td align="center">
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            FIRMA Y SELLO DEL EMPLEADOR
                    </td>
                        <td align="center">
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            SELLO Y FECHA DE RECEPCIÓN EN EL SEGURO SOCIAL UNIVERSITARIO </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div style="text-align:right; font-size:13px;">
            <p><em>La Paz, {{$fecha_formateada}} </em></p>

        </div>



        <div class="footer">
            
            <table width="100%">
                <tr>
                    <td style="font-size: 9px; vertical-align: top;" >
                        <b>Fecha: </b> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} <br>
                        <b>Usuario: </b> {{$nombre_persona_registra}} <br>
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