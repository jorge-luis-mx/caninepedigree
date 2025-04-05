
<x-app-layout>

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-6-tablet is-5-desktop is-4-widescreen">
                <div class="box">
                    <h1 class="title is-4 has-text-centered mb-5">Solicitar Cruza</h1>

                    <form action="#" method="POST">
                        @csrf

                        <!-- ID de la hembra -->
                        <div class="field">
                            <label class="label" for="female_dog_id">ID de la hembra</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" id="female_dog_id" name="female_dog_id" placeholder="Ej: HMBR12345" required>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-venus"></i>
                                </span>
                            </div>
                            @error('female_dog_id')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ID del macho -->
                        <div class="field">
                            <label class="label" for="male_dog_id">ID del macho</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" id="male_dog_id" name="male_dog_id" placeholder="Ej: MACH12345" required>
                                <span class="icon is-small is-left">
                                    <i class="fas fa-mars"></i>
                                </span>
                            </div>
                            @error('male_dog_id')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comentarios opcionales -->
                        <div class="field">
                            <label class="label" for="comments">Comentarios (opcional)</label>
                            <div class="control">
                                <textarea class="textarea" id="comments" name="comments" placeholder="Detalles adicionales..."></textarea>
                            </div>
                            @error('comments')
                                <p class="help is-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón de envío -->
                        <div class="field mt-5">
                            <div class="control">
                                <button type="submit" class="button has-background-warning has-text-white is-fullwidth">
                                    Registrar Cruza
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

</x-app-layout>