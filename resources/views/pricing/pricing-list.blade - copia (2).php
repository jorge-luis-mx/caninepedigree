<x-app-layout>

@if(!empty($airportsWithServices))
    <!-- <div class="notification-dinamy notification is-hidden"></div> -->
    
    <h1 class="">Price Chart</h1>
    <div class="bg-blue-100 mb-10 rounded-md border-2 border-blue-300 p-5">
        <p>Here you have to set the price for each area. You will have two cases for each area and for each type of service, the public price and the net price, the net price would be the one you will get paid.</p>
    </div>
    @php
        // Contar los elementos que cumplen la condición
        $validAirports = collect($airportsWithServices)->filter(function($airport) {
            return !empty($airport['maps']) && !empty($airport['maps']['poligons']) && is_array($airport['maps']['poligons']);
        });
    @endphp

    @php
        $isActiveAssigned = false;
    @endphp



    <div id="tabs-prices" class="custom-tabs custom-slider-pricing mt-3 {{ $validAirports->isEmpty() ? 'is-hidden' : '' }}" >
        <ul>
            @foreach($airportsWithServices as $index => $airport)

            
                @if (!empty($airport['maps']) && !empty($airport['maps']['poligons']) && is_array($airport['maps']['poligons']) )
                        <a href="{{ $airport['id_airport']}}" class="{{ !$isActiveAssigned ? 'is-active' : '' }} item-service " data-airport="{{ $airport['id_airport']}}">
                            <li>
                                <span>{{ ucfirst($airport['airport_alias'])}}</span>
                            </li>
                        </a>

                    @php
                        $isActiveAssigned = true; // Marcar que 'is-active' ya fue asignado
                    @endphp
                @endif

            @endforeach
        </ul>
    </div>

    
    @foreach($airportsWithServices as $airport)
        @if (!empty($airport['maps']) && !empty($airport['maps']['poligons']) && is_array($airport['maps']['poligons']))
   
        <div class="content content-pricing" id="{{ $airport['id_airport']}}" data-map="{{$airport['maps']['pvr_map_id']}}">
            <div class="box-content">
                <div class="table-container">
                    <table class="table is-fullwidth " >
                        <thead>
                            <tr>
                                @if(is_array($airport['services']) && !empty($airport['services']))
                                    <th>Area</th>
                                    @foreach( $airport['services'] as $service)
                                        @if($service['status']===1)
                                            <th class="table-title">
                                                {{ucfirst($service['servicesType']['ser_typ_alias'])}}
                                            </th>
                                        @endif
                                    @endforeach
                                @endif
                                
                            </tr>
                        </thead>

                        <tbody>
                        
                            @if(is_array($airport['services']) && !empty($airport['services']))


                                @if(!empty($airport['maps']['poligons']))
                                
                                    @foreach($airport['maps']['poligons'] as $key => $poligon)



                                        <tr data-poligon="{{ $poligon['id'] }}">
                                            <td>{{ ucfirst($poligon['name']) }}</td>

                                            @foreach($airport['services'] as $keySegundo => $service)
                                                @if($service['status'] == 1)
                                                    @php
                                                        $fee = null;
                                                        // Reiniciar las variables para cada servicio
                                                        $hasPrice = false;
                                                        $priceData = null;

                                                        // Verificar si el servicio tiene precios asociados al polígono actual
                                                        if (!empty($service['prices'])) {
                                                            foreach ($service['prices'] as $price) {
                                                                if ($poligon['id'] == $price['pricing_polygon_id'] && $service['pvr_ser_id'] == $price['pvr_ser_id']) {
                                                                    $hasPrice = true;
                                                                    $priceData = $price;
                                                                    break; // Salir del bucle si encontramos el precio correspondiente
                                                                }
                                                            }
                                                        }

                                                        $hasPriceFee =  false;
                                                        $priceDataFee = null;

                                                        if (!empty($service['retentionFee']) ) {

                                                            foreach ($service['retentionFee'] as $fee) {
                                                                
                                                                if ($poligon['id'] == $fee['pricing_polygon_id'] && $service['pvr_ser_id'] == $fee['pvr_ser_id']) {
                                                                    $hasPriceFee = true;
                                                                    $priceDataFee = $fee;
                                                                    break; // Salir del bucle si encontramos el precio correspondiente
                                                                }
                                                            }
                                                        }

                                                        if($hasPrice){

                                                            $fee = $priceData['retentionFee'];
                                                        }
                                                        if($hasPriceFee){

                                                            $fee = $priceDataFee['retentionFee'];
                                                        }
                                                        
                                                       
                                                    @endphp

                                                    <td data-service="{{ $service['pvr_ser_id'] }}" data-retention="{{ $fee }}">

                                                        <div class="card">
                                                            <div class="card-content">
                                                                <div class="prices-card-content">
                                                                    <span>Suggested public price</span>
                                                                    <div class="control is-flex">
                                                                        <div class="input-container" style="flex: 1; min-width: 115px;">
                                                                            <div class="container-label">
                                                                                <label for="pr_oneway" class="label">One Way</label>
                                                                            </div>
                                                                            <input class="input no-border-input oneWay" type="number" step="0.01" name="pr_oneway" placeholder="Enter price" min="0" pattern="^\d+(\.\d{1,2})?$"
                                                                                value="{{ $hasPrice ? $priceData['oneWay'] : '' }}">
                                                                        </div>
                                                                        <div class="input-container" style="flex: 1; min-width: 115px;">
                                                                            <div class="container-label">
                                                                                <label for="pr_roundTrip" class="label">Round Trip </label>
                                                                            </div>
                                                                            <input class="input no-border-input roundTrip" type="number" step="0.01" name="pr_roundTrip" placeholder="Enter price" min="0" pattern="^\d+(\.\d{1,2})?$"
                                                                                value="{{ $hasPrice ? $priceData['roundTrip'] : '' }}">
                                                                        </div>
                                                                    </div>

                                                                    <span>Amount you will get paid</span>
                                                                    <div class="control is-flex ">
                                                                        <div class="input-container" style="flex: 1; min-width: 115px;">
                                                                            <div class="container-label">
                                                                                <label for="pr_oneway" class="label">One Way</label>
                                                                            </div>
                                                                            <input class="input no-border-input oneWay" type="text" name="pr_oneway" value="{{ $hasPrice ? number_format(floatval($priceData['retentionAmountOneWay'] ?? 0), 2) : '' }}" readonly>
                                                                            
                                                                        </div>

                                                                        <div class="input-container" style="flex: 1; min-width: 115px;">
                                                                            <div class="container-label">
                                                                                <label for="pr_roundTrip" class="label">Round Trip</label>
                                                                            </div>
                                                                            <input class="input no-border-input roundTrip" type="text" name="pr_roundTrip" value="{{ $hasPrice ? number_format(floatval($priceData['retentionAmountRoundTrip'] ?? 0), 2) : '' }}" readonly>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach



                                @endif
                                    
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
     @endforeach

    @if($validAirports->isEmpty())
      <p>You don't have a map available to add prices yet.</p>                                           
    @endif

@else
    <div class="card">
        <div class="card-content">
            You don't have any services available to add prices yet.
        </div>
    </div>
@endif
</x-app-layout>