<x-app-layout>



    

        <!-- Mensaje de éxito -->
        @if(session('success'))
        <div class="notification is-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Formulario -->
        <!-- <form action="/create-airport" method="POST"> -->

        <form id="form-map" action="#" method="POST">

            <!-- <div class="card">
                <div class="card-content">

                    <div class="is-flex">

                        <button type="button" id="btnGeneratePolygon" class="mt-3 is-flex is-justify-content-center is-align-content-center p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="btn-icon-map"  viewBox="0 0 24 24">
                                <path fill="currentColor" d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5v2H5v14h14v-5z" />
                                <path fill="currentColor" d="M21 7h-4V3h-2v4h-4v2h4v4h2V9h4z" />
                            </svg>
                        </button>

                       
                        <div class="is-flex">
                            <button id="cart" class="mt-3 is-flex is-justify-content-center p-1 ml-2 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="btn-icon-map" viewBox="0 0 24 24">
                                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <path d="M3.587 13.779c1.78 1.769 4.883 4.22 8.413 4.22s6.634-2.451 8.413-4.22c.47-.467.705-.7.854-1.159c.107-.327.107-.913 0-1.24c-.15-.458-.385-.692-.854-1.159C18.633 8.452 15.531 6 12 6c-3.53 0-6.634 2.452-8.413 4.221c-.47.467-.705.7-.854 1.159c-.107.327-.107.913 0 1.24c.15.458.384.692.854 1.159" />
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
                                    </g>
                                </svg>
                            </button>
                            <span id="carritoCount" class="tag is-danger is-small is-rounded">0</span>
                        </div>

                        <button type="submit" id="btnSave" class="button  mt-3 p-2 ml-4 custom-btn_color">
                            {{ __('Save') }}
                        </button>

                    </div>
                    <ul class="cart mt-8 hidden"></ul>
                </div>
            </div> -->
            <!-- CSRF Token para protección -->
            @csrf

            <!-- Campo de Selección -->
            @if(!$id)
            <div class="columns">
                <div class="column is-4">
                    <div class="field">
                        <label for="airportSelect" class="label">Selecciona un aeropuerto</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="airportSelect" name="airport">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif

            @if($id)
            <div class="field">
                <label class="label">Airport</label> 
                <p><small>{{ $airport[0]['pvr_airport_alias']}}</small></p>
            </div>
            @endif
            <div class="columns">
                <div class="column is-4">
                    <div class="field">
                        <label class="label">Alias</label>
                        <div class="control">
                            <input class="input" id="aliasMap" type="text" name="aliasMap" placeholder="Alias">
                        </div>
                    </div>
                </div>
            </div>


            <!-- Mapa -->
            <div class="field">
                <div id="map" data-airport="{{ $id }}" class="box" style="height: 600px;  margin-top: 20px;"></div>
            </div>


            <button type="submit" id="btnSaveMap" class="button  mt-3 p-2  custom-btn_color">
                {{ __('Save Map') }}
            </button>

        </form>
    




    @push('scripts')
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&callback=initMap&v=3.exp"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {

            const btnSaveMap = document.getElementById('btnSaveMap');
            if (btnSaveMap) {
                btnSaveMap.addEventListener("click", (e) => {
                    createMap(e);
                });
            }

            // Convertir datos PHP a un array JavaScript
            let airports = @json($airports);
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
            
            // // Añadir un primer <option> por defecto
            // let defaultOption = document.createElement('option');
            // defaultOption.value = '';
            // // defaultOption.textContent = 'Seleccione un aeropuerto';
            // select.appendChild(defaultOption);

            // Iterar sobre los aeropuertos y obtener detalles
            // for (let airport of airports) {
            //     try {

            //         let response = await getAirport(airport.pvr_airport_api_ref);
            //         if (response) {
            //             // Crear un nuevo <option> para el select
            //             let option = document.createElement('option');
            //             option.value = response.place_id;
            //             option.textContent = response.name;
            //             option.setAttribute('data-airport', airport.pvr_airport_id);
            //             select.appendChild(option);
            //         }
            //     } catch (error) {
            //         console.error('Error obteniendo detalles del aeropuerto:', error);
            //     }
            // }

            // Selecciona el elemento 'select' con id 'airportSelect'
            //const airportSelect = document.getElementById('airportSelect');
            // Verifica si el elemento es un select
            // if (airportSelect.tagName.toLowerCase() === 'select') {

            //     // Añade el event listener para el cambio en el select
            //     airportSelect.addEventListener('change', function() {

            //         // Verifica que el valor del select no esté vacío o nulo
            //         if (airportSelect.value != '' && airportSelect.value != null) {

            //             // Llama a la función que quita la clase 'danger'
            //             removeDangerClass(airportSelect);
            //         }
            //     });
            // }

            // actualizarCart();

            // const btnGeneratePolygon = document.getElementById('btnGeneratePolygon');
            // if (btnGeneratePolygon) {
            //     btnGeneratePolygon.addEventListener('click', function() {
            //         event.preventDefault();

            //         createPolygons();

            //     });
            // }

            // const btnCart = document.getElementById('cart');
            // if (btnCart) {
            //     btnCart.addEventListener('click', function() {
            //         event.preventDefault();

                    
            //         const contadorCarrito = document.getElementById('carritoCount');
            //         const valorCarrito = parseInt(contadorCarrito.textContent, 10);

                    
            //         if (valorCarrito > 0) {
            //             const cartList = document.querySelector('.cart');
            //             if (cartList.classList.contains('hidden')) {
                           
            //                 cartList.classList.remove('hidden');
            //             } else {
                         
            //                 cartList.classList.add('hidden');
            //             }

            //         }
                    
            //     });
            // }


            // const btnSave = document.getElementById('btnSave');
            // if (btnSave) {
            //     btnSave.addEventListener('click', function() {
            //         event.preventDefault();

            //         realizarOperacionConBaseDeDatos(getPolygon)
            //             .then(polygons => {

            //                 if (polygons.length > 0) {
            //                     saveBd(polygons);
            //                 } else {

            //                 }
            //             })
            //             .catch(error => {
            //                 console.error('Error al obtener productos:', error);
            //             });

            //     });
            // }

        });




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
                    polygons.push(newPolygon); // Agregar el nuevo polígono al array

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

        function createPolygons() {

            const airportSelect = document.getElementById('airportSelect');
            let dataReference = airportSelect.value;
            const selectedText = airportSelect.options[airportSelect.selectedIndex].text;
            let selectedOption = airportSelect.options[airportSelect.selectedIndex];
            let airport = selectedOption.getAttribute('data-airport');

            if (airportSelect.value != '' && airportSelect.value != null) {

                if (polygons.length > 0) {
                    const PolygonJson = {
                        type: "FeatureCollection",
                        features: polygons.map(polygon => {
                            const path = polygon.getPath().getArray().map(point => [point.lng(), point.lat()]);
                            // Asegurarse de que el primer y último punto sean los mismos
                            if (path.length > 0 && (path[0][0] !== path[path.length - 1][0] || path[0][1] !== path[path.length - 1][1])) {
                                path.push(path[0]); // Añadir el primer punto al final para cerrar el polígono
                            }

                            return {
                                type: "Feature",
                                geometry: {
                                    type: "Polygon",
                                    coordinates: [path]
                                },
                                properties: {}
                            };
                        })
                    };

                    let GeneratePolygon = {
                        "place_id": dataReference,
                        "airport": airport,
                        "name": selectedText,
                        "colections": [JSON.stringify(PolygonJson, null, 2)]
                    }


                    // Ejemplo de uso solo para obtener Polygon
                    realizarOperacionConBaseDeDatos(getPolygon)
                        .then(poligons => {
                            if (poligons.length > 0) {

                                let place = poligons.map(hero => hero.place_id === dataReference);
                                if (place[0]) {
                                    alert("Ya existe un poligo!");
                                } else {
                                    savePolygonIndexedDB(GeneratePolygon);
                                }

                            } else {

                                savePolygonIndexedDB(GeneratePolygon);
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener productos:', error);
                        });

                } else {
                    alert('Por favor, dibuja un polígono primero.');
                }

            } else {


                if (airportSelect.tagName.toLowerCase() === 'select') {
                    const selectWrapper = airportSelect.closest('.select');
                    if (selectWrapper) {
                        selectWrapper.classList.add('is-danger');
                    }
                }
                airportSelect.focus();

            }

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

                if (polygons.length > 0) {
                    const PolygonJson = {
                        type: "FeatureCollection",
                        features: polygons.map(polygon => {
                            const path = polygon.getPath().getArray().map(point => [point.lng(), point.lat()]);
                            // Asegurarse de que el primer y último punto sean los mismos
                            if (path.length > 0 && (path[0][0] !== path[path.length - 1][0] || path[0][1] !== path[path.length - 1][1])) {
                                path.push(path[0]); // Añadir el primer punto al final para cerrar el polígono
                            }

                            return {
                                type: "Feature",
                                geometry: {
                                    type: "Polygon",
                                    coordinates: [path]
                                },
                                properties: {}
                            };
                        })
                    };

                    let GeneratePolygon = {
                        "airport": airport,
                        "aliasMap":aliasMap,
                        "colections": [JSON.stringify(PolygonJson, null, 2)]
                    }

                   saveBd(GeneratePolygon);
                } else {
                    alert('Por favor, dibuja un polígono primero.');
                }


            }



        }


        // Función para abrir la base de datos
        function abrirBaseDeDatos() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('carritoDB', 2);

                request.onupgradeneeded = function(event) {
                    db = event.target.result;
                    if (!db.objectStoreNames.contains('productos')) {
                        db.createObjectStore('productos', {
                            keyPath: 'id',
                            autoIncrement: true
                        });
                    }
                };

                request.onsuccess = function(event) {
                    db = event.target.result;
                    console.log('Base de datos abierta');
                    resolve(); // Resuelve la promesa cuando la base de datos está abierta
                };

                request.onerror = function(event) {
                    console.error('Error al abrir la base de datos:', event.target.errorCode);
                    reject(event.target.error); // Rechaza la promesa en caso de error
                };
            });
        }

        // Función para obtener
        function getPolygon() {
            return new Promise((resolve, reject) => {
                if (!db) {
                    reject('La base de datos no está abierta.');
                    return;
                }

                const transaction = db.transaction(['productos'], 'readonly');
                const store = transaction.objectStore('productos');
                const request = store.getAll();

                request.onsuccess = function(event) {
                    const polygons = event.target.result;
                    resolve(polygons);
                };

                request.onerror = function(event) {
                    console.error('Error al obtener polygons:', event.target.errorCode);
                    reject(event.target.error); // Rechaza la promesa en caso de error
                };
            });
        }

        // Función para agregar un producto
        function agregarPolygon(nuevoProducto) {

            return new Promise((resolve, reject) => {
                if (!db) {
                    reject('La base de datos no está abierta.');
                    return;
                }

                const transaction = db.transaction(['productos'], 'readwrite');
                const store = transaction.objectStore('productos');

                if (nuevoProducto.id) {
                    // Si el producto tiene un ID, verificar si ya existe
                    const getRequest = store.get(nuevoProducto.id);

                    getRequest.onsuccess = function(event) {
                        const existingProduct = event.target.result;

                        if (existingProduct) {
                            // Si el producto ya existe, actualízalo
                            const putRequest = store.put(nuevoProducto);

                            putRequest.onsuccess = function() {
                                //resolve('Producto actualizado');
                                resolve({
                                    status: 'success',
                                    message: 'Producto actulizado.'
                                });
                            };

                            putRequest.onerror = function(event) {
                                //reject('Error al actualizar el producto:', event.target.error);
                                reject({
                                    status: 'error',
                                    message: 'Error al agregar el producto.',
                                    error: event.target.error
                                });
                            };
                        } else {
                            // Si el producto no existe, agrégalo
                            const addRequest = store.add(nuevoProducto);

                            addRequest.onsuccess = function() {
                                //resolve('Producto agregado');
                                resolve({
                                    status: 'success',
                                    message: 'Producto agregado correctamente.'
                                });
                            };

                            addRequest.onerror = function(event) {
                                //reject('Error al agregar el producto:', event.target.error);
                                reject({
                                    status: 'error',
                                    message: 'Error al agregar el producto.',
                                    error: event.target.error
                                });
                            };
                        }
                    };

                    getRequest.onerror = function(event) {
                        reject('Error al verificar si el producto existe:', event.target.error);
                    };
                } else {
                    // Si el producto no tiene ID, agregarlo directamente
                    const addRequest = store.add(nuevoProducto);

                    addRequest.onsuccess = function() {
                        //resolve('Producto agregado');
                        resolve({
                            status: 'success',
                            message: 'Producto agregado correctamente.'
                        });
                    };

                    addRequest.onerror = function(event) {
                        //reject('Error al agregar el producto:', event.target.error);
                        reject({
                            status: 'error',
                            message: 'Error al agregar el producto.',
                            error: event.target.error
                        });
                    };
                }
            });
        }

        // Función para realizar una operación con la base de datos
        function realizarOperacionConBaseDeDatos(operacion) {
            return abrirBaseDeDatos()
                .then(() => operacion())
                .then(result => {
                    return result;
                })
                .catch(error => {
                    console.error('Error:', error); // Manejar el error
                });
        }

        // Función para borrar todos los datos del objectStore 'productos'
        function borrarTodosLosProductos() {
            return new Promise((resolve, reject) => {
                if (!db) {
                    reject('La base de datos no está abierta.');
                    return;
                }

                const transaction = db.transaction(['productos'], 'readwrite');
                const store = transaction.objectStore('productos');
                const request = store.clear();

                request.onsuccess = function() {
                    actualizarCart();
                    // console.log('Todos los productos han sido eliminados.');
                    // resolve(); // Resuelve la promesa cuando todos los datos han sido eliminados
                    resolve({
                        status: 'success',
                        message: 'sido eliminados correctamente.'
                    });
                };

                request.onerror = function(event) {
                    console.error('Error al borrar productos:', event.target.errorCode);
                    reject(event.target.error); // Rechaza la promesa en caso de error
                };
            });
        }

        function borrarProductoPorId(id) {
            return new Promise((resolve, reject) => {
                if (!db) {
                    reject('La base de datos no está abierta.');
                    return;
                }

                const transaction = db.transaction(['productos'], 'readwrite');
                const store = transaction.objectStore('productos');
                const request = store.delete(id);

                request.onsuccess = function() {
                    console.log(`Producto con id ${id} ha sido eliminado.`);
                    //resolve(); // Resuelve la promesa cuando el producto ha sido eliminado
                    resolve({
                        status: 'success',
                        message: 'Eliminado con exito!.'
                    });
                };

                request.onerror = function(event) {
                    console.error('Error al borrar el producto:', event.target.errorCode);
                    //reject(event.target.error); // Rechaza la promesa en caso de error
                    reject({
                        status: 'error',
                        message: 'Error al borrar.',
                        error: event.target.error
                    });
                };
            });
        }

        function savePolygonIndexedDB(json) {

            realizarOperacionConBaseDeDatos(() => agregarPolygon(json))
                .then(response => {

                    if (response.status === 'success') {

                        // document.getElementById('inputAirport').value = '';
                        polygons = [];
                        selectedPolygon = null;
                        initMap();
                        actualizarCart();


                    }
                })
                .catch(error => {
                    console.error(error.message);

                });

        }

        function listarProductosEnUl(productos) {

            const ulElement = document.querySelector('.cart');
            ulElement.innerHTML = '';

            if (productos.length > 0) {
                productos.forEach(producto => {
                    // Crear el li
                    const liElement = document.createElement('li');
                    liElement.textContent = producto.name;

                    // Crear el botón como un elemento HTML
                    const buttonHTML = `
                        <x-primary-button class="btn-cart-delete ms-3 bg-red-700 hover:bg-blue-600 mt-3">
                            Delete
                        </x-primary-button>
                    `;

                    // Insertar el botón como HTML dentro del li
                    liElement.innerHTML += buttonHTML;

                    liElement.querySelector('.btn-cart-delete').addEventListener('click', () => {
                        event.preventDefault();
                        borrarProductoPorId(producto.id).then((response) => {

                            if (response.status === 'success') {
                                ulElement.removeChild(liElement);
                                actualizarCart();

                            }

                        }).catch(error => {
                            console.error('Error al eliminar producto:', error);
                        });
                    });

                    // Añadir el li al ul
                    ulElement.appendChild(liElement);
                });
            }

        }

        function actualizarCart() {

            const contador = document.getElementById('carritoCount');
            realizarOperacionConBaseDeDatos(getPolygon)
                .then(productos => {

                    if (productos.length > 0) {

                        contador.textContent = productos.length;
                        listarProductosEnUl(productos);
                    } else {
                        contador.textContent = 0;
                    }
                })
                .catch(error => {
                    console.error('Error al obtener productos:', error);
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

                    // const cartList = document.querySelector('.cart');
                    // cartList.classList.add('hidden');

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
    </script>
    @endpush
</x-app-layout>