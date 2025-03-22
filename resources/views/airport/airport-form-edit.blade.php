<x-app-layout>

<nav class="breadcrumb mb-3 p-0" aria-label="breadcrumbs">
  <ul class="p-0">

    <li><a href="/airport">My Airports</a></li>
    <li class="is-active"><a href="#" aria-current="page">Update Airport</a></li>
  </ul>
</nav>

<div class="columns is-multiline">
    <div class="column custom-card-airport">
        <div class="card">
            <div class="card-content">
                
                <!-- <div id="dynamic-notification" class="notification" style="display: none;"></div> -->
                
                <form id="form_airport_update" action="#" method="POST">
                    @csrf

                    <div class="field mb-2">
                        <label class="label" for="inputAirport">Airport</label>
                        <div class="control">
                            <input
                                id="inputAirport"
                                class="input"
                                type="text"
                                name="inputAirport"
                                value=""
                                data-reference="{{ $airport->pvr_airport_api_ref}}"
                                data-name="{{ $airport->pvr_airport_alias}}"
                                data-airport="{{ $airport->pvr_airport_id}}"
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
                                value="{{ $airport->pvr_airport_alias}}"
                                required
                                autofocus
                                placeholder="Alias" />
                        </div>
                    </div>


                    <!-- <div class="field">
                        <div class="control">

                        </div>
                    </div> -->
                </form>
            </div>
            <footer class="card-footer footer-edit">
                <button
                    id="btn_airport_update"
                    type="submit"
                    class="card-footer-item btn-edit-fomr">
                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="80" height="80" viewBox="0 0 24 24">
                        <path fill="currentColor" d="m7 17.013l4.413-.015l9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583zM18.045 4.458l1.589 1.583l-1.597 1.582l-1.586-1.585zM9 13.417l6.03-5.973l1.586 1.586l-6.029 5.971L9 15.006z" />
                        <path fill="currentColor" d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2" />
                    </svg>
                    <span class="ml-1">Update Airport</span>
                </button>
            </footer>
        </div>
    </div>
</div>






    @push('scripts')
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&v=3.exp"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputElement = document.getElementById('inputAirport');

            const autocomplete = new google.maps.places.Autocomplete(inputElement, {
               types: ['establishment']

            });

            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                const place = autocomplete.getPlace();
                // Verifica si se ha seleccionado un lugar
                if (place && place.place_id) {
                    // Asegúrate de que el lugar es un aeropuerto
                    if (place.types.includes('airport')) {

                        inputElement.style.borderColor ='';
                        inputElement.setAttribute('data-reference', place.place_id);
                        inputElement.setAttribute('data-name', place.name);

                    } else {
                        inputElement.value = '';
                        inputElement.style.borderColor ='red';
                        inputElement.focus();
                    }
                }
               
            });

            if (inputElement) {
                getAirport(inputElement.getAttribute('data-reference')).then(airport => {
                    
                    inputElement.value = airport; 
                }).catch(error => {
                    console.error("Error obteniendo el aeropuerto: ", error);
                });
            }

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
                                resolve(name);  // Resuelve la promesa con el nombre del aeropuerto
                            } else {
                                reject("No se pudo obtener el aeropuerto");  // Rechaza la promesa si falla
                            }
                        });
                    } else {
                        reject("ID de aeropuerto no proporcionado");
                    }
                });
            }


        });
    </script>
    @endpush
</x-app-layout>