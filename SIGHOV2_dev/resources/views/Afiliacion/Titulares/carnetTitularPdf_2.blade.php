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
        /* background-image: url("images/carnets/ssu3.png"); */
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

        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        color: aliceblue;
        width: 250px;
        margin-left: 260;
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


        text-align: left;
        /* background-color: black; */
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
        color: #ffffff;
    }

    .respuesta {
        font-size: 1.1em;
        padding-left: 10px;
        width: 210px;
        padding: 0px 4px;
        /*background-color: #a5deed;*/
    }

    .espacio {
        width: 200px;
        padding: 10px;
        /* background-color: aqua; */
    }



    .tabla_datos {
        position: absolute;
        /* o 'fixed' si quieres que no se mueva al hacer scroll */
        top: 157px;
        /* distancia desde el borde superior */
        left: 380px;
        /* distancia desde el borde izquierdo */
        font-size: 1.2em;
        color: white;
        font-family: Arial, Helvetica, sans-serif;
    }


    .footer {
        position: absolute;
        bottom: 0px;
        width: 100%;
        /* background-color: antiquewhite; */
        font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
        color: aliceblue;
        padding-bottom: 1px;
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
        margin-top: 335px;
        margin-left: 290px;
        text-transform: capitalize;
        color: white;
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
        margin-right: 15px;
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
        right: 37px;
        text-transform: uppercase;
        margin-top: 275px;
        font-size: 1.9em;
        font-weight: bold;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        z-index: 7;
    }

    .usuario {
        position: absolute;
        background-color: none;
        right: 37px;
        text-transform: uppercase;
        margin-top: 330px;
        font-size: 1.1em;
        font-weight: bold;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        z-index: 7;
        text-transform: lowercase;
        
    }

    .datos {
        background-color: none;
        position: absolute;
        margin-top: 70px;
        margin-left: 235px;
        font-size: 1.0em;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        height: 80px;
        width: 210px;

    }

    .qr {
        position: absolute;
        margin-top: 10px;
        margin-LEFT: 440px;

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
        margin-top: 40%;
        margin-left: 245px;
        width: 350px;
        text-align: left;
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
        font-size: 15px;
        padding: 4px 10px;
        margin-top: 24px;


    }

    .linea_texto_telefono {

        font-size: 15px;
        padding: 4px 10px;
        background-color: none;
        position: absolute;
        margin-top: 220px;
        margin-left: 235px;
        font-size: 1.0em;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        height: 40px;
        width: 200px;



    }

    .linea_texto_direccion {

        font-size: 15px;
        padding: 4px 10px;
        background-color: none;
        position: absolute;
        margin-top: 130px;
        margin-left: 235px;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        height: 70px;
        width: 180px;



    }


    /* Estilos para la segunda hoja
 .foto_afiliado {
        position: absolute;
        width: 35%;
        height: 85%;
        margin-left: 0px;
        margin-top: 0px;
        z-index: -1;
    }
*/

    .foto_afiliado {
        position: absolute;
        width: 55%;
        height: auto;
        margin-left: -50px;
        margin-top: -25px;
        z-index: -1;
        object-fit: contain;

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

            <div>{{ $p_apellido . ' ' . $s_apellido }}</div>
            <div>{{ $nombres }}</div>
        </div>
    </div>
    <table border="0" class="tabla_datos">
        <tbody>
            <tr>

                <td class="respuesta">{{ $matricula_seguro }}</td>
            </tr>
            <tr>

                <td class="respuesta"> {{ $ci }}</td>
            </tr>
            <tr>

                <td class="respuesta" style="font-size: 14px;">{{ $sigla_institucion }}</td>
            </tr>
            <!-- @if (count($sigla_institucion_transferencia) != 0) -->
            <!-- <tr>
                        <td class="item_campo color_campo">Transf:</td>
                        <td class="respuesta">
                            @foreach ($sigla_institucion_transferencia as $sigla)
                                {{ $sigla }} <br>
                            @endforeach
                        </td>
                        {{-- <td class="resp_i">{{ $sigla_institucion_transferencia }}</td> --}}
                    </tr> -->
            <!-- @endif -->
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table border="0" class="footer_table">
            <tr>
                <td colspan="2">
                    <!-- <div class="tipo_afiliado color_campo">
                        {{ $tipo_asegurado }}
                        {{-- BENEFICIARIO HIJO MAYOR 19 AÑOS --}}
                    </div> -->
                </td>
            </tr>
            <tr>
                <td colspan="0" align="left" style=" padding: 1px 210px">
                    <div class="fecha_validez">
                        <strong style="font-size: 19px;" class="color_campo">


                            {{ $fecha_vencimiento }}</strong>
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <div class="gerente">
        <div>{{-- {{$sigla_mencion_gral}} --}} Dr. {{ $nombre_gral }}</div>
        <div>Gerente General
            @if ($interino_gral)
            <strong style="text-transform: lowercase;"> a.i.</strong>
            @endif
        </div>
    </div>
    @if (isset($foto_firma_gral))
    <img src="data:image/png;base64,{{ $foto_firma_gral }}" class="firma_gerente" />
    @endif
</div>

<div class="page-break"></div>
<img src='images/carnets_final/APROBADOS/CREDENCIAL_SSU_ATRAS.png' alt="fondo_espalda" />
<div class="espalda">
    <div class="datos">
        <!-- <div class="linea_texto">
            
            <div class="texto">
                {{ $grupo_sanguineo }}
            </div>
        </div> -->
        <div class="linea_texto">

            <div class="texto">
                {{ $alergias }}
            </div>
        </div>


    </div>
    <div class="linea_texto_direccion">

        <div style="font-size: 14px;">
            {{ $domicilio }}<br>
        </div>
    </div>
    <div class="linea_texto_telefono">

        <div>
            {{ $telefono }} REF. {{ $telefono_referencia }}
        </div>
    </div>
    <div class="grupo_sanguineo">
        {{ $grupo_sanguineo }}
    </div>

    <div class="usuario">
        {{ $usuario->email }}
    </div>

    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="150" height="150" class="qr" />
    <!-- <div class="datos_referencia ">
        {{ $nombre_referencia }} <br />
        <strong class="color_campo">Cel.:</strong>
        {{ $telefono_referencia }}  
    </div> -->

</div>