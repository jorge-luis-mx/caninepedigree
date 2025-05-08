<x-app-layout>

   <div class="container">

      <h3>Registrar Cachorros</h3>
      
      <div class="columns is-multiline">
         <div class="column is-full is-flex is-justify-content-flex-end	">
            <div class="contador-wrapper">
               <div class="btn-contador" onclick="decrementar()">−</div>
                  <div id="contador">1</div>
               <div class="btn-contador" onclick="incrementar()">+</div>
            </div>
         </div>
         <div class="column">
            <div class="card" style="box-shadow: none;">
               <div class="card-content">
                  <form id="formPuppies" action="{{ route('dogs.store') }}" method="post">
                     @csrf
                     @method('post')

                     <div class="columns is-multiline">

                        <!-- Bloque para Female (Dog) -->
                        <!-- <div class="column">
                           <div class="field search-container" data-type="dog">
                              <label class="label">Enter the IDDR number or the dog's name (Female)</label>
                              <div class="is-flex align-items-center">
                                 <div class="control has-icons-left" style="width: 100%;">
                                    <input class="input dog-search" type="text" name="dog" data-type="dog">
                                    <input type="hidden" name="dog_id" class="dog-id">
                                    <small class="error-message"></small>
                                    <span class="icon is-small is-left">
                                       
                                    </span>
                                 </div>
                                 <div class="btn-container">
                                    <button type="button" class="button btn-search" data-type="dog" style="background-color: #fdcd8a;color:#450b03;margin:0!important">
                                       Search
                                    </button>
                                 </div>
                              </div>
                           </div>
                           <div class="results-container" id="dogResults" style="display: none;"></div>
                        </div> -->

                        <!-- Bloque para Male (Sire) -->
                        <div class="column">
                           <div class="field search-container" data-type="sire">
                              <label class="label">Enter the IDDR number or the dog's name (Male / Sire)</label>
                              <div class="is-flex align-items-center">
                                 <div class="control has-icons-left" style="width: 100%;">
                                    <input class="input dog-search" type="text" name="sire" data-type="sire">
                                    <input type="hidden" name="sire_id" class="sire-id">
                                    <small class="error-message"></small>
                                    <span class="icon is-small is-left">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                          <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z" />
                                       </svg>
                                    </span>
                                 </div>
                                 <div class="btn-container">
                                    <button type="button" class="button btn-search-dog" data-type="sire" style="background-color: #fdcd8a;color:#450b03;margin:0!important">
                                       Search
                                    </button>
                                 </div>
                              </div>
                           </div>
                           <div class="results-container" data-type="sire" style="display: none;"></div>
                        </div>

                        <!-- Bloque para Dam (si necesario) -->
                        <div class="column">
                           <div class="field search-container" data-type="dam">
                              <label class="label">Enter the IDDR number or the dog's name (Female / Dam)</label>
                              <div class="is-flex align-items-center">
                                 <div class="control has-icons-left" style="width: 100%;">
                                    <input class="input dog-search" type="text" name="dam" data-type="dam">
                                    <input type="hidden" name="dam_id" class="dam-id">
                                    <small class="error-message"></small>
                                    <span class="icon is-small is-left">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                          <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z" />
                                       </svg>
                                    </span>
                                 </div>
                                 <div class="btn-container">
                                    <button type="button" class="button btn-search-dog" data-type="dam" style="background-color: #fdcd8a;color:#450b03;margin:0!important">
                                       Search
                                    </button>
                                 </div>
                              </div>
                           </div>
                           <div class="results-container" data-type="dam" style="display: none;"></div>
                        </div>
                     </div>
                     <div id="puppyForm" class="" style="display: none;">
                        <div id="puppyNamesContainer" class="mt-4"></div>
                     </div>
                     <div class="field mt-4">
                        <button class="button savePuppies is-primary btn-general">Save Puppies</button>
                     </div>
                  </form>

               </div>
            </div>
         </div>
      </div>

   </div>

   @push('scripts')
   <script>
   document.addEventListener('DOMContentLoaded', () => {

      const saved = sessionStorage.getItem('puppiesTemp');

      if (saved) {
         const obj = JSON.parse(saved);
         //contador = obj.count;
         contador = obj.count > 0 ? obj.count : 1;
         actualizarContador();
         mostrarFormularioCachorros();
         generarFormulariosCachorros(obj.puppies);


      } else {
         contador = 1;
         actualizarContador();
         mostrarFormularioCachorros();
         generarFormulariosCachorros();
      }

      // Enviar con fetch
      const saveBtn = document.querySelector('.savePuppies');
      if (saveBtn) {
         saveBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const formData = capturarDatosFormulario();
            
            try {
               const response = await fetch('{{ route('dogs.store') }}', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify(formData)
               });
               const result = await response.json();
               console.log(result);

               if (response.ok) {
                  alert('¡Cachorros guardados!');
                  sessionStorage.removeItem('puppiesTemp');
               } else {
                  alert('Error al guardar.');
               }

            } catch (err) {
               console.error('Error en fetch:', err);
               alert('Error de red o servidor');
            }
         });
      }

   });

   let contador = 1;

   function incrementar() {
      const actuales = capturarDatosFormulario().puppies;
      contador++;
      actualizarContador();
      mostrarFormularioCachorros();

      actuales.unshift({ name: '', sex: 'M', color: '', birthdate: '' });
      generarFormulariosCachorros(actuales);
   }


   function decrementar() {
      if (contador > 1) {
         contador--;
         actualizarContador();
         
         // Eliminar el primer formulario (el más reciente)
         const currentPuppies = capturarDatosFormulario().puppies;
         currentPuppies.shift(); // Elimina el primero del arreglo
         guardarDatosEnStorage(currentPuppies); // Guardamos el nuevo estado
         generarFormulariosCachorros(currentPuppies); // Re-renderizamos con los datos actualizados
      }
   }


   function actualizarContador() {
      document.getElementById('contador').textContent = contador;
   }

   function mostrarFormularioCachorros() {
      const form = document.getElementById('puppyForm');
      if (form.style.display === 'none') {
         form.style.display = 'block';
      }
   }

   function generarFormulariosCachorros(data = []) {
      const container = document.getElementById('puppyNamesContainer');
      container.innerHTML = ''; // Limpiamos el contenedor

      // Usamos la cantidad de formularios definida en el contador
      //<h5 class="title is-6">Cachorro ${i + 1}</h5>
      for (let i = 0; i < contador; i++) {
         const cachorro = data[i] || { name: '', sex: 'M', color: '', birthdate: '' };

         const card = document.createElement('div');
         card.className = 'card mb-4';
         card.innerHTML = `
            <div class="card-content">
               
               <div class="columns">
                  <div class="column">
                     <div class="field">
                        <label class="label">Name</label>
                        <div class="control">
                           <input class="input puppy-name" type="text" value="${cachorro.name || ''}" required>
                        </div>
                     </div>
                  </div>
                  <div class="column">
                     <div class="field">
                        <label class="label">Sex</label>
                        <div class="control">
                           <div class="select is-fullwidth">
                              <select class="puppy-sex" required>
                                 <option value="M" ${cachorro.sex === 'M' ? 'selected' : ''}>Male</option>
                                 <option value="F" ${cachorro.sex === 'F' ? 'selected' : ''}>Female</option>
                              </select>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="columns">
                  <div class="column">
                     <div class="field">
                        <label class="label">Color</label>
                        <div class="control">
                           <input class="input puppy-color" type="text" value="${cachorro.color || ''}" required>
                        </div>
                     </div>
                  </div>
                  <div class="column">
                     <div class="field">
                        <label class="label">Date of Birth</label>
                        <div class="control">
                           <input class="input puppy-birthdate" type="date" value="${cachorro.birthdate || ''}" required>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         `;
         container.appendChild(card); // Usamos appendChild para agregar los formularios
      }

      // Detectar cambios y guardar automáticamente
      container.querySelectorAll('input, select').forEach(el => {
         el.addEventListener('input', guardarDatosEnStorage);
         el.addEventListener('change', guardarDatosEnStorage);
      });

      // Guardar los datos en sessionStorage
      guardarDatosEnStorage(data); // Guardamos directamente el arreglo de cachorros
   }



   function guardarDatosEnStorage(puppies) {
      sessionStorage.setItem('puppiesTemp', JSON.stringify({
         count: puppies.length,
         puppies: puppies
      }));
   }


   function capturarDatosFormulario() {
      const puppies = [];
      const cards = document.querySelectorAll('#puppyNamesContainer .card');

      cards.forEach(card => {
         puppies.push({
            name: card.querySelector('.puppy-name').value,
            sex: card.querySelector('.puppy-sex').value,
            color: card.querySelector('.puppy-color').value,
            birthdate: card.querySelector('.puppy-birthdate').value
         });
      });

      return {
         count: puppies.length, // <-- este cambio es clave
         puppies
      };
   }

</script>


   @endpush
</x-app-layout>