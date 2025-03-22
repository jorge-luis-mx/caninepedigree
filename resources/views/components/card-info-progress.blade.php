
@props(['description', 'title', 'instructions', 'isAdvanced'])

<div class="card custom-card-info-progress">
    <div class="card-content">
        <div class="columns is-multiline">
            <div class="column is-three-fifths">
                @if($description)
                    <p>{{ $description }}</p>
                @endif
                <h2>{{ $title }}</h2>
                <p>For your transport services to be available on our platform you must make sure you comply with the following points.</p>
                <ol>
                    @foreach ($instructions as $instruction)
                        <li>{{ $instruction }}</li>
                    @endforeach
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
