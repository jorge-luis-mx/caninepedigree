<x-app-layout>
   <div class="container">
      @if ($errors->any())
         <div class="notification is-danger">
            <strong>There were problems with your upload:</strong>
            <ul>
                  @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                  @endforeach
            </ul>
         </div>
      @endif

      <h1 class="is-size-4">Upload Photos for the Breeding of {{ $breeding->femaleDog->name }} and {{ $breeding->maleDog->name }}</h1>
        <form id="photo-upload-form" action="{{ route('breeding.storePhotos', $breeding->request_id) }}" method="POST" enctype="multipart/form-data">
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

<style>
/* Contenedor principal */
.upload-wrapper {
    padding: 2rem;
    border: 1px solid #ffffffff;
    border-radius: 12px;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: box-shadow 0.3s ease;
}

.upload-wrapper:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}

/* Input de archivos */
.hidden-file-input {
    display: block;
    width: 100%;
    padding: 1rem;
    border: 2px dashed #dbdbdb;
    border-radius: 8px;
    background-color: #fafafa;
    cursor: pointer;
    transition: border-color 0.3s, background-color 0.3s;
}

.hidden-file-input:hover {
    border-color: #48c774; /* Bulma success verde */
    background-color: #f0fdf4;
}

/* Etiqueta */
.custom-file-label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #363636;
    font-size: 1rem;
}

/* Placeholder de archivos */
/* .file-placeholder {
    margin-top: 0.5rem;
    padding: 0.75rem;
    border-radius: 6px;
    border: 1px solid #dbdbdb;
    background-color: #f5f5f5;
    color: #7a7a7a;
    text-align: center;
    font-size: 0.9rem;
} */

/* Previsualización de imágenes */
/* Contenedor de previsualización */
/* .preview-container {
    margin-top: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    
} */

/* Cada miniatura */
.preview-container .preview-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    border: 1px solid #dbdbdb;
    background-color: #fafafa;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    display: flex;               /* Flex para centrar la imagen */
    align-items: center;         /* Centrado vertical */
    justify-content: center;     /* Centrado horizontal */
    overflow: hidden;            /* Evita que la imagen sobresalga */
}

/* Imagen dentro del contenedor */
.preview-container .preview-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;          /* Ajusta la imagen sin recortar */
    display: block;               /* Evita espacio extra por inline */
}

/* Botón */
.btn-general {
    background-color: #48c774;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    transition: background-color 0.3s, box-shadow 0.3s;
}



</style>

   </div>
   @push('scripts')

   <script>

      const input = document.getElementById('photo-upload');
      const previewContainer = document.getElementById('preview-container');
      const filePlaceholder = document.getElementById('file-placeholder');
      const noFilesText = @json(__('messages.no_files_selected'));

      let filesArray = [];

      input.addEventListener('change', () => {
         const newFiles = Array.from(input.files);
         filesArray = filesArray.concat(newFiles);

         updatePreview();
         updateFileList(); 
      });

      function updatePreview() {
         previewContainer.innerHTML = '';

         if (filesArray.length === 0) {
               filePlaceholder.textContent = noFilesText;
               return;
         }

         filePlaceholder.textContent = '';

         filesArray.forEach((file, index) => {
               const reader = new FileReader();

               reader.onload = (e) => {
                  const div = document.createElement('div');
                  div.classList.add('image-preview');
                  div.innerHTML = `
                     <img src="${e.target.result}" alt="Preview">
                     <button type="button" class="delete-btn" data-index="${index}">&times;</button>
                  `;
                  previewContainer.appendChild(div);
               };

               reader.readAsDataURL(file);
         });
      }

      function updateFileList() {
         const dataTransfer = new DataTransfer();
         filesArray.forEach(file => dataTransfer.items.add(file));
         input.files = dataTransfer.files;
      }



      previewContainer.addEventListener('click', (e) => {
         if (e.target.classList.contains('delete-btn')) {
            const index = parseInt(e.target.dataset.index);
            filesArray.splice(index, 1);
            updatePreview();
            updateFileList(); 
         }
      });

      document.getElementById('photo-upload-form').addEventListener('submit', function (e) {
         e.preventDefault(); // Bloquea el submit por defecto siempre
         updateFileList();   // Asegura que input.files esté sincronizado

         if (filesArray.length === 0) {
            alert('You must select at least one image.');
            return;
         }

         // Check size limit (2MB per file)
         for (let file of filesArray) {
            if (file.size > 2 * 1024 * 1024) {
                  alert(`The file "${file.name}" exceeds 2 MB.`);
                  return;
            }
         }

         this.submit(); // Si hay al menos una imagen, ahora sí envía el formulario
      });


   </script>

   @endpush

</x-app-layout>