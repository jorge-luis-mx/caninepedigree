import { Utils } from './utils.js'; 

export  function dogs() {

//    document.addEventListener("DOMContentLoaded", function () {

    //   let table = new DataTable("#miTabla", {
    //       processing: true,
    //       serverSide: true,
    //       ajax: "{{ route('users.data') }}",
    //       columns: [
    //           { data: "id", name: "id" },
    //           { data: "name", name: "name" },
    //           { data: "email", name: "email" },
    //           { data: "action", name: "action", orderable: false, searchable: false }
    //       ]
    //   });

    //   document.querySelector("#miTabla").addEventListener("click", function (event) {
    //       if (event.target.classList.contains("deleteUser")) {
    //           let userId = event.target.dataset.id;
    //           if (confirm("¿Estás seguro de eliminar este usuario?")) {
    //               fetch(`/users/${userId}`, {
    //                   method: "DELETE",
    //                   headers: {
    //                       "X-CSRF-TOKEN": "{{ csrf_token() }}",
    //                       "Content-Type": "application/json",
    //                   },
    //               })
    //               .then(response => response.json())
    //               .then(data => {
    //                   alert(data.message);
    //                   table.ajax.reload(); // Recargar la tabla
    //               })
    //               .catch(error => alert("Error al eliminar el usuario."));
    //           }
    //       }
    //   });


    document.addEventListener("DOMContentLoaded", function () {


    
    const dogFormContainer = document.getElementById('dog-form'); 
    dogFormContainer.addEventListener('keydown', function (e) {
        // Solo procesar eventos en los campos .sire o .dam
        if (e.target.classList.contains('sire')) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el envío del formulario
                searchSire(e.target, e.target.closest('.groupsForms')); // Llamar a la función de búsqueda para Sire
            }
        }

        if (e.target.classList.contains('dam')) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir el envío del formulario
                searchDam(e.target, e.target.closest('.groupsForms')); // Llamar a la función de búsqueda para Dam
            }
        }
    });

    dogFormContainer.addEventListener('blur', function (e) {
        // Solo procesar eventos en los campos .sire o .dam
        if (e.target.classList.contains('sire')) {
            searchSire(e.target, e.target.closest('.groupsForms')); // Llamar a la función de búsqueda para Sire
        }

        if (e.target.classList.contains('dam')) {
            searchDam(e.target, e.target.closest('.groupsForms')); // Llamar a la función de búsqueda para Dam
        }
    }, true); // Usamos el tercer argumento `true` para delegar el evento en la fase de captura

        // const dogForms = document.querySelectorAll('.groupsForms');
    
        // dogForms.forEach((form) => {
        //     const inputSire = form.querySelector('.sire');
        //     const inputDam = form.querySelector('.dam');
    
        //     // Asignar eventos para buscar Sire
        //     if (inputSire) {
        //         inputSire.addEventListener('keydown', function (e) {
        //             if (e.key === 'Enter') {
        //                 e.preventDefault(); // Prevenir el envío del formulario
        //                 searchSire(inputSire, form); // Realizar la búsqueda cuando se presiona Enter
        //             }
        //         });
        //         inputSire.addEventListener('blur', function () {
        //             searchSire(inputSire, form); // Realizar la búsqueda cuando el input pierde el foco
        //         });
        //     }
    
        //     // Asignar eventos para buscar Dam
        //     if (inputDam) {
        //         inputDam.addEventListener('keydown', function (e) {
        //             if (e.key === 'Enter') {
        //                 e.preventDefault(); // Prevenir el envío del formulario
        //                 searchDam(inputDam, form); // Realizar la búsqueda cuando se presiona Enter
        //             }
        //         });
        //         inputDam.addEventListener('blur', function () {
        //             searchDam(inputDam, form); // Realizar la búsqueda cuando el input pierde el foco
        //         });
        //     }

        //     let btnSave = form.querySelector('.saveDog');

        //     if (btnSave) {
        //         btnSave.addEventListener('click', function(e) {
        //             e.preventDefault();
        //             submit(); // Verifica que esta función esté definida
        //         });
        //     }

        // });

    });
    
    function searchSire(input, form) {
        const regNo = input.value.trim();
    
        // Validación para asegurarse de que hay al menos 3 caracteres
        if (regNo.length < 3) {
            console.log("El término de búsqueda debe tener al menos 3 caracteres.");
            return;
        }
    
        // Realizar la solicitud fetch para buscar el Sire
        fetch(`/dogs/search/${regNo}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    console.log("Sire encontrado:", data);
                    // Aquí puedes mostrar el resultado en el formulario, por ejemplo:
                    form.querySelector('.sire_name').textContent = data.name;
                    form.querySelector('.sire_id').value = data.dog_id;
                } else {
                    form.querySelector('.sireMail').classList.remove('is-hidden'); 
                    form.querySelector('.serchSire').classList.add('is-hidden');
                }
            })
            .catch(error => {
                console.log("Error al buscar Sire:", error);
            });
    }
    
    function searchDam(input, form) {
        const regNo = input.value.trim();
    
        // Validación para asegurarse de que hay al menos 3 caracteres
        if (regNo.length < 3) {
            console.log("El término de búsqueda debe tener al menos 3 caracteres.");
            return;
        }
    
        // Realizar la solicitud fetch para buscar el Dam
        fetch(`/dogs/search/${regNo}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    console.log("Dam encontrado:", data);
                    // Aquí puedes mostrar el resultado en el formulario, por ejemplo:
                    form.querySelector('.dam_name').textContent = data.name;
                    form.querySelector('.dam_id').value = data.dog_id;
                } else {
                    form.querySelector('.damMail').classList.remove('is-hidden'); 
                    form.querySelector('.serchDam').classList.add('is-hidden');
                }
            })
            .catch(error => {
                console.log("Error al buscar Dam:", error);
            });
    }
    
    
    


    let count = 1;
    const counter = document.getElementById("counter");
    const cartList = document.getElementById("dog-form"); // Formulario donde se agregarán los elementos
    const addButton = document.getElementById("add-btn");
    const removeButton = document.getElementById("remove-btn");
    
    // Obtener el botón Save Dog
    const saveButtonContainer = document.querySelector(".field.mt-4.ml-4");
    
    addButton.addEventListener("click", function () {
        count++;
        updateCounter();
        addItem();
    });
    
    removeButton.addEventListener("click", function () {
        if (count > 0) {
            count--;
            updateCounter();
            removeItem();
        }
    });
    
    function updateCounter() {
        counter.textContent = count;
        removeButton.style.display = count > 0 ? "inline-block" : "none";
    }
    
    function addItem() {
        if (!cartList) return;
    
        let div = document.createElement("div");
        div.classList.add("card", "groupsForms", "m-4");
        div.style.boxShadow = "none";
        div.style.marginBottom = "30px";
    
        div.innerHTML = `

            
               <div class="card-content">

                  <div class="field mb-4">
                     <label class="label" for="name">Name</label>
                     <div class="control">
                        <input
                           class="input"
                           type="text"
                           name="name"
                           value="">
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
                                 value="">
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
                                 value="">
                           </div>
                        </div>
                     </div>
                     <div class="column">
                        <div class="field">
                           <label class="label" for="sex">Sex</label>
                           <div class="control">
                              <input
                                 class="input"
                                 type="text"
                                 name="sex"
                                 value="">
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
                                 id="birthdate">
                           </div>
                        </div>
                     </div>
                     <div class="column">
                        <div class="columns is-multiline">
                           <div class="column">
                              <!-- Serch sire dog -->
                              <div class="field serchSire">
                                 <label class="label" for="sire">Sire</label>
                                 <div class="control has-icons-left">
                                    <input
                                       class="input sire"
                                       type="text"
                                       name="sire"
                                       id="sire"
                                       placeholder="Search sire...">
                                    <span class="icon is-small is-left">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                          <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z" />
                                       </svg>
                                    </span>
                                 </div>
                              </div>
                              <!-- Email sire dog -->
                              <div class="field is-hidden sireMail">
                                 <label class="label" for="sire_email">Sire Email</label>
                                 <div class="control has-icons-left">
                                    <input
                                       class="input"
                                       type="email"
                                       name="sire_email"
                                       id="sire_email"
                                       placeholder="Enter Sire's email">
                                    <span class="icon is-small is-left">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                          <path fill="currentColor" d="M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4zm2 0v1.6l8 4.8l8-4.8V4H4zm16 2.4l-8 4.8l-8-4.8V18h16V6.4z" />
                                       </svg>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <div class="column">
                              <!-- Serch dam dog -->
                              <div class="field serchDam">
                                 <label class="label" for="dam">Dam</label>
                                 <div class="control has-icons-left">
                                    <input
                                       class="input dam"
                                       type="text"
                                       name="dam"
                                       id="dam"
                                       placeholder="Search dam...">
                                    <span class="icon is-small is-left">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                          <path fill="currentColor" d="M10 2a8 8 0 1 1-5.293 14.293l-3.147 3.147a1 1 0 0 1-1.415-1.414l3.147-3.147A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12A6 6 0 0 0 10 4z" />
                                       </svg>
                                    </span>
                                 </div>
                              </div>
                              <!-- Email dam dog -->
                              <div class="field damMail is-hidden">
                                 <label class="label" for="dam_email">Dam Email</label>
                                 <div class="control has-icons-left">
                                    <input
                                       class="input"
                                       type="email"
                                       name="dam_email"
                                       id="dam_email"
                                       placeholder="Enter Dam's email">
                                    <span class="icon is-small is-left">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                          <path fill="currentColor" d="M2 4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4zm2 0v1.6l8 4.8l8-4.8V4H4zm16 2.4l-8 4.8l-8-4.8V18h16V6.4z" />
                                       </svg>
                                    </span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
           
        `;
    
        cartList.appendChild(div);
    
        // Mover el botón Save Dog al final de todos los elementos
        cartList.appendChild(saveButtonContainer);
    }
    
    function removeItem() {
        let groupsForms = document.querySelectorAll(".groupsForms");
        if (groupsForms.length > 1) {
            groupsForms[groupsForms.length - 1].remove();
        }
    
        // Mantener el botón siempre al final
        cartList.appendChild(saveButtonContainer);
    }
    

//////save

    // Captura de los datos del formulario, incluidos los campos dinámicos
    const saveButton = document.querySelector(".saveDog");
    saveButton.addEventListener("click", function (e) {
        e.preventDefault(); // Evitar que se envíe el formulario de inmediato

        // Recoger todos los datos de los campos del formulario
        const formData = new FormData(document.getElementById("dog-form"));
        const formObj = {};

        formData.forEach((value, key) => {
            if (formObj[key]) {
                formObj[key].push(value);  // Si ya existe el campo, agregar el valor al array
            } else {
                formObj[key] = [value];  // Si es la primera vez que se encuentra el campo, crear un array
            }
        });

        console.log(formObj); // Aquí puedes ver los valores en el objeto
        // Enviar los datos al servidor si es necesario
        // fetch(form.action, {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //     },
        //     body: JSON.stringify(formObj)
        // })
        // .then(response => response.json())
        // .then(data => {
        //     console.log("Respuesta del servidor:", data);
        // })
        // .catch(error => {
        //     console.error("Error al enviar los datos:", error);
        // });
    });

}