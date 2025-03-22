<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    @if(session('status') && session('message'))
        @php
            $isError = session('status') === 'error'; // Ajusta el valor para definir cuándo es un error
            $bgColor = $isError ? 'bg-red-100' : 'bg-green-100';
            $borderColor = $isError ? 'border-red-500' : 'border-green-500';
            $textColor = $isError ? 'text-red-700' : 'text-green-700';
        @endphp

        <div class="{{ $bgColor }} {{ $borderColor }} {{ $textColor }} rounded-lg mt-3"
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2000)"
        >
            <div class="mb-4 text-sm text-gray-600 pl-4 pr-4 pb-4 p-5">
                {{ session('message') }}
            </div>
        </div>
    @endif

    <form method="post" action="" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="pvr_name" type="text" class="mt-1 block w-full" :value="old('name', $provider[0]->pvr_name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('pvr_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="pvr_email" type="email" class="mt-1 block w-full" :value="old('email', $provider[0]->pvr_email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('pvr_email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>           
        </div>
    </form>
</section>
