<!DOCTYPE html>
<html lang="es" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <title>Bienvenida a Seguro Social Universitario La Paz</title>
    <style type="text/css">
        body {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            background-color: white;
        }

        .es-wrapper-color {
            background-color: white;
            width: calc(100% - 2cm);
            margin: 0 auto;
            padding: 0cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0px;
        }

        img {
            display: block;
        }

        p {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 16px;
            color: #333333;
        }

        strong {
            font-size: 18px;
            color: #0c0201;
        }

        .highlight {
            color: #054d94;
        }

        .important {
            color: #c50b07;
        }

        .social-icons {
            padding: 10px 0;
        }

        .social-icons td {
            padding: 0 10px;
        }
    </style>
</head>

<body>
    <div class="es-wrapper-color">
        <table>
            <tr>
                <td valign="top">
                    <table align="center" bgcolor="#ffffff" style="width:100%;">
                        <tr>
                            <td align="left" style="padding:20px;">
                                <table cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td valign="top" align="left" style="width:186px">
                                            <img src="https://eoykfqm.stripocdn.email/content/guids/b8743148-8a89-40f9-b5fd-0c80c15d1875/images/logo2.png"
                                                alt="Logo" width="186" height="108">
                                        </td>
                                    </tr>
                                </table>

                                <table cellspacing="0" cellpadding="0" align="right" style="float:right">
                                    <tr>
                                        <td align="center">
                                            <b style="font-family: Arial, Helvetica, sans-serif;font-size: 20px;">
                                                <span class="highlight">SISTEMA SIGHO</span>
                                                <span class="important">V2</span>
                                            </b>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table align="center" style="background-color:#FFFFFF;">
                        <tr>
                            <td style="padding:20px;">
                                <p><em>Señor/a Asegurado/a <strong>{{$datos_titular->p_apellido}} {{$datos_titular->s_apellido}}
                                {{$datos_titular->nombres}}</strong>,</em></p>
                                <p style="text-align: justify;"><em>
                                        Le damos la bienvenida a nuestro <strong>"Seguro Social Universitario La Paz"</strong>.
                                        Agradecemos de antemano la confianza que deposita en nuestro seguro, esperamos
                                        ayudarlo y brindarle una atención eficaz y eficiente. Se le informa que su registro
                                        de afiliación fue <span class="text-success">exitoso</span>. Mediante la presente le damos
                                        la bienvenida y también le otorgamos el <b>usuario</b> y
                                        <b>contraseña</b> para acceder a los sistemas de agendamiento web
                                        para obtener citas médicas.
                                    </em>
                                </p>
                                <p style="text-align: justify;">
                                    <em>
                                        Recordarle la confidencialidad de este mensaje y las credenciales otorgadas. Por su seguridad, le recomendamos no compartir su usuario y contraseña con terceros, y asegurarse de que su contraseña sea robusta, combinando letras mayúsculas, minúsculas, números y caracteres especiales. Además, le sugerimos cambiar su contraseña periódicamente y estar atento a cualquier actividad sospechosa en su cuenta.
                                    </em>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <table align="center" style="background-color:#FFFFFF;">
                        <tr>
                            <td style="padding:20px;">
                                <p align="right"><em>
                                        <br><br>
                                        <strong><span class="highlight">
                                            Usuario: {{$usuario}}
                                            <br>
                                            Contraseña: {{$contraseña}}
                                            <br>
                                        </span></strong>
                                        <br><br>
                                        Este es un mensaje de confirmación del sistema <b>SIGHO V2</b>, contribuyendo
                                        al avance de la tecnología y brindando una mejor atención a nuestros pacientes.
                                        Estamos comprometidos con la innovación y la mejora continua de nuestros servicios
                                        para ofrecerle una experiencia más eficiente y efectiva.
                                    </em>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Social Icons -->
                    <!-- Aquí añades los íconos sociales -->
                    <table style="width: 250px;" align="center">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <a target="_blank" href="https://www.facebook.com/profile.php?id=100057490179503">
                                        <img alt="Fb" width="40" height="40" title="Facebook"
                                            src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/facebook-rounded-black.png">
                                    </a>
                                </td>
                                <td align="center">
                                    <a href="https://www.tiktok.com/@ssulapazbolivia" target="_blank">
                                        <img title="TikTok"
                                            src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/tiktok-rounded-black.png"
                                            alt="Tt" width="40" height="40">
                                    </a>
                                </td>
                                <td align="center">
                                    <a href="tel:+59171559744">
                                        <img src="https://eoykfqm.stripocdn.email/content/assets/img/other-icons/rounded-black/phone-rounded-black.png"
                                            alt="Phone" width="40" height="40" title="Phone">
                                    </a>
                                </td>
                                <td align="center">
                                    <a target="_blank" href="https://ssulapaz.org/">
                                        <img alt="Db" width="40" height="40" title="Dribbble"
                                            src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/dribbble-rounded-black.png">
                                    </a>
                                </td>
                                <td align="center">
                                    <img title="YouTube"
                                        src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/youtube-rounded-black.png"
                                        alt="Yt" width="40" height="40">
                                </td>
                                <td align="center">
                                    <a href="https://wa.me/59171559744" target="_blank">
                                        <img title="Whatsapp"
                                            src="https://eoykfqm.stripocdn.email/content/assets/img/messenger-icons/rounded-black/whatsapp-rounded-black.png"
                                            alt="Whatsapp" width="40" height="40">
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Fin de los íconos sociales -->


                </td>
            </tr>
        </table>
    </div>
</body>

</html>
