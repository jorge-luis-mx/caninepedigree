<x-app-layout>

    <!-- <x-slot:title>My Airports</x-slot:title> -->
    <h1 class="">My Airports</h1>
    <div class="bg-blue-100 mb-10 rounded-md border-2 border-blue-300 p-5">
        <p>Here you’ll select the airports from where you can provide the transportation service, you need to be able to pick up at the airport and drop off at the airport.</p>
        <ul class="!list-none !mt-5 !ml-0 text-base">
            <li class="flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="#3b82f6" d="m10.6 13.4l-2.15-2.15q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7L9.9 15.5q.3.3.7.3t.7-.3l5.65-5.65q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275zM5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zM5 5v14z"/></svg>
                <p>Start typing the name of the airport and select it from the list.</p>
            </li>
            <li class="flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="#3b82f6" d="m10.6 13.4l-2.15-2.15q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7L9.9 15.5q.3.3.7.3t.7-.3l5.65-5.65q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275zM5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zM5 5v14z"/></svg>
                <p>Add an alias of just type the same name as the airport.</p>
            </li>
        </ul>
    </div>
    <div class="columns is-multiline" id="airports">

        <div class="column custom-card-airport">
            <div class="card">

                <div class="card-content">

                    <!-- <div id="dynamic-notification" class="notification" style="display: none;"></div> -->
                    <form id="form_airport" action="#" method="POST">
                        @csrf

                        <div class="field mb-2">
                            <label class="label" for="inputAirport">Airport</label>
                            <div class="control">
                                <input
                                    id="inputAirport"
                                    class="input"
                                    type="text"
                                    name="inputAirport"
                                    data-provider=" {{ $provider_auth }}"
                                    required
                                    autofocus
                                    placeholder="Name Airport" />
                            </div>
                        </div>
                        <div class="field mt-3 mb-2">
                            <label class="label" for="inputAirport">Alias</label>
                            <div class="control">
                                <input
                                    id="aliasAirport"
                                    class="input"
                                    type="text"
                                    name="aliasAirport"
                                    required
                                    autofocus
                                    placeholder="Alias" />
                            </div>
                        </div>
                    </form>
                </div>
                <footer class="card-footer footer-save">
                    <button
                        id="btn_airport_store"
                        type="button"
                        class="card-footer-item btn-save">
                        <svg xmlns="http://www.w3.org/2000/svg" class=" custom-icon-action"  viewBox="0 0 448 512">
                            <path fill="currentColor" d="m433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941M224 416c-35.346 0-64-28.654-64-64s28.654-64 64-64s64 28.654 64 64s-28.654 64-64 64m96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A12 12 0 0 1 320 111.48"/>
                        </svg>
                        <span class="ml-1">Add Airport</span>
                    </button>

                </footer>
            </div>
        </div>

    </div>




    @push('scripts')
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&v=3.exp"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            //Autocomplete Airport formm
            google.maps.event.addDomListener(window, 'load', initAutocomplete);

            //list airports
            let airports = @json($airports);
            const airportsContainer = document.getElementById('airports');

            airports.forEach(function(airport) {

                getAirport(airport.pvr_airport_api_ref).then(airportName => {


                    let statusChecked = airport.pvr_airport_status == 1 ? 'checked' : '';
                    let buttoEdit = '';
                    let buttoMap = '';

                    const pvrAirportStatus = airport.pvr_airport_status;

                    if (airport.pvr_airport_status == 1) {

                        buttoEdit = `
                            <button data-id="${airport.pvr_airport_id}" class="btn-edit card-footer-item" title="Edit Airport">
                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="80" height="80" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="m7 17.013l4.413-.015l9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583zM18.045 4.458l1.589 1.583l-1.597 1.582l-1.586-1.585zM9 13.417l6.03-5.973l1.586 1.586l-6.029 5.971L9 15.006z" />
                                    <path fill="currentColor" d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2" />
                                </svg>
                            </button>`;
                        buttoMap = `
                            <button data-id="${airport.pvr_airport_id}" class="btn-map card-footer-item" title="Create | Edit your Map">
                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="128" height="128" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5L9 17l-6 3V7l6-3l6 3l6-3v8.5M9 4v13m6-10v8m1 4h6m-3-3v6" />
                                </svg>
                            </button>`;
                    }
                    


                    const cardHTML = `
                    <div class="column custom-card-airport" data-id="${airport.pvr_airport_id}">
                        <div class="card">
                            <header class="card-header is-flex is-align-items-center is-justify-content-space-between">
                                <span class="ml-3">
                                    <strong>${airport.pvr_airport_alias.charAt(0).toUpperCase() + airport.pvr_airport_alias.slice(1).toLowerCase()}</strong>
                                </span>
                                <span class="card-header-icon">
                                    <input type="checkbox"  class="checkbox-airport" data-id="${airport.pvr_airport_id}" ${statusChecked}/>
                                </span>
                            </header>
                            <div class="card-content">
                                <div class="content">
                                    ${airportName}
                                </div>
                            </div>
                            <footer class="card-footer footer-delete ${pvrAirportStatus ==0 ?'footer-delete-one':''}">
                                ${buttoEdit}
                                ${buttoMap}
                                <button data-id="${airport.pvr_airport_id}"  class="btn-delete card-footer-item" title="Delete this Airport"><svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="128" height="128" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4zm2 2h6V4H9zM6.074 8l.857 12H17.07l.857-12zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1" />
                                    </svg></button>
                                
                            </footer>
                        </div>
                    </div>
                 `;
                    // Inserta el HTML dentro del contenedor
                    airportsContainer.innerHTML += cardHTML;

                }).catch(error => {
                    console.error("Error obteniendo el aeropuerto: ", error);
                });

            });



            function getAirport(id_airport) {
                return new Promise((resolve, reject) => {
                    if (id_airport) {
                        let service = new google.maps.places.PlacesService(document.createElement('div'));
                        const request = {
                            placeId: id_airport,
                        };
                        service.getDetails(request, function(details, status) {
                            if (status === google.maps.places.PlacesServiceStatus.OK) {
                                let name = details.name;

                                resolve(name);
                            } else {
                                reject("No se pudo obtener el aeropuerto"); // Rechaza la promesa si falla
                            }
                        });
                    } else {
                        reject("ID de aeropuerto no proporcionado");
                    }
                });
            }

            // Espera a que la API de Google Maps esté lista
            function initAutocomplete() {
                const inputElement = document.getElementById('inputAirport');

                // Inicializa el autocompletado para el campo de aeropuerto
                const autocomplete = new google.maps.places.Autocomplete(inputElement, {
                    types: ['establishment'],

                });

                // Escucha el evento place_changed
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    const place = autocomplete.getPlace();

                    // Verifica si se ha seleccionado un lugar
                    if (place && place.place_id) {
                        // Asegúrate de que el lugar es un aeropuerto
                        if (place.types.includes('airport')) {
                            inputElement.style.borderColor = ''; // Restablece el color del borde
                            inputElement.setAttribute('data-reference', place.place_id);
                            inputElement.setAttribute('data-name', place.name);
                        } else {
                            // Si no es un aeropuerto, vacía el campo y establece un borde rojo
                            inputElement.value = '';
                            inputElement.style.borderColor = 'red';
                            inputElement.focus();
                        }
                    }
                });
            }

            // Llama a la función para inicializar el autocompletado
            // google.maps.event.addDomListener(window, 'load', initAutocomplete);


        });
    </script>


    @endpush

</x-app-layout>