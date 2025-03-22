<x-app-layout>


        <!-- Mensaje de éxito -->
        @if(session('success'))
        <div class="notification is-success">
            {{ session('success') }}
        </div>
        @endif


        <form id="form-map-update" action="#" method="POST">

            @csrf
            <!-- Campo de Selección -->
           
            <div class="columns is-multiline">
                <div class="column ">
                    <div class="field">
                        <label for="airportSelect" class="label">Select Airport</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="airportSelect" name="airport">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column ">
                    <div class="field">
                        <label class="label">Alias</label>
                        <div class="control">
                            <input class="input" id="aliasMap" type="text" name="aliasMap" value="{{ $map[0]['pvr_map_alias'] }}" placeholder="Alias">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="field">
                <div id="map" data-map="{{ $id }}" data-map-file="{{ $map[0]['pvr_map_filename'] }}" class="box" style="height: 600px;  margin-top: 20px;"></div>
            </div>


            <button type="submit" id="btnUpdateMap" class="button  mt-3 p-2 btn-update">
                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="80" height="80" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m7 17.013l4.413-.015l9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583zM18.045 4.458l1.589 1.583l-1.597 1.582l-1.586-1.585zM9 13.417l6.03-5.973l1.586 1.586l-6.029 5.971L9 15.006z" />
                    <path fill="currentColor" d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2" />
                </svg>
                <span class="ml-1">{{ __('Update Map') }}</span>
                
            </button>

        </form>
        @push('scripts')

        <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&callback=initMap&v=3.exp"></script>
        <script>
        document.addEventListener('DOMContentLoaded', async () => {


            // Convertir datos PHP a un array JavaScript
            let airports = @json($airports);
            let map = @json($map);
       
            const airportSelect = document.getElementById('airportSelect');
            if (airportSelect) {
                // Añadir un primer <option> por defecto
                let defaultOption = document.createElement('option');
                // defaultOption.value = '';
                // airportSelect.appendChild(defaultOption);
                
                for (let airport of airports) {
                    try {

                        let response = await getAirport(airport.pvr_airport_api_ref);
                        if (response) {
                            // Crear un nuevo <option> para el select
                            let option = document.createElement('option');
                            option.value = response.place_id;
                            option.textContent = response.name;
                            option.setAttribute('data-airport', airport.pvr_airport_id);
                            
                            if (airport.pvr_airport_id === map[0]['pvr_airport_id']) {
                                option.selected = true;
                            }
                            airportSelect.appendChild(option);
                        }
                    } catch (error) {
                        console.error('Error obteniendo detalles del aeropuerto:', error);
                    }
                }
                if (airportSelect.tagName.toLowerCase() === 'select') {
                    // Añade el event listener para el cambio en el select
                    airportSelect.addEventListener('change', function() {

                        // Verifica que el valor del select no esté vacío o nulo
                        if (airportSelect.value != '' && airportSelect.value != null) {
                            // Llama a la función que quita la clase 'danger'
                            removeDangerClass(airportSelect);
                        }
                    });
                }
            }
            

            const btnUpdateMap = document.getElementById('btnUpdateMap');
            if (btnUpdateMap) {
                btnUpdateMap.addEventListener("click", (e) => {
                    updateMap(e);
                });
            }

        });

        function getAirport(id_airport) {

            return new Promise((resolve, reject) => {
                let service = new google.maps.places.PlacesService(document.createElement('div'));
                const request = {
                    placeId: id_airport,
                };

                service.getDetails(request, function(details, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        let data = {
                            "place_id": details.place_id,
                            'name': details.name
                        };

                        resolve(data);
                    } else {
                        reject(status);
                    }
                });
            });
        }



        let infoWindow = null; 
        let currentPolygon = null; // Variable para almacenar el polígono actual
        let polygons = [];
        let selectedPolygon = null;
        
        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: {
                            lat: 35.0,
                            lng: -40.0
                        },
                        zoom: 3,
            });

            // Cargar los polígonos guardados (en formato GeoJSON)
            let savedPolygons = <?=$mapJson?>;
            let bounds = new google.maps.LatLngBounds();
            
            // Mostrar los polígonos previamente guardados
            for (let feature of savedPolygons.features) {
                let coordinates = feature.geometry.coordinates[0].map(coord => ({ lat: coord[1], lng: coord[0] }));

                let polygon = new google.maps.Polygon({
                    paths: coordinates,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#00FF00",
                    fillOpacity: 0.35,
                    editable: true,
                    draggable: true
                });
                
                // Agregar propiedades del GeoJSON (si existen)
                polygon.properties = feature.properties; // Cargar propiedades

                polygon.setMap(map); // Agregar el polígono al mapa
                polygons.push(polygon); // Guardarlo en el array de polígonos

                // Extender los límites para incluir el polígono actual
                coordinates.forEach(coord => bounds.extend(coord));

                // Evento para editar el polígono al hacer clic
                google.maps.event.addListener(polygon, 'click', function () {
                    if (selectedPolygon) {
                        selectedPolygon.setEditable(false);
                    }
                    selectedPolygon = polygon;
                    selectedPolygon.setEditable(true);
                });

                // Evento para eliminar el polígono con clic derecho
                google.maps.event.addListener(polygon, 'rightclick', function () {
                    polygon.setMap(null); // Eliminar del mapa
                    polygons = polygons.filter(p => p !== polygon); // Eliminar del array
                });


                google.maps.event.addListener(polygon, 'click', function(event) {
                    currentPolygon = polygon; 
                    showInfoWindow(event.latLng, map); 
                });
            }

            // Ajustar el mapa a los límites de todos los polígonos
            if (!bounds.isEmpty()) {
                map.fitBounds(bounds);
                // Espera un breve momento para que el mapa ajuste el zoom automáticamente
                // google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                //     // Luego ajusta manualmente el nivel de zoom para acercar un poco más
                //     map.setZoom(map.getZoom() + .6); // Aumenta el nivel de zoom en 2 pasos
                // });
            }

            // Configurar el Drawing Manager
            const drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ["polygon"],
                },
                polygonOptions: {
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#00FF00",
                    fillOpacity: 0.35,
                    editable: true,
                    draggable: true,
                },
            });

            drawingManager.setMap(map);

            // Evento para capturar las coordenadas cuando se dibuja un nuevo polígono
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
                if (event.type === google.maps.drawing.OverlayType.POLYGON) {
                    const newPolygon = event.overlay;

                    const path = newPolygon.getPath(); // Obtener la ruta (los puntos) del polígono
                    // Validar si el polígono tiene al menos 3 puntos
                    const validatePolygonPoints = () => {
                        if (path.getLength() < 3) {
                            Swal.fire({
                                text: "The polygon must have at least 3 points and be properly closed. If it does not meet these requirements, it will be automatically removed.",
                                icon: "error",
                                confirmButtonColor: "#4CAF50",
                            });
                            
                            newPolygon.setMap(null); // Eliminar el polígono del mapa

                            // Restablecer el DrawingManager al modo de dibujo POLYGON
                            drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
                            
                            return false;
                        }
                        return true;
                    };

                    // Validar inmediatamente después de dibujar el polígono
                    if (!validatePolygonPoints()) {
                        return; // Detener si no cumple los criterios
                    }


                    newPolygon.properties = {}; // Inicializamos las propiedades del polígono
                    polygons.push(newPolygon); // Agregar el nuevo polígono al array

                    //--modal map
                    // Guardamos el polígono actual en la variable
                    currentPolygon = newPolygon; 
                    // Obtener la coordenada del primer punto del polígono
                    const firstLatLng = newPolygon.getPath().getAt(0);
                    // Mostrar el InfoWindow inmediatamente después de dibujar el polígono
                    showInfoWindow(firstLatLng, map);
                    remoBtnCancelInput(map);
                    google.maps.event.addListener(newPolygon, 'click', function(event) {
                        showInfoWindow(event.latLng, map); 
                    });
                    ///----fnf modal

                    //-- Listeners para cambios en los puntos del polígono
                    google.maps.event.addListener(path, 'set_at', validatePolygonPoints);
                    google.maps.event.addListener(path, 'insert_at', validatePolygonPoints);
                    google.maps.event.addListener(path, 'remove_at', validatePolygonPoints);
                    
                    // Hacer que el polígono sea editable cuando se hace clic en él
                    google.maps.event.addListener(newPolygon, 'click', function () {
                        if (selectedPolygon) {
                            selectedPolygon.setEditable(false); // Desactivar la edición en el polígono anterior
                        }
                        selectedPolygon = newPolygon;
                        selectedPolygon.setEditable(true); // Hacer editable el polígono seleccionado
                    });

                    // Habilitar la eliminación del polígono con clic derecho
                    google.maps.event.addListener(newPolygon, 'rightclick', function () {
                        newPolygon.setMap(null); // Eliminar el polígono del mapa
                        polygons = polygons.filter(p => p !== newPolygon); // Eliminarlo del array
                    });
                }
            });
        }


        // Función para mostrar la ventana de confirmación de eliminación y opción de agregar zona_id
        function showInfoWindow(latLng, map) {

            
            if (infoWindow) {
                
                infoWindow.close();
            }
            // Crear el contenido del InfoWindow
            const contentString = `
                <div>
                    <input type="text" id="zona" name="zona"value="${currentPolygon.properties.name ? currentPolygon.properties.name : ''}" placeholder="Enter zone"><br><br>
                    <button class="button" onclick="saveZona(event)">Save</button>
                    <button class="button" onclick="clearInputZona(event)">Clear</button>
                    <button class="button" onclick="closeInfoWindow(event)">Cancel</button>
                </div>
            `;
            // Crear una nueva InfoWindow
            infoWindow = new google.maps.InfoWindow({
                content: contentString,
                position: latLng
            });
    
            // show el InfoWindow 
            infoWindow.open(map);
        }


        function saveZona() {
            event.preventDefault();
            const zonaInput = document.getElementById('zona');
            const zona = zonaInput.value.trim();

            const regex = /^[a-zA-Z0-9\s]+$/;
            // Limpiar el estado de error si lo tenía previamente
            zonaInput.classList.remove('is-danger');

            if (!zona) {
                zonaInput.classList.add('is-danger'); 
                zonaInput.focus(); 
                return;
            } else if (!regex.test(zona)) {
                zonaInput.classList.add('is-danger'); 
                zonaInput.focus();
                return;
            }
            zonaInput.classList.remove('is-danger');
            if (currentPolygon) {
                currentPolygon.properties.name = zona;
            }

            closeInfoWindow(event);
        }

        function closeInfoWindow(event) {
            event.preventDefault();
            if (infoWindow) {
                infoWindow.close();
            }
        }
    
        function clearInputZona(e){
            event.preventDefault();
            
            const zona = document.getElementById('zona');
            if (zona && zona.value !='') {
                
                zona.value = '';
            }
        }

        function remoBtnCancelInput(map){
            
            // Usar MutationObserver para ocultar el botón de cerrar
            const observer = new MutationObserver(() => {
                const closeButton = document.querySelector('.gm-ui-hover-effect');
                if (closeButton) {
                    closeButton.style.display = 'none'; 
                    observer.disconnect(); 
                }
                const cancelButton = Array.from(document.querySelectorAll('.button')).find(button => button.textContent === 'Cancel');
    
                if (cancelButton) {
                    cancelButton.style.display = 'none'; // Ocultar el botón de cancelar
                    observer.disconnect(); // Desconectar el observer una vez que se oculta el botón
                }

            });
            // Iniciar el observador en el contenedor principal del mapa
            const mapDiv = map.getDiv();
            observer.observe(mapDiv, { childList: true, subtree: true });
        }

        function updateMap(e) {

            e.preventDefault();

            const isValid = validateAliasInput(); 
            if (isValid) {
                const aliasInput = document.getElementById('aliasMap');

                const airportSelect = document.getElementById('airportSelect');
                let selectedOption = airportSelect.options[airportSelect.selectedIndex];
                let airport = selectedOption.getAttribute('data-airport');

                let aliasMap = aliasInput.value;
                let ruleProperties = [];
                if (polygons.length > 0) {
                    const PolygonJson = {
                        type: "FeatureCollection",
                        features: polygons.map((polygon, index)=> {
                            
                            const path = polygon.getPath().getArray().map(point => [point.lng(), point.lat()]);
                            // Asegurarse de que el primer y último punto sean los mismos
                            if (path.length > 0 && (path[0][0] !== path[path.length - 1][0] || path[0][1] !== path[path.length - 1][1])) {
                                path.push(path[0]); // Añadir el primer punto al final para cerrar el polígono
                            }
                            // Obtener propiedades del polígono, si no tiene, asignar una por defecto
                            let properties = polygon.properties || {};
                            // Verificar si el ID está presente, si no, generar uno
                            if (!properties.id) {
                                properties.id = generateUUID();
                            }
                            if (properties && !properties.name) {
                               
                                Swal.fire({
                                    text: "A polygon without a defined area has been detected. The area is a required field, so the polygon will be automatically removed. If you still need this polygon, please add it again ensuring it has a valid area.",
                                    icon: "error",
                                    confirmButtonColor: "#4CAF50",
                                });
                                
                                polygon.setMap(null);
                                polygons = polygons.filter(p => p !== polygon);

                                ruleProperties.push(true);
                                polygons.splice(index, 1);
                                closeInfoWindow(event);
                                
                                return null; 
                            }

                            return {
                                type: "Feature",
                                geometry: {
                                    type: "Polygon",
                                    coordinates: [path]
                                },
                                properties: properties
                            };
                        }).filter(feature => feature !== null) // Eliminar cualquier polígono que haya sido excluido por la validación
                    };


                    let GeneratePolygon = {
                        "airport": airport,
                        "aliasMap":aliasMap,
                        "colections": [JSON.stringify(PolygonJson, null, 2)]
                    }

                    if (!ruleProperties.includes(true)) {
                        updateMapBd(GeneratePolygon);
                    }
                    ruleProperties = [];

                    
                } else {

                    Swal.fire({
                        text: "Please draw a polygon first.",
                        icon: "error",
                        confirmButtonColor: "#4CAF50",
                    });

                }

            }

        }


        const aliasInput = document.getElementById('aliasMap');
        const map = document.getElementById('map');
        
        function validateAliasInput() {
            let airport = map.getAttribute('data-airport');
            // Verifica el estado del aeropuerto
            if (airport === '' || airport === null) {
                
                const airportSelect = document.getElementById('airportSelect');

                // Verifica si no hay una opción seleccionada
                if (airportSelect.value === '' || airportSelect.value === null) {
                    if (airportSelect.tagName.toLowerCase() === 'select') {
                        const selectWrapper = airportSelect.closest('.select');
                        if (selectWrapper) {
                            Swal.fire({
                                text: "Required Airport",
                                icon: "error",
                                confirmButtonColor: "#4CAF50",
                            });
                            selectWrapper.classList.add('is-danger'); // Agrega la clase is-danger
                            airportSelect.focus(); // Enfoca el select
                            return false; // Indica que la validación falló
                        }
                    }
                } else {
                    // Si hay una opción seleccionada, quita la clase is-danger
                    const selectWrapper = airportSelect.closest('.select');
                    if (selectWrapper) {
                        selectWrapper.classList.remove('is-danger'); // Elimina la clase is-danger
                    }
                }
            }

            // Validación del alias
            const validTextPattern = /^[a-zA-Z\s]*$/; 
            if (aliasInput.value.trim() === '' || !validTextPattern.test(aliasInput.value)) {

                Swal.fire({
                    text: "Required Alias",
                    icon: "error",
                    confirmButtonColor: "#4CAF50",
                });

                aliasInput.classList.add('is-danger'); 
                return false; 
            } else {
                aliasInput.classList.remove('is-danger'); 
            }

            return true; // Si todas las validaciones pasan
            
        }



        function updateMapBd(mapJSON) {

           
            let map_form_update = document.getElementById('form-map-update');
            
            const map_data = document.getElementById('map');
            let map = map_data.getAttribute('data-map');
           mapJSON.file = map_data.getAttribute('data-map-file')

            fetch(`/map/update/${map}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(mapJSON)
                })
                .then(response => response.json())
                .then(data => {

                    if (data.status == 200) {
                        map_form_update.reset();
                        polygons.forEach(polygon => polygon.setMap(null));
                        polygons = []; 

                        Swal.fire({
                            title: "Successful Update",
                            text: "The Map identified as "+mapJSON.aliasMap+" has been successfully upgraded",
                            icon: "success",
                            confirmButtonText: "OK",
                            showConfirmButton: false,
                            confirmButtonColor: "#4CAF50", 
                            timer: 1500,
                        }).then(() => {
                            
                            window.location.href = '/map';
                        });
                        
                    }else{
                       
                        alert(data.message);
                        map_form_update.reset();
                        if (data.status==409) {
                           window.location.href = '/map'; 
                        }
                        // Limpiar los polígonos después de guardar
                        polygons.forEach(polygon => polygon.setMap(null)); // Remover los polígonos del mapa
                        polygons = []; 
                        
                    }

                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }

        function generateUUID() { // Public Domain/MIT
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        //rules
        function removeDangerClass(element) {

            if (element.tagName.toLowerCase() === 'select') {
                const selectWrapper = element.closest('.select');
                if (selectWrapper && element.value !== '') {
                    selectWrapper.classList.remove('is-danger');
                }
            } else {
                if (element.value.trim() !== '') {

                    element.classList.remove('is-danger');
                }
            }

        }

        </script>
        @endpush
</x-app-layout>