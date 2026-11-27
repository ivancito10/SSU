<style>
    * {
        margin: 0;
        padding: 0;
    }

    .fondo_cara {
        background-image: url("images/carnets_final/celeste.png");
        background-size: cover;
        background-repeat: no-repeat;
        color: white;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    .fondo_escudo {
        position: absolute;
        background-image: url("images/carnets/ssu3.png");
        background-size: contain;
        /* Hacer que la imagen ocupe toda la hoja */
        background-repeat: no-repeat;
        opacity: 50%;
        width: 50%;
        height: 100%;
        margin-left: -40px;
        margin-top: 20px;
        z-index: 0;
    }

    .datos_persona {
        position: absolute;
        margin-top: 15%;
        margin-left: 65%;
    }

    .nombres {
        /* background-color: blueviolet; */
        font-family: Helvetica;
        font-size: 1.7em;
        text-transform: capitalize;
        text-align: center;
    }

    .apellidos {
        /* background-color: blueviolet; */
        font-family: Helvetica;
        font-size: 1.7em;
        text-transform: capitalize;
        text-align: center;
    }

    .institucion_nombre {
        position: absolute;
        margin-top: 30%;
        margin-left: 49%;
        font-weight: bold;
    }

    .campo_institucion {
        display: inline-block;
        text-align: right;
        margin-right: 10px;
        font-size: 1.2em;
    }

    .datos_insitucion {
        display: inline-block;
        font-size: 1.2em;
    }

    .datos_insitucion {
        display: inline-block;
        font-size: 1.2em;
    }

    .datos_cod_afiliado {
        position: absolute;
        margin-top: 42%;
        margin-left: 38%;
        font-weight: bold;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1.4em;
        width: 200px;
    }

    .tipo_asegurado {
        text-align: center;
    }

    strong {
        color: #ccfbff;
    }

    .datos_gerente {
        position: absolute;
        margin-top: 56%;
        margin-left: 75%;
        font-size: 0.9em;
        font-style: italic;
        text-align: center;
        text-transform: capitalize;

    }

    .fechas {
        position: absolute;
        margin-top: 58%;
        margin-left: 2%;
        font-size: 0.9em;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
    }

    .foto_firma {
        position: absolute;
        background-color: none;
        width: 40%;
        height: 40%;
        padding: 0px;
        right: 0px;
        bottom: 15px;
        margin-right: -50px;
        z-index: 5;
    }

    .foto_afiliado {
        position: absolute;
        width: 45%;
        height: 100%;
        margin-left: -50px;
        margin-top: -20px;
        z-index: 1;
    }

    .page-break {
        page-break-after: always;
    }

    .fondo_espalda {
        background-image: url("images/carnets/titular/espalda02.png");
        background-size: cover;
        background-repeat: no-repeat;
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
        margin-left: 2px;
        bottom: 2px;
    }

    .datos_referencia {
        margin-top: 38%;
        margin-left: 160px;
        width: 350px;
    }

    .emergencia_ssu {
        position: absolute;
        margin-left: 160px;
        bottom: 10px;

    }

    .texto {
        display: inline-block;
        margin-left: 120px;
        width: 450px;
    }

    .titulo {
        position: absolute;
        display: inline-block;
        text-align: left;
        top: 0px;
    }

    .linea_texto {
        position: relative;
    }

    .emergencia {
        position: absolute;
        bottom: 155px;
        margin-left: 10px;
        font-family: "Comic Sans MS", cursive, sans-serif;
    }

    .tipo_letra {
        font-size: 1.2em;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<div class="fondo_escudo"></div>

<img src="data:image/png;base64,{{ $foto_afiliado }}" class="foto_afiliado" />
<div class="fondo_cara">
    @if (isset($foto_firma_gral))
        <img src="data:image/png;base64,{{ $foto_firma_gral }}" class="foto_firma" />
    @endif
    <div class="datos_persona">
        <div class="nombres">{{ $nombres }}</div>
        <div class="apellidos">
            {{ $p_apellido . ' ' . $s_apellido }}
        </div>
    </div>
    <div class="institucion_nombre">
        <div class="campo_institucion">
            <div><strong></strong></div>
            @if ($sigla_institucion_transferencia != null)
                <div><strong>Transf</strong>:</div>
            @endif
        </div>
        <div class="datos_insitucion">
            <p>{{ $sigla_institucion }}</p>
            @if ($sigla_institucion_transferencia != null)
                <p>{{ $sigla_institucion_transferencia }}</p>
            @endif
        </div>
    </div>
    <div class="datos_cod_afiliado">
        <div class="linea_texto">
            <div class="titulo_cara">Mat. </div>
            <div class="texto_cara">
                {{ $matricula_seguro }}
            </div>
        </div>
        <div class="linea_texto">
            <div class="titulo_cara">C.I.</div>
            <div class="texto_cara">
                {{ $ci }}
            </div>
        </div>
        <div class="tipo_asegurado">
            <strong>{{ $tipo_asegurado }}</strong>
        </div>
    </div>
    <div class="datos_gerente">
        {{ $nombre_gral }}
        <p>Gerente General</p>
    </div>
    <div class="fechas">
        <div><strong>VALIDO HASTA: </strong> {{ $fecha_vencimiento }}</div>
    </div>
</div>

<div class="page-break"></div>

<div class="fondo_espalda">
    <div class="datos">
        <div class="linea_texto">
            <div class="titulo">
                <strong>Alergias: </strong>
            </div>
            <div class="texto">
                {{ $alergias }}
            </div>
        </div>
        <div class="linea_texto">
            <div class="titulo">
                <strong>Domicilio: </strong>
            </div>
            <div class="texto">
                {{ $domicilio }}<br>
            </div>
        </div>
        <div class="linea_texto">
            <div class="titulo">
                <strong>Telefono: </strong>
            </div>
            <div class="texto">
                {{ $telefono }}
            </div>
        </div>
    </div>
    <div class="emergencia tipo_letra">
        <strong>
            En caso de emergencia comunicarse con:<br />
        </strong>
    </div>

    <div class="grupo_sanguineo">
        <p>{{ $grupo_sanguineo }}</p>
    </div>
    <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR" width="150" height="150"
        class="qr" />
    <div class="datos_referencia tipo_letra">
        {{ $nombre_referencia }} - {{ $telefono_referencia }}
    </div>
    <div class="emergencia_ssu tipo_letra">
        <strong>Tel. Emergencias SSU:</strong><br />
        71559744 - 2434262
    </div>
</div>