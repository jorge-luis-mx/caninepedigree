<x-app-layout>
   <div class="container">
      <h2 class="title">Subir Fotos para la Cruza de {{ $breeding->femaleDog->name }} y {{ $breeding->maleDog->name }}</h2>

      <form action="{{ route('breeding.storePhotos', $breeding->request_id) }}" method="POST" enctype="multipart/form-data">
         @csrf

         <div class="field">
               <label class="label">Seleccionar Fotos</label>
               <div class="control">
                  <input class="input" type="file" name="photos[]" multiple required>
               </div>
         </div>

         <div class="field mt-4">
               <button type="submit" class="button btn-general">Subir Fotos</button>
         </div>
      </form>
   </div>
</x-app-layout>