<x-app-layout>
   
    @if((!is_null($airports) || !is_null($airport)) && empty($mapData))
        <!-- Mensaje de éxito -->
        @if(session('success'))
        <div class="notification is-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="form-map" id="container-form-map">

            <form id="form-map" action="#" method="POST">
                <!-- CSRF Token para protección -->
                @csrf

               
                <div class="columns  is-multiline">

                    @if($id == null)
                    <div class="column">
                        <div class="field">
                            <label for="airportSelect" class="label">Select Airport</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select id="airportSelect" name="airport"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="column">
                        <div class="field" id="labelAirport"></div>
                    </div>
                    @endif
                    <div class="column">
                        <div class="field">
                             <label class="label">Alias</label>
                            <div class="control">
                                <input class="input" id="aliasMap" type="text" name="aliasMap" placeholder="Alias" style="width: 100%;">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Mapa -->
                <div class="field">
                    <div id="map" data-airport="{{ $id }}" class="box" style="height: 600px;  margin-top: 20px;"></div>
                </div>


                <button type="submit" id="btnSaveMap" class="button  mt-3 p-2 btn-update">
                    <svg xmlns="http://www.w3.org/2000/svg" class=" custom-icon-action"  viewBox="0 0 448 512">
                        <path fill="currentColor" d="m433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941M224 416c-35.346 0-64-28.654-64-64s28.654-64 64-64s64 28.654 64 64s-28.654 64-64 64m96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A12 12 0 0 1 320 111.48"/>
                    </svg>
                    <span class="ml-1">{{ __('Add Map') }}</span>
                
                </button>
            </form>

        </div>
        

        <!-- Modal -->
        <div class="modal" id="modal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <button class="delete" aria-label="close" id="closeModal"></button>
                </header>
                <section class="modal-card-body">
                    <video id="videoPlayer" controls autoplay >
                        <source src="{{ asset('assets/videos/how-to-create-a-map-airport-transportation.mp4') }}" type="video/mp4">
                        Tu navegador no soporta el elemento de video.
                    </video>
                </section>
            </div>
        </div>


    
    @push('scripts')
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&callback=initMap&v=3.exp"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {

            // Convertir datos PHP a un array JavaScript
            let airports = @json($airports);
            let airport = @json($airport);

            const form_map = document.getElementById('form-map');
            const container_for_map = document.getElementById('container-form-map');

            if (airport && Object.keys(airport).length > 0) {
                console.log(airport);
                form_map.classList.remove('is-hidden');
                

                if (typeof airport === 'object' && airport !== null) {
                    const labelAirport = document.getElementById('labelAirport');

                    
                    getAirport(airport.pvr_airport_api_ref).then(mapName => {

                        labelAirport.innerHTML += `
                            <label class="label">Airport </label> 
                            
                            <div class="control">
                                <input class="input" type="text" name="" value="${mapName.name}" style="width: 100%;outline: none; border: 1px solid #ccc; /* o el color que prefieras */" readonly>
                            </div>
                        `;
                    }).catch(error => {
                        console.error("Error obteniendo el aeropuerto: ", error);
                    });
                }

            } else if(airports && Object.keys(airports).length > 0){
                
                form_map.classList.remove('is-hidden');
                

                const airportSelect = document.getElementById('airportSelect');
                if (airportSelect) {
                    // Añadir un primer <option> por defecto
                    let defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    airportSelect.appendChild(defaultOption);
                    
                    for (let airport of airports) {
                        try {

                            let response = await getAirport(airport.pvr_airport_api_ref);
                            if (response) {
                                // Crear un nuevo <option> para el select
                                let option = document.createElement('option');
                                option.value = response.place_id;
                                option.textContent = response.name;
                                option.setAttribute('data-airport', airport.pvr_airport_id);
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

            }else{

                form_map.classList.add('is-hidden');

                container_for_map.innerHTML =`
                <div class="card">
                    <div class="card-content">
                        All airports already have a map assigned.
                    </div>
                </div>
                `;
            }

            const btnSaveMap = document.getElementById('btnSaveMap');
            if (btnSaveMap) {
                btnSaveMap.addEventListener("click", (e) => {
                    createMap(e);
                });
            }
            
        });




        let polygons = [];
        let infoWindow = null; 
        let selectedPolygon = null;
        let currentPolygon = null; // Variable para almacenar el polígono actual

        function initMap() {

            const map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 35.0,
                    lng: -40.0
                },
                zoom: 3,
            });

            // Configurar el Drawing Manager
            const drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ["polygon"],
                },
                polygonOptions: {
                    strokeColor: "#FF0000", // Color del contorno (rojo)
                    strokeOpacity: 0.8, // Opacidad del contorno
                    strokeWeight: 2, // Grosor del contorno en píxeles
                    fillColor: "#00FF00", // Color de relleno (verde)
                    fillOpacity: 0.35,
                    editable: true,
                    draggable: true, // Permitir arrastrar el polígono
                },
            });

            drawingManager.setMap(map);

            // Evento para capturar las coordenadas cuando se dibuja un polígono
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
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
                    
                    google.maps.event.addListener(newPolygon, 'click', function(event) {
                        showInfoWindow(event.latLng, map); 
                    });
                    ///----fnf modal
                    //-- Listeners para cambios en los puntos del polígono
                    google.maps.event.addListener(path, 'set_at', validatePolygonPoints);
                    google.maps.event.addListener(path, 'insert_at', validatePolygonPoints);
                    google.maps.event.addListener(path, 'remove_at', validatePolygonPoints);
                    // Hacer que el polígono sea editable cuando se hace clic en él
                    google.maps.event.addListener(newPolygon, 'click', function() {
                        if (selectedPolygon) {
                            selectedPolygon.setEditable(false); // Desactivar la edición en el polígono anterior
                        }
                        selectedPolygon = newPolygon;
                        selectedPolygon.setEditable(true); // Hacer editable el polígono seleccionado
                    });

                    // Habilitar la eliminación del polígono con clic derecho
                    google.maps.event.addListener(newPolygon, 'rightclick', function() {
                        newPolygon.setMap(null); // Eliminar el polígono del mapa
                        polygons = polygons.filter(p => p !== newPolygon); // Eliminarlo del array
                    });
                }
            });
        }
        // Función para mostrar el InfoWindow de los polígonos con la propiedad 'name' vacía
        function showInfoWindowsForEmptyNamePolygons(map) {
            polygons.forEach(polygon => {
                if (polygon.properties && polygon.properties.name === "") {
                    const firstLatLng = polygon.getPath().getAt(0); // Obtener la primera coordenada del polígono
                    showInfoWindow(firstLatLng, map); // Mostrar el InfoWindow
                }
            });
        }

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

        const aliasInput = document.getElementById('aliasMap');
        const map = document.getElementById('map');
        
        function validateAliasInput() {
            let airport = map.getAttribute('data-airport');
            // Verifica el estado del aeropuerto
            if (airport === '') {
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
                            airportSelect.focus(); 
                            return false; 
                        }
                    }
                } else {
                    // Si hay una opción seleccionada, quita la clase is-danger
                    const selectWrapper = airportSelect.closest('.select');
                    if (selectWrapper) {
                        selectWrapper.classList.remove('is-danger'); 
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

        function createMap(e) {

            e.preventDefault();

            const isValid = validateAliasInput(); 
            if (isValid) {
                const aliasInput = document.getElementById('aliasMap');
                const map = document.getElementById('map');

                let airport = map.getAttribute('data-airport');
                if (airport==='') {
                    const airportSelect = document.getElementById('airportSelect');
                    let selectedOption = airportSelect.options[airportSelect.selectedIndex];
                    airport = selectedOption.getAttribute('data-airport');
                }

                let aliasMap = aliasInput.value;
                let ruleProperties = [];
                let contadorPoligon = 0;
                if (polygons.length > 0) {
                    const PolygonJson = {
                        type: "FeatureCollection",
                        features: polygons.map((polygon,index) => {

                            const path = polygon.getPath().getArray().map(point => [point.lng(), point.lat()]);

                            // Verificar que el polígono tenga al menos 3 puntos
                            if (path.length < 3) {
                                // Si el polígono tiene menos de 3 puntos, eliminarlo del mapa y del array
                                polygon.setMap(null);
                                polygons = polygons.filter(p => p !== polygon);
                                return null; // Salir de la función, no agregar este polígono
                            }

                            // Asegurarse de que el primer y último punto sean los mismos
                            if (path.length > 0 && (path[0][0] !== path[path.length - 1][0] || path[0][1] !== path[path.length - 1][1])) {
                                path.push(path[0]); // Añadir el primer punto al final para cerrar el polígono
                            }

                            const polygonIndex = index + 1; // Asegurar que el índice comience en 1
                            const uniqueId = generateUUID();


                            if (!polygon.properties.zona) {
                                contadorPoligon++; 

                                // Eliminar solo este polígono del mapa
                                polygon.setMap(null);

                                // Eliminar solo este polígono de la lista
                                polygons = polygons.filter(p => p !== polygon);

                                // Opcional: Cerrar ventana de información si está abierta
                                if (typeof closeInfoWindow === "function") {
                                    closeInfoWindow(event);
                                }

                                return null; // Salir de la función para evitar procesar un polígono inválido
                            }

                            return {
                                type: "Feature",
                                geometry: {
                                    type: "Polygon",
                                    coordinates: [path]
                                },
                                properties: {
                                   id: uniqueId, 
                                   name: polygon.properties.zona // Agregar el zona a las propiedades
                                }
                            };
                        }).filter(feature => feature !== null) // Eliminar cualquier polígono que haya sido excluido por la validación
                    };

                    let GeneratePolygon = {
                        "airport": airport,
                        "aliasMap":aliasMap,
                        "colections": [JSON.stringify(PolygonJson, null, 2)]
                    }

                    let dataColections = JSON.parse(GeneratePolygon.colections);

                    if (contadorPoligon>0) {
 
                        let removedPoliigon = contadorPoligon ==1?'polygon has been':'polygons have been'
                        Swal.fire({
                            text: contadorPoligon +" "+removedPoliigon+"  a defined zone and will be removed. Please ensure all polygons have a valid zone before adding them.",
                            icon: "error",
                            confirmButtonColor: "#4CAF50", 
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (dataColections.features.length > 0) {
                                    saveBd(GeneratePolygon);
                                }

                            }
                        });
                    }else{
                        if (dataColections.features.length > 0) {
                            saveBd(GeneratePolygon);
                        }

                    }
                    

                } else {
                    
                    Swal.fire({
                        text: "Please Draw a Polygon First",
                        icon: "error",
                        confirmButtonColor: "#4CAF50", 
                            
                    });
                    
                }


            }
        }



        // Función para mostrar la ventana de confirmación de eliminación y opción de agregar zona_id
        function showInfoWindow(latLng, map) {

            if (infoWindow) {
                
                infoWindow.close();
            }
            // Crear el contenido del InfoWindow
            const contentString = `
                <div>
                    <input type="text" id="zona" name="zona"value="${currentPolygon.properties.zona ? currentPolygon.properties.zona : ''}" placeholder="Enter zone"><br><br>
                    <button class="button" onclick="saveZona(event)">Save</button>
                    <button class="button" onclick="clearInputZona(event)">Clear</button>
                    
                </div>
            `;
            // Crear una nueva InfoWindow
            infoWindow = new google.maps.InfoWindow({
                content: contentString,
                position: latLng
            });
    
            // show el InfoWindow 
            infoWindow.open(map);

            // Usar MutationObserver para ocultar el botón de cerrar
            const observer = new MutationObserver(() => {
                const closeButton = document.querySelector('.gm-ui-hover-effect');
                if (closeButton) {
                    closeButton.style.display = 'none'; // Ocultar el botón
                    observer.disconnect(); // Detener el observador
                }
            });

            // Iniciar el observador en el contenedor principal del mapa
            const mapDiv = map.getDiv();
            observer.observe(mapDiv, { childList: true, subtree: true });

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
                currentPolygon.properties.zona = zona;
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


        function generateUUID() { // Public Domain/MIT
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }
        
        function saveBd(providers) {

            let map_form = document.getElementById('form-map');
            
            fetch('/map/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(providers)
                })
                .then(response => response.json())
                .then(data => {

                    if (data.status == 200) {
                        map_form.reset();
                        polygons.forEach(polygon => polygon.setMap(null)); // Remover los polígonos del mapa
                        polygons = []; // Vaciar el array de polígonos

                        window.location.href = '/map';
                    }else{
                       
                        alert(data.message);
                        map_form.reset();
                        if (data.status==409) {
                           window.location.href = '/map'; 
                        }
                        
                        // Limpiar los polígonos después de guardar
                        polygons.forEach(polygon => polygon.setMap(null)); // Remover los polígonos del mapa
                        polygons = []; // Vaciar el array de polígonos
                        
                    }

                })
                .catch((error) => {
                    console.error('Error:', error);
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


    //script modal
    const videoPlayer = document.getElementById("videoPlayer");
    
    if (videoPlayer) {
        // Verifica si el video está listo para reproducir
        videoPlayer.addEventListener("canplay", function () {
            videoPlayer.play().catch((error) => {
                console.error("Error al intentar reproducir el video:", error);
            });
        });
        // Seleccionar el modal y el botón de cierre
        const modal = document.getElementById('modal');
        const closeModal = document.getElementById('closeModal');
    
        // Mostrar el modal al cargar la página
        window.addEventListener('DOMContentLoaded', () => {
          modal.classList.add('is-active');
        });
    
        // Cerrar el modal al hacer clic en el botón
        closeModal.addEventListener('click', () => {
          modal.classList.remove('is-active');
        });
    
        // Cerrar el modal al hacer clic en el fondo
        modal.querySelector('.modal-background').addEventListener('click', () => {
          modal.classList.remove('is-active');
        });  
    }

    </script>
    @endpush
    @elseif (!empty($mapData) && $mapData !=null)

        <script>
            let maps = @json($mapData);
            const editUrl = `/map/edit/${maps.pvr_map_id}`; 
            window.location.href = editUrl;
        
        </script>
    @else
    <div class="card">
        <div class="card-content">
            To create a map, it is necessary to register the airports. No airport is registered.
        </div>
    </div>
    @endif
</x-app-layout>