<x-app-layout>

<div class="container mt-5">
    <div class="notification is-danger">
        <strong>This dog has no registered breeding.</strong><br>
        You cannot register puppies without a previous breeding.
    </div>

    <a href="{{ route('dogs.index') }}" class="button is-primary mt-3">
        Go back
    </a>
</div>

</x-app-layout>
