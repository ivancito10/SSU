<div class="row" id="collapseExample">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="ubicacion_recogida">Ubicación de Recogida OpenStreetMaps</label>
        <input type="text" id="ubicacion_recogida" class="form-control" placeholder="Escribe la ubicación..." autocomplete="off" onkeypress="verificarEnter(event)" />
        <ul id="suggestions" class="list-group"></ul>

        <div id="map" style="height: 400px; width: 100%;"></div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="hidden" class="form-control" name="latitud" id="latitud">
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <input type="hidden" class="form-control" name="longitud" id="longitud">
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
<!-- <link rel="stylesheet" href="{{ asset('leaflet/dist/leaflet.css') }}" />
<script src="{{ asset('leaflet/dist/leaflet.js') }}"></script> -->


<script>
    let marker;
    let map;

    function initializeMap() {

        //console.log('Inicializando el mapa');

        // Inicializa el mapa en OpenStreetMap
        map = L.map('map').setView([-16.497995278526176, -68.11867967247963], 15);
        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        // Evento de clic en el mapa para capturar coordenadas
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
            var d_lat = '' + lat;
            var d_lng = '' + lng;
            Livewire.dispatch('recibirLonLat', [d_lat, d_lng]);


            if (marker) {
                map.removeLayer(marker);
            }
            //console.log('entra 2');
            marker = L.marker([lat, lng]).addTo(map);
                //.bindPopup(`Latitud: ${lat}, Longitud: ${lng}`).openPopup();

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    let address = data.address;
                    let houseNumber = address.house_number ? address.house_number : "No se encontró número de casa";
                    marker.bindPopup(`Latitud: ${lat}<br> Longitud: ${lng}<br> Número de casa: ${houseNumber}`).openPopup();
                })
                .catch(error => console.log('Error al obtener la dirección:', error));
        });

    }

    function verificarEnter(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evita que el formulario se envíe si está dentro de un formulario
            buscarUbicacion();
        }
    }

    //buscar ubicacion
    function buscarUbicacion() {
        let direccion = document.getElementById('ubicacion_recogida').value;
        let suggestionsList = document.getElementById('suggestions');

        // Limpiar las sugerencias anteriores
        suggestionsList.innerHTML = '';

        // Si el campo de entrada está vacío, no hacemos la búsqueda
        if (direccion.length < 3) return;

        // Primero, busca en la ciudad de La Paz
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${direccion}, La Paz&countrycodes=BO`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    mostrarSugerencias(data);
                } else {
                    // Si no se encontraron resultados en La Paz
                    suggestionsList.innerHTML = '';
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${direccion}&countrycodes=BO`)
                        .then(response => response.json())
                        .then(dataBolivia => {
                            if (dataBolivia.length > 0) {
                                mostrarSugerencias(dataBolivia);
                            } else {
                                // Mostrar mensaje si no se encontraron coincidencias
                                let noResults = document.createElement('li');
                                noResults.classList.add('list-group-item');
                                noResults.textContent = 'No se encontraron coincidencias';
                                suggestionsList.appendChild(noResults);
                            }
                        })
                        .catch(error => {
                            console.error('Error en la búsqueda de la ubicación en Bolivia:', error);
                        });
                }
            })
            .catch(error => {
                console.error('Error en la búsqueda de la ubicación en La Paz:', error);
            });
    }

    // Función para mostrar sugerencias en la lista
    function mostrarSugerencias(data) {
        let suggestionsList = document.getElementById('suggestions');

        data.slice(0, 5).forEach(item => {
            let suggestionItem = document.createElement('li');
            suggestionItem.classList.add('list-group-item', 'list-group-item-action');
            suggestionItem.textContent = item.display_name;

            // Al hacer clic en una sugerencia, centrar el mapa
            suggestionItem.onclick = function() {
                let lat = item.lat;
                let lon = item.lon;

                // Centrar el mapa en la ubicación seleccionada
                map.setView([lat, lon], 15);

                // Si hay un marcador existente, eliminarlo
                if (marker) {
                    map.removeLayer(marker);
                }

                // Agregar un nuevo marcador en la ubicación sseleccionada
                marker = L.marker([lat, lon]).addTo(map)
                    .bindPopup(`Ubicación: ${item.display_name}`).openPopup();

                // Actualizar los campos ocultos de latitud y longitud
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lon;

                // Limpiar las sugerencias después de seleccionar
                suggestionsList.innerHTML = '';
            };

            // Añadir la sugerencia a la lista
            suggestionsList.appendChild(suggestionItem);
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
        $('#collapseExample').on('shown.bs.collapse', function() {

            // Verificamos si ya existe una instancia del mapa
            if (map !== undefined) {
                // Destruimos el mapa si ya existe
                //console.log('Mapa eliminado:', map);
                map.remove(); // Elimina el mapa
                map = undefined;
                document.getElementById('map').innerHTML = ""; // Limpia el contenedor del mapa
            }

            // Esperamos un corto periodo para asegurar que el colapsable esté completamente visible
            setTimeout(function() { // Llamamos a la función para crear el nuevo mapa
                if (!map) {
                    //console.log('mapa inicializado correctamente');
                    initializeMap();
                    map.invalidateSize(); // Ajusta el tamaño si el contenedor cambia
                }
            }, 100); // Retraso corto para asegurarnos de que el contenedor esté completamente renderizado
        });
    });

</script>
