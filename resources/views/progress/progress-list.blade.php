<x-app-layout>

    <h1 class="is-size-4 mb-5 ml-1">Configuration Progress</h1>
    <div class="card custom-card-info-progress">
        <div class="card-content">
            <div class="columns is-multiline ">
                <div class="column is-three-fifths">
                    <p>Consider this section as guide to know when your services as a Private Transportation provider are properly configured and then be one of the options for our travelers.</p>
                    <h2>How it works?</h2>
                    <p>For youur transport services to be available on our platform youuu must make sure you comply with the following points.</p>
                    <ol>
                        <li>You have registered at least 1 airport</li>
                        <li>You have created and associated a map to your airport</li>
                        <li>Have selected the type of service you provide</li>
                        <li>Enter the rates for your services</li>
                    </ol>
                </div>
                <div class="column is-flex is-justify-content-center is-align-items-center">
                    <div class="container is-flex is-flex-direction-column is-align-items-center">
                        <a href="/pricing" class="button is-warning custom-button-rates">Enter your rates</a>
                        <a href="/service/type" class="button is-warning custom-button-service">Choose your service type</a>
                        <a href="/map" class="button is-warning custom-button-map">Create a map</a>
                        <a href="/airport" class="button is-warning custom-button-airport">Register an Airport</a>

                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (!empty($progress))
        @foreach ($progress as $item)
            @php
                $progressValue =  null;
                $statusAirport = false;
                $statusMap = false;
                $statusService = false;
                $statusRates = false;
            @endphp

            @if(!empty($item['progress']['progressAirport']))
                
                @php $statusAirport = true; $progressValue = $item['progress']['progressAirport']; @endphp
                
            @endif

            @if(!empty($item['progress']['progressMap']))
            
                @php $statusMap =true; $progressValue = $item['progress']['progressMap']; @endphp

            @endif

            @if(!empty($item['progress']['progressService']))

                @php $statusService = true; $progressValue = $item['progress']['progressService']; @endphp

            @endif

            @if(!empty($item['progress']['progressPrices']))

                @php $statusRates = true; $progressValue = $item['progress']['progressPrices']; @endphp

            @endif

            
            <div class="card custom-card-progress">
                <div class="card-content">
                    
                        <div class="column is-full"><h3 class="is-size-6"><strong>{{ucwords($item['airport']['pvr_airport_alias'])}} Configuration</strong></h3></div>
                        <div class="column is-full">
                            <div class="container-title-progress is-flex">
                                <div class="{{ $statusAirport? 'item-progress-next' :'item-progress'}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FBA53E" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"/></svg>
                                    <span>Register an Airport</span>
                                </div>
                                <div class="{{ $statusMap? 'item-progress-next' :'item-progress'}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FBA53E" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"/></svg>
                                    <span>Create a Map</span>
                                </div>
                                <div class="{{ $statusService? 'item-progress-next' :'item-progress'}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FBA53E" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"/></svg>
                                    <span>Choose your services</span>
                                </div>
                                <div class="{{ $statusRates? 'item-progress-next' :'item-progress'}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#FBA53E" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4zM12 22q-2.075 0-3.9-.788t-3.175-2.137T2.788 15.9T2 12t.788-3.9t2.137-3.175T8.1 2.788T12 2t3.9.788t3.175 2.137T21.213 8.1T22 12t-.788 3.9t-2.137 3.175t-3.175 2.138T12 22m0-2q3.35 0 5.675-2.325T20 12t-2.325-5.675T12 4T6.325 6.325T4 12t2.325 5.675T12 20m0-8"/></svg>
                                    <span>Enter your rates</span>
                                </div>
                            </div>
                            <div class="container-progress">
                                <progress id="progress-bar" class="progress is-warning" value="{{ $progressValue}}" max="100"></progress>
                                <span id="progress-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold;">
                                    {{$progressValue }}%
                                </span>
                            </div>
                        </div>
                    
                </div>
            </div>
        @endforeach
    @else

    <div class="card custom-card-take-first-step ">
        <div class="card-content is-flex is-justify-content-center">
            <a href="/airport">Take the first step and <span>register an Airport</span></a>
        </div>
    </div>

    @endif


</x-app-layout>