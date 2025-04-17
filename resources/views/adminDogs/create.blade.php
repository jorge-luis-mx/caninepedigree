<x-guest-layout>




   <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">


      <div class="w-full max-w-md space-y-6 bg-white p-6 rounded-lg shadow-lg flex flex-col items-center">

         @if (session('success'))
            <div 
               x-data="{ show: true }" 
               x-init="setTimeout(() => show = false, 4000)" 
               x-show="show"
               x-transition
               class="w-full mb-4 flex items-center gap-2 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3 text-sm"
            >
               <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
               </svg>
               <span class="flex-1">{{ session('success') }}</span>
            </div>
         @endif


         <form action="{{ route('adminDogs.store') }}" method="post" id="dog-form" class="w-full">
            @csrf
            @method('post')

            {{-- Nombre --}}
            <div class="mb-6">
               <label class="block text-gray-700 font-bold mb-2 text-lg" for="name">
                  {{ __('messages.main.formDog.name') }}
               </label>
               <input
                  class="w-full border rounded px-5 py-3 text-base @error('name') border-red-500 @else border-gray-300 @enderror"
                  type="text"
                  name="name"
                  value="{{ old('name') }}">
               @error('name')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>



            {{-- Color --}}
            <div class="mb-6">
               <label class="block text-gray-700 font-bold mb-2 text-lg" for="color">
                  {{ __('messages.main.formDog.color') }}
               </label>
               <input
                  class="w-full border rounded px-5 py-3 text-base @error('color') border-red-500 @else border-gray-300 @enderror"
                  type="text"
                  name="color"
                  value="{{ old('color') }}">
               @error('color')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>

            {{-- Sexo --}}
            <div class="mb-6">
               <label class="block text-gray-700 font-bold mb-2 text-lg" for="sex">
                  {{ __('messages.main.formDog.sex') }}
               </label>
               <select name="sex" id="sex" class="w-full border rounded px-5 py-3 text-base @error('sex') border-red-500 @else border-gray-300 @enderror">
                  <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>{{ __('messages.main.formDog.selectedMale') }}</option>
                  <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>{{ __('messages.main.formDog.slectedFemale') }}</option>
               </select>
               @error('sex')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
               @enderror
            </div>


            {{-- Botón guardar --}}
            <div class="mt-6 text-right">
               <button type="submit" class="bg-orange-400 text-white px-6 py-3 text-base rounded hover:bg-orange-500 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 36 36">
                     <path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" />
                     <path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" />
                     <path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" />
                     <circle cx="30" cy="6" r="5" fill="#fff" />
                  </svg>
                  <span>{{ __('messages.main.formDog.btnSaveDog') }}</span>
               </button>
            </div>
         </form>

      </div>
   </div>
   
   <script>
    document.addEventListener('DOMContentLoaded', () => {
        const fields = document.querySelectorAll('input, select');

        fields.forEach(field => {
            field.addEventListener('input', () => {
                if (field.classList.contains('border-red-500')) {
                    field.classList.remove('border-red-500');
                    field.classList.add('border-gray-300');

                    const errorMessage = field.parentElement.querySelector('p.text-red-500');
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                }
            });

            // Opcional: para selects como el de 'sex'
            field.addEventListener('change', () => {
                if (field.classList.contains('border-red-500')) {
                    field.classList.remove('border-red-500');
                    field.classList.add('border-gray-300');

                    const errorMessage = field.parentElement.querySelector('p.text-red-500');
                    if (errorMessage) {
                        errorMessage.remove();
                    }
                }
            });
        });
    });
</script>

</x-guest-layout>
