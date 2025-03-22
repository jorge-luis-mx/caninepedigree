<x-app-layout>
    @if (!empty($pricing))
        <h1 class="">Price Chart</h1>
        <div class="bg-blue-100 mb-10 rounded-md border-2 border-blue-300 p-5">
            <p>Here you have to set the price for each area. You will have two cases for each area and for each type of
                service, the public price and the net price, the net price would be the one you will get paid.</p>
        </div>

        <div>
            <div>
                @foreach ($pricing as $airport)
                    <a>{{ $airport['airport'] }}</a>

                    @foreach ($airport['areas'] as $area)
                        <a>{{ $area['area'] }}</a>

                        @foreach ($area['services'] as $service)
                            <div class="p-5 shadow-lg rounded-md">
                                <b>{{ $service['serviceAlias'] }}</b>
                                <div>
                                    <span>Oneway Service</span>
                                    <div>
                                        <input type="text"
                                            value="{{ $service['pricing'] != null ? $service['pricing']['oneway'] : 0 }}">
                                        <small>Suggested public price</small>
                                    </div>
                                    <div>
                                        @if ($service['pricing'] != null)
                                            <span>{{ $service['pricing']['oneway'] - $service['pricing']['oneway'] * 0.3 }}</span>
                                        @else
                                            <span>0</span>
                                        @endif
                                        <small>Amount you will get paid</small>
                                    </div>
                                </div>
                                <div>
                                    <span>Roundtrip Service</span>
                                    <div>
                                        <input type="text"
                                            value="{{ $service['pricing'] != null ? $service['pricing']['roundtrip'] : 0 }}">
                                        <small>Suggested public price</small>
                                    </div>
                                    <div>
                                        @if ($service['pricing'] != null)
                                            <span>{{ $service['pricing']['roundtrip'] - $service['pricing']['roundtrip'] * 0.3 }}</span>
                                        @else
                                            <span>0</span>
                                        @endif
                                        <small>Amount you will get paid</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endforeach
            </div>



        </div>
    @else
        <div class="card">
            <div class="card-content">
                You don't have any services available to add prices yet.
            </div>
        </div>
    @endif
</x-app-layout>
