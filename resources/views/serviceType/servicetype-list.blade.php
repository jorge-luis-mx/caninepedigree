<x-app-layout>


    @if(!empty($airportsWithServices))

    <!-- <div class="notification-dinamy notification is-hidden"></div> -->
    <h1 class="">My Fleet</h1>
    <div class="bg-blue-100 mb-10 rounded-md border-2 border-blue-300 p-5">
        <p>Select the types of services you are able to provide from the selected airports. The images shown here are of the category type we want to provide, it doesn’t have to be exactly the same vehicle, but a similar one and able to carry the same amount of people.</p>
    </div>
    <div class="tabs is-boxed custom-slider">
        <ul>
            @foreach($airportsWithServices as $index => $airport)
            <li class="{{ $index === 0 ? 'is-active' : '' }}" data-airport="{{ $airport['id_airport']}}">
                <a href="{{ $airport['id_airport']}}" class="item-service">
                    <span>{{ ucfirst($airport['airport_alias'])}}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    @foreach($airportsWithServices as $index =>$airport)
        <div class="content-service content" id="{{ $airport['id_airport']}}" data-provider="{{$airport['id_provider']}}">
            <div class="">
                <article class="item" >
                    <div class="columns is-multiline is-flex is-justify-content-flex-start" >
                        @foreach( $airport['services'] as $serviceType)

                            @php $isCheckedOpacity = in_array($serviceType->ser_typ_id, $airport['servicesExist']) ? '' : 'custom-column-opacity'; @endphp
                            @php $isChecked = in_array($serviceType->ser_typ_id, $airport['servicesExist']) ? '' : 'is-hidden'; @endphp
                            @php $isCheckedBox = in_array($serviceType->ser_typ_id, $airport['servicesExist']) ? 1 : 0 ; @endphp

                            @php $passengers = $serviceType->ser_typ_capacity <=1 ? 'Passenger' : 'Passengers' ; @endphp

                            <div class="column custom-column {{ $isCheckedOpacity }}" data-checkbox ="{{ $isCheckedBox }}" data-service-type="{{ $serviceType->ser_typ_id}}" data-airport="{{ $airport['id_airport']}}" data-provider="{{$airport['id_provider']}}">
                                <div class="card has-fixed-size">
                                    <div class="card-image">
                                        <figure class="image">
                                            <img src="{{$airport['urlImg']}}{{ $serviceType->ser_typ_avatar }}" class="!w-2/3 block mx-auto" alt="Placeholder image"/>
                                        </figure>
                                        <div class="icon-checkbox {{ $isChecked }} isChecked">
                                            <div class="is-flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"><path fill="#FBA53E" d="M256 512a256 256 0 1 0 0-512a256 256 0 1 0 0 512m113-303L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="columns is-multiline">
                                            <div class="column is-half mt-4">
                                                <div class="is-flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FBA53E" d="M16 6H6l-5 6v3h2a3 3 0 0 0 3 3a3 3 0 0 0 3-3h6a3 3 0 0 0 3 3a3 3 0 0 0 3-3h2v-3c0-1.11-.89-2-2-2h-2zM6.5 7.5h4V10h-6zm5.5 0h3.5l1.96 2.5H12zm-6 6A1.5 1.5 0 0 1 7.5 15A1.5 1.5 0 0 1 6 16.5A1.5 1.5 0 0 1 4.5 15A1.5 1.5 0 0 1 6 13.5m12 0a1.5 1.5 0 0 1 1.5 1.5a1.5 1.5 0 0 1-1.5 1.5a1.5 1.5 0 0 1-1.5-1.5a1.5 1.5 0 0 1 1.5-1.5"/></svg>
                                                    <span class="ml-1"><strong>{{ $serviceType->ser_typ_alias}}</strong></span>
                                                </div>
                                                <span class="ml-1">Type service</span>
                                                
                                            </div>
                                            <div class="column is-half mt-4">
                                                <div class="is-flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FBA53E" d="M5.35 5.64c-.9-.64-1.12-1.88-.49-2.79c.63-.9 1.88-1.12 2.79-.49c.9.64 1.12 1.88.49 2.79c-.64.9-1.88 1.12-2.79.49M16 19H8.93c-1.48 0-2.74-1.08-2.96-2.54L4 7H2l1.99 9.76A5.01 5.01 0 0 0 8.94 21H16zm.23-4h-4.88l-1.03-4.1c1.58.89 3.28 1.54 5.15 1.22V9.99c-1.63.31-3.44-.27-4.69-1.25L9.14 7.47c-.23-.18-.49-.3-.76-.38a2.2 2.2 0 0 0-.99-.06h-.02a2.27 2.27 0 0 0-1.84 2.61l1.35 5.92A3.01 3.01 0 0 0 9.83 18h6.85l3.82 3l1.5-1.5z"/></svg>
                                                    <span class="ml-1"><strong>{{ $serviceType->ser_typ_capacity}}</strong></span>
                                                    <span class="ml-1"><strong>{{$passengers}}</strong></span>
                                                </div>
                                                <span class="ml-1">Capacity</span>
                                            </div>
                                            <div class="column is-half is-hidden">
                                                @if(in_array($serviceType->ser_typ_id, $airport['servicesExist']))
                                                    <span>Unassign</span>
                                                @else
                                                    <span>Assign</span>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @endforeach
                    </div>
                </article>
            </div>
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