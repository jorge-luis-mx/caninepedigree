<x-guest-layout>

<div class="w-full flex h-screen flex-col md:flex-row ">
    <!-- Content section -->
    <div class="hidden md:flex w-1/2  bg-custom-gray flex-col">
        
            <!-- <x-application-logo/> -->
            <!-- <x-image-component-logo image-path="{{ asset('assets/img/dog.jpg') }}" /> -->
    </div>

    <!-- Login section -->
    <div class="flex h-screen w-full md:w-1/2  bg-white flex items-center justify-center">
        <div class="w-full max-w-md pl-4 pr-4 bg-white">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <h1 class="text-3xl font-bold text-gray-900">
                    Welcome to 
                </h1>

                @if(request()->has('token'))
                    <input type="hidden" name="token" value="{{ request()->input('token') }}">
                @endif

                @if(request()->has('invoice'))
                    <input type="hidden" name="invoice" value="{{ request()->input('invoice') }}">
                @endif

                <!-- Login Input -->
                <div class="mt-5">
                    <x-input-label for="login" :value="__('Email (Username)')" />
                    <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4 flex justify-end">
                    <!-- <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label> -->
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif
                </div>

                <!-- Forgot Password and Login Button -->
                <div class="mt-5">
                    <x-primary-button class="w-full">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</div>

</x-guest-layout>