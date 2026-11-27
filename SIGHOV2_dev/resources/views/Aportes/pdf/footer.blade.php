<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        .footer {
            font-size: 10px;
            text-align: center;
            width: 100%;

            padding-top: 5px;
            background-color: #f8f9fa;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            font-size: 9px;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <b>Fecha:</b> {{ now()->toDateTimeString() }} <br>
                    <b>Usuario:</b> {{ auth()->user()->name ?? 'Usuario Desconocido' }} <br>

                    {{-- -<b>Fecha:</b> 2025-05-30 15:00:00 <br>
                    <b>Usuario:</b> LIBUSE DANOVA PEÑARANDA KALTEIS <br> --}}
                </td>
                <td align="left">
                    <b>Información de Contacto:</b><br>
                    Dirección: Av. 6 de Agosto Nº 2630, La Paz, Bolivia<br>
                    Teléfono: +591 2 2434267 <br>
                    Email: contactos@ssulapaz.org<br>
                    <!--  <em><b>
                            'Este documento puede ser verificado escaneando el QR adjunto en este documento.'
                        </b></em> -->
                </td>
                <td align="right">
                    <img src="{{ public_path('images/logo_fondo_blanco.png') }}" width="100" alt="Logo">
                </td>

            </tr>
        </table>
    </div>
</body>

</html>
