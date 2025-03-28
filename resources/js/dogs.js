import { Utils } from './utils.js'; 

export  function dogs() {


    document.addEventListener("DOMContentLoaded", function () {

        const dogFormContainer = document.getElementById('dog-form'); 

        dogFormContainer.addEventListener('keydown', function (e) {
            
            if (e.target.classList.contains('sire')) {
                if (e.key === 'Enter') {
                e.preventDefault(); 
                searchSire(e.target, e.target.closest('form')); 
                }
            }

            if (e.target.classList.contains('dam')) {
                if (e.key === 'Enter') {
                e.preventDefault(); 
                searchDam(e.target, e.target.closest('form')); 
                }
            }
        });

        dogFormContainer.addEventListener('blur', function (e) {
            
            if (e.target.classList.contains('sire')) {
                searchSire(e.target, e.target.closest('form')); 
            }

            if (e.target.classList.contains('dam')) {
                searchDam(e.target, e.target.closest('form')); 
            }

        }, true); 

        const saveBtn = document.querySelector(".saveDog");
        saveBtn.addEventListener("click", function (e) {
            e.preventDefault(); 
    
            // let form = document.getElementById("dog-form");
            // let formData = new FormData(form);
    
            // let data = {};
            // formData.forEach((value, key) => {
            //     data[key] = value;
            // });

            // objets.saveDog(e,form,data);

            // let form = document.getElementById("dog-form");
            // let formData = new FormData(form);
        
            // let data = {};
        
            // formData.forEach((value, key) => {
            //     // Obtener el elemento del formulario por su nombre
            //     let element = form.querySelector(`[name=${key}]`);
        
            //     // Verificar si el elemento no está oculto ni deshabilitado
            //     if (element && element.offsetParent !== null && !element.disabled) {
            //         data[key] = value;
            //     }
            // });
        
            // objets.saveDog(e, form, data);

            let form = document.getElementById("dog-form");
            let formData = new FormData(form);
            
            let data = {};
            
            formData.forEach((value, key) => {
                let element = form.querySelector(`[name="${key}"]`);
            
                // Incluir los campos ocultos en los datos enviados
                if (element && (!element.disabled || element.type === "hidden")) {
                    data[key] = value;
                }
            });
            console.log(data);
            objets.saveDog(e, form, data);
    
        });

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

                    showDamResults(data.data, form);
                } else {
                    Swal.fire({
                        title: 'No results found',
                        text: 'Would you like to search in another way?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        backdrop: true,
                        allowOutsideClick: false
                      }).then((result) => {
                        if (!result.isConfirmed) {
                            form.querySelector('.sireMail').classList.remove('is-hidden'); 
                            form.querySelector('.searchSire').classList.add('is-hidden');
                          
                        }
                    });

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
                    showDamResults(data.data, form);
                    // Aquí puedes mostrar el resultado en el formulario, por ejemplo:
                    // form.querySelector('.dam_name').textContent = data.name;
                    // form.querySelector('.dam_id').value = data.dog_id;
                } else {

                    Swal.fire({
                        title: 'No results found',
                        text: 'Would you like to search in another way?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        backdrop: true,
                        allowOutsideClick: false
                      }).then((result) => {
                        if (!result.isConfirmed) {
                            
                            form.querySelector('.damMail').classList.remove('is-hidden'); 
                            form.querySelector('.searchDam').classList.add('is-hidden');
                          
                        }
                    });

                }
            })
            .catch(error => {
                console.log("Error al buscar Dam:", error);
            });
   }
    

function showDamResults(dogs, form) {
    const resultsContainer = document.getElementById('sireResults');
    resultsContainer.innerHTML = '';  // Limpiar resultados previos

    // Convertir dogs en un array si no lo es
    if (!Array.isArray(dogs)) {
        dogs = [dogs];  // Si no es un array, lo convertimos en uno (asumiendo que solo hay un perro)
    }

    // Verificar si hay perros
    if (dogs.length === 0) {
        resultsContainer.innerHTML = '<div class="no-results">No dogs found.</div>';
        resultsContainer.style.display = 'block';
        return;
    }

    // Crear la lista de resultados
    dogs.forEach(dog => {
        const dogItem = document.createElement('div');
        dogItem.classList.add('result-item');
        dogItem.textContent = dog.name;  // Nombre del perro
        dogItem.setAttribute('data-dog-id', dog.dog_id);  // Guardamos el dog_id

        // Asignar un evento de clic para seleccionar un perro
        dogItem.addEventListener('click', function () {
            selectSire(dog.dog_id, dog.name, form);
        });

        // Asignar un evento de clic para seleccionar un perro
        dogItem.addEventListener('click', function () {
            selectDam(dog.dog_id, dog.name, form);
        });

        resultsContainer.appendChild(dogItem);  // Agregar el item al contenedor
    });

    resultsContainer.style.display = 'block';  // Mostrar el contenedor con los resultados
}


function selectSire(dogId, dogName, form) {
    // Actualiza el campo de entrada con el nombre del perro seleccionado
    form.querySelector('input[name="sire"]').value = dogName; 
    form.querySelector('input[name="sire_id"]').value = dogId;
    // Ocultar el contenedor de resultados después de seleccionar un perro
    document.getElementById('sireResults').style.display = 'none';
}

// Función para seleccionar un Dam
function selectDam(dogId, dogName, form) {
    // Actualiza el campo de entrada con el nombre del perro seleccionado
    form.querySelector('input[name="dam"]').value = dogName; 
    form.querySelector('input[name="dam_id"]').value = dogId;
    // Ocultar el contenedor de resultados después de seleccionar un perro
    document.getElementById('damResults').style.display = 'none';
}


    const objets = {

        
        saveDog: function(e,form,data) {
            e.preventDefault();

            // Enviar datos mediante Fetch API (ejemplo con POST)
            fetch(form.action, {
                method: form.method.toUpperCase(),
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {


            })
            .catch(error => {
                console.error("Error al enviar el formulario:", error);
                alert("An error occurred. Please try again.");
            });

        }

    }

}