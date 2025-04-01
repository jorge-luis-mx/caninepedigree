<x-guest-layout>

<div class="w-full flex h-screen flex-col md:flex-row ">

    <div class="hidden md:flex w-1/2  bg-custom-gray flex-col">
        <!-- (logo) -->
        <!-- <x-application-logo/> -->
         logo
        <!-- <x-image-component-logo image-path="{{ asset('assets/img/taxi_airport.png') }}" /> -->
    </div>

    <div class="flex h-screen w-full md:w-1/2  bg-white flex items-center justify-center">
        <div class="w-full max-w-md pl-4 pr-4 bg-white">

            <div class="mb-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="flex flex-col items-center justify-end mt-4">
                    <x-primary-button  class="w-full">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>

                    <a href="/" class="mt-2 w-full inline-flex items-center justify-center px-3 py-1 border border-blue-500 text-blue-500 font-semibold rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                        Back to Login
                    </a>

                </div>
            </form>

        </div>
    </div>
</div>


</x-guest-layout>
