<div class="modal fade" id="UbicacionVerTitular" tabindex="-1" role="dialog"
    aria-labelledby="UbicacionVerTitularLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div style="display: none;">
                            <input type="hidd" id="lat" name="lat" disabled
                                wire:model="d_latitud" class="form control">

                            <input type="text" id="lon" name="lon" disabled
                                wire:model="d_longitud" class="form control">
                        </div>
                        <!--DATOS REFERENCIALES-->
                        <div class="card">
                            <div class="card-header"
                                style="background-color:#0872b8; color:white"><b>UBICACION
                                    TITULAR
                                </b></div>
                            <div class="card-body">
                                <div id="map"
                                    style="height: 400px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var latitud, longitud;

    window.addEventListener('ModalAbrirUbicacionTitular', event => {
        $('#UbicacionVerTitular').modal('show');
        latitud = parseFloat(document.getElementById('lat').value);
        longitud = parseFloat(document.getElementById('lon').value);


        let map;
        let circle;
        let marker;

        async function initMap() {
            const {
                Map,
                Circle,
                InfoWindow
            } = await google.maps.importLibrary("maps");

            const location = {
                lat: latitud,
                lng: longitud
            };

            map = new Map(document.getElementById("map"), {
                center: location,
                zoom: 19,
            });

            // Añadir círculo
            circle = new Circle({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
                map: map,
                center: location,
                radius: 25, // Radio en metros
            });

            // Añadir marcador
            marker = new google.maps.Marker({
                position: location,
                map: map,
                title: '¡Ubicacion Encontrada!',
            });

            const infoWindow = new InfoWindow({
                content: " <b style:'color:green'> Aquí se encuentra la ubicación del </b> <br> Direccion: Zona Direcion: ",
                position: location,
            });

            // Cambiar propiedades después de 1 segundo
            setTimeout(() => {
                circle.setOptions({
                    strokeColor: "#0000FF",
                    fillColor: "#0000FF",
                    radius: 50,
                });
                infoWindow.open(map);

                // Añadir clase para parpadeo
                document.getElementById('blinkingText').classList.add('blinking');
            }, 5000);
        }

        initMap();

    });
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZ0Qhk2HbBMXGwf4AWFElxR25GMj-E-5U&callback=initMap&libraries=places"
    async defer></script>