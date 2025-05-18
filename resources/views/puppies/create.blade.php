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
                              <label class="label">Enter the IDDR number or the dog's name (Sire)</label>
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
                              <label class="label">Enter the IDDR number or the dog's name (Dam)</label>
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

      agregarListenersInputs();
      
      const saved = sessionStorage.getItem('puppiesTemp');

      if (saved) {

         const obj = JSON.parse(saved);

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
            
            if (!validarFormularioCompleto()) {
               return; // Detiene si hay errores
            }

            const formData = capturarDatosCompletos();

             try {
                const response = await fetch('/puppies/register', {
                   method: 'POST',
                   headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                   },
                   body: JSON.stringify(formData)
                });

                  const result = await response.json();
                  if (response.ok) {

                        const dogStatus = ['Admin', 'Administrator', 'Employee'];
                        if (dogStatus.includes(result.data.rol)) {

                           Swal.fire({
                                 icon: 'success',
                                 title: 'Registration Successful!',
                                 text: 'The dog has been registered successfully.',
                                 confirmButtonText: 'OK'
                           }).then((result) => {
                                 if (result.isConfirmed) {
                                    window.location.href = '/dogs';
                                 }
                           });
                              
                           
                        }else{
                           
                           let id = result.data.id_hash;
                           const editUrl = `/payments/pay/${id}/puppies`;
                           window.location.href = editUrl;
                        }

                        sessionStorage.removeItem('puppiesTemp');
                        // Limpiar campos principales del formulario
                        document.querySelector('input[name="sire"]').value = '';
                        document.querySelector('input[name="sire_id"]').value = '';
                        document.querySelector('input[name="dam"]').value = '';
                        document.querySelector('input[name="dam_id"]').value = '';

                        // Eliminar todos los formularios de cachorros generados
                        const puppiesContainer = document.querySelector('#puppyNamesContainer'); // Ajusta el selector si es diferente
                        puppiesContainer.innerHTML = '';

                        // Reiniciar contador si lo usas
                        contador = 1; // Asegúrate de declarar esta variable en el scope global
                        actualizarContador();
                        // Crear nuevo formulario inicial vacío
                        generarFormulariosCachorros();

                        // Limpiar mensajes de error y clases
                        document.querySelectorAll('.error-message').forEach(el => el.remove());
                        document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

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
      
      // Al final de la función generarFormulariosCachorros()
      container.querySelectorAll('.puppy-name, .puppy-color, .puppy-birthdate').forEach(input => {
         input.addEventListener('input', () => limpiarError(input));
      });

   }



   function guardarDatosEnStorage(puppies = null) {
      const datos = puppies || capturarDatosFormulario().puppies;

      sessionStorage.setItem('puppiesTemp', JSON.stringify({
         count: datos.length,
         puppies: datos
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


function mostrarError(input, mensaje) {
   input.classList.add('is-danger');
   const small = input.parentElement.querySelector('.error-message');
   if (small) {
      small.textContent = mensaje;
      small.style.display = 'block';
   }
}

function limpiarError(input) {
   input.classList.remove('is-danger');
   const small = input.parentElement.querySelector('.error-message');
   if (small) {
      small.textContent = '';
      small.style.display = 'none';
   }
}

function validarFormularioCompleto() {
   let valido = true;

   const damInput = document.querySelector('input[name="dam"]');
   const damIdInput = document.querySelector('.dam-id');
   const sireInput = document.querySelector('input[name="sire"]');
   const sireIdInput = document.querySelector('.sire-id');

   if (!damInput || damInput.value.trim() === '') {
      mostrarError(damInput, 'You must enter the name or IDDR');
      valido = false;
   } else {
      limpiarError(damInput);
   }

   if (!damIdInput || damIdInput.value.trim() === '') {
      mostrarError(damInput, 'You must enter a valid name or IDDR');
      valido = false;
   }

   if (!sireInput || sireInput.value.trim() === '') {
      mostrarError(sireInput, 'You must enter the name or IDDR');
      valido = false;
   } else {
      limpiarError(sireInput);
   }

   if (!sireIdInput || sireIdInput.value.trim() === '') {
      mostrarError(sireInput, 'You must enter a valid name or IDDR');
      valido = false;
   }

   const cards = document.querySelectorAll('#puppyNamesContainer .card');
   cards.forEach((card, index) => {
      const name = card.querySelector('.puppy-name');
      const color = card.querySelector('.puppy-color');
      const birthdate = card.querySelector('.puppy-birthdate');

      if (name && name.value.trim() === '') {
         mostrarError(name, `Puppy ${index + 1} name is required`);
         valido = false;
      } else if (name) {
         limpiarError(name);
      }

      if (color && color.value.trim() === '') {
         mostrarError(color, `Puppy ${index + 1} color is required`);
         valido = false;
      } else if (color) {
         limpiarError(color);
      }

      if (birthdate && birthdate.value.trim() === '') {
         mostrarError(birthdate, `Puppy ${index + 1} birth date is required`);
         valido = false;
      } else if (birthdate) {
         limpiarError(birthdate);
      }
   });

   return valido;
}

function capturarDatosCompletos() {

   const form = document.getElementById('formPuppies');
   const formData = new FormData(form);

   // Convertir los datos generales a objeto plano
   const datosGenerales = {};
   formData.forEach((value, key) => {
      if (datosGenerales[key]) {
         if (!Array.isArray(datosGenerales[key])) {
            datosGenerales[key] = [datosGenerales[key]];
         }
         datosGenerales[key].push(value);
      } else {
         datosGenerales[key] = value;
      }
   });

   // Capturar los datos de los cachorros
   const puppies = [];
   const cards = document.querySelectorAll('#puppyNamesContainer .card');
   cards.forEach(card => {
      puppies.push({
         name: card.querySelector('.puppy-name')?.value || '',
         sex: card.querySelector('.puppy-sex')?.value || '',
         color: card.querySelector('.puppy-color')?.value || '',
         birthdate: card.querySelector('.puppy-birthdate')?.value || ''
      });
   });

   // Combinar y retornar todo
   return {
      ...datosGenerales,      // Ej: mother_id, father_id, camada_id, etc.
      totalPuppies: puppies.length,
      puppies: puppies
   };
}


function agregarListenersInputs() {
   // Inputs de búsqueda
   const searchInputs = document.querySelectorAll('input[name="dam"], input[name="sire"]');
   searchInputs.forEach(input => {
      input.addEventListener('input', () => limpiarError(input));
   });

   // Inputs de cachorros
   const puppyInputs = document.querySelectorAll('.puppy-name, .puppy-color, .puppy-birthdate');
   puppyInputs.forEach(input => {
      input.addEventListener('input', () => limpiarError(input));
   });
}


</script>


   @endpush
</x-app-layout>