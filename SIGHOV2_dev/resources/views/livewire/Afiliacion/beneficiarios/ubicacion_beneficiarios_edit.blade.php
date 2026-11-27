<div>
    <div class="row" id="collapseExample">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="ubicacion_recogida">Ubicación de Recogida </label>
            <input type="text" id="ubicacion_recogida" class="form-control" placeholder="Escribe la ubicación...">
            <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" class="form-control" name="latitud" id="latitud">
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" class="form-control" name="longitud" id="longitud">
        </div>
    </div>
</div>


<script>

    var map;
    var marker;
    var geocoder;
    var infowindow;

    function initMap() {
        var center = { lat: -16.5037, lng: -68.1312 }; // Coordenadas de La Paz, Bolivia 
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: center
        });
        marker = new google.maps.Marker({
            position: center,
            map: map,
            title: '¡Hola!'
        });
        geocoder = new google.maps.Geocoder;
        infowindow = new google.maps.InfoWindow();

        var input = document.getElementById('ubicacion_recogida');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("No se encontró la ubicación especificada");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            map.panTo(place.geometry.location);

            updateLatLongInputs(place.geometry.location.lat(), place.geometry.location.lng());

            updateHouseNumber(place.geometry.location);
        });

        google.maps.event.addListener(map, 'click', function (event) {
            
            updateLatLongInputs(event.latLng.lat(), event.latLng.lng());
            marker.setPosition(event.latLng);

            updateHouseNumber(event.latLng);
        });
    }

    function updateLatLongInputs(lat, lng) {
        console.log(lat, lng);
        var d_lat = ''+lat; 
        var d_lng = ''+lng; 
        
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        //$wire.$set('latitud', 5)

        Livewire.dispatch('recibirLonLat', [d_lat, d_lng]);
    }

    function updateHouseNumber(location) {
        geocoder.geocode({ 'location': location }, function (results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    var houseNumber = getHouseNumber(results[0]);
                    var contentString;
                    if (houseNumber !== '') {
                        contentString = '<span style="color: black;">' +
                            '<p>Número de casa: ' + houseNumber + '</p>' +
                            '</span>';
                    } else {
                        contentString = '<span style="color: black;">' +
                            '<p>No se encontró un número de casa</p>' +
                            '</span>';
                    }
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                } else {
                    window.alert('No se encontraron resultados');
                }
            } else {
                window.alert('Geocoder falló debido a: ' + status);
            }
        });
    }

    function getHouseNumber(result) {
        var houseNumber = '';
        for (var i = 0; i < result.address_components.length; i++) {
            var component = result.address_components[i];
            if (component.types.includes('street_number')) {
                houseNumber = component.long_name;
                break;
            }
        }
        return houseNumber;
    }
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZ0Qhk2HbBMXGwf4AWFElxR25GMj-E-5U&callback=initMap&libraries=places" async defer></script>

