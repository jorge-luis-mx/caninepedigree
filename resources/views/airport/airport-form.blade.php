<x-app-layout>


    <!-- <div class="card" style="max-width: 500px; margin: 20px;">
        <div class="card-content">
            
            <div id="dynamic-notification" class="notification" style="display: none;"></div>
            
            <form id="form_airport" action="#" method="POST">
                @csrf

                <div class="field mt-3 mb-2">
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
                <div class="field">
                    <div class="control">
                        <button
                            id="btn_airport_store"
                            type="submit"
                            class="button custom-btn_color mt-3">
                            Save Airport
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div> -->





    @push('scripts')
    <!-- <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&v=3.exp"></script>
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


        });
    </script> -->
    @endpush
</x-app-layout>