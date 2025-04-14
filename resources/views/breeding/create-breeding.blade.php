<x-app-layout>


    <div class="columns is-multiline mt-4">

        <div class="column">
            <div class="card" style="box-shadow: none;">
                <form action="{{ route('breeding.store') }}" method="post" id="breeding_form">
                    @csrf
                    @method('post')

                    <div class="card-content">

<div class="columns is-multiline">

    <!-- My dog -->
    <div class="column">
        <div class="field mb-4">
            <label class="label" for="my_dog_id">Select your registered dog</label>
            <div class="control">
                <div class="select is-fullwidth">
                    <select name="my_dog_id" id="my_dog_id" required>
                        <option value="" disabled selected>Choose your dog</option>
                        @foreach($dogs as $dog)
                            <option value="{{ $dog->dog_id }}">
                                {{ $dog->name }} ({{ $dog['sex'] == 'M' ? 'Male' : 'Female' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <p class="help">This is the dog you have registered in the system.</p>
        </div>
    </div>

    <!-- Other dog's name -->
    <div class="column">
        <div class="field mb-4">
            <label class="label" for="other_dog_name">Name of the dog you want to breed with</label>
            <div class="control">
                <input class="input" type="text" name="other_dog_name" id="other_dog_name" placeholder="E.g., Rocky, Luna, etc." required>
            </div>
            <p class="help">Enter the name of the other owner's dog.</p>
        </div>
    </div>

    <!-- Other owner's email -->
    <div class="column">
        <div class="field mb-4">
            <label class="label" for="other_owner_email">Email of the other dog’s owner</label>
            <div class="control">
                <input class="input" type="email" name="other_owner_email" id="other_owner_email" placeholder="example@email.com" required>
            </div>
            <p class="help">We will contact this person to confirm the breeding request.</p>
        </div>
    </div>

</div>

<div class="columns is-multiline">
    <!-- Comments -->
    <div class="column">
        <div class="field">
            <label class="label" for="comments">Additional comments (optional)</label>
            <div class="control">
                <textarea class="textarea" id="comments" name="comments" placeholder="You can add notes, preferences, etc."></textarea>
            </div>
        </div>
    </div>
</div>

</div>


                    <!-- <div class="card-content">

                        <div class="columns is-multiline">

                        <div class="column">
                            <div class="field mb-4">
                                <label class="label" for="my_dog_id">Tu perro</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="my_dog_id" id="my_dog_id" required>
                                            @foreach($dogs as $dog)
                                                <option value="{{ $dog->dog_id }}">{{ $dog->name }} ({{ $dog['sex'] == 'M' ? 'Male' : 'Female' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="field mb-4">
                                <label class="label" for="other_dog_name">Nombre del otro perro</label>
                                <div class="control">
                                    <input class="input" type="text" name="other_dog_name" id="other_dog_name" required>
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="field mb-4">
                                <label class="label" for="other_owner_email">Email del dueño del otro perro</label>
                                <div class="control">
                                    <input class="input" type="email" name="other_owner_email" id="other_owner_email" required>
                                </div>
                            </div>
                        </div>

                        </div>
                        <div class="columns is-multiline">
                            <div class="column">
                                <div class="field">
                                    <label class="label" for="comments">Comentarios (opcional)</label>
                                    <div class="control">
                                        <textarea class="textarea" id="comments" name="comments" placeholder="Detalles adicionales..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="field mt-4 ml-4 mb-4">
                        <div class="control">
                            <button type="submit" class="button  mt-3 p-2 btn-general saveBreedingRequest">
                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 36 36">
                                    <path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" class="clr-i-outline--badged clr-i-outline-path-1--badged" />
                                    <path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" class="clr-i-outline--badged clr-i-outline-path-2--badged" />
                                    <path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" class="clr-i-outline--badged clr-i-outline-path-3--badged" />
                                    <circle cx="30" cy="6" r="5" fill="#fff" class="clr-i-outline--badged clr-i-outline-path-4--badged clr-i-badge" />
                                    <path fill="none" d="M0 0h36v36H0z" />
                                </svg>
                                <span class="ml-1">{{ __('Save Breeding') }}</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>



</x-app-layout>