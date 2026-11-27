<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Liquidación de Planilla de Aportante Voluntario</title>
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
            font-size: 24px;
            color: #0c0201;
        }
    </style>
    </head>

    <body>
        <div class="es-wrapper-color">
            <?php
            setlocale(LC_TIME, 'Spanish');
            Carbon\Carbon::setUtf8(true);
            ?>
            <table>
                <tr>
                    <td valign="top">
                        <table align="center" bgcolor="#ffffff"
                            style="width:100%;">
                            <tr>
                                <td align="left" style="padding:20px;">
                                    <table cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td valign="top" align="left"
                                                style="width:186px">
                                                <img
                                                    src="https://eoykfqm.stripocdn.email/content/guids/b8743148-8a89-40f9-b5fd-0c80c15d1875/images/logo2.png"
                                                    alt="Logo" width="186"
                                                    height="108">
                                            </td>
                                        </tr>
                                    </table>

                                    <table cellspacing="0" cellpadding="0"
                                        align="right" style="float:right">
                                        <tr>
                                            <td align="center">
                                                <b
                                                    style="font-family: Arial, Helvetica, sans-serif;font-size: 20px;">
                                                    <span
                                                        style="color:#054d94;">SISTEMA
                                                        SIGHO </span>
                                                    <span
                                                        style="color: #c50b07;">
                                                        V2 </span>
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
                                    <p><em>Estimado usuario,</em></p>
                                    <p style="text-align: justify;">
                                        <em>
                                            Le informamos que la planilla
                                            correspondiente al aportante
                                            voluntario
                                            <b><u>{{$datos_persona->personaContratoVoluntario->p_apellido}}
                                                    {{$datos_persona->personaContratoVoluntario->s_apellido}}
                                                    {{$datos_persona->personaContratoVoluntario->nombres}}</u></b>,
                                            del mes <b><u>{{Carbon\Carbon::createFromDate(null,$comprobante->id_mes,1)->formatLocalized('%B ')}}</u></b> de la
                                            gestión <b><u>{{$comprobante->gestion}}</u></b>,
                                            ha sido debidamente liquidada.
                                        </em>
                                    </p>
                                    <p style="text-align: justify;">
                                        <em>
                                            En adjunto encontrará el comprobante
                                            de la planilla voluntaria liquidada,
                                            que puede ser utilizado para sus
                                            registros y fines correspondientes.
                                        </em>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <table align="center" style="background-color:#FFFFFF;">
                            <tr>
                                <td style="padding:20px;">
                                    <p align="right">
                                        <em>
                                            Atentamente,
                                            <br>
                                            <strong><span
                                                    style="color:#054d94;">Seguro
                                                    Social Universitario</span>
                                                <span style="color:#c50b07;">La
                                                    Paz</span></strong>
                                            <br>
                                            <span style="color: #333333;">La Paz
                                                {{Carbon\Carbon::now()->formatLocalized('%A %d de %B de %Y')}}</span>
                                        </em>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- Íconos sociales -->
                        <table style="width: 250px;" align="center">
                            <tbody>
                                <tr>
                                    <td align="center">
                                        <a target="_blank"
                                            href="https://www.facebook.com/profile.php?id=100057490179503">
                                            <img alt="Fb" width="40" height="40"
                                                title="Facebook"
                                                src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/facebook-rounded-black.png">
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a
                                            href="https://www.tiktok.com/@ssulapazbolivia"
                                            target="_blank">
                                            <img title="TikTok"
                                                src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/tiktok-rounded-black.png"
                                                alt="Tt" width="40" height="40">
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a href="tel:+59171559744">
                                            <img
                                                src="https://eoykfqm.stripocdn.email/content/assets/img/other-icons/rounded-black/phone-rounded-black.png"
                                                alt="Phone" width="40"
                                                height="40" title="Phone">
                                        </a>
                                    </td>
                                    <td align="center">
                                        <a target="_blank"
                                            href="https://ssulapaz.org/">
                                            <img alt="Db" width="40" height="40"
                                                title="Dribbble"
                                                src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/dribbble-rounded-black.png">
                                        </a>
                                    </td>
                                    <td align="center">
                                        <img title="YouTube"
                                            src="https://eoykfqm.stripocdn.email/content/assets/img/social-icons/rounded-black/youtube-rounded-black.png"
                                            alt="Yt" width="40" height="40">
                                    </td>
                                    <td align="center">
                                        <a href="https://wa.me/59171559744"
                                            target="_blank">
                                            <img title="Whatsapp"
                                                src="https://eoykfqm.stripocdn.email/content/assets/img/messenger-icons/rounded-black/whatsapp-rounded-black.png"
                                                alt="Whatsapp" width="40"
                                                height="40">
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
