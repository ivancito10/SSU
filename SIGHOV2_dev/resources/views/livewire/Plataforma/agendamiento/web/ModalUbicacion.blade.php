<div class="modal fade" id="UbicacionSeguroInterior"  tabindex="-1"
    aria-labelledby="UbicacionSeguroInteriorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" id="lat" wire:model="d_latitud" class="form control">

                        <input type="hidden" id="lon" wire:model="d_longitud" class="form control">

                        <!--DATOS REFERENCIALES-->
                        <div class="card">
                            <div class="card-header" style="background-color:#0872b8; color:white"><b>UBICACION SEGURO
                                    DEL INTERIOR
                                </b></div>
                            <div class="card-body">

                                <div class="row" id="collapseExample">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                        <div id="map" style="height: 400px; width: 100%;"></div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar Modal</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>

<script>

    var latitud, longitud, nombre_seguro, nombre_zona, nombre_direccion, numero;

    window.addEventListener('ModalAbrirUbicacion', event => {

        $('#UbicacionSeguroInterior').modal('show');

        let map;
        let circle;
        let marker;

        async function initMap() {
            const { Map, Circle, InfoWindow } = await google.maps.importLibrary("maps");

            const location = { lat: -16.511528773073216, lng: -68.12371415377217 };

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
                radius: 25,  // Radio en metros
            });

            // Añadir marcador
            marker = new google.maps.Marker({
                position: location,
                map: map,
                title: '¡Ubicacion Encontrada!',
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