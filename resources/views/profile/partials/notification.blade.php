<div class="columns is-multiline">
    <div class="column is-full">
            @if(session('status') && session('message'))
                @php
                    $isError = session('status') === 'error'; // Ajusta el valor para definir cuándo es un error
                    $bgColor = $isError ? 'is-danger is-light' : 'is-success is-light';
                @endphp

                <div class="notification mb-3 {{ $bgColor }}"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="">
                    {{ session('message') }}
                </div>
            @endif
    </div>
</div>