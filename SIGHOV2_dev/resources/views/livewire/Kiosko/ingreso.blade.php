<div>

    <!--TIPOS DE BUSQUEDA-->
    <div class="row">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-4">
            <div class="card-vista-comun">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <strong style="color:white; font-size:32px">BUSQUEDA POR SCANNER </strong>
                            <p style="color:white; font-size:25px"> Acerque el código QR de su carnet al scanner </p>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: flex; align-items: center;">
                            <!-- Aquí va el botón con icono de lupa -->
                            <input class="form-control" type="text" id="codigo_qr"
                                style="width: calc(100% - 20px); margin-left: 5px; background-color:white; border-radius:10px; padding:12px;
                                 font-size:18px; 
                                 font-family: 'text-security-disc';
                                  -webkit-text-security: disc;"
                                placeholder="Ingreso datos mediante scanner ..." autocomplete="off" autocorrect="off"
                                spellcheck="false" wire:model="codigo_qr" wire:change="buscarPorQr" />
                        </div>
                    </div>
                    <div wire:loading wire:target="buscarPorQr">
                        <div class="spinner-grow text-warning" role="status">
                        </div>
                        <b><i>Cargando datos espere porfavor...</i></b>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 text-center">
            <br>
            <p style="font-style: italic; font-size: 25px; color: white; margin-bottom: 0;">
                <b>Toque este botón para buscar manualmente por </b>
            </p>
            <p style="font-style: italic; font-size: 25px; color: white; margin-top: 0;">
                <b> su nombre o número de carnet de identidad.</b>
            </p>
            <x-manual-button class="p-4 mt-3" wire:click="de1IngresoA2Busqueda">
                BUSQUEDA MANUAL <br><span style="font-size:18px;">(Presione aquí)</span>
            </x-manual-button>
        </div>

        <div class="col-lg-1">
        </div>
    </div> <br><br><br>
    <div class="col-12 text-center">
        <div class="logo-halo-wrap">
            <img src="{{ asset('images/LOGO_SIGHOV2.png') }}" alt="Logo SIGHO V2"
                class="img-fluid logo-base logo-animado">
        </div>
    </div>

    <div class="col-12 text-center">
        <p class="mensaje-animado">
            Innovando por tu bienestar, conectando tecnología y salud para mejorar la experiencia de nuestros
            asegurados.
        </p>
    </div>

</div>
<script>
    // para que este focus el buscador qr
    //  document.addEventListener('DOMContentLoaded', function() {
    //      document.getElementById('codigo_qr').focus();
    //  });
    //
    //  document.addEventListener('focusear', function() {
    //      var input = document.getElementById('codigo_qr');
    //      if (input) {
    //          input.focus();
    //      } else {
    //          // Opcional: intenta de nuevo después de un pequeño retraso
    //          setTimeout(function() {
    //              var inputRetry = document.getElementById('codigo_qr');
    //              if (inputRetry) inputRetry.focus();
    //          }, 100);
    //      }
    //  });
</script>
