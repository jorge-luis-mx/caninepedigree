<x-app-layout>
    
    <h1 class="is-size-4">{{__('messages.main.profile.title')}}</h1>

    <!-- @include('profile.partials.nav-profile') -->

    @include('profile.partials.notification')

    <div class="columns is-multiline">

        <div class="column is-full">
            <div class="card" style="box-shadow: none;">
                <div class="card-content">

                    <!-- <h2 class="title is-4 mb-4 pb-3">{{__('messages.main.profile.subtitle')}}</h2> -->

                    <form action="{{ route('profile.update') }}" method="post">
                        @csrf
                        @method('patch')
                        <!-- Campo: Company Name -->
                        <div class="field mb-4">
                            <label class="label" for="fullname">{{__('messages.main.profile.name')}}</label>
                            <div class="control">
                                <input
                                    class="input"
                                    type="text"
                                    name="fullname"
                                    value="{{$profileUser->name}}"
                                    >
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                       
                        <div class="columns is-multiline">

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="email">{{__('messages.main.profile.email')}}</label>
                                    <div class="control">
                                        <input
                                            class="input"
                                            type="email"
                                            name="email"
                                            value="{{$profileUser->email}}"
                                            >
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                            </div>

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="phone">{{__('messages.main.profile.phone')}}</label>
                                    <div class="control">
                                        <input
                                            class="input"
                                            type="text"
                                            name="phone"
                                            value="{{$profileUser->phone}}"
                                            >
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                </div>
                            </div>
                        </div>


                        
                        <div class="columns is-multiline">

                            <div class="column">
                                <div class="field">
                                    <label class="label" for="email">{{__('messages.main.profile.address')}}</label>
                                    <div class="control">
                                        <input
                                            class="input"
                                            type="address"
                                            name="address"
                                            value="{{$profileUser->address}}"
                                            >
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>
                            </div>

                            <div class="column">
                                <!-- Campo: Select -->
                                <div class="field ">
                                    <label class="label" for="country">{{__('messages.main.profile.country')}}</label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select id="country" name="country">

                                                @foreach ($countries as $code => $name)
                                                        <option value="{{ $code }}" {{ $code === $profileUser->country ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('country')" />
                                </div>
                            </div>

                        </div>


                        <div class="field mt-4">
                            <div class="control">

                            <button type="submit" id="btnUpdateMap" class="button  mt-3 p-2 btn-general">
                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 36 36"><path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" class="clr-i-outline--badged clr-i-outline-path-1--badged"/><path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" class="clr-i-outline--badged clr-i-outline-path-2--badged"/><path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" class="clr-i-outline--badged clr-i-outline-path-3--badged"/><circle cx="30" cy="6" r="5" fill="#fff" class="clr-i-outline--badged clr-i-outline-path-4--badged clr-i-badge"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                    
                                <span class="ml-1">{{ __('Save Changes') }}</span>
                            </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>