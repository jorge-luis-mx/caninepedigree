<x-app-layout>

    <h1 class="is-size-4 mb-5 ml-1">My Sales</h1>

    <div class="columns is-multiline">
        <div class="column is-full">
            <div class="card-content">
                <form action="{{ route('sales.search') }} " method="POST">
                    @csrf
                    <div class="columns">
                       
                        <div class="column">
                            <div class="field">
                                <label class="label" for="saleDate">Reservation Date</label>
                                <div class="control">
                                    <input
                                        id="saleDate"
                                        class="input"
                                        type="date"
                                        name="saleDate"
                                        value="{{$date ?? ''}}"
                                        required
                                        autofocus />
                                </div>
                            </div>
                        </div>

                       
                        <div class="column">
                            <div class="field">
                                <label class="label">&nbsp;</label> 
                                <div class="control">
                                    <button class="button is-warning is-fullwidth">
                                        <span class="icon"><i class="fas fa-search" aria-hidden="true"></i></span>
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="columns is-multiline">
    @if(!empty($salesData))
        <div class="column is-full">
            <div class="table-custom-sales">
                <div class="table-card-header">
                    <div class="content-header p-3">
                        <p class="ml-2"><strong><span>
                        @if(!empty($salesData))
                            {{ count($salesData) }}
                        @endif
                            
                        </span> Services were found</strong></p>
                    </div>
                    <div class="card-content">

                        <div class="table-container">
                            <table class="table is-fullwidth is-striped is-hoverable is-fullwidth is-tbl-1200">
                                <thead>
                                    <tr>
                                        <th class="is-narrow">Client</th>
                                        <th class="is-narrow">Service</th>
                                        <th class="is-narrow">Pax</th>
                                        <th >From - To</th>
                                        <th class="is-narrow">Arrival</th>
                                        <th class="is-narrow">Departure</th>
                                        <th class="is-narrow">Total</th>
                                        <th class="is-narrow">Payment</th>
                                        <th class="is-narrow">Channel</th>
                                        <th class="is-narrow">Booked</th>
                                        <th class="is-narrow">Status</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th class="is-narrow">Client</th>
                                        <th class="is-narrow">Service</th>
                                        <th class="is-narrow">Pax</th>
                                        <th>From - To</th>
                                        <th class="is-narrow">Arrival</th>
                                        <th class="is-narrow">Departure</th>
                                        <th class="is-narrow">Total</th>
                                        <th class="is-narrow">Payment</th>
                                        <th class="is-narrow">Channel</th>
                                        <th class="is-narrow">Booked</th>
                                        <th class="is-narrow">Status</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                
                                    @if(!empty($salesData))



                                        @foreach($salesData as $index => $data)

                                            @php

                                                $service = $data['operations'][0]->op_way =='A'?' Arrival':'Departure';
                                                $serviceType = $data['sale']->sale_transfer == 'RT'? 'Roundtrip': $service;

                                                $arrival = null;
                                                $timeArrival = null;

                                                $departure  = null;
                                                $timeDeparture = null;
                                                
                                            @endphp


                                            @foreach($data['operations'] as $index => $operation)
                                                @if($operation['op_way'] == 'A')
                                                    @php 
                                                        $arrival = null;
                                                        $timeArrival = null;

                                                        $arrival = $operation['op_date']->format('Y-m-d'); 
                                                        $timeArrival = $operation['op_time']->format('H:i:s'); 

                                                    @endphp
                                                @endif
                                                @if($operation['op_way'] == 'D')
                                                    @php 
                                                        $departure  = null;
                                                        $timeDeparture = null;
                                                        $departure = $operation['op_date']->format('Y-m-d');
                                                        $timeDeparture = $operation['op_time']->format('H:i:s');
                                                     @endphp
                                                @endif
                                            @endforeach

                                            <tr>
                                                <td>
                                                    <span class="is-block">{{ $data['client']->cli_as}}. {{$data['client']->cli_fullname}}</span>
                                                    <a href="{{ url('/sales/view/' . $data['sale']->sale_md_id) }}" target="_blank"><small>{{ $data['sale']->sale_invoice}}</small></a>
                                                </td>
                                                <td>
                                                    <span class="is-block">{{ $data['sale']->sale_service}}</span>

                                                    <span class="is-block" title="2025-03-07 11:00 AM, 2025-03-15 03:45 PM">{{ $serviceType }}</span> 
                                                </td>
                                                <td>
                                                    <span class="is-block"> {{ $data['operations'][0]->op_pax}}</span>
                                                </td>
                                                <td>
                                                    <span class="is-block">{{$data['operations'][0]->op_pickup_place}}                                  
                                                        <hr class="m-2 has-background-grey-light">{{$data['operations'][0]->op_dropoff_place}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="is-block has-text-centered">{{$arrival}}<br> {{$timeArrival}}</span>
                                                </td>
                                                <td>
                                                    <span class="is-block has-text-centered">{{$departure}} <br> {{$timeDeparture}} <br> </span>
                                                </td>
                                                <td>
                                                    <span class="is-block"> {{$data['sale']->sale_charge}} {{$data['sale']->sale_currency}}</span>
                                                </td>
                                                <td>
                                                    <span class="tag tag-custom tag-card">{{strtoupper($data['sale']->sale_gateway)}}</span>
                                                </td>
                                                <td>
                                                    <small class="is-block" title="">{{strtoupper($data['channel']->ch_name)}}</small>
                                                </td>
                                                <td>
                                                    <span class="is-block" title="">{{$data['sale']->sale_created->format('Y-m-d')}}</span>
                                                </td>
                                                <td>
                                                    <span class="tag tag-custom tag-completed">{{strtoupper($data['sale']->sale_status)}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="column is-full">
            <div class="notification is-danger is-light">
                <h1 class="is-size-4">Ouch! There are no reservations</h1>
                <p>Unfortunately for the consulted day there were no reservations for you, please stay tuned for future services.</p>
            </div>
        </div>
        @endif
    </div>



</x-app-layout>