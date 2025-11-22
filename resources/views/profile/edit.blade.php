<x-app-layout>
    
    <h1 class="is-size-4">{{__('messages.main.profile.title')}}</h1>

    @include('profile.partials.notification')

    <div class="columns is-multiline">
        <div class="column is-full">
            <div class="card" style="box-shadow: none;">
                <div class="card-content">

                    <form action="{{ route('profile.update') }}" method="post">
                        @csrf
                        @method('patch')
                        
                        <!-- Primera fila: First Name, Last Name y Checkbox -->
                        <div class="columns is-multiline">
                            <div class="column">
                                <div class="field mb-4">
                                    <label class="label" for="first_name">{{__('messages.main.profile.first_name')}}</label>
                                    <div class="control">
                                        <input
                                            class="input {{ $errors->has('first_name') ? 'is-danger' : '' }}"
                                            type="text"
                                            name="first_name"
                                            id="first_name"
                                            value="{{ old('first_name', $profileUser->first_name) }}"
                                        >
                                    </div>
                                    <x-input-error :messages="$errors->get('first_name')" />
                                </div>
                            </div>
                            
                            <div class="column">
                                <div class="field mb-4">
                                    <label class="label" for="last_name">{{__('messages.main.profile.last_name')}}</label>
                                    <div class="control">
                                        <input
                                            class="input {{ $errors->has('last_name') ? 'is-danger' : '' }}"
                                            type="text"
                                            name="last_name"
                                            id="last_name"
                                            value="{{ old('last_name', $profileUser->last_name) }}"
                                        >
                                    </div>
                                    <x-input-error :messages="$errors->get('last_name')" />
                                </div>
                            </div>
                            
                            <div class="column is-narrow">
                                <div class="field mt-5">
                                    <label class="checkbox">
                                        <input 
                                            type="checkbox" 
                                            name="use_kennel_name" 
                                            value="1"
                                            {{ old('use_kennel_name', $profileUser->kennel_name_status) == 1 ? 'checked' : '' }}
                                        >
                                        <span class="ml-2">Accept Kennel Name</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda fila: Middle Name y Kennel Name -->
                        <div class="columns is-multiline">
                            <div class="column">
                                <div class="field mb-4">
                                    <label class="label" for="middle_name">{{__('messages.main.profile.middle_name')}}</label>
                                    <div class="control">
                                        <input
                                            class="input {{ $errors->has('middle_name') ? 'is-danger' : '' }}"
                                            type="text"
                                            name="middle_name"
                                            id="middle_name"
                                            value="{{ old('middle_name', $profileUser->middle_name) }}"
                                        >
                                    </div>
                                    <x-input-error :messages="$errors->get('middle_name')" />
                                </div>
                            </div>
                            
                            <div class="column">
                                <div class="field mb-4">
                                    <label class="label" for="kennel_name">{{__('messages.main.profile.kennel_name')}}</label>
                                    <div class="control">
                                        <input
                                            class="input {{ $errors->has('kennel_name') ? 'is-danger' : '' }}"
                                            type="text"
                                            name="kennel_name"
                                            id="kennel_name"
                                            value="{{ old('kennel_name', $profileUser->kennel_name) }}"
                                        >
                                    </div>
                                    <x-input-error :messages="$errors->get('kennel_name')" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tercera fila: Email y Phone -->
                        <div class="columns is-multiline">
                            <div class="column">
                                <div class="field mb-4">
                                    <label class="label" for="email">{{__('messages.main.profile.email')}}</label>
                                    <div class="control">
                                        <input
                                            class="input {{ $errors->has('email') ? 'is-danger' : '' }}"
                                            type="email"
                                            name="email"
                                            id="email"
                                            value="{{ old('email', $profileUser->email) }}"
                                        >
                                    </div>
                                    <x-input-error :messages="$errors->get('email')" />
                                </div>
                            </div>

                            <div class="column">
                                <div class="field mb-4">
                                    <label class="label" for="phone">{{__('messages.main.profile.phone')}}</label>
                                    <div class="control">
                                        <input
                                            class="input {{ $errors->has('phone') ? 'is-danger' : '' }}"
                                            type="text"
                                            name="phone"
                                            id="phone"
                                            value="{{ old('phone', $profileUser->phone) }}"
                                        >
                                    </div>
                                    <x-input-error :messages="$errors->get('phone')" />
                                </div>
                            </div>
                        </div>

                        <!-- Botón de submit -->
                        <div class="field mt-4">
                            <div class="control">
                                <button type="submit" class="button mt-3 p-2 btn-general">
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