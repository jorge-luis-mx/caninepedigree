<x-guest-layout>

    <div class="w-full flex h-screen flex-col md:flex-row ">

        <div class="hidden md:flex w-1/2  bg-custom-gray flex-col">
            <!-- (logo) -->
            <x-application-logo/>
            <x-image-component-logo image-path="{{ asset('assets/img/taxi_airport.png') }}" />
        </div>

        <div class="flex h-screen w-full md:w-1/2  bg-white flex items-center justify-center">
            <div class="w-full max-w-md pl-4 pr-4 bg-white">
                <div class="bg-green-100  border-green-500 text-green-700  rounded-lg">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3 pl-4 pr-4 pt-4">
                        Email password change sent
                    </h1>
                    <div class="mb-4 text-sm text-gray-600 pl-4 pr-4 pb-4">
                        {{ __('We have sent to  ') }}
                        {{$email}}

                        {{__('the instructions to follow in order to change your password. If you have not received any email after 20 minutes, please check your spam folder.')}}
                    </div>
                </div>


                <!-- Session Status -->
                <a href="/" class="mt-2 w-full inline-flex items-center justify-center px-3 py-1 border border-blue-500 text-blue-500 font-semibold rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                    Back to Login
                </a>
            </div>
        </div>

    </div>

    <!-- Content section -->
    <!-- <div class="w-1/2 h-screen bg-custom-gray flex flex-col">
            
            <x-application-logo/>
            <x-image-component-logo image-path="{{ asset('assets/img/taxi_airport.png') }}" />
    </div> -->

    <!-- Login section -->
    <!-- <div class="w-1/2 bg-white flex items-center justify-center">
        <div class="w-full max-w-md p-6 bg-white">
            <div class="bg-green-100  border-green-500 text-green-700  rounded-lg">
                <h1 class="text-3xl font-bold text-gray-900 mb-3 pl-4 pr-4 pt-4">
                    Email password change sent
                </h1>
                <div class="mb-4 text-sm text-gray-600 pl-4 pr-4 pb-4">
                    {{ __('We have sent to  ') }}
                    {{$email}}

                    {{__('the instructions to follow in order to change your password. If you have not received any email after 20 minutes, please check your spam folder.')}}
                </div>
            </div>


            
            <a href="/" class="mt-2 w-full inline-flex items-center justify-center px-3 py-1 border border-blue-500 text-blue-500 font-semibold rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                Back to Login
            </a>
        </div>
    </div> -->

</x-guest-layout>
