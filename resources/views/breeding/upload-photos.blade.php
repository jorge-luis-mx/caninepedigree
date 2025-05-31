<x-app-layout>
   <div class="container">
      
      <h1 class="is-size-4">Upload Photos for the Breeding of {{ $breeding->femaleDog->name }} and {{ $breeding->maleDog->name }}</h1>
      <form action="{{ route('breeding.storePhotos', $breeding->request_id) }}" method="POST" enctype="multipart/form-data">
         @csrf
            <div class="upload-wrapper">
               <div class="upload-control">
                  <label for="photo-upload" class="custom-file-label">
                     {{ __('messages.files.select_files') }}
                  </label>
                  <input
                     id="photo-upload"
                     class="hidden-file-input"
                     type="file"
                     name="photos[]"
                     accept="image/*"
                     multiple
                     required
                  >
                  <div id="file-placeholder" class="file-placeholder">
                     {{ __('messages.files.no_files_selected') }}
                  </div>
               </div>
               <div id="preview-container" class="preview-container"></div>
            </div>
            <div class="field mt-4">
               <button type="submit" class="button btn-general">Subir Fotos</button>
            </div>
      </form>
   </div>
   @push('scripts')
   <script>

      const input = document.getElementById('photo-upload');
      const filePlaceholder = document.getElementById('file-placeholder');
      const previewWrapper = document.querySelector('.upload-wrapper'); // contenedor deseado
      const previewContainer = document.getElementById('preview-container');
      const noFilesText = @json(__('messages.no_files_selected'));

      input.addEventListener('change', () => {
      const files = input.files;
      previewContainer.innerHTML = ''; // Limpiar anteriores

      if (files.length > 0) {
         filePlaceholder.textContent = '';

         Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = e => {
               const div = document.createElement('div');
               div.classList.add('image-preview');
               div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;

               // Aquí lo agregas al .upload-wrapper
               previewWrapper.appendChild(div);
            };

            reader.readAsDataURL(file);
            }
         });

      } else {
         filePlaceholder.textContent = noFilesText;
      }
      });


    </script>
   @endpush

</x-app-layout>