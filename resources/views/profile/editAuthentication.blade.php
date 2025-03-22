<x-app-layout>

    <h1 class="is-size-4">Authentication</h1>

    @include('profile.partials.nav-profile')
    
    @include('profile.partials.notification')
    <div class="columns is-multiline">
        
        <div class="column is-full">
            @include('profile.partials.update-user')
        </div>

        <div class="column is-full">

            @include('profile.partials.update-password')

        </div>
        <div class="column is-full">

            @include('profile.partials.delete-user')

        </div>

    </div>

</x-app-layout>