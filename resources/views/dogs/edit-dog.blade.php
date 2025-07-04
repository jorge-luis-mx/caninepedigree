<x-app-layout>


   <h1 class="is-size-4">Edit Dog</h1>

   <div class="columns is-multiline">

      <div class="column">

         <div class="card " style="box-shadow: none;">
            <form id="dogFormEdit" action="" method="PUT" >
               @csrf
               @method('put')
               
                  <div class="card-content">

                     <div class="field mb-4">
                        <label class="label" for="name">Name</label>
                        <div class="control">
                           <input
                              class="input"
                              type="text"
                              name="name"
                              value="{{$dog->name}}">
                        </div>
                     </div>
                     
                     <div class="columns is-multiline">
                        <div class="column">
                           <div class="field mb-4">
                              <label class="label" for="breed">Breed</label>
                              <div class="control">
                                 <input
                                    class="input"
                                    type="text"
                                    name="breed"
                                    value="{{$dog->breed}}">
                              </div>
                           </div>
                        </div>
                        <div class="column">
                           <div class="field">
                              <label class="label" for="phone">Color</label>
                              <div class="control">
                                 <input
                                    class="input"
                                    type="text"
                                    name="color"
                                    value="{{$dog->color}}">
                              </div>
                           </div>
                        </div>
                        <div class="column">
                           <div class="field" >
                              <label class="label" for="sex">Sex</label>
                              <div class="control">
                                 <div class="select is-fullwidth">
                                    <select name="sex" id="sex">
                                       <option value="M"  @selected($dog->sex == 'M')>Macho</option>
                                       <option value="H"  @selected($dog->sex == 'H')>Hembra</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="columns is-multiline">
                        <div class="column is-one-fifth">
                           <div class="field">
                              <label class="label" for="birthdate">Date of Birth</label>
                              <div class="control">
                                 <input
                                    class="input"
                                    type="date"
                                    name="birthdate"
                                    id="birthdate"
                                    value="{{ $dog->birthdate ? $dog->birthdate->format('Y-m-d') : '' }}"
                                    >
                                    
                              </div>
                           </div>
                        </div>
                        <div class="column">
                           <div class="columns is-multiline">
                              <div class="column">
                                 <div class="field mb-4">
                                    <label class="label" for="name">Sire</label>

                                    <div class="control">
                                       <div class="select is-fullwidth">
                                          <select name="sire_id" id="sire_id">
                                             @foreach($dogs as $item)
                                                <option value="{{ $item->dog_id }}" {{ $dog->sire_id == $item->dog_id ? 'selected' : '' }}>
                                                      {{ $item->name }}
                                                </option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="column">
                                 <div class="field mb-4">
                                    <label class="label" for="name">Dam</label>
                                    <div class="control">
                                       <div class="select is-fullwidth">
                                          <select name="dam_id" id="dam_id">
                                             @foreach($dogs as $item)
                                                <option value="{{ $item->dog_id }}" {{ $dog->dam_id == $item->dog_id ? 'selected' : '' }}>
                                                      {{ $item->name }}
                                                </option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <input type="hidden" id="dog_id" value="{{ $dog->dog_id }}">
                  <div class="field mt-4 ml-4 mb-4">
                     <div class="control">
                        <button type="submit" class="button updateDog mt-3 p-2 btn-general">
                           <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 36 36">
                              <path fill="#fff" d="M22.28 26.07a1 1 0 0 0-.71.29L19 28.94V16.68a1 1 0 1 0-2 0v12.26l-2.57-2.57A1 1 0 0 0 13 27.78l5 5l5-5a1 1 0 0 0-.71-1.71Z" class="clr-i-outline--badged clr-i-outline-path-1--badged" />
                              <path fill="#fff" d="M19.87 4.69a8.8 8.8 0 0 1 2.68.42a7.5 7.5 0 0 1 .5-1.94a10.8 10.8 0 0 0-3.18-.48a10.47 10.47 0 0 0-9.6 6.1A9.65 9.65 0 0 0 10.89 28a3 3 0 0 1 0-2A7.65 7.65 0 0 1 11 10.74h.67l.23-.63a8.43 8.43 0 0 1 7.97-5.42" class="clr-i-outline--badged clr-i-outline-path-2--badged" />
                              <path fill="#fff" d="M30.92 13.44a7.1 7.1 0 0 1-2.63-.14v.23l-.08.72l.65.3A6 6 0 0 1 26.38 26h-1.29a3 3 0 0 1 0 2h1.28a8 8 0 0 0 4.54-14.61Z" class="clr-i-outline--badged clr-i-outline-path-3--badged" />
                              <circle cx="30" cy="6" r="5" fill="#fff" class="clr-i-outline--badged clr-i-outline-path-4--badged clr-i-badge" />
                              <path fill="none" d="M0 0h36v36H0z" />
                           </svg>
                           <span class="ml-1">{{ __('Update Dog') }}</span>
                        </button>
                     </div>
                  </div>

            </form>
         </div>
      </div>
   </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
   const form = document.getElementById('dogFormEdit');

   form.addEventListener('submit', async function (e) {
      e.preventDefault(); 


      const dogId = document.getElementById('dog_id').value;
      const csrfToken = document.querySelector('input[name="_token"]').value;

      const formData = new FormData(form);
      const data = {};

      formData.forEach((value, key) => {
         data[key] = value;
      });

      try {
         const response = await fetch(`/dog/${dogId}`, {
            method: 'PUT',
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
         });

         const result = await response.json();

         if (response.ok && result.status === 200) {
            alert(result.message || 'Perro actualizado correctamente.');
            window.location.href = '/dogs';
         } else {
            alert(result.message || 'Error al actualizar el perro.');
            console.error(result);
         }
      } catch (error) {
         console.error('Error de red:', error);
         alert('Error de red al actualizar el perro.');
      }
   });
});
</script>

</x-app-layout>