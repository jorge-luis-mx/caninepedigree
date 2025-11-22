@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'help is-danger']) }}>
        @foreach ((array) $messages as $message)
            <p>{{ $message }}</p>
        @endforeach
    </div>
@endif