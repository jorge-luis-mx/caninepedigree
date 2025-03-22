<x-app-layout>

@if(!empty($airportsWithServices))
    <div class="notification-dinamy notification is-hidden"></div>

    <div class="tabs is-boxed custom-slider">
        <ul>
            @foreach($airportsWithServices as $index => $airport)
            <li class="{{ $index === 0 ? 'is-active' : '' }}" data-airport="{{ $airport['id_airport']}}">
                <a href="{{ $airport['id_airport']}}" class="item-service">
                    <span>{{ $airport['airport_alias']}}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>


    @foreach($airportsWithServices as $airport)

        <div class="card">
            <header class="card-header">
                <p class="card-header-title airport" data-airport="{{ $airport['id_airport']}}" data-map="{{$airport['pvr_map_id']}}">{{ $airport['airport_alias']}}</p>
            </header>
                <div class="card-content">
                    <div class="columns is-multiline is-flex is-justify-content-flex-start">

        
                        @foreach( $airport['services'] as $service)

                            @if($service['status']===1)
                                <div class="column custom-column " data-service="{{ $service['pvr_ser_id']}}">
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
                                                <div class="is-flex is-justify-content-flex-end">
                                                    <p class=" is-4">{{$service['ser_typ_alias']}}</p>
                                                    
                                                </div>
                                            </div>
                                            <p>Capacity: {{ $service['capacity']}}</p>

                                            @php
                                                $index = 1; 
                                            @endphp
                                            @if(!empty($airport['poligons']))
                                                @foreach($airport['poligons'] as $poligon)
                                                    
                                                    <div class="field" data-poligon="{{$poligon['id']}}">
                                                        <label class="label">Poligon-{{$index}}</label>
                                                        <div class="conta">
                                                            <div class="control is-flex">
                                                                <div class="column">
                                                                    <label for="label">ONE WAY</label>
                                                                    <input class="input" type="number" step="0.01" name="pr_oneway" placeholder="Enter price" min="0" pattern="^\d+(\.\d{1,2})?$">
                                                                    
                                                                </div>
                                                                <div class="column">
                                                                    <label for="label">Round Trip</label>
                                                                    <input class="input" type="number" step="0.01" name="pr_roundTrip" placeholder="Enter price" min="0" pattern="^\d+(\.\d{1,2})?$">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $index++; 
                                                    @endphp

                                                @endforeach
                                            @endif

                                            
                                        </div>
                                    </div>
                                </div>
                            
                                @endif
                        @endforeach
                        
                    </div>
                </div>

            <footer class="card-footer is-flex is-justify-content-flex-start">
                <button
                    type="submit"
                    class="button custom-btn_color mt-3 ml-3 mb-3 btn-save-pricing">
                    Save
                </button>
            </footer>
        </div>

    @endforeach
@else
    <div class="card">
        <div class="card-content">
            You don't have any services available to add prices yet.
        </div>
    </div>
@endif
</x-app-layout>