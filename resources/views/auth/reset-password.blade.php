<x-guest-layout>

<div class="w-full flex h-screen flex-col md:flex-row ">
    <!-- Content section -->
    <div class="hidden md:flex w-1/2  bg-custom-gray flex-col">
            <!-- (logo) -->
            <x-application-logo/>
            <x-image-component-logo image-path="{{ asset('assets/img/taxi_airport.png') }}" />
    </div>

    <div class="flex h-screen w-full md:w-1/2  bg-white flex items-center justify-center">
        <div class="w-full max-w-md pl-4 pr-4 bg-white">

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>

                    @if($errors->get('email'))
                        <div class="bg-red-100 border-red-500 text-red-700 p-4 rounded-lg">
                            <div class="mb-4 text-sm text-gray-600">
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
                            </div>
                        </div>
                    @endif
                    
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                        type="password"
                                        name="password_confirmation" required autocomplete="new-password" />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="w-full">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>


</x-guest-layout>
