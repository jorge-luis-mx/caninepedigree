<x-app-layout>


    @if(!empty($airportsWithServices))

        <div class="notification-dinamy notification is-hidden"></div>
        @foreach($airportsWithServices as $airport)

        @if(!empty($airport['map']))
        
            @if(isset($airport['map'][0]))
                
            @endif
        @endif
        
        <div class="card">
            <header class="card-header">
                <p class="card-header-title airport" data-airport="{{ $airport['id_airport']}}" data-provider="{{$airport['id_provider']}}">{{ $airport['airport_alias']}}</p>
            </header>
                <div class="card-content">
                    <div class="columns is-multiline is-flex is-justify-content-flex-start">

        
                        @foreach( $servicesType as $serviceType)

                        <!-- @php
                            // Buscar el servicio por ser_typ_id en el array de servicios del aeropuerto
                            $service = collect($airport['services'])->firstWhere('ser_typ_id', $serviceType->ser_typ_id);

                            // Verificar si el servicio tiene estado 1 para marcar el checkbox
                            $isChecked = $service && $service['status'] == 1 ? 'checked' : '';

                        @endphp -->

                        <div class="column custom-column">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media is-flex is-justify-content-space-between">
                                        <div class="media-left">
                                            <figure class="image">
                                            <img class="custom-image"
                                                src="https://bulma.io/assets/images/placeholders/96x96.png"
                                                alt="Placeholder image"
                                            />
                                            </figure>
                                        </div>
                                        <div class="is-flex">
                                            <p class=" is-4">{{ $serviceType->ser_typ_alias}}</p>
                                            @php
                                                $isChecked = in_array($serviceType->ser_typ_id, $airport['services']) ? 'checked' : '';

                                            @endphp
                                            <input type="checkbox" class="ml-3" data-service-type="{{ $serviceType->ser_typ_id}}" {{ $isChecked }}/>

                                        </div>
                                    </div>
                                    <p>Capacity: {{ $serviceType->ser_typ_capacity}}</p>
                                    
                                </div>
                            </div>
                        </div>

                        @endforeach
                        
                    </div>
                </div>

            <footer class="card-footer is-flex is-justify-content-flex-start">
                <button
                    type="submit"
                    class="button custom-btn_color mt-3 ml-3 mb-3 btn-save-services">
                    Save
                </button>
            </footer>
        </div>

        @endforeach
    @else
    <div class="card">
        <div class="card-content">
            At the moment, we don't have any services available. You need to register the airports.
        </div>
    </div>
    @endif

</x-app-layout>