<x-app-layout>

    <h1 class="is-size-4 mb-5 ml-1">My Operation</h1>


    <div class="columns is-multiline">
        <div class="column is-full">
            <div class="card-content">
                <form action="{{ route('operations.search') }}" method="POST">
                    @csrf
                    <div class="columns">
                        <!-- Columna para el campo de fecha -->
                        <div class="column">
                            <div class="field">
                                <label class="label" for="operaDate">Operation Date</label>
                                <div class="control">
                                    <input
                                        id="operaDate"
                                        class="input"
                                        type="date"
                                        name="operaDate"
                                        value="{{$date ?? ''}}"
                                        required
                                        autofocus />
                                </div>
                            </div>
                        </div>

                        <!-- Columna para el botón de búsqueda -->
                        <div class="column">
                            <div class="field">
                                <label class="label">&nbsp;</label> <!-- Espacio para alineación -->
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

    @if(!empty($saleOperations) && count($saleOperations) > 0)
        <div class="column is-full">
            <div class="card-custom-operation">
                <div class="card-header">
                    <div class="content-header p-3">
                        <p><strong><span>{{ count($saleOperations) }}</span> Services were found</strong></p>
                    </div>
                </div>
                <div class="card-content">

                    @foreach($saleOperations as $operation)
                    <div class="card-operation is-flex is-flex-direction-column" data_sale="{{$operation['sale']->sale_id}}">

                        <div class="content-operation-star">
                            <div class="p-3">
                                <div class="hrs is-flex is-justify-content-space-between">
                                    <div class="hrs-item">
                                        <span class="is-size-5">{{ $operation['operation']->op_time->format('H:i') }} HRS</span>
                                    </div>
                                    <div class="hrs-item">
                                        <span><strong>Invoice</strong></span>
                                        <p>{{ $operation['sale']->sale_invoice }}</p>
                                    </div>
                                    <div class="hrs-item">
                                        <span><strong>Client</strong></span>
                                        <p>{{ $operation['client']->cli_as}}.{{ $operation['client']->cli_fullname}}</p>
                                    </div>
                                    <div class="hrs-item">
                                        <span><strong>Service</strong></span>
                                        <p>{{ $operation['sale']->sale_service }}</p>
                                    </div>
                                    <div class="hrs-item">
                                        <span><strong>Passengers</strong></span>
                                        <p>{{ $operation['operation']->op_pax }} {{ $operation['operation']->op_pax >= 2 ? 'Passengers' : 'Passenger' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="content-operation-center">
                            <div class="p-3 is-flex is-justify-content-space-between">
                                <div class="item-from">
                                    
                                    <span><strong>From</strong></span>
                                    <p>{{ $operation['operation']->op_pickup_place}}</p>
                                </div>
                                <div class="item-from">
                                    <span><strong>To</strong></span>
                                    <p>{{ $operation['operation']->op_dropoff_place }}</p>
                                </div>
                            </div>
                        </div>
                        

                        <div class="content-operation-end">

                            <div class="p-3 is-flex is-justify-content-space-between">
                                <div class="item-details">
                                    <span><strong>Date service</strong></span>
                                    <p>{{ $operation['operation']->op_date->format('F j, Y') }}</p>
                                </div>
                                <div class="item-details">
                                    <span><strong>Schedule service</strong></span>
                                    <p>{{ $operation['operation']->op_time->format('H:i')}} Hrs</p>
                                </div>
                                <div class="item-details">
                                    <span><strong>Airline</strong></span>
                                    <p>{{$operation['operation']->op_airline}}</p>
                                </div>
                                <div class="item-details">
                                    <span><strong>Number flight</strong></span>
                                    <p>{{$operation['operation']->op_flight_number}}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
        @else

            <div class="column is-full">
                <div class="notification is-danger is-light">
                    <h1 class="is-size-4">Ouch! There are no operations.</h1>
                    <p>Unfortunately, there are no operations available for the selected day. Please stay tuned for future updates and services.</p>
                </div>
            </div>

        @endif
    </div>


</x-app-layout>