<div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label for="ubicacion_recogida_2">Ubicación actual:</label>
            <input type="hidden" id="ubicacion_recogida_2" class="form-control" placeholder="Escribe la ubicación de entrega...">
            <input type="hidden" name="d_latitud" id="d_latitud" wire:model.live="d_latitud" placeholder="Latitud">
            <input type="hidden" name="d_longitud" id="d_longitud" wire:model.live="d_longitud" placeholder="Longitud">
            <div id="map2" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div><script>
    var map2;
    var marker2;
    var circle2; // Variable para el círculo
    var infoWindow2; // Variable para la ventana de información

    function initMap2() {
        var lat = parseFloat(document.getElementById('d_latitud').value) || -16.499813166806344;
        var lng = parseFloat(document.getElementById('d_longitud').value) || -68.13529007657472;
        var center = { lat: lat, lng: lng };

        map2 = new google.maps.Map(document.getElementById('map2'), {
            zoom: 17,
            center: center
        });
        
        marker2 = new google.maps.Marker({
            position: center,
            map: map2,
            title: 'Ubicación Actual'
        });

        // Añadir el círculo
        circle2 = new google.maps.Circle({
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
            map: map2,
            center: center,
            radius: 40, // Radio en metros
        });

        // Añadir ventana de información
        infoWindow2 = new google.maps.InfoWindow({
            content: " <b style='color:green'> Aquí se encuentra la ubicación del asegurado </b> <br> ",
            position: center,
        });

        // Cambiar propiedades del círculo y abrir la ventana de información después de 5 segundos
        setTimeout(() => {
            circle2.setOptions({
                strokeColor: "#0000FF",
                fillColor: "#0000FF",
                radius: 80,
            });
            infoWindow2.open(map2);

            // Añadir clase para parpadeo
            document.getElementById('blinkingText').classList.add('blinking');
        }, 5000);
    }

    // Función para actualizar los inputs de latitud y longitud
    function updateLatLngInputs(lat, lng) {
        @this.set('d_latitud', lat);
        @this.set('d_longitud', lng);
    }

    // Escuchar los cambios de los inputs de latitud y longitud
    document.addEventListener('livewire:load', function () {
        // Cuando cambien los valores de los inputs, actualiza el centro del mapa
        Livewire.on('updatedLatLng', () => {
            var lat = parseFloat(@this.get('d_latitud'));
            var lng = parseFloat(@this.get('d_longitud'));

            if (!isNaN(lat) && !isNaN(lng)) {
                var newLocation = new google.maps.LatLng(lat, lng);
                map2.setCenter(newLocation);
                marker2.setPosition(newLocation);
                circle2.setCenter(newLocation); // Actualiza el centro del círculo
            }
        });

        // También actualiza cuando el usuario ingrese manualmente en los inputs
        document.getElementById('d_latitud').addEventListener('input', function() {
            var lat = parseFloat(this.value);
            var lng = parseFloat(document.getElementById('d_longitud').value);

            if (!isNaN(lat) && !isNaN(lng)) {
                var newLocation = new google.maps.LatLng(lat, lng);
                map2.setCenter(newLocation);
                marker2.setPosition(newLocation);
                circle2.setCenter(newLocation); // Actualiza el centro del círculo
            }
        });

        document.getElementById('d_longitud').addEventListener('input', function() {
            var lng = parseFloat(this.value);
            var lat = parseFloat(document.getElementById('d_latitud').value);

            if (!isNaN(lat) && !isNaN(lng)) {
                var newLocation = new google.maps.LatLng(lat, lng);
                map2.setCenter(newLocation);
                marker2.setPosition(newLocation);
                circle2.setCenter(newLocation); // Actualiza el centro del círculo
            }
        });
    });
</script>
