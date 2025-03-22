<x-app-layout>
    <!-- <x-slot:title>My Maps</x-slot:title> -->
    <h1 class="">My Maps</h1>
    <div class="bg-blue-100 mb-10 rounded-md border-2 border-blue-300 p-5">
        <p>In this section you will create the areas or zones, this will be done by creating shapes in the map, limiting certain areas. This is to set different prices to those different areas.
        <br>Each area has to have a different price.
        <br>Or if you have different areas with the same price, you need to create them all in order to be specific to those areas.</p>
        <ul class="!list-none !mt-5 !ml-0 text-base">
            <li class="flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="#3b82f6" d="m10.6 13.4l-2.15-2.15q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7L9.9 15.5q.3.3.7.3t.7-.3l5.65-5.65q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275zM5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zM5 5v14z"/></svg>
                <p>Use the shape from to create shapes on the map.</p>
            </li>
            <li class="flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="#3b82f6" d="m10.6 13.4l-2.15-2.15q-.275-.275-.7-.275t-.7.275t-.275.7t.275.7L9.9 15.5q.3.3.7.3t.7-.3l5.65-5.65q.275-.275.275-.7t-.275-.7t-.7-.275t-.7.275zM5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zM5 5v14z"/></svg>
                <p>Use the hand to select the shapes.</p>
            </li>
        </ul>
    </div>

    <div class="columns is-multiline">
        <div class="column is-one-quarter">
            <div class="custom-card-map-add maps-form sticky-card">
                <div class="card add-map is-flex is-flex-wrap-wrap is-justify-content-center is-align-items-center ">
                    <div class="container-icon-map is-flex is-align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 448 512">
                            <path fill="currentColor" d="m433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941M224 416c-35.346 0-64-28.654-64-64s28.654-64 64-64s64 28.654 64 64s-28.654 64-64 64m96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A12 12 0 0 1 320 111.48"/>
                        </svg>
                        <span class="ml-1"><strong>Add Map</strong></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="columns is-multiline" id="maps"></div>
        </div>
    </div>





    @push('scripts')
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBm3022UVy1EVjpuND7yoxTTqGSESOmbQE&libraries=drawing,places&v=3.exp"></script>
    <script>

    document.addEventListener('DOMContentLoaded', () => {
        //list airports
        let maps = @json($mapsWithApiRef);
        const mapsContainer = document.getElementById('maps');
        if (Array.isArray(maps) && maps.length > 0) {

            maps.forEach(function(map) {

               const poligons = map['poligons'];
               
                getAirport(map.pvr_airport_api_ref,poligons).then(mapName => {

                    
                    let statusChecked = map.pvr_map_status == 1 ? 'checked' : '';

                        const cardHTML = `

                            <div class="column custom-card-map" data-id="${map.pvr_map_id}">
                                <div class="card">
                                    <header class="card-header is-flex is-align-items-center is-justify-content-space-between" style="padding-top: 10px;padding-bottom: 10px;">
                                        <div class="ml-3">
                                            <div class="is-flex is-align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><g fill="none" stroke="#FBA53E" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M19.914 11.105A7 7 0 0 0 20 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0a32 32 0 0 0 .824-.738"/><circle cx="12" cy="10" r="3"/><path d="M16 18h6m-3-3v6"/></g></svg>
                                                <div class ="header-alias">
                                                    <span class="ml-1"><strong>${map.pvr_map_alias.charAt(0).toUpperCase() + map.pvr_map_alias.slice(1)}</strong></span>
                                                </div>
                                                
                                            </div>
                                            <div class="ml-5" style="    font-style: italic;margin-top: -5px;font-size: 16px;"><small>By ${map.pvr_airport}</small></div>
                                        </div>
                                        <div class="card-header-icon">
                                            <input type="checkbox"  class="checkbox-map" data-id="${map.pvr_map_id}" ${statusChecked} />
                                        </div>
                                    </header>
                                    <div class="card-content">
                                        <div class="is-flex container-inputs">

                                        ${poligons.map(element => `<input class="input" data-poligon="${element.id}" type="text" style ="border: 0 !important;background-color: #fbfbfb;" value="${element.name}" />`).join('')}
   
                                        </div>

                                    </div>
                                    <footer class="card-footer footer-map">
                                        <div class="is-flex">
                                            <button data-id="${map.pvr_map_id}" class="is-flex is-align-items-center btn-edit" style ="margin-left: 10px;font-size: 16px;gap: 5px; margin-top: 10px;margin-bottom: 10px;padding: 10px;border-radius: 4px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="80" height="80" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="m7 17.013l4.413-.015l9.632-9.54c.378-.378.586-.88.586-1.414s-.208-1.036-.586-1.414l-1.586-1.586c-.756-.756-2.075-.752-2.825-.003L7 12.583zM18.045 4.458l1.589 1.583l-1.597 1.582l-1.586-1.585zM9 13.417l6.03-5.973l1.586 1.586l-6.029 5.971L9 15.006z" />
                                                    <path fill="currentColor" d="M5 21h14c1.103 0 2-.897 2-2v-8.668l-2 2V19H8.158c-.026 0-.053.01-.079.01c-.033 0-.066-.009-.1-.01H5V5h6.847l2-2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2" />
                                                </svg>
                                                <span>Edit Map</span>
                                            </button>
                                            <button data-id="${map.pvr_map_id}" class="is-flex is-align-items-center btn-delete" style ="margin-left:30px; font-size: 16px;gap: 5px; margin-top: 10px;margin-bottom: 10px;padding: 10px;border-radius: 4px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" width="128" height="128" viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4zm2 2h6V4H9zM6.074 8l.857 12H17.07l.857-12zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1" />
                                                </svg>
                                                <span>Delete Map</span>
                                            </button>
                                        </div>
                                    </footer>
                                </div>
                            </div>
                        
                        `;
                    // Inserta el HTML dentro del contenedor
                    mapsContainer.innerHTML += cardHTML;
                }).catch(error => {
                    console.error("Error obteniendo el aeropuerto: ", error);
                });

            });
        }

        function getAirport(id_refmap) {
            
                return new Promise((resolve, reject) => {
                    if (id_refmap) {
                        let service = new google.maps.places.PlacesService(document.createElement('div'));
                        const request = {
                            placeId: id_refmap,
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
    });
    </script>


    @endpush

</x-app-layout>