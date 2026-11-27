<style>
    * {
        margin: 0;
        padding: 0;
    }

    @font-face {
        font-family: 'Liga Sans';
        src: url("fonts/LigaSans-Bold.otf") format('opentype');
        /* Ruta a la fuente */
        font-weight: bold;
        font-style: normal;
    }

    .fondo_escudo {
        position: absolute;
        background-image: url("images/carnets/ssu3.png");
        background-size: contain;
        /* Hacer que la imagen ocupe toda la hoja */
        background-repeat: no-repeat;
        opacity: 100%;
        width: 50%;
        height: 100%;
        margin-left: -40px;
        margin-top: 20px;
        z-index: -2;
    }

    img {

        position: absolute;
        width: 600px;
        height: 380px;
        z-index: 0;
    }

    .container {
        filter: contrast(200%);
        position: absolute;
        width: 600px;
        height: 380px;
        z-index: 1;
    }

    .info_persona {
        position: absolute;
        padding: 0px;
        top: 80px;
        right: 0px;
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        color: aliceblue;
        /*
        
        background-color: red;
        */
    }

    .nombres {
        color: aliceblue;
        font-size: 1.2em;
        text-align: center;
        text-transform: uppercase;
        font-family: 'Liga Sans', Arial, sans-serif;
        padding-bottom: 10px;
        width: 300px;
        margin-left: 30px;
        /*
        
        background-color: #a5deed;
        /* Usa la fuente Liga Sans */

    }

    .item_campo {
        text-align: right;
        vertical-align: top;
        font-size: 1em;
    }

    .color_campo {
        color: #73e8ef;
    }

    .respuesta {
        font-size: 1.1em;
        padding-left: 10px;
        width: 210px;
        /*background-color: #a5deed;*/
    }

    .espacio {
        width: 200px;
        padding: 10px;
        /* background-color: aqua; */
    }



    .tabla_datos {
        font-size: 1.2em;
        right: 10px;
    }

    .footer {
        position: absolute;
        bottom: 0px;
        width: 100%;
        /* background-color: antiquewhite; */
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        color: aliceblue;
        padding-bottom: 5px;
    }

    .footer_table {
        width: 100%;
        text-align: center;
    }

    .gerente {
        text-align: center;
        margin-right: -30px;
        font-family: Calibri, "Trebuchet MS", sans-serif;
        font-style: italic;
        text-transform: capitalize;
    }

    .fecha_validez {
        margin-left: -40px;
    }

    .firma_gerente {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 35%;
        height: 35%;
        margin-right: 0px;
        z-index: 2;
    }

    .tipo_afiliado {
        font-size: 1.3em;
        color: #a9faf9;
        width: 350px;
        padding: 0px;
        margin-left: 80px;
    }

    .page-break {
        page-break-after: always;
    }


    .fondo_espalda {
        z-index: 0;
    }

    .espalda {
        width: 100%;
        height: 100%;
        color: white;
        z-index: 1;
    }

    .titulos {
        display: inline-block;
        background-color: none;
    }

    .info {
        display: inline-block;
        background-color: none;
    }

    .titulo_cara {
        position: absolute;
        display: inline-block;
        text-align: right;
        top: 0px;
        width: 45px;
    }

    .texto_cara {
        display: inline-block;
        margin-left: 60px;
        width: 450px;
    }

    .tipo_letra {
        font-size: 1.2em;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
    }

    .grupo_sanguineo {
        position: absolute;
        background-color: none;
        right: 10px;
        text-transform: uppercase;
        margin-top: 10px;
        font-size: 1.7em;
        font-weight: bold;
        font-family: "Times New Roman", Times, serif;
        z-index: 7;
    }

    .datos {
        background-color: none;
        position: absolute;
        margin-top: 70px;
        margin-left: 10px;
        font-size: 1.4em;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;

    }

    .qr {
        position: absolute;
        margin-top: 30px;
        margin-left: 5px;
        bottom: 5px;
        width: 150px;
        height: 150px;

    }

    .emergencia {
        position: absolute;
        bottom: 118px;
        margin-left: 160px;
        font-family: "Comic Sans MS", cursive, sans-serif;
        font-size: 1em;
    }

    .datos_referencia {
        margin-top: 44%;
        margin-left: 160px;
        width: 350px;
        text-align: center;
        font-weight: bold;
        font-size: 1em;
        font-family: Arial, Helvetica, sans-serif;
        padding-left: -50px;
    }

    .emergencia_ssu {
        position: absolute;
        margin-left: 170px;
        bottom: 10px;
        font-size: 1.3em;
        font-weight: bold;
        text-align: center;
        letter-spacing: 1px;
    }

    .texto {
        display: inline-block;
        margin-left: 130px;
        width: 450px;
    }

    .titulo_e {
        position: absolute;
        display: inline-block;
        width: 122px;
        text-align: right;
        /* background-color: red; */
        top: 0px;

        padding: 0;

    }

    .linea_texto {
        position: relative;
    }

    .foto_afiliado {
        position: absolute;
        width: 50%;
        height: 85%;
        margin-left: -50px;
        margin-top: 0px;
        z-index: -1;
    }

    .container {
        font-weight: bold;
    }
</style>
<img src={{ $ruta_carnet }} alt="fondo_carnet" />
<div class="fondo_escudo"></div>
<img src="data:image/png;base64,{{ $foto_afiliado }}" class="foto_afiliado" />

<div class="container">

    <div class="info_persona">
        <div class="nombres">
            <div>{{ $nombres }}</div>
            <div>{{ $p_apellido . ' ' . $s_apellido }}</div>
        </div>
        <table border="0" class="tabla_datos">
            <tbody>
                <tr>
                    <td class="item_campo color_campo">Matricula:</td>
                    <td class="respuesta">{{ $matricula_seguro }}</td>
                </tr>
                <tr>
                    <td class="item_campo color_campo">Carnet:</td>
                    <td class="respuesta"> {{ $ci }}</td>
                </tr>
                <tr>
                    <td class="item_campo color_campo">Insitucion:</td>
                    <td class="respuesta">{{ $sigla_institucion }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="footer">
        <table border="0" class="footer_table">
            <tr>
                <td colspan="2">
                    <div class="tipo_afiliado color_campo">
                        {{ $tipo_asegurado }}
                        {{-- BENEFICIARIO HIJO MAYOR 19 AÑOS --}}
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="fecha_validez">
                        <strong class="color_campo">
                            Valido hasta:
                        </strong>
                        {{ $fecha_vencimiento }}
                    </div>
                </td>
                <td>
                    <div class="gerente">
                        <div>{{$sigla_mencion_gral}} Dr. {{ $nombre_gral }}</div>
                        <div>Gerente General
                            @if ($interino_gral)
                                <strong style="text-transform: lowercase;"> a.i.</strong>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @if (isset($foto_firma_gral))
        <img src="data:image/png;base64,{{ $foto_firma_gral }}" class="firma_gerente" />
    @endif
</div>

<div class="page-break"></div>
<img src='images/carnets_final/espalda.png' alt="fondo_espalda" />
<div class="espalda">
    <div class="datos">
        <div class="linea_texto">
            <div class="titulo_e color_campo">
                <strong>Alergias: </strong>
            </div>
            <div class="texto">
                {{ $alergias }}
            </div>
        </div>
        <div class="linea_texto">
            <div class="titulo_e color_campo ">
                <strong>Domicilio: </strong>
            </div>
            <div class="texto">
                {{ $domicilio }}<br>
            </div>
        </div>
        <div class="linea_texto">
            <div class="titulo_e color_campo">
                <strong>Telefono: </strong>
            </div>
            <div class="texto">
                {{ $telefono }}
            </div>
        </div>
    </div>
    <div class="emergencia tipo_letra">
        <strong class="color_campo">
            En caso de emergencia <br /> comunicarse con:<br />
        </strong>
    </div>

    <div class="grupo_sanguineo">
        <p>{{ $grupo_sanguineo }}</p>
    </div>
    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="150" height="150"
        class="qr" />
    <div class="datos_referencia ">
        {{ $nombre_referencia }} <br />
        <strong class="color_campo">Cel.:</strong>
        {{ $telefono_referencia }}
    </div>
    <div class="emergencia_ssu tipo_letra ">
        <strong class="color_campo">Tel. Emergencias SSU:</strong><br />
        71559744 - 2434262
    </div>
</div>
