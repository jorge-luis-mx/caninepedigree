<x-app-layout>




<h1 class="is-size-4 mb-5 ">Reservation #{{$detailsOperations->sale->sale_invoice}}</h1>
    <section class=" tab py-0 columns is-multiline is-centered mt-1">
        <div class="column is-12">
            <div class="card is-family-sans-serif has-text-weight-bold has-background-warning">

                <div class="has-text-black is-size-5 card-content columns is-multiline is-justify-content-space-between">
                    <div class="column is-4 ">
                        <p class="has-text-white mb-0">{{$detailsOperations->sale->sale_invoice}}</p>
                        <p class="is-capitalized mb-0 ">{{$detailsOperations->client->cli_as }} {{$detailsOperations->client->cli_fullname}}</p>
                        <div class="has-text-grey-dark  is-italic has-text-weight-normal is-size-6">
                            <p class="mb-0" style="letter-spacing: 1;">{{$detailsOperations->client->cli_email }}</p>
                            <p>+{{$detailsOperations->client->cli_lada_phone }} {{$detailsOperations->client->cli_phone }} / {{$detailsOperations->client->cli_country }}</p>
                        </div>
                    </div>

                    <div class="column is-4 is-align-content-center">
                        <p style="line-height: normal;" class="mb-0 has-text-white is-size-4">{{$detailsOperations->sale->sale_charge}}<small>{{$detailsOperations->sale->sale_currency}}</small></p>
                        <span class="is-uppercase has-text-white tag is-warning">
                        {{ strtoupper($detailsOperations->sale->sale_status) }}
                        </span>

                        <p class="has-text-grey-dark mt-3  is-italic has-text-weight-normal is-size-7">{{$detailsOperations->sale->sale_created->format('F j, Y') }} </p>


                    </div>


                </div>
            </div>

        </div>

        <div class="column is-12  mobile ">
            <div class="card mt-1">
                <header class="card-header mb-5">
                    <p class="card-header-title">
                        My Operation Details
                    </p>
                </header>
                <div class="card-content px-6 columns is-multiline  mb-0 pb-0 ml-4 ">


                    <div class=" column is-3  px-0">
                        <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Transfer Type</label>
                        <div class="control">
                            <label id="transfer_type" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                            {{ $detailsOperations->sale->sale_transfer =='RT'?  'Round Trip' : 'One Way' }}  </label>
                        </div>
                    </div>

                    <div class=" column is-3  px-0">
                        <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Service Type</label>
                        <div class="control">
                            <label id="service_type" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                            {{ $detailsOperations->sale->sale_service}}</label>
                        </div>
                    </div>

                    <div class=" column is-3  px-0">
                        <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Passengers</label>
                        <div class="control">
                            <label id="passengers" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{ $detailsOperations->operations[0]->op_pax}}</label>
                        </div>
                    </div>

                    <div class=" column is-3  px-0">
                        <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">number of vehicles</label>
                        <div class="control">
                            <label id="cant-vehiculo" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                            {{ $detailsOperations->operations[0]->op_vehicle_qty}}</label>
                        </div>
                    </div>



                </div>
                <div class="card-content px-6 columns is-multiline ">
                    @foreach($detailsOperations->operations as $operation)
                    <div class="card column  mx-4 px-0">
                        <header class="card-header mb-5">
                            <h4 class="card-header-title">
                                @php
                                    $opWay = $operation->op_way =='A'? 'Arrival Details':'Departure Details';
                                @endphp
                                <img class="ml-3 mr-1" src="https://platform.airporttransportation.com/assets/img/plane-arrival.svg" alt="">
                                {{$opWay}}
                            </h4>
                        </header>
                        <div class="card-content pt-0 pb-5">


                            <div class="columns is-multiline pt-3 px-5">
                                <div class=" column is-12  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">From</label>
                                    <div class="control">
                                        <label id="from" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_pickup_place }}</label>
                                    </div>
                                </div>

                                <div class=" column is-12  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">To</label>
                                    <div class="control">
                                        <label id="to" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_dropoff_place }}</label>
                                    </div>
                                </div>


                                <div class=" column is-6  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Date</label>
                                    <div class="control">
                                        <label id="op_date" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_date->format('Y-m-d') }}</label>
                                    </div>
                                </div>


                                <div class=" column is-6  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Time</label>
                                    <div class="control">
                                        <label id="op_time" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_time->format('H:i') }}</label>
                                    </div>
                                </div>

                                <div class=" column is-6  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Airline</label>
                                    <div class="control">
                                        <label id="op_airline" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_airline }}</label>
                                    </div>
                                </div>

                                <div class=" column is-6  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Flight Number</label>
                                    <div class="control">
                                        <label id="op_flight_number" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_flight_number }}</label>
                                    </div>
                                </div>

                                <div class=" column is-6  px-0">
                                    <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Flight Time  {{ $operation->op_way =='A'?  'Arrival' : 'Departure' }} </label>
                                    <div class="control">
                                        <label id="op_flight_time" class="py-0 is-size-6 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                        {{ $operation->op_flight_time->format('H:i') }}</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach


                </div>

                <div class="card-content px-6 pb-0 ">
                    <h6 class="mx-4 mb-5">
                        Special Request &amp; Comments
                    </h6>



                    <div class="px-6 columns is-multiline  mb-0 pb-5">

                        <div class="card column is-auto">
                            <div class="card-content pb-0">
                                <div class="media mb-0">
                                    <div class="media-left">
                                        <figure class="image is-32x32">

                                            <img src="https://platform.airporttransportation.com/assets/img/user-bg.svg" class="is-rounded" alt="Placeholder image">
                                        </figure>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-4 is-size-6">{{ucfirst(strtolower($detailsOperations->salesInfo->sif_request))}} <span class="is-size-7 has-text-grey is-italic has-text-weight-light"> Lng: {{$detailsOperations->salesInfo->sif_language}}</span></p>
                                        <p class="subtitle is-size-6 has-text-grey is-italic"> {{$detailsOperations->salesInfo->sif_created}} </p>
                                    </div>
                                </div>

                                <div class="content pl-0 pt-2 has-background-white has-text-grey"></div>

                            </div>
                        </div>



                    </div>
                </div>

                <div class="card-content px-6  ">
                    <div class="card is-family-sans-serif has-text-weight-bold has-text-white has-background-warning px-6 py-5 columns is-multiline mx-1">
                        <div class=" column is-4  px-0">
                            <label class="label mb-0 has-text-weight-bold is-size-6 has-text-black mb-2" style="line-height: 1; font-size: 0.90rem; ">Transfer Amount</label>
                            <div class="control">
                                <label id="sale_amount" class="py-0 is-size-4 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{$detailsOperations->sale->sale_amount}}<small>{{$detailsOperations->sale->sale_currency}}</small></label>
                            </div>
                        </div>

                        <div class=" column is-4  px-0">
                            <label class="label mb-0 has-text-weight-bold is-size-6 has-text-black mb-2" style="line-height: 1; font-size: 0.90rem; ">Discount</label>
                            <div class="control">
                                <label id="sale_discount" class="py-0 is-size-4 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{$detailsOperations->sale->sale_discount}}<small>{{$detailsOperations->sale->sale_currency}}</small></label>
                            </div>
                        </div>

                        <div class=" column is-4  px-0">
                            <label class="label mb-0 has-text-weight-bold is-size-6 has-text-black mb-2" style="line-height: 1; font-size: 0.90rem; ">Total</label>
                            <div class="control">
                                <label id="op_flight_time" class="py-0 is-size-4 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{$detailsOperations->sale->sale_charge}}<small>{{$detailsOperations->sale->sale_currency}}</small></label>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-content px-6  ">
                    <div class=" px-6 columns is-multiline  mb-0 pb-0   is-justify-content-space-between mt-1 ">


                        <div class=" column is-3  px-0">
                            <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Payment Method</label>
                            <div class="control">
                                <label id="" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{ucfirst(strtolower($detailsOperations->payments->pay_method ?? ''))}}</label>
                            </div>
                        </div>

                        <div class=" column is-3  px-0">
                            <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Provider Gateway</label>
                            <div class="control">
                                <label id="" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{ ucfirst(strtolower($detailsOperations->payments->pay_platform?? '')) }}</label>
                            </div>
                        </div>


                        <div class=" column is-3  px-0">
                            <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Channel Sale</label>
                            <div class="control">
                                <label id="" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{ucfirst(strtolower($detailsOperations->channel->ch_name))}}</label>
                            </div>
                        </div>

                        <div class=" column is-3  px-0">
                            <label class="label mb-0 has-text-grey-dark has-text-weight-normal" style="line-height: 1; font-size: 0.90rem; ">Sale Payment Fee</label>
                            <div class="control">
                                <label id="cant-vehiculo" class="py-0 is-size-5 has-text-weight-bold" style="height: fit-content; line-height: 1;">
                                {{$detailsOperations->sale->sale_payment_fee}}<small>{{$detailsOperations->sale->sale_currency}}</small></label>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

        </div>
    </section>

</x-app-layout>